$(document).ready(function () {
    function getSettings() {
        const el = document.querySelector("#imaticDarkmode");
        if (el == null) {
            return;
        }
        return JSON.parse(el.dataset.data);
    }

    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    const settings = getSettings();

    if (settings) {
        const darkmode = settings.darkmode;
        const $navbarButtons = $('.navbar-buttons .nav');

        const extraItemsHtml = (settings.extraItems || []).map(function (item) {
            return `
                <li>
                    <a href="${item.url}">
                        <i class="fa ${item.icon}"></i>
                        <span>${escapeHtml(item.label)}</span>
                    </a>
                </li>
            `;
        }).join('');

        const settingsMenu = `
            <li id="imatic-settings-menu" class="dropdown grey">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="${escapeHtml(settings.settingsTitle)}">
                    <i class="fa fa-cog ace-icon fa-2x white"></i>
                    <i class="fa fa-angle-down ace-icon bigger-110"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-yellow dropdown-caret dropdown-close">
                    <li>
                        <a href="${settings.url}" id="darkmode-toggler" title="${escapeHtml(settings.tooltip)}">
                            <i class="fa" id="darkmode-icon" aria-hidden="true"></i>
                            <span>${escapeHtml(settings.toggleLabel)}</span>
                            <i class="fa fa-toggle-off pull-right" id="darkmode-toggle-icon" aria-hidden="true"></i>
                        </a>
                    </li>
                    ${extraItemsHtml ? '<li class="divider"></li>' + extraItemsHtml : ''}
                </ul>
            </li>
        `;
        $navbarButtons.append(settingsMenu);

        const $darkModeIcon = $('#darkmode-icon');
        const $toggleIcon = $('#darkmode-toggle-icon');

        function paintToggle(isDark) {
            $darkModeIcon.toggleClass('fa-moon-o', isDark).toggleClass('fa-sun-o', !isDark);
            $toggleIcon.toggleClass('fa-toggle-on', isDark).toggleClass('fa-toggle-off', !isDark);
        }

        paintToggle(darkmode);

        $('#darkmode-toggler').on('click', function (e) {
            e.preventDefault();
            paintToggle(!darkmode);
            window.location.href = $(this).attr('href');
        });
    }
});
