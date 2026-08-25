/**
 * BULIG — sidebar.js
 * Opens/closes the off-canvas sidebar on tablet/mobile widths.
 * On desktop (>=900px) the sidebar is always visible via CSS, so this
 * script only matters below that breakpoint.
 */
(function () {
    'use strict';

    var sidebar   = document.getElementById('sidebar');
    var backdrop  = document.getElementById('sidebarBackdrop');
    var toggleBtn = document.getElementById('sidebarToggle');

    if (!sidebar || !backdrop || !toggleBtn) return;

    function openSidebar() {
        sidebar.classList.add('is-open');
        backdrop.classList.add('is-open');
        toggleBtn.setAttribute('aria-expanded', 'true');
    }
    function closeSidebar() {
        sidebar.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
    }

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
    });
    backdrop.addEventListener('click', closeSidebar);

    // Close automatically after tapping a nav link (mobile UX nicety)
    sidebar.querySelectorAll('.side-link').forEach(function (link) {
        link.addEventListener('click', closeSidebar);
    });

    // If the viewport grows past the mobile breakpoint, make sure we don't
    // leave the drawer state stuck "open" underneath the now-static sidebar.
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 900) closeSidebar();
    });
})();
