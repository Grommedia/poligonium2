(function () {
    window.poligoniumContactTabExternalReady = true;

    function toggleContactPanel(isOpen) {
        document.querySelectorAll('.offCanvas__info, .offCanvas__overly').forEach(function (element) {
            element.classList.toggle('active', isOpen);
        });
    }

    document.addEventListener('click', function (event) {
        var contactTrigger = event.target.closest('.poligonium-contact-tab');

        if (contactTrigger) {
            toggleContactPanel(true);
            return;
        }

        if (event.target.closest('.menu-close, .offCanvas__overly')) {
            toggleContactPanel(false);
        }
    }, true);
})();
