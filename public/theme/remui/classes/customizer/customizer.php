<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme customizer class
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Yogesh Shirsath
 */

namespace theme_remui\customizer;

use moodle_exception;
use theme_remui\customizer\color;

/**
 * Customizer class
 */
class customizer {
    // Add settings methods.
    use add\typography;
    use add\colors;
    use add\buttons;
    use add\header;
    use add\footer;
    use add\login;
    use add\quicksetup;
    use add\layout;

    // Add processing methods.
    use process\body;
    use process\buttons;
    use process\colors;
    use process\heading;
    use process\header;
    use process\footer;
    use process\login;
    use process\radius;
    use process\iconset;

    /**
     * Instance for singletone
     *
     * @var null
     */
    private static $instance = null;

    /**
     * Fonts local copy
     *
     * @var array
     */
    public static $fonts = null;

    /**
     * Fonts local copy with inherit option
     *
     * @var array
     */
    public static $fontsinherit = null;

    /**
     * Panels array
     *
     * @var array
     */
    private $panels = [];

    /**
     * Temporary object to prepare panel.
     *
     * @var array
     */
    private $preppanel = [];

    /**
     * Settings key value array.
     *
     * @var array
     */
    private $settings = [];

    /**
     * Device sizes.
     *
     * @var array
     */
    private $devices = [
        'tablet' => 768,
        'mobile' => 480,
    ];

    /**
     * Default text transform options.
     *
     * @var array
     */
    private $texttransform = [];

    /**
     * Default colors.
     *
     * @var array
     */
    private $color = [];

    /**
     * Additional options.
     *
     * @var mixed
     */
    private static $options = null;

    /**
     * Get singletone instance of customizer class.
     * @param array $options Additional options
     * @return customizer
     */
    public static function instance($options = []) {
        if (self::$instance == null) {
            self::$instance = new customizer();
        }
        self::$options = $options;
        return self::$instance;
    }

    /**
     * Check whether resetting mode is on.
     * Currently using only for checkbox.
     *
     * @return boolean
     */
    public static function is_resetting() {
        if (self::$options == null) {
            return false;
        }
        if (!isset(self::$options['reset'])) {
            return false;
        }
        return self::$options['reset'];
    }

    /**
     * Contructor
     */
    private function __construct() {
        // Get default text transformatios options.
        $this->texttransform = [
            "inherit" => get_string("inherit", "theme_remui"),
            "none" => get_string("none", "theme_remui"),
            "capitalize" => get_string("capitalize", "theme_remui"),
            "uppercase" => get_string("uppercase", "theme_remui"),
            "lowercase" => get_string("lowercase", "theme_remui"),
        ];

        // Calcualate colors.
        $this->color = self::build_color_map('#0051F9', '#37BE71', '#4C5A73', '#D5DDEA');
        $this->add_main_settings();
    }

    /**
     * Get color for setting.
     * Use this only for default colors.
     *
     * @param string $type Color type
     * @return string color value
     */
    public function get_default_color($type) {
        return isset($this->color[$type]) ? $this->color[$type] : '';
    }

    /**
     * Build a full color map from the four main brand colors using the same
     * derivation formulas as the constructor and smartcolor.js.
     *
     * @param string $p Primary color (hex)
     * @param string $s Secondary color (hex)
     * @param string $t Text color (hex)
     * @param string $b Border color (hex)
     * @return array Full color map
     */
    public static function build_color_map(string $p, string $s, string $t, string $b): array {
        $w = '#FFFFFF';
        $color = [
            'primary'   => $p,
            'secondary' => $s,
            'text'      => $t,
            'border'    => $b,
            'white'     => $w,
        ];

        // Background Colors.
        $color['ascentbg']      = Color::shade($p, 60);
        $color['bg']            = Color::tint($p, 96);
        $color['elementbg']     = Color::tint($b, 58);
        $color['blockbg']       = $w;
        $color['headerbg']      = $w;
        $color['headerelementbg'] = Color::tint($p, 94);
        $color['footerbg']      = Color::shade($p, 90);

        // Border Colors.
        $color['lightborder']   = Color::tint($b, 58);
        $color['mediumborder']  = $b;

        // Divider Colors.
        $color['headerdividercolordark'] = Color::shade($b, 15);
        $color['footerdivider'] = Color::shade($p, 70);

        // Button - Primary.
        $color['primarybuttonbg']           = $p;
        $color['primarybuttonbghover']      = Color::shade($p, 20);
        $color['primarybuttonbgactive']     = Color::shade($p, 41);
        $color['primarybuttonborder']       = $p;
        $color['primarybuttonborderhover']  = Color::shade($p, 20);
        $color['primarybuttonborderactive'] = Color::shade($p, 41);
        $color['primarybuttontext']         = $w;
        $color['primarybuttontexthover']    = $w;
        $color['primarybuttontextactive']   = $w;
        $color['primarybuttonicon']         = $w;
        $color['primarybuttoniconhover']    = $w;
        $color['primarybuttoniconactive']   = $w;

        // Button - Secondary.
        $color['secondarybuttontext']         = $p;
        $color['secondarybuttontexthover']    = Color::shade($p, 20);
        $color['secondarybuttontextactive']   = Color::shade($p, 41);
        $color['secondarybuttonborder']       = $p;
        $color['secondarybuttonborderhover']  = Color::shade($p, 20);
        $color['secondarybuttonborderactive'] = Color::shade($p, 41);
        $color['secondarybuttonicon']         = $p;
        $color['secondarybuttoniconhover']    = Color::shade($p, 20);
        $color['secondarybuttoniconactive']   = Color::shade($p, 41);
        $color['secondarybuttonbg']           = $w;
        $color['secondarybuttonbghover']      = $w;
        $color['secondarybuttonbgactive']     = $w;

        // Text (Font).
        $color['headingstext']    = Color::shade($t, 38);
        $color['osinfotext']      = Color::tint($t, 15);
        $color['link']            = $p;
        $color['linkhover']       = Color::shade($p, 20);
        $color['headertext']      = Color::tint($t, 12);
        $color['headertexthover'] = $p;
        $color['headertextactive'] = $p;
        $color['footertext']      = $w;
        $color['footerlinktext']  = Color::tint($t, 70);

        // Icons.
        $color['headericons']      = Color::tint($t, 9);
        $color['headericonshover'] = Color::shade($t, 11);
        $color['headericonsactive'] = $p;
        $color['footericons']      = Color::tint($t, 40);
        $color['footericonshover'] = $p;

        // Login.
        $color['loginbg']                  = Color::tint($p, 96);
        $color['loginpaneltextcolor']      = Color::shade($t, 38);
        $color['loginpanelcontentcolor']   = $t;
        $color['loginpanellinkcolor']      = $p;
        $color['loginpanellinkhovercolor'] = Color::shade($p, 20);
        $color['loginpagebackgroundcolor'] = $t;

        return $color;
    }

    /**
     * Clear cache
     *
     */
    private function clear_cache() {
        global $CFG, $PAGE;
        $link = $PAGE->url;
        $link->remove_params();
        purge_other_caches();
        remove_dir($CFG->dataroot . '/temp/theme/remui');
        theme_reset_all_caches();
    }

    /**
     * Add all main settings
     *
     * @return void
     */
    private function add_main_settings() {
        $this->quicksetup_settings();
        $this->global_settings();
        $this->header_settings();
        // Icon design settings (commented out for future use).
        $this->footer_settings();
        $this->add_login_settings();
        $this->additional_css_settings();
    }

    /**
     * This method is neccessary for old users who migrated to new theme.
     * In old theme site color is stored in hex but without #.
     * This method will check and # if missing in sitecolorhex.
     *
     * @return string Site primary color
     */
    public function get_site_primary_color() {
        $sitecolor = $this->get_config('sitecolorhex');
        if (stripos($sitecolor, '#') === false) {
            $sitecolor = '#' . $sitecolor;
        }
        return $sitecolor;
    }

    /**
     * Insert setting in settings array.
     * Should not be called externally.
     * Only internal function should call this.
     *
     * @param array                        $panels    Main panels array
     * @param string                       $name      Unique name of setting
     * @param string                       $label     Label for setting
     * @param string                       $panel     Panel name
     * @param string                       $type      Type of setting panel/setting
     * @param \theme_remui\customizer\base $setting   Setting object
     * @return void
     */
    private function insert_setting(
        &$panels,
        $name,
        $label,
        $panel,
        $type,
        ?\theme_remui\customizer\elements\base $setting = null
    ) {
        if ($panel == 'root') {
            $object = (object) [
                'name' => $name,
                'label' => $label,
                'type' => $type,
            ];
            if ($type == 'panel') {
                $object->children = [];
            } else {
                $object->setting = $setting;
                $this->settings[$name] = &$object->setting;
            }
            $panels[] = $object;
            return;
        }
        foreach ($panels as $key => $panelobject) {
            if ($panelobject->name == $panel) {
                $object = (object) [
                    'name' => $name,
                    'label' => $label,
                    'type' => $type,
                ];
                if ($type == 'panel') {
                    $object->children = [];
                } else {
                    $object->setting = $setting;
                    $this->settings[$name] = &$object->setting;
                }
                $panelobject->children[] = $object;
                $panels[$key] = $panelobject;
                return;
            }
            if (!empty($panelobject->children)) {
                $this->insert_setting($panelobject->children, $name, $label, $panel, $type, $setting);
            }
        }
    }

    /**
     * Add setting in panel
     *
     * @param string $setting   Setting type
     * @param string $name      Unique name of setting
     * @param string $label     Label for setting
     * @param string $panel     Panel name
     * @param array  $options   Setting options
     * @return void
     */
    public function add_setting($setting, $name, $label, $panel, $options = []) {
        if (!class_exists("theme_remui\customizer\\elements\\" . $setting)) {
            throw new moodle_exception(
                'err_setting_type_not_supported',
                'theme_remui',
                '',
                [],
                "theme_remui\customizer\\elements\\" . $setting . " setting type does not exists"
            );
        }
        $class = "theme_remui\customizer\\elements\\" . $setting;
        $options['name'] = $name;
        $options['label'] = $label;
        $this->insert_setting(
            $this->panels,
            $name,
            $label,
            $panel,
            'setting',
            new $class($options)
        );
    }

    /**
     * Add panel
     *
     * @param string                       $name      Unique name of panel
     * @param string                       $label     Label for setting
     * @param string                       $parent    Parent panel name
     * @param \theme_remui\customizer\base $setting   Setting object
     * @return void
     */
    public function add_panel($name, $label, $parent, ?\theme_remui\customizer\elements\base $setting = null) {
        $this->insert_setting($this->panels, $name, $label, $parent, 'panel', $setting);
    }

    /**
     * Get font array
     *
     * @param array $custom Custom font array
     * @return array
     */
    public function get_fonts($custom = []) {
        if (self::$fonts == null || self::$fontsinherit == null) {
            $fontsobject = new fonts();
            $fonts = $fontsobject->get_fonts();
            self::$fonts = array_combine($fonts, $fonts);
        }
        if (!empty($custom)) {
            return array_merge($custom, self::$fonts);
        }
        return self::$fonts;
    }

    /**
     * Add global settings
     */
    private function global_settings() {
        $this->add_panel('global', get_string('global', 'theme_remui'), 'root');

        $this->add_panel('site', get_string('sitefavicon', 'theme_remui'), 'global');
        // Favicon.
        $label = get_string('favicon', 'theme_remui');
        $name = 'faviconurl';
        $this->add_setting(
            'file',
            $name,
            $label,
            'site',
            [
                'help' => get_string('favicondesc', 'theme_remui'),
                'description' => get_string('favicosize', 'theme_remui'),
                'options' => [
                    'subdirs' => 0,
                    'maxfiles' => 1,
                    'accepted_types' => ['web_image'],
                ],
            ]
        );

        $this->add_theme_colors();

        $this->add_global_typography();

        $this->add_global_radius_settings();

        $this->add_global_icon_settings();

        $this->add_global_layout();

        $this->add_global_buttons();
    }

    /**
     * Add addition css settings
     */
    private function additional_css_settings() {
        $label = get_string('customcss', 'theme_remui');
        $this->add_panel('additional-css-page', $label, 'root');
        $this->add_setting(
            'textarea',
            'customcss',
            $label,
            'additional-css-page',
            [
                'default' => '',
                'help' => get_string('customcssdesc', 'theme_remui'),
                'options' => [
                    'rows' => 10,
                ],
            ]
        );
    }

    /**
     * Prepare accordion data for template
     *
     * @param object $panel    Panel object
     * @param string $parent   Parent name
     */
    private function prepare_accordion($panel, $parent) {
        $object = (object)[
            'name' => $panel->name,
            'label' => $panel->label,
        ];
        if ($panel->type == 'panel') {
            $object->panel = true;
            $label = $panel->label;
            $label = '<a href="#" class="title-item" sidebar-panel-link data-panel-id="' . $panel->name . '">' . $label . '</a>';
            if (isset($this->preppanel[$parent]) && $this->preppanel[$parent]->name != 'root') {
                $label = $this->preppanel[$parent]->label .
                ' / ' . $label;
            } else {
                $label = '<a href="#" class="title-item" sidebar-panel-link data-panel-id="root">' .
                get_string('site', 'theme_remui') . '</a> / ' . $label;
            }
            $this->preppanel[$panel->name] = (object)[
                'name' => $panel->name,
                'label' => $label,
                'parent' => $parent,
                'children' => [],
            ];
            if (isset($panel->children)) {
                foreach ($panel->children as $setting) {
                    $this->prepare_accordion($setting, $panel->name);
                }
            }
        } else {
            $object->setting = $panel->setting;
        }
        $this->preppanel[$parent]->children[] = $object;
    }

    /**
     * Prepare and return accordion data
     */
    public function accordion() {
        global $SITE;
        $this->preppanel = [];
        $panels = $this->panels;
        $this->preppanel['root'] = (object)[
            'name' => 'root',
            'label' => 'Root',
            'current' => true,
            'root' => [
                'label' => $SITE->shortname,
            ],
            'children' => [],
        ];
        foreach ($panels as $panel) {
            $this->prepare_accordion($panel, 'root');
        }
        return array_values($this->preppanel);
    }

    /**
     * Customizer public method to save settings to database
     *
     * @param array $settings Settings array
     * @return array
     */
    public function save($serializedsettings) {
        $formsettings = [];
        foreach ($serializedsettings as $setting) {
            if (!isset($formsettings[$setting['name']])) {
                $formsettings[$setting['name']] = $setting['value'];
                continue;
            }
            if (!is_array($formsettings[$setting['name']])) {
                $formsettings[$setting['name']] = [$formsettings[$setting['name']]];
            }
            $formsettings[$setting['name']][] = $setting['value'];
        }
        // Error handling on theme color because its settings has been called twice.
        $normalizeonly = [
            'sitecolorhex',
            'secondarycolor',
            'themecolors-textcolor',
            'themecolors-bordercolor',
            'quicksetup-card-radius',
            'quicksetup-btn-radius',
            'quicksetup-iconset',
        ];
        foreach ($formsettings as $name => $value) {
            if (in_array($name, $normalizeonly, true) && is_array($value)) {
                $formsettings[$name] = end($value);
            }
        }
        $response = [
            'status' => true,
            'errors' => json_encode([]),
            'message' => get_string('savesuccess', 'theme_remui'),
        ];
        $errors = [];
        foreach ($this->settings as $setting) {
            if ($setting->do_not_save()) {
                continue;
            }
            $setting->process_form_save($formsettings, $errors);
        }
        $this->sync_header_settings_to_core();
        $this->clear_cache();
        return $response;
    }

    /**
     * Get config from settings.
     *
     * @param string $name Name
     * @param bool   $devices Get config of all devices
     * @return mixed config from database.
     */
    public function get_config($name, $devices = false) {
        if (!isset($this->settings[$name])) {
            return null;
        }
        return $this->settings[$name]->get_config($devices);
    }

    /**
     * Wrap content in responsive media query.
     *
     * @param  string $device  Target device.
     * @param  string $content CSS content
     * @return string          Css content
     */
    private function wrap_responsive($device, $content) {
        if ($device == 'tablet') {
            return "@media screen and (min-width: " . ($this->devices['mobile'] + 1) . "px)
            and (max-width: " . $this->devices['tablet'] . "px) {
                {$content}
            }";
        }
        if ($device == 'mobile') {
            return "@media screen and (max-width: " . $this->devices['mobile'] . "px) {
                {$content}
            }";
        }
        return $content;
    }

    /**
     * Get fallback font list.
     *
     * @return string
     */
    public function get_font_fallback() {
        return implode(", ", [
            "-apple-system",
            "BlinkMacSystemFont",
            "Segoe UI",
            "Inter",
            "Helvetica Neue",
            "Arial",
            "Noto Sans",
            "Liberation Sans",
            "sans-serif",
            "Apple Color Emoji",
            "Segoe UI Emoji",
            "Segoe UI Symbol",
            "Noto Color Emoji",
        ]);
    }

    /**
     * Get font list which will be loaded in page head.
     *
     * @return array
     */
    public function get_fonts_to_load() {
        $fonts = [];
        $this->get_global_font($fonts);
        $this->get_heading_fonts($fonts);
        $this->get_button_fonts($fonts);
        $this->get_header_fonts($fonts);
        $this->get_footer_fonts($fonts);
        unset($fonts['inherit']);
        unset($fonts['Inherit']);
        return array_keys($fonts);
    }

    /**
     * Validate responsive sizes and set null if don't exists;
     *
     * @param array $vars Array of variables
     *
     * @return array
     */
    private function validate_responsive_sizes($vars) {
        foreach ($vars as $key => $value) {
            if (!isset($value['tablet']) || $value['tablet'] == '') {
                $value['tablet'] = 'null';
            }
            if (!isset($value['mobile']) || $value['mobile'] == '') {
                $value['mobile'] = 'null';
            }
            $value['tablet'] = $value['tablet'] == $value['default'] ? 'null' : $value['tablet'];
            $value['mobile'] = $value['mobile'] == $value['default'] ? 'null' : $value['mobile'];
            $vars[$key] = $value;
        }
        return $vars;
    }

    /**
     * Process settings and generate variables settings.
     * @return array Variables array for scss.
     */
    public function process() {
        $variables = [];

        $this->process_global_base($variables);
        $this->process_global_heading($variables);
        $this->process_global_colors($variables);
        $this->process_global_buttons($variables);
        $this->process_radius($variables);
        $this->process_iconset($variables);
        $this->process_header($variables);
        $this->process_login($variables);
        $this->process_footer($variables);
        // Validate settings.
        // To make sure no setting breaks scss.
        foreach ($variables as $key => $value) {
            if (empty($value) && $value !== 0) {
                $variables[$key] = 'null';
            }
        }
        return $variables;
    }
}
