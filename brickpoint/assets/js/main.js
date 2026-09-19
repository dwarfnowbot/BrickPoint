/* BrickPoint — shared front-end helpers */
(function () {
  /* Smooth anchor scrolling inside pages */
  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href^="#"]');
    if (!a) { return; }
    var id = a.getAttribute('href').slice(1);
    if (!id) { return; }
    var target = document.getElementById(id);
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
})();
