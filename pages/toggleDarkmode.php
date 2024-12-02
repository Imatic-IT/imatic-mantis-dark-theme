<?php

$plugin = plugin_get('MantisBTModernDarkTheme');

$config_key = 'plugin_MantisBTModernDarkTheme_enabled';
$current_user_id = auth_get_current_user_id();
$current_state = config_get($config_key, false, $current_user_id, ALL_PROJECTS);
config_set($config_key, !$current_state, $current_user_id, ALL_PROJECTS);

$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : plugin_page('default_page');

header('Location: ' . $redirect_url);
exit;
