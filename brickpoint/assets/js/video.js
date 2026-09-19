/* BrickPoint — video helpers (deferred embed) */
(function () {
  document.querySelectorAll('[data-bp-video-embed]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var wrap = btn.closest('.bp-video-thumb');
      var src = btn.getAttribute('data-bp-video-embed');
      if (!wrap || !src) { return; }
      var iframe = document.createElement('iframe');
      iframe.src = src;
      iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture');
      iframe.setAttribute('allowfullscreen', 'true');
      iframe.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:0';
      wrap.style.aspectRatio = '16/9';
      wrap.appendChild(iframe);
    });
  });
})();
