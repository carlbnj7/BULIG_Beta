(function () {
    'use strict';

    var fill = document.querySelector('.xp-fill');
    if (!fill) { return; }

    var target = fill.getAttribute('data-percent') || '0';

    // Start at 0 then animate to the real value so the bar "fills up"
    // on page load instead of just appearing.
    window.requestAnimationFrame(function () {
        setTimeout(function () {
            fill.style.width = target + '%';
        }, 120);
    });
})();
