
(function () {
    'use strict';

    var button = document.querySelector('.cv-menu');
    var nav = document.querySelector('.cv-nav');

    if (!button || !nav) {
        return;
    }

    function setMenuOpen(isOpen) {
        button.setAttribute('aria-expanded', String(isOpen));
        nav.classList.toggle('open', isOpen);
    }

    button.addEventListener('click', function () {
        var isOpen = button.getAttribute('aria-expanded') === 'true';
        setMenuOpen(!isOpen);
    });

    nav.addEventListener('click', function (event) {
        if (event.target.closest('a')) {
            setMenuOpen(false);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            button.getAttribute('aria-expanded') === 'true'
        ) {
            setMenuOpen(false);
            button.focus();
        }
    });

    window.addEventListener('resize', function () {
        if (window.matchMedia('(min-width: 768px)').matches) {
            setMenuOpen(false);
        }
    });
})();
