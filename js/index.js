$(document).ready(function () {
    function getSettings() {
        const el = document.querySelector("#imaticDarkmode");
        if (el == null) {
            return;
        }
        return JSON.parse(el.dataset.data);
    }

    const settings = getSettings();

    if (settings) {
        const url = settings.url;
        const darkmode = settings.darkmode;
        const $navbarButtons = $('.navbar-buttons .nav');
        const tooltip = settings.tooltip;

        const toggleButton = `
            <li id="darkmode-icons" title="${tooltip}">
                <a href="/plugin.php?page=ImaticMantisDarkTheme/toggleDarkmode" id="darkmode-toggler" style="display: flex; align-items: center;">
                    <div style="text-align: center; margin-right: 10px;">
                        <i class="fa" id="darkmode-icon" aria-hidden="true" style="font-size: 20px; color: #f39c12;"></i>
                    </div>
                    <div>
                        <i class="menu-icon fa fa-toggle-off" style="font-size: 20px; display: flex; align-items: center; margin-bottom: 4px;">
                        </i>
                    </div>
                </a>
                <b class="arrow"></b>
            </li>
        `;
        $navbarButtons.append(toggleButton);

        const $darkModeIcon = $('#darkmode-icon');
        const $toggleIcon = $('#darkmode-toggler .fa-toggle-off');

        if (darkmode) {
            $darkModeIcon.addClass('fa-moon-o').removeClass('fa-sun-o');
            $toggleIcon.addClass('fa-toggle-on').removeClass('fa-toggle-off');
        } else {
            $darkModeIcon.addClass('fa-sun-o').removeClass('fa-moon-o');
            $toggleIcon.addClass('fa-toggle-off').removeClass('fa-toggle-on');
        }

        $('#darkmode-toggler').on('click', function (e) {
            e.preventDefault();

            const newDarkmodeState = !darkmode; // Opačný stav
            const url = $(this).attr('href');

            if (newDarkmodeState) {
                $darkModeIcon.addClass('fa-moon-o').removeClass('fa-sun-o');
                $toggleIcon.addClass('fa-toggle-on').removeClass('fa-toggle-off');
            } else {
                $darkModeIcon.addClass('fa-sun-o').removeClass('fa-moon-o');
                $toggleIcon.addClass('fa-toggle-off').removeClass('fa-toggle-on');
            }

            window.location.href = url;
        });
    }
});
