/* BrickPoint — single product gallery swap */
(function () {
  var main = document.getElementById('bpProductMain');
  var thumbs = document.querySelectorAll('.bp-thumbs img');
  if (!main || !thumbs.length) { return; }
  thumbs.forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      var tmp = main.src;
      main.src = thumb.src;
      thumb.src = tmp;
      thumbs.forEach(function (t) { t.classList.remove('active'); });
      thumb.classList.add('active');
    });
  });
})();
