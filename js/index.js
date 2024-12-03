$(document).ready(function () {
    function getSettings() {
        const el = document.querySelector("#imaticDarkmode");
        if (el == null) {
            return;
        }

        return JSON.parse(el.dataset.data);
    }

    const settings = getSettings();
    const url = settings.url;
    const darkmode = settings.darkmode;
    const $navbarButtons = $('.navbar-buttons .nav');

    const toggleButton = `
        <li id="darkmode-toggler">
            <input type="checkbox" class="checkbox" id="checkbox">
            <label for="checkbox" class="checkbox-label">
                <i class="fa fa-moon-o" aria-hidden="true"></i>
                <i class="fa fa-sun-o" aria-hidden="true"></i>
                <span class="ball"></span>
            </label>
        </li>
    `;

    $navbarButtons.append(toggleButton);

    if (darkmode === true) {
        $('#checkbox').prop('checked', true);
    }

    const $icon = $('#darkmode-toggler .fa');
    if (darkmode === true) {
        $icon.removeClass('fa-sun-o').addClass('fa-moon-o');
        $icon.css('color', '#f39c12');
    } else {
        $icon.removeClass('fa-moon-o').addClass('fa-sun-o');
        $icon.css('color', '#f39c12');
    }

    $('#checkbox').on('change', function () {
        const isChecked = $('#checkbox').prop('checked');
        if (isChecked) {
            $icon.removeClass('fa-sun-o').addClass('fa-moon-o');
        } else {
            $icon.removeClass('fa-moon-o').addClass('fa-sun-o');
        }
        $icon.css('color', '#f39c12');
    });

    $('#darkmode-toggler').on('click', function () {
        window.location.href = url;
    });
});
