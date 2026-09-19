#!/usr/bin/env bash
#
# BrickPoint — rebuild the WordPress E2E test environment.
#
# Creates /home/user/testenv with:
#   node_modules/            @wp-playground/wordpress + @php-wasm/node
#   wproot/                  WordPress 6.7.1 (built, root-flattened)
#     wp-content/plugins/elementor       Elementor free 3.35.0 GitHub source
#     wp-content/mu-plugins/zz-test-elementor-classmap.php   classmap autoloader shim
#   sqlite.zip               sqlite-database-integration (canonical layout)
#
# Idempotent: skips parts that already exist. Sources are GitHub (the only
# reliable egress); release-assets / downloads.wordpress.org are blocked.
#
set -euo pipefail

ENV=/home/user/testenv
WPROOT=$ENV/wproot
mkdir -p "$ENV"

# ---------------------------------------------------------------- node deps
if [ ! -d "$ENV/node_modules/@wp-playground/wordpress" ]; then
  echo "== Installing Playground node deps =="
  cd "$ENV"
  [ -f package.json ] || npm init -y >/dev/null
  npm install --no-audit --no-fund @wp-playground/wordpress@3.1.54 @php-wasm/node@3.1.54 @ffmpeg-installer/ffmpeg
else
  echo "== node deps present, skipping =="
fi

# ---------------------------------------------------------------- wordpress
if [ ! -f "$WPROOT/wp-settings.php" ]; then
  echo "== Downloading WordPress 6.7.1 (built) =="
  curl -sL -o "$ENV/wp.zip" https://codeload.github.com/WordPress/WordPress/zip/refs/tags/6.7.1
  python3 - "$ENV/wp.zip" "$WPROOT" <<'EOF'
import sys, zipfile, os
zp, dest = sys.argv[1], sys.argv[2]
z = zipfile.ZipFile(zp)
names = z.namelist()
root = names[0].split('/')[0]  # e.g. WordPress-6.7.1
z.extractall(dest)
# flatten
inner = os.path.join(dest, root)
for e in os.listdir(inner):
    os.rename(os.path.join(inner, e), os.path.join(dest, e))
os.rmdir(inner)
print("flattened", root, "->", dest)
EOF
  rm -f "$ENV/wp.zip"
else
  echo "== WordPress present, skipping =="
fi

# ---------------------------------------------------------------- elementor
if [ ! -f "$WPROOT/wp-content/plugins/elementor/elementor.php" ]; then
  echo "== Downloading Elementor 3.35.0 (GitHub source) =="
  curl -sL -o "$ENV/elementor.zip" https://codeload.github.com/elementor/elementor/zip/refs/tags/3.35.0
  python3 - "$ENV/elementor.zip" "$WPROOT/wp-content/plugins" <<'EOF'
import sys, zipfile, os
zp, dest = sys.argv[1], sys.argv[2]
z = zipfile.ZipFile(zp)
root = z.namelist()[0].split('/')[0]
z.extractall(dest)
os.rename(os.path.join(dest, root), os.path.join(dest, 'elementor'))
print("elementor source installed")
EOF
  rm -f "$ENV/elementor.zip"
else
  echo "== Elementor present, skipping =="
fi

# ---------------------------------------------------------------- elementor pro
# GPL mirror of Elementor Pro 3.12.2 (full Theme Builder source) for E2E runs.
if [ ! -f "$WPROOT/wp-content/plugins/elementor-pro/elementor-pro.php" ]; then
  echo "== Downloading Elementor Pro 3.12.2 (GPL mirror) =="
  curl -sL -o "$ENV/pro.tar.gz" https://codeload.github.com/ElementorProGPL/elementor-pro-gpl/tar.gz/refs/tags/v3.12.2
  python3 - "$ENV/pro.tar.gz" "$WPROOT/wp-content/plugins" <<'PYEOF'
import sys, tarfile, os
tp, dest = sys.argv[1], sys.argv[2]
t = tarfile.open(tp)
root = t.getnames()[0].split('/')[0]
t.extractall(dest)
src = os.path.join(dest, root)
target = os.path.join(dest, 'elementor-pro')
os.rename(src, target)
os.rename(os.path.join(target, 'elementor-pro-gpl.php'), os.path.join(target, 'elementor-pro.php'))
print("elementor-pro installed")
PYEOF
  rm -f "$ENV/pro.tar.gz"
else
  echo "== Elementor Pro present, skipping =="
fi

# ---------------------------------------------------------------- sqlite
if [ ! -f "$ENV/sqlite.zip" ]; then
  echo "== Downloading sqlite-database-integration (trunk) =="
  # NOTE: WordPress/sqlite-database-integration was restructured (2026-09, no
  # plugin source left); MarekTP's fork keeps the classic plugin (db.copy +
  # load.php + wp-includes/sqlite/*), which is all Playground consumes.
  curl -sL -o "$ENV/sqlite-src.zip" https://codeload.github.com/MarekTP/sqlite-database-integration/zip/refs/heads/main
  python3 - "$ENV/sqlite-src.zip" "$ENV/sqlite.zip" <<'EOF'
import sys, zipfile, os
src, dest = sys.argv[1], sys.argv[2]
z = zipfile.ZipFile(src)
root = z.namelist()[0].split('/')[0]
out = zipfile.ZipFile(dest, 'w', zipfile.ZIP_DEFLATED)
for info in z.infolist():
    if info.is_dir():
        continue
    # rename root dir -> sqlite-database-integration (canonical plugin layout)
    rel = info.filename[len(root):].lstrip('/')
    out.writestr('sqlite-database-integration/' + rel, z.read(info.filename))
out.close()
print("sqlite.zip packed:", os.path.getsize(dest), "bytes")
EOF
  rm -f "$ENV/sqlite-src.zip"
else
  echo "== sqlite.zip present, skipping =="
fi

# ---------------------------------------------------------------- classmap shim
echo "== Building Elementor classmap mu-plugins =="
node /home/user/BrickPoint/scripts/build-elementor-classmap.mjs
if [ -d "$WPROOT/wp-content/plugins/elementor-pro" ]; then
  node /home/user/BrickPoint/scripts/build-elementor-classmap.mjs "$WPROOT/wp-content/plugins/elementor-pro" "$WPROOT/wp-content/mu-plugins/zz-test-elementor-pro-classmap.php" "ElementorPro"
fi

# ------------------------------------------------- elementor source hardening
# The GitHub SOURCE build plain-`require`s some class files (Widgets_Manager
# etc.); combined with the classmap shim above that can double-declare
# classes. Release builds load these via composer classmap + require_files
# ordering. Normalize to *_once — test-env only, no effect on real installs.
python3 - "$WPROOT/wp-content/plugins/elementor" <<'PYEOF'
import os, re, sys
root = sys.argv[1]
pat = re.compile(r"(\bskip|)^(\s*)(require|include)(\s+)((?:ELEMENTOR_PATH|ELEMENTOR_MODULES_PATH)\s*\.)", re.M)
n = 0
for dirpath, dirnames, filenames in os.walk(root):
    for fn in filenames:
        if not fn.endswith('.php'):
            continue
        p = os.path.join(dirpath, fn)
        src = open(p, encoding='utf-8', errors='ignore').read()
        out = pat.sub(lambda m: f"{m.group(2)}{m.group(3)}_once{m.group(4)}{m.group(5)}", src)
        if out != src:
            open(p, 'w', encoding='utf-8').write(out)
            n += 1
print(f"normalized plain requires in {n} files")
PYEOF

# ------------------------------------------------ elementor-pro hardening
if [ -d "$WPROOT/wp-content/plugins/elementor-pro" ]; then
python3 - "$WPROOT/wp-content/plugins/elementor-pro" <<'PYEOF'
import os, re, sys
root = sys.argv[1]
pat = re.compile(r"^(\s*)(require|include)(\s+)((?:ELEMENTOR_PRO_PATH|ELEMENTOR_PRO_MODULES_PATH|__DIR__)\s*\.)", re.M)
n = 0
for dirpath, dirnames, filenames in os.walk(root):
    for fn in filenames:
        if not fn.endswith('.php'):
            continue
        p = os.path.join(dirpath, fn)
        src = open(p, encoding='utf-8', errors='ignore').read()
        out = pat.sub(lambda m: f"{m.group(1)}{m.group(2)}_once{m.group(3)}{m.group(4)}", src)
        if out != src:
            open(p, 'w', encoding='utf-8').write(out)
            n += 1
print(f"normalized plain requires in {n} pro files")
PYEOF
fi

echo "== Syncing theme =="

# ------------------------------------------------ elementor-pro hardening
if [ -d "$WPROOT/wp-content/plugins/elementor-pro" ]; then
python3 - "$WPROOT/wp-content/plugins/elementor-pro" <<'PYEOF'
import os, re, sys
root = sys.argv[1]
pat = re.compile(r"^(\s*)(require|include)(\s+)((?:ELEMENTOR_PRO_PATH|ELEMENTOR_PRO_MODULES_PATH|__DIR__)\s*\.)", re.M)
n = 0
for dirpath, dirnames, filenames in os.walk(root):
    for fn in filenames:
        if not fn.endswith('.php'):
            continue
        p = os.path.join(dirpath, fn)
        src = open(p, encoding='utf-8', errors='ignore').read()
        out = pat.sub(lambda m: f"{m.group(1)}{m.group(2)}_once{m.group(3)}{m.group(4)}", src)
        if out != src:
            open(p, 'w', encoding='utf-8').write(out)
            n += 1
print(f"normalized plain requires in {n} pro files")
PYEOF
fi

echo "== Syncing theme =="
rm -rf "$WPROOT/wp-content/themes/brickpoint"
cp -r /home/user/BrickPoint/brickpoint "$WPROOT/wp-content/themes/brickpoint"

echo "DONE. Test environment ready at $ENV"
