/**
 * BULIG — login.js
 * Handles the Pupil / Teacher tab toggle, password visibility, and a brief
 * loading state on submit. Kept dependency-free on purpose.
 */
(function () {
    'use strict';

    var tabs   = document.querySelectorAll('.tab');
    var tabsEl = document.querySelector('.tabs');
    var panels = document.querySelectorAll('.panel');

    function activate(target) {
        tabs.forEach(function (t) {
            var isMatch = t.dataset.target === target;
            t.classList.toggle('is-active', isMatch);
            t.setAttribute('aria-selected', isMatch ? 'true' : 'false');
        });
        panels.forEach(function (p) {
            p.classList.toggle('is-active', p.id === 'panel' + capitalize(target));
        });
        tabsEl.setAttribute('data-active', target);

        // Keep the URL shareable/bookmarkable without a full reload.
        if (window.history && window.history.replaceState) {
            var url = new URL(window.location.href);
            url.searchParams.set('type', target);
            window.history.replaceState({}, '', url);
        }
    }

    function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activate(tab.dataset.target);
        });
    });

    // Initialize the sliding thumb position to match the server-rendered state.
    var initiallyActive = document.querySelector('.tab.is-active');
    if (initiallyActive) {
        tabsEl.setAttribute('data-active', initiallyActive.dataset.target);
    }

    // Student ID is numbers only — strip anything else as the pupil types
    document.querySelectorAll('.js-numeric').forEach(function (input) {
        input.addEventListener('input', function () {
            var digitsOnly = input.value.replace(/[^0-9]/g, '');
            if (digitsOnly !== input.value) input.value = digitsOnly;
        });
    });

    // Show / hide password
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.previousElementSibling;
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.textContent = isPassword ? '🙈' : '👁️';
            btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    });

    // Loading state on submit (client-side only — the real work happens in PHP)
    document.querySelectorAll('.login-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var idField = form.querySelector('input[type="text"]');
            var pwField = form.querySelector('input.js-password');

            if (!idField.value.trim() || !pwField.value.trim()) {
                // Let the browser's own required-field validation handle the message,
                // but stop the loading animation from firing on an incomplete form.
                return;
            }

            var btn = form.querySelector('.btn-submit');
            btn.classList.add('is-loading');
            btn.disabled = true;
            // Form submits normally right after; PHP will redirect to the
            // right dashboard or bounce back here with a friendly error.
        });
    });
})();
