(function () {
    'use strict';

    var pipelineIcons = [
        ['ai.svg', 'photoshop.png', 'illustrator.png'],
        ['blender.png', 'houdini.png', 'zbrush.png'],
        ['blender.png', 'houdini.png', 'zbrush.png'],
        ['substance-painter.png', 'substance-designer.png', 'blender.png', 'houdini.png'],
        ['blender.png', 'houdini.png'],
        ['blender.png', 'houdini.png'],
        ['blender.png', 'houdini.png'],
        ['blender.png', 'houdini.png'],
        ['blender.png', 'houdini.png'],
        ['after-effects.png', 'nuke.png'],
    ];

    var iconBasePath = '/themes/zelio/images/pipeline-icons/';
    var serviceTones = ['modeling', 'rigging', 'video', 'vr'];

    function activateTab(root, tab) {
        var tabs = Array.prototype.slice.call(root.querySelectorAll('.poligonium-service-tab'));
        var panels = Array.prototype.slice.call(root.querySelectorAll('.poligonium-service-panel'));
        var activeIndex = tabs.indexOf(tab);

        root.setAttribute('data-service-tone', serviceTones[activeIndex] || serviceTones[0]);

        tabs.forEach(function (item) {
            var isActive = item === tab;
            item.classList.toggle('is-active', isActive);
            item.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        panels.forEach(function (panel, index) {
            var isActive = index === activeIndex;
            panel.classList.toggle('is-active', isActive);
            panel.toggleAttribute('hidden', ! isActive);
        });
    }

    document.addEventListener('click', function (event) {
        var pipelineItem = event.target.closest('.poligonium-pipeline-map > ol > li');

        if (pipelineItem) {
            var map = pipelineItem.closest('.poligonium-pipeline-map');

            map.querySelectorAll('ol > li').forEach(function (item) {
                item.classList.toggle('is-open', item === pipelineItem && ! item.classList.contains('is-open'));
            });

            return;
        }

        document.querySelectorAll('.poligonium-pipeline-map ol > li.is-open').forEach(function (item) {
            item.classList.remove('is-open');
        });

        var tab = event.target.closest('.poligonium-service-tab');

        if (! tab) {
            return;
        }

        var root = tab.closest('.poligonium-service-tabs');

        if (! root) {
            return;
        }

        root.dataset.servicePausedUntil = String(Date.now() + 4500);
        activateTab(root, tab);
    });

    function setupPipelineMap(map) {
        var items = Array.prototype.slice.call(map.querySelectorAll(':scope > ol > li'));
        var activeIndex = -1;
        var isPaused = false;

        items.forEach(function (item, index) {
            item.setAttribute('tabindex', '0');
            item.addEventListener('click', pause);

            if (! item.querySelector('.poligonium-pipeline-tools')) {
                var tools = document.createElement('div');
                tools.className = 'poligonium-pipeline-tools';

                (pipelineIcons[index] || []).forEach(function (iconName) {
                    var tool = document.createElement('span');
                    var image = document.createElement('img');

                    tool.className = 'poligonium-pipeline-tool';
                    image.src = iconBasePath + iconName;
                    image.alt = iconName.replace(/\.(svg|png)$/i, '').replace(/-/g, ' ');
                    image.loading = 'lazy';

                    tool.appendChild(image);
                    tools.appendChild(tool);
                });

                item.appendChild(tools);
            }
        });

        function setAutoActive(index) {
            if (isPaused || ! items.length) {
                return;
            }

            activeIndex = index % items.length;
            items.forEach(function (item, itemIndex) {
                item.classList.toggle('is-auto-active', itemIndex === activeIndex);
            });
        }

        function clearAutoActive() {
            items.forEach(function (item) {
                item.classList.remove('is-auto-active');
            });
        }

        function pause() {
            isPaused = true;
            clearAutoActive();
        }

        function resume() {
            isPaused = false;
            setAutoActive(activeIndex + 1);
        }

        map.addEventListener('mouseenter', pause);
        map.addEventListener('focusin', pause);
        map.addEventListener('mouseleave', resume);
        map.addEventListener('focusout', function (event) {
            if (! map.contains(event.relatedTarget)) {
                resume();
            }
        });

        setAutoActive(0);
        window.setInterval(function () {
            setAutoActive(activeIndex + 1);
        }, 1500);
    }

    document.querySelectorAll('.poligonium-pipeline-map').forEach(function (map) {
        setupPipelineMap(map);
    });

    function setupServiceTabs(root) {
        var tabs = Array.prototype.slice.call(root.querySelectorAll('.poligonium-service-tab'));
        var isPaused = false;

        if (! tabs.length) {
            return;
        }

        activateTab(root, root.querySelector('.poligonium-service-tab.is-active') || tabs[0]);

        function getTabs() {
            return Array.prototype.slice.call(root.querySelectorAll('.poligonium-service-tab'));
        }

        function getActiveIndex(items) {
            var index = items.findIndex(function (tab) {
                return tab.classList.contains('is-active');
            });

            return index >= 0 ? index : 0;
        }

        function pause() {
            isPaused = true;
        }

        function resume() {
            isPaused = false;
        }

        root.addEventListener('mouseenter', pause);
        root.addEventListener('focusin', pause);
        root.addEventListener('mouseleave', resume);
        root.addEventListener('focusout', function (event) {
            if (! root.contains(event.relatedTarget)) {
                resume();
            }
        });

        window.setInterval(function () {
            var items = getTabs();
            var pauseUntil = Number(root.dataset.servicePausedUntil || 0);

            if (isPaused || Date.now() < pauseUntil || ! items.length) {
                return;
            }

            activateTab(root, items[(getActiveIndex(items) + 1) % items.length]);
        }, 1500);
    }

    document.querySelectorAll('.poligonium-service-tabs').forEach(function (root) {
        setupServiceTabs(root);
    });

    document.addEventListener('keydown', function (event) {
        if (! ['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) {
            return;
        }

        var tab = event.target.closest('.poligonium-service-tab');

        if (! tab) {
            return;
        }

        var root = tab.closest('.poligonium-service-tabs');
        var tabs = Array.prototype.slice.call(root.querySelectorAll('.poligonium-service-tab'));
        var currentIndex = tabs.indexOf(tab);
        var nextIndex = currentIndex;

        if (event.key === 'ArrowRight') {
            nextIndex = (currentIndex + 1) % tabs.length;
        } else if (event.key === 'ArrowLeft') {
            nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
        } else if (event.key === 'Home') {
            nextIndex = 0;
        } else if (event.key === 'End') {
            nextIndex = tabs.length - 1;
        }

        event.preventDefault();
        tabs[nextIndex].focus();
        activateTab(root, tabs[nextIndex]);
    });
})();
