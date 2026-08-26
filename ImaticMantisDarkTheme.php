<?php

class ImaticMantisDarkThemePlugin extends MantisPlugin
{
    const CFG_ENABLED = 'plugin_ImaticMantisDarkTheme_enabled';
    const CFG_THEME = 'plugin_ImaticMantisDarkTheme_theme_selected';

    function register()
    {
        $this->name = 'ImaticMantisDarkTheme';
        $this->description = 'A clean and dark theme for MantisBT.';

        $this->version = '2.2.0';
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

    /**
     * Declares a shared event other plugins can hook to contribute an item
     * (title/url/icon, same shape as EVENT_MENU_MAIN) to the navbar settings
     * dropdown owned by this plugin — so this plugin doesn't need to know
     * anything about who else contributes to it.
     */
    function events()
    {
        return array(
            'EVENT_IMATIC_SETTINGS_MENU' => EVENT_TYPE_DEFAULT,
        );
    }

    function hooks()
    {
        return array(
            'EVENT_ACCOUNT_PREF_UPDATE_FORM' => 'account_update_form',
            'EVENT_ACCOUNT_PREF_UPDATE' => 'account_update',
            'EVENT_LAYOUT_HEAD_BEGIN' => 'layout_head_begin_hook',
            'EVENT_LAYOUT_RESOURCES' => 'layout_resources_hook',
            'EVENT_LAYOUT_BODY_END' => 'layout_body_end_hook',
        );
    }

    /**
     * Collects items other plugins contributed via EVENT_IMATIC_SETTINGS_MENU,
     * resolving labels/urls/access checks the same way the core sidebar does.
     *
     * Must not run for anonymous/unauthenticated visitors: layout_body_end_hook()
     * fires on every page including login_page.php, and a contributed item
     * resolving the current user (e.g. layout_manage_menu_link()) can trigger
     * access_denied() from inside login_page.php's own render — which then
     * redirects back to login_page.php with no ?return, causing an infinite
     * redirect loop. auth_is_user_authenticated() is safe here: it only checks
     * cookie validity and never redirects.
     */
    function settingsMenuItems()
    {
        if ( !auth_is_user_authenticated() ) {
            return array();
        }

        $t_items = layout_plugin_menu_options_for_sidebar( event_signal( 'EVENT_IMATIC_SETTINGS_MENU' ) );

        $t_resolved = array();
        foreach ( $t_items as $t_item ) {
            if ( !isset( $t_item['url'] ) || !isset( $t_item['title'] ) || is_blank( $t_item['url'] ) ) {
                continue;
            }
            if ( isset( $t_item['access_level'] ) && !access_has_project_level( $t_item['access_level'] ) ) {
                continue;
            }
            $t_resolved[] = array(
                'label' => lang_get_defaulted( $t_item['title'] ),
                'url' => helper_mantis_url( $t_item['url'] ),
                'icon' => $t_item['icon'] ?? 'fa-circle',
            );
        }

        return $t_resolved;
    }

    function is_enabled()
    {
        return auth_is_user_authenticated() && config_get(self::CFG_ENABLED, false, auth_get_current_user_id(), ALL_PROJECTS);;
    }

    function userSelectedTheme()
    {
        # Guard: on pages without a real logged-in user (e.g. login_page.php)
        # auth_get_current_user_id() triggers access_denied() in MantisBT >= 2.28 -> redirect loop.
        if (!auth_is_user_authenticated() || current_user_is_anonymous()) {
            return false;
        }
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


    function layout_head_begin_hook()
    {
        if ( !$this->is_enabled() ) {
            return;
        }

        $selectedTheme = $this->userSelectedTheme() ?: plugin_config_get('dark_theme_option');

        echo '<script>(function(){document.documentElement.classList.add("dark-mode","dark-mode-' . htmlspecialchars($selectedTheme) . '");})();</script>' . "\n";
    }

    function layout_resources_hook()
    {
        if ( !$this->is_enabled() ) {
            return;
        }

        $selectedTheme = $this->userSelectedTheme() ?: plugin_config_get('dark_theme_option');

        echo '<link rel="stylesheet" type="text/css" href="' . plugin_file('ModernDarkTheme.css') . '" />' . "\n";
        echo '<link rel="stylesheet" type="text/css" href="' . plugin_file('dark_theme_' . $selectedTheme . '.css') . '" />' . "\n";
    }

    function layout_body_end_hook()
    {

        $t_data = htmlspecialchars(json_encode([
            'url' => plugin_page('toggleDarkmode'),
            'darkmode' => $this->is_enabled(),
            'tooltip' => plugin_lang_get('tooltip'),
            'toggleLabel' => plugin_lang_get('toggle_label'),
            'settingsTitle' => plugin_lang_get('settings_menu_title'),
            'extraItems' => $this->settingsMenuItems(),
        ]));


        echo '<script id="imaticDarkmode" data-data="' . $t_data . '" src="' . plugin_file('index.js') . '&v=' . $this->version . '"></script>';
        echo '<link rel="stylesheet" type="text/css" href="' . plugin_file('style.css') . '&v=' . $this->version . '" />';
    }
}