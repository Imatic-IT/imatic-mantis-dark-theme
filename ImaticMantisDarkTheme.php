<?php

class ImaticMantisDarkThemePlugin extends MantisPlugin
{
    const CFG_ENABLED = 'plugin_ImaticMantisDarkTheme_enabled';
    const CFG_THEME = 'plugin_ImaticMantisDarkTheme_theme_selected';

    function register()
    {
        $this->name = 'ImaticMantisDarkTheme';
        $this->description = 'A clean and dark theme for MantisBT.';

        $this->version = '2.1.0';
        $this->requires = array(
            'MantisCore' => '2.0.0',
        );

        $this->author = 'Simone Tellini & Imatic software';
        $this->url = 'https://github.com/Imatic-IT/imatic-mantis-dark-theme';
    }

    function config()
    {
        return array(
            'dark_theme_option' => 'tagi_darker',
        );
    }

    function hooks()
    {
        return array(
            'EVENT_ACCOUNT_PREF_UPDATE_FORM' => 'account_update_form',
            'EVENT_ACCOUNT_PREF_UPDATE' => 'account_update',
            'EVENT_MENU_MAIN' => 'menu_main_hook',
            'EVENT_LAYOUT_BODY_END' => 'layout_body_end_hook',
        );
    }


    public function menu_main_hook()
    {
        return [
            [
                'title' => 'Dark Mode',
                'url' => plugin_page('toggleDarkmode'),
                'icon' => $this->is_enabled() ? 'fa-toggle-on' : 'fa-toggle-off',
                'access_level' => DEVELOPER,
            ]
        ];
    }

    function is_enabled()
    {
        return auth_is_user_authenticated() && config_get(self::CFG_ENABLED, false, auth_get_current_user_id(), ALL_PROJECTS);;
    }

    function userSelectedTheme()
    {
        return config_get(self::CFG_THEME, false, auth_get_current_user_id(), ALL_PROJECTS);
    }

    function account_update_form($p_event, $p_user_id)
    {
        echo '<tr>' .
            '<td class="category">' .
            '<label for="DarkModeSwitch">Dark Mode</label>' .
            '</td>' .
            '<td>' .
            '<input id="DarkModeSwitch" type="checkbox" name="' . self::CFG_ENABLED . '" value="1" ' . ($this->is_enabled() ? 'checked' : '') . '/>' .
            '</td>' .
            '</tr>';

        include 'inc/darkmode-themes.php';
    }

    function account_update($p_event, $p_user_id)
    {
        config_set(self::CFG_ENABLED, gpc_get_bool(self::CFG_ENABLED, false), $p_user_id, ALL_PROJECTS);
        config_set(self::CFG_THEME, gpc_get_string(self::CFG_THEME, false), $p_user_id, ALL_PROJECTS);
    }


    function layout_body_end_hook()
    {

        $t_data = htmlspecialchars(json_encode([
            'url' => plugin_page('toggleDarkmode'),
            'darkmode' => $this->is_enabled(),
            'tooltip' => plugin_lang_get('tooltip')

        ]));


        echo '<script id="imaticDarkmode" data-data="' . $t_data . '" src="' . plugin_file('index.js') . '&v=' . $this->version . '"></script><link rel="stylesheet" type="text/css" href="' . plugin_file('style.css') . '&v=' . $this->version . '" />';


        $selectedTheme = $this->userSelectedTheme() ? $this->userSelectedTheme() : plugin_config_get('dark_theme_option');

        if ($this->is_enabled()) {

            echo '<link rel="stylesheet" type="text/css" href="' . plugin_file('ModernDarkTheme.css') . '" />' . "\n";
            echo '<link rel="stylesheet" type="text/css" href="' . plugin_file('dark_theme_' . $selectedTheme . '.css') . '" />' . "\n";
        }
    }
}