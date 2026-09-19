/* BrickPoint — header interactions (sticky shadow, mobile menu, dropdown a11y) */
(function () {
  var header = document.getElementById('bpHeader');
  if (header) {
    var onScroll = function () {
      header.classList.toggle('scrolled', window.scrollY > 8);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
  var toggle = document.getElementById('bpMenuToggle');
  var menu = document.getElementById('bpMobileMenu');
  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      var open = !menu.hidden;
      menu.hidden = open;
      toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
    });
  }
  document.addEventListener('keyup', function (e) {
    if (e.key === 'Escape' && menu && !menu.hidden) {
      menu.hidden = true;
      if (toggle) { toggle.setAttribute('aria-expanded', 'false'); toggle.focus(); }
    }
  });
})();
