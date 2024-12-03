<?php
$g_dark_theme_options = [
    'tagi_default' => 'Tagi (default)',
    'tagi_darker' => 'Tagi (darker)',
    'orange' => 'Orange',
    'ocean' => 'Ocean',
    'gold' => 'Gold',
    'ai_1_experimental' => 'AI Neon forest (experimental)',
    'ai_2_experimental' => 'AI Mystic sunset (experimental)',
    'ai_3_experimental' => 'AI Electric Violer (experimental)',
    'ai_4_experimental' => 'AI Ocean Breeze (experimental)',
];

$plugin = plugin_get('ImaticMantisDarkTheme');

?>
<tr>
    <th class="category">
        <?php echo plugin_lang_get('config_menu_option') ?>
    </th>
    <td class="exclude_special_fields">

        <select name="<?php echo $plugin::CFG_THEME ?>">
            <?php
            foreach ($g_dark_theme_options as $key => $value) {
                $option_selected = $plugin->userSelectedTheme();
                if ($option_selected == $key) {
                    $checked = ' selected';
                } else {
                    $checked = '';
                }
                echo(
                    '<option value="' . $key . '"' . $checked
                    . '>' . $g_dark_theme_options[$key] . '</option>;'
                );
            }
            ?>
        </select>

    </td>
</tr>
