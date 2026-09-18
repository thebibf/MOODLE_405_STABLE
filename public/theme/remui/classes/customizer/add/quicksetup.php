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
 * Theme customizer buttons trait
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Yogesh Shirsath
 */

namespace theme_remui\customizer\add;

define('EDW_STATIC_CDN', 'https://staticcdn.edwiser.org');
use theme_remui\customizer\Color;

/**
 * Quick setup trait.
 *
 * Provides functionality for adding quick setup customizer settings including color schemes and fonts.
 */
trait quicksetup {
    /**
     * Quick setup settings for customizer panel.
     *
     * @return void
     */
    private function quicksetup_settings() {
        $panel = 'quicksetup';
        $this->add_panel($panel, get_string('quicksetup', 'theme_remui'), 'root');

        $this->add_theme_presets();

        $this->add_color_schemes();

        $this->add_brand_colors_settings($panel);

        $this->add_fonts_selector();

        $this->add_radius_settings();

        $this->add_icon_selection();

        // Apply button to apply pallet on click (commented out for future use).
    }

    /**
     * Add theme preset selector to the Quick Setup panel.
     *
     * Renders the section heading with help icon, Default/Modern radio cards,
     * and an info box with Note, Tip, and the (initially disabled) Apply Preset button.
     *
     * @return void
     */
    private function add_theme_presets() {
        global $CFG;
        $panel = 'quicksetup';

        // Default preset: footer colors are handled in JS per the active footer design.
        $default_colors = \theme_remui\customizer\customizer::build_color_map('#0051f9', '#37be71', '#4C5A73', '#D5DDEA');
        $footer_keys = [
            'footerbg', 'footermainbg', 'footerbottombg', 'footerbottomtext', 'footermaintext',
            'footertext', 'footerlinktext', 'footerdivider',
            'footericons', 'footericonshover', 'footericonbg',
        ];
        foreach ($footer_keys as $k) {
            unset($default_colors[$k]);
        }

        $modern_colors = \theme_remui\customizer\customizer::build_color_map('#7C3AED', '#06AAC6', '#1E293B', '#E5EAF0');
        $modern_colors['footerbg']          = '#F0E5FF';
        $modern_colors['footermainbg']      = '#F0E5FF';
        $modern_colors['footerbottombg']    = '#7C3AED';
        $modern_colors['footerbottomtext']  = '#FFFFFF';
        $modern_colors['footermaintext']    = '#1E293B';
        $modern_colors['footertext']        = '#1E293B';
        $modern_colors['footerlinktext']    = '#7C3AED';
        $modern_colors['footerdivider']     = '#C4B5FD';
        $modern_colors['footericons']       = '#FFFFFF';
        $modern_colors['footericonshover']  = '#E9D5FF';
        $modern_colors['footericonbg']      = '#7C3AED';

        $classic_colors = \theme_remui\customizer\customizer::build_color_map('#E00040', '#7678ED', '#2B2D42', '#E5E7EB');
        $classic_colors['footerbg']          = '#FFE5E8';
        $classic_colors['footermainbg']      = '#FFE5E8';
        $classic_colors['footerbottombg']    = '#E00040';
        $classic_colors['footerbottomtext']  = '#FFFFFF';
        $classic_colors['footermaintext']    = '#444444';
        $classic_colors['footertext']        = '#444444';
        $classic_colors['footerlinktext']    = '#444444';
        $classic_colors['footerdivider']     = '#E00040';
        $classic_colors['footericons']       = '#FFFFFF';
        $classic_colors['footericonshover']  = '#99002B';
        $classic_colors['footericonbg']      = '#E00040';

        $preset_configs = [
            'preset-default' => [
                'colors'     => $default_colors,
                'font'       => 'Inter',
                'cardRadius' => 8,
                'btnRadius'  => 5,
                'iconset'    => 'iconset-default',
            ],
            'preset-modern' => [
                'colors'     => $modern_colors,
                'font'       => 'Sora',
                'cardRadius' => 20,
                'btnRadius'  => 128,
                'iconset'    => 'iconset-set1',
            ],
            'preset-classic' => [
                'colors'     => $classic_colors,
                'font'       => 'Space Grotesk',
                'cardRadius' => 0,
                'btnRadius'  => 0,
                'iconset'    => 'iconset-set2',
            ],
        ];

        // Build radio card options for Default and Modern presets.
        $presets = [
            [
                'name'  => 'preset-default',
                'label' => 'Standard',
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/theme_preset_default.png',
            ],
            [
                'name'  => 'preset-modern',
                'label' => get_string('themepreset_modern', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/theme_preset_modern.png',
            ],
            [
                'name'  => 'preset-classic',
                'label' => get_string('themepreset_classic', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/theme_preset_classic.svg',
            ],
        ];

        $options = [];
        foreach ($presets as $preset) {
            $content  = "<div class='theme-preset-preview'>";
            $content .= "<img class='theme-preset-image' src='" . $preset['image'] . "' alt='" . $preset['label'] . "'>";
            $content .= "</div>";
            $content .= "<button type='button' name='preset-reset' class='reset-button btn p-0 preset-reset-btn'" .
                " title='" . get_string('preset_reset_tooltip', 'theme_remui') . "'>" .
                "<span class='edw-icon edw-icon-Refresh small'></span></button>";
            $options[] = [
                'name'    => $preset['name'],
                'class'   => 'theme-preset-option',
                'label'   => $preset['label'],
                'content' => $content,
                'data'    => [[
                    'key'   => 'preset',
                    'value' => json_encode($preset_configs[$preset['name']]),
                ]],
            ];
        }

        $this->add_setting(
            'radio_modified',
            'radio_themepreset',
            get_string('themepresets', 'theme_remui'),
            $panel,
            [
                'help'    => get_string('themepresets_help', 'theme_remui'),
                'default' => 'preset-default',
                'options' => $options,
            ]
        );

        // Info box: Note + Tip + Apply Preset button.
        $this->add_setting(
            'html',
            'theme-preset-info-box',
            get_string('themepresets_note', 'theme_remui'),
            $panel,
            [
                'content' => '
                    <div class="theme-preset-info-box">
                        <div class="theme-preset-info">
                            <p class="small-info-regular mb-1">
                                <strong>' . get_string('note', 'theme_remui') . ':</strong> ' .
                    get_string('themepresets_note', 'theme_remui') . '
                            </p>
                            <p class="small-info-regular mb-3">
                                <strong>' . get_string('tip', 'theme_remui') . ':</strong> ' .
                    get_string('themepresets_tip', 'theme_remui') . '
                            </p>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" name="preset-apply" id="id_preset-apply"
                                class="btn btn-secondary btn-sm" disabled>' .
                    get_string('themepresets_apply', 'theme_remui') . '
                            </button>
                        </div>
                    </div>
                ',
            ]
        );
    }

    /**
     * Add color schemes settings.
     *
     * @return void
     */
    private function add_color_schemes() {

        // Color Preset settings.
        $pallets = [[
            'label' => get_string('default', 'theme_remui'),
            'preset' => [
                'primary' => "#0051f9",
                'secondary' => '#37be71',
                'text' => '#4C5A73',
                'border' => '#D5DDEA',

                // Background colors.
                'ascentbg' => '#1C376F',
                'bg' => '#F5F9FD',
                'elementbg' => '#EBF0F9',
                'blockbg' => '#FFFFFF',
                'headerbg' => '#FFFFFF',
                'headerelementbg' => '#F0F6FC',
                'footerbg' => '#000819',

                // Border colors.
                'lightborder' => '#EBF0F9',
                'mediumborder' => '#D5DDEA',
                // Divider colors.
                'headerdividercolordark' => '#7590C2',
                'footerdivider' => '#00184B',

                // Button - Primary.
                'primarybuttonbg' => '#0051f9',
                'primarybuttonbghover' => Color::shade('#0051f9', 20),
                'primarybuttonborder' => '#0051f9',
                'primarybuttonborderhover' => Color::shade('#0051f9', 20),
                'primarybuttontext' => '#FFFFFF',
                'primarybuttonicon' => '#FFFFFF',

                // Button - Secondary.
                'secondarybuttontext' => '#0051f9',
                'secondarybuttontexthover' => Color::shade('#0051f9', 20),
                'secondarybuttonborder' => '#0051f9',
                'secondarybuttonborderhover' => Color::shade('#0051f9', 20),
                'secondarybuttonicon' => '#0051f9',
                'secondarybuttoniconhover' => Color::shade('#0051f9', 20),
                'secondarybuttonbg' => '#FFFFFF',

                // Text (Font).
                'headingstext' => '#313848',
                'osinfotext' => '#647390',
                'link' => '#0051f9',
                'linkhover' => '#0041C7',
                'headertext' => '#63718C',
                'headertexthover' => '#0051F9',
                'headertextactive' => '#0051F9',
                'footertext' => '#FFFFFF',
                'footerlinktext' => '#D0D4DD',

                // Icon - Single Color.
                'singlecoloricon' => '#5B6880',
                'singlecoloriconhover' => '#465062',
                'singlecoloriconactive' => '#003094',
                'headericons' => '#5B6880',
                'headericonshover' => '#465062',
                'headericonsactive' => '#0051F9',
                'footericons' => '#909BB1',
                'footericonshover' => '#FFFFFF',

                // Login page colors.
                'loginpaneltextcolor' => '#2f3847',
                'loginpanelcontentcolor' => '#4c5a73',
                'loginpanellinkcolor' => '#0051f9',
                'loginpanellinkhovercolor' => '#0041c7',
                'loginbg' => '#FFFFFF',

                'loginpagebackgroundcolor' => '#4C5A73',
            ],
        ], [
            'label' => get_string('pallete1', 'theme_remui'),
            'preset' => [
                'primary' => "#FF7272",
                'secondary' => '#472A52',
                'text' => '#460061',
                'border' => '#D8CACA',

                // Background colors.
                'ascentbg' => '#724242',
                'bg' => '#FFFAFA',
                'elementbg' => '#EFE9E9',
                'blockbg' => '#FFFFFF',
                'headerbg' => '#FFFFFF',
                'headerelementbg' => '#FFF7F7',
                'footerbg' => '#1A0B0B',

                // Border colors.
                'lightborder' => '#EFE9E9',
                'mediumborder' => '#D8CACA',

                // Divider colors.
                'headerdividercolordark' => '#B8ACAC',
                'footerdivider' => '#4D2222',

                // Button - Primary.
                'primarybuttonbg' => '#FF7272',
                'primarybuttonbghover' => Color::shade('#FF7272', 20),
                'primarybuttonborder' => '#FF7272',
                'primarybuttonborderhover' => Color::shade('#FF7272', 20),
                'primarybuttontext' => '#FFFFFF',
                'primarybuttonicon' => '#FFFFFF',

                // Button - Secondary.
                'secondarybuttontext' => '#FF7272',
                'secondarybuttontexthover' => Color::shade('#FF7272', 20),
                'secondarybuttonborder' => '#FF7272',
                'secondarybuttonborderhover' => Color::shade('#FF7272', 20),
                'secondarybuttonicon' => '#FF7272',
                'secondarybuttoniconhover' => Color::shade('#FF7272', 20),
                'secondarybuttonbg' => '#FFFFFF',

                // Text (Font).
                'headingstext' => '#2B003C',
                'osinfotext' => '#622679',
                'link' => '#FF7272',
                'linkhover' => '#CC5B5B',
                'headertext' => '#5C1F74',
                'headertexthover' => '#FF7272',
                'headertextactive' => '#FF7272',
                'footertext' => '#FFFFFF',
                'footerlinktext' => '#C7B2D0',

                // Single color icon settings (commented out for future use).
                'headericons' => '#57176F',
                'headericonshover' => '#3E0056',
                'headericonsactive' => '#FF7272',
                'footericons' => '#9066A0',
                'footericonshover' => '#FFFFFF',

                // Login page colors.
                'loginpaneltextcolor' => '#2b003c',
                'loginpanelcontentcolor' => '#460061',
                'loginpanellinkcolor' => '#ff7272',
                'loginpanellinkhovercolor' => '#cc5b5b',
                'loginbg' => '#fff9f9',

                'loginpagebackgroundcolor' => '#460061',
            ],
        ], [
            'label' => get_string('pallete2', 'theme_remui'),
            'preset' => [
                'primary' => "#0FC8BC",
                'secondary' => '#1C232C',
                'text' => '#1C006D',
                'border' => '#B9D4D2',

                // Background colors.
                'ascentbg' => '#21605B',
                'bg' => '#F6FDFC',
                'elementbg' => '#E2EDEC',
                'blockbg' => '#FFFFFF',
                'headerbg' => '#FFFFFF',
                'headerelementbg' => '#F1FCFB',
                'footerbg' => '#011413',

                // Border colors.
                'lightborder' => '#E2EDEC',
                'mediumborder' => '#B9D4D2',

                // Divider colors.
                'headerdividercolordark' => '#9DB4B3',
                'footerdivider' => '#043C38',

                // Button - Primary.
                'primarybuttonbg' => '#0FC8BC',
                'primarybuttonbghover' => Color::shade('#0FC8BC', 20),
                'primarybuttonborder' => '#0FC8BC',
                'primarybuttonborderhover' => Color::shade('#0FC8BC', 20),
                'primarybuttontext' => '#FFFFFF',
                'primarybuttonicon' => '#FFFFFF',

                // Button - Secondary.
                'secondarybuttontext' => '#0FC8BC',
                'secondarybuttontexthover' => Color::shade('#0FC8BC', 20),
                'secondarybuttonborder' => '#0FC8BC',
                'secondarybuttonborderhover' => Color::shade('#0FC8BC', 20),
                'secondarybuttonicon' => '#0FC8BC',
                'secondarybuttoniconhover' => Color::shade('#0FC8BC', 20),
                'secondarybuttonbg' => '#FFFFFF',

                // Text (Font).
                'headingstext' => '#110044',
                'osinfotext' => '#3E2683',
                'link' => '#0FC8BC',
                'linkhover' => '#0CA096',
                'headertext' => '#371F7F',
                'headertexthover' => '#0FC8BC',
                'headertextactive' => '#0FC8BC',
                'footertext' => '#FFFFFF',
                'footerlinktext' => '#BBB2D3',

                // Single color icon settings (commented out for future use).
                'headericons' => '#30177A',
                'headericonshover' => '#190061',
                'headericonsactive' => '#0FC8BC',
                'footericons' => '#7766A7',
                'footericonshover' => '#FFFFFF',

                // Login page colors.
                'loginpaneltextcolor' => '#110044',
                'loginpanelcontentcolor' => '#1c006d',
                'loginpanellinkcolor' => '#0fc8bc',
                'loginpanellinkhovercolor' => '#0ca096',
                'loginbg' => '#f5fdfc',

                'loginpagebackgroundcolor' => '#1C006D',
            ],
        ], [
            'label' => get_string('pallete3', 'theme_remui'),
            'preset' => [
                'primary' => "#F26440",
                'secondary' => '#0D2F3F',
                'text' => '#3B3B3B',
                'border' => '#EAC2B8',

                // Background colors.
                'ascentbg' => '#6E3E32',
                'bg' => '#FEF9F8',
                'elementbg' => '#F6E5E1',
                'blockbg' => '#FFFFFF',
                'headerbg' => '#FFFFFF',
                'headerelementbg' => '#FEF6F4',
                'footerbg' => '#180A06',

                // Border colors.
                'lightborder' => '#F6E5E1',
                'mediumborder' => '#EAC2B8',

                // Divider colors.
                'headerdividercolordark' => '#C7A59C',
                'footerdivider' => '#491E13',

                // Button - Primary.
                'primarybuttonbg' => '#F26440',
                'primarybuttonbghover' => Color::shade('#F26440', 20),
                'primarybuttonborder' => '#F26440',
                'primarybuttonborderhover' => Color::shade('#F26440', 20),
                'primarybuttontext' => '#FFFFFF',
                'primarybuttonicon' => '#FFFFFF',

                // Button - Secondary.
                'secondarybuttontext' => '#F26440',
                'secondarybuttontexthover' => Color::shade('#F26440', 20),
                'secondarybuttonborder' => '#F26440',
                'secondarybuttonborderhover' => Color::shade('#F26440', 20),
                'secondarybuttonicon' => '#F26440',
                'secondarybuttoniconhover' => Color::shade('#F26440', 20),
                'secondarybuttonbg' => '#FFFFFF',

                // Text (Font).
                'headingstext' => '#1D1D1D',
                'osinfotext' => '#4F4F4F',
                'link' => '#F26440',
                'linkhover' => '#C25033',
                'headertext' => '#353535',
                'headertexthover' => '#F26440',
                'headertextactive' => '#F26440',
                'footertext' => '#FFFFFF',
                'footerlinktext' => '#C4C4C4',

                // Single color icon settings (commented out for future use).
                'headericons' => '#353535',
                'headericonshover' => '#1D1D1D',
                'headericonsactive' => '#F26440',
                'footericons' => '#C4C4C4',
                'footericonshover' => '#FFFFFF',

                // Login page colors.
                'loginpaneltextcolor' => '#252525',
                'loginpanelcontentcolor' => '#3b3b3b',
                'loginpanellinkcolor' => '#f26440',
                'loginpanellinkhovercolor' => '#c25033',
                'loginbg' => '#fef9f7',

                'loginpagebackgroundcolor' => '#3B3B3B',
            ],
        ], [
            'label' => get_string('pallete4', 'theme_remui'),
            'preset' => [
                'primary' => "#7c3aed",
                'secondary' => '#06aac6',
                'text' => '#1e293b',
                'border' => '#e5eaf0',

                // Background colors.
                'ascentbg' => '#32175f',
                'bg' => '#faf7fe',
                'elementbg' => '#f4f6f9',
                'blockbg' => '#FFFFFF',
                'headerbg' => '#FFFFFF',
                'headerelementbg' => '#f7f3fe',
                'footerbg' => '#0c0618',

                // Border colors.
                'lightborder' => '#f4f6f9',
                'mediumborder' => '#e5eaf0',

                // Divider colors.
                'headerdividercolordark' => '#c3c7cc',
                'footerdivider' => '#251147',

                // Button - Primary.
                'primarybuttonbg' => '#7c3aed',
                'primarybuttonbghover' => Color::shade('#7c3aed', 20),
                'primarybuttonborder' => '#7c3aed',
                'primarybuttonborderhover' => Color::shade('#7c3aed', 20),
                'primarybuttontext' => '#FFFFFF',
                'primarybuttonicon' => '#FFFFFF',

                // Button - Secondary.
                'secondarybuttontext' => '#7c3aed',
                'secondarybuttontexthover' => Color::shade('#7c3aed', 20),
                'secondarybuttonborder' => '#7c3aed',
                'secondarybuttonborderhover' => Color::shade('#7c3aed', 20),
                'secondarybuttonicon' => '#7c3aed',
                'secondarybuttoniconhover' => Color::shade('#7c3aed', 20),
                'secondarybuttonbg' => '#FFFFFF',

                // Text (Font).
                'headingstext' => '#131925',
                'osinfotext' => '#4F4F4F',
                'link' => '#7c3aed',
                'linkhover' => '#632ebe',
                'headertext' => '#394353',
                'headertexthover' => '#7c3aed',
                'headertextactive' => '#7c3aed',
                'footertext' => '#FFFFFF',
                'footerlinktext' => '#bcbfc4',

                // Single color icon settings (commented out for future use).
                'headericons' => '#394353',
                'headericonshover' => '#1b2435',
                'headericonsactive' => '#7c3aed',
                'footericons' => '#787f89',
                'footericonshover' => '#FFFFFF',

                // Login page colors.
                'loginpaneltextcolor' => '#131925',
                'loginpanelcontentcolor' => '#1e293b',
                'loginpanellinkcolor' => '#7c3aed',
                'loginpanellinkhovercolor' => '#632ebe',
                'loginbg' => '#faf7fe',

                'loginpagebackgroundcolor' => '#1e293b',
            ],
        ], [
            'label' => get_string('pallete5', 'theme_remui'),
            'preset' => [
                'primary' => "#e00040",
                'secondary' => '#7678ed',
                'text' => '#2b2d42',
                'border' => '#e5e7eb',

                // Background colors.
                'ascentbg' => '#5a001a',
                'bg' => '#fef5f7',
                'elementbg' => '#f4f5f7',
                'blockbg' => '#FFFFFF',
                'headerbg' => '#FFFFFF',
                'headerelementbg' => '#fdf0f4',
                'footerbg' => '#000819',

                // Border colors.
                'lightborder' => '#f4f5f7',
                'mediumborder' => '#e5e7eb',

                // Divider colors.
                'headerdividercolordark' => '#c3c4c8',
                'footerdivider' => '#00184b',

                // Button - Primary.
                'primarybuttonbg' => '#e00040',
                'primarybuttonbghover' => Color::shade('#e00040', 20),
                'primarybuttonborder' => '#e00040',
                'primarybuttonborderhover' => Color::shade('#e00040', 20),
                'primarybuttontext' => '#FFFFFF',
                'primarybuttonicon' => '#FFFFFF',

                // Button - Secondary.
                'secondarybuttontext' => '#e00040',
                'secondarybuttontexthover' => Color::shade('#e00040', 20),
                'secondarybuttonborder' => '#e00040',
                'secondarybuttonborderhover' => Color::shade('#e00040', 20),
                'secondarybuttonicon' => '#e00040',
                'secondarybuttoniconhover' => Color::shade('#e00040', 20),
                'secondarybuttonbg' => '#FFFFFF',

                // Text (Font).
                'headingstext' => '#1b1c29',
                'osinfotext' => '#4F4F4F',
                'link' => '#e00040',
                'linkhover' => '#b30033',
                'headertext' => '#444659',
                'headertexthover' => '#e00040',
                'headertextactive' => '#e00040',
                'footertext' => '#FFFFFF',
                'footerlinktext' => '#f5f8ff',

                // Single color icon settings (commented out for future use).
                'headericons' => '#3e4053',
                'headericonshover' => '#26283b',
                'headericonsactive' => '#e00040',
                'footericons' => '#E00040',
                'footericonshover' => '#99002B',

                // Login page colors.
                'loginpaneltextcolor' => '#2b2d42',
                'loginpanelcontentcolor' => '#1b1c29',
                'loginpanellinkcolor' => '#e00040', 
                'loginpanellinkhovercolor' => '#b30033',
                'loginbg' => '#fef5f7',

                'loginpagebackgroundcolor' => '#2b2d42',
            ],
        ]];

        $panel = 'quicksetup';

        $this->add_setting(
            'html',
            'current-pallet-heading',
            get_string('currentpallet', 'theme_remui'),
            $panel,
            [
                'content' => '
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="col-form-label" for="id_current-pallet">
                        ' . get_string('colorpalletes', 'theme_remui') . '
                        </label>
                    </div>
                ',
            ]
        );

        // Show current color.
        $this->add_setting(
            'html',
            'current-pallet',
            get_string('currentpallet', 'theme_remui'),
            $panel,
            [
                'content' => '
                <div class="current-pallete color-pallet" title="' . get_string('currentpallet', 'theme_remui') . '">
                    <label class="option-label mb-0 ">' . get_string('currentpallet', 'theme_remui') .
                    '<i class="edw-icon edw-icon-Edit" aria-hidden="true" title="Edit Palette"></i>' .
                    '<i class="edw-icon edw-icon-Refresh small" title="Reset Palette"></i></label>
                    <div class="pallet-color">
                        <span style="background-color: #FF7272;"></span>
                        <span style="background-color: #472A52;"></span>
                        <span style="background-color: #460061;"></span>
                        <span style="background-color: #D8CACA;"></span>
                    </div>
                </div>
                ',
            ]
        );

        $this->add_setting(
            'html',
            'current-pallet',
            get_string('selectpallete', 'theme_remui'),
            $panel,
            [
                'content' => '
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="col-form-label v2" for="id_current-pallet">
                        ' . get_string('selectpallete', 'theme_remui') . '
                        </label>
                        <button type="button" name="pallet-apply" id="id_pallet-apply" class="btn btn-secondary btn-sm" disabled>' .
                        ' ' . get_string('apply', 'theme_remui') . '</button>
                    </div>
                ',
            ]
        );

        $options = [];

        foreach ($pallets as $index => $pallet) {
            $num = $index + 1;
            $option = [
                'name' => 'pallet-' . $num,
                'class' => 'color-pallet',
                'label' => $pallet['label'],
                'content' => "",
                'data' => [[
                    'key' => 'colors',
                    'value' => json_encode($pallet['preset']),
                ]],
            ];
            $content = "<div class='pallet-color'>";
            $content .= "<span style=\"background-color: " . $pallet['preset']['primary'] . ";\"></span>";
            $content .= "<span style=\"background-color: " . $pallet['preset']['secondary'] . ";\"></span>";
            $content .= "<span style=\"background-color: " . $pallet['preset']['text'] . ";\"></span>";
            $content .= "<span style=\"background-color: " . $pallet['preset']['border'] . ";\"></span>";
            $content .= "</div>";
            $option['content'] = $content;
            $options[] = $option;
        }
        $name = 'colorpallet';
        $label = get_string('selectpallete', 'theme_remui');
        $this->add_setting(
            'radio',
            $name,
            $label,
            $panel,
            [
                'help' => get_string('colorpalletdesc', 'theme_remui'),
                'default' => false,
                'options' => $options,
            ]
        );
    }

    /**
     * Add fonts selector settings.
     *
     * @return void
     */
    private function add_fonts_selector() {
        $panel = 'quicksetup';

        $this->add_setting(
            'html',
            'font-family-heading',
            get_string('font-family', 'theme_remui'),
            $panel,
            [
                'content' => '
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="col-form-label" for="id_current-pallet">
                        ' . get_string('font-family', 'theme_remui') . '
                        </label>
                    </div>
                ',
            ]
        );

        $currentfont = $this->get_body_font();

        if (!$currentfont) {
            $currentfont = 'Inter';
        }

        // Show current font.
        $this->add_setting(
            'html',
            'current-font',
            get_string('currentfont', 'theme_remui'),
            $panel,
            [
                'content' => '
                <div class="current-pallete font-pallet" title="' . get_string('currentfont', 'theme_remui') . '">
                    <label class="option-label mb-0">' . get_string('currentfont', 'theme_remui') . '</label>
                    <div class="font-sample">
                        <div class="font-name"></div>
                        <div class="sample-text">A quick brown fox jumps over the lazy dog</div>
                    </div>
                </div>
                ',
            ]
        );

        $this->add_setting(
            'html',
            'current-pallet',
            get_string('selectfont', 'theme_remui'),
            $panel,
            [
                'content' => '
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="col-form-label v2" for="id_current-font">
                        ' . get_string('selectfont', 'theme_remui') . '
                        </label>
                        <button type="button" name="font-apply" id="id_font-apply" class="btn btn-secondary btn-sm" disabled>
                            ' . get_string('apply', 'theme_remui') . '
                        </button>
                    </div>
                ',
            ]
        );

        // Font selector HTML setting (commented out for future use).

        // Font list.
        $options = [];
        $url = EDW_STATIC_CDN . "/customizer/remuifonts/";
        $index = 1;

        foreach ($this->get_fonts() as $font) {
            $imageurl = $url . $font . ".png";

            $options[$font] = [
                'name' => $font,
                'label' => "",
                'class' => 'col-12 p-0 quicksetup-font-item',
                'url' => $imageurl,
            ];
        }

        $name = 'quicksetup-font-family';
        $label = get_string('font-family', 'theme_remui');
        $this->add_setting(
            'fontselect',
            $name,
            $label,
            $panel,
            [
                'help' => get_string('font-family_help', 'theme_remui'),
                'default' => 'Inter',
                'save' => false,
                'options' => $options,
            ]
        );

        // Apply button to apply pallet on click (commented out for future use).
        // 'font-apply',
        // get_string('apply', 'theme_remui'),
        // $panel,
        // [
        // 'options' => [
        // 'class' => 'btn btn-primary btn-sm d-none'
        // Apply button setting (commented out for future use).
    }

    /**
     * Add radius settings (Cards & Buttons) to the Quick Setup panel.
     *
     * Renders a section heading and two number inputs:
     *   - quicksetup-card-radius  → controls cards and block backgrounds
     *   - quicksetup-btn-radius   → controls buttons and tags
     *
     * @return void
     */
    private function add_radius_settings() {
        $panel = 'quicksetup';

        $this->add_setting(
            'heading_start',
            'radius-settings-heading',
            get_string('radius_settings', 'theme_remui'),
            $panel,
            [
                'collapsed' => true,
            ]
        );

        // Cards & Block Background radius.
        $this->add_setting(
            'number',
            'quicksetup-card-radius',
            get_string('quicksetup_card_radius', 'theme_remui') . ' (px)',
            $panel,
            [
                'help'    => get_string('quicksetup_card_radius_help', 'theme_remui'),
                'default' => 8,
                'options' => [
                    'min'  => 0,
                    'step' => 1,
                ],
            ]
        );

        // Buttons & Tags radius.
        $this->add_setting(
            'number',
            'quicksetup-btn-radius',
            get_string('quicksetup_btn_radius', 'theme_remui') . ' (px)',
            $panel,
            [
                'help'    => get_string('quicksetup_btn_radius_help', 'theme_remui'),
                'default' => 5,
                'options' => [
                    'min'  => 0,
                    'step' => 1,
                ],
            ]
        );

        $this->add_setting(
            'heading_end',
            'radius-settings-heading',
            '',
            $panel
        );
    }

    /**
     * Add icon set selector to the Quick Setup panel.
     *
     * Renders a radio_modified setting with Default and Set 1 icon options
     * and a disabled Apply Icon Set button that enables on selection.
     *
     * @return void
     */
    private function add_icon_selection() {
        global $CFG, $OUTPUT;
        $panel = 'quicksetup';

        // Render help icon the same way radio_modified does internally.
        $helpdata = new \stdClass();
        $helpdata->ltr = !right_to_left();
        $helpdata->text = get_string('iconset_settings_help', 'theme_remui');
        $helpicon = $OUTPUT->render_from_template('theme_remui/customizer/help_icon', $helpdata);

        $iconsets = [
            [
                'name'  => 'iconset-default',
                'label' => get_string('iconset_default', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/icons-default.png',
            ],
            [
                'name'  => 'iconset-set1',
                'label' => get_string('iconset_set1', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/icons-set1.png',
            ],
            [
                'name'  => 'iconset-set2',
                'label' => get_string('iconset_set2', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/icons-set2.svg',
            ],
        ];

        $options = [];
        foreach ($iconsets as $iconset) {
            $content  = "<div class='iconset-preview'>";
            $content .= "<img class='iconset-image' src='" . $iconset['image'] . "' alt='" . $iconset['label'] . "'>";
            $content .= "</div>";
            $options[] = [
                'name'    => $iconset['name'],
                'class'   => 'iconset-option',
                'label'   => $iconset['label'],
                'content' => $content,
                'data'    => [],
            ];
        }

        // Heading row: title + help icon + Apply button, all on one line.
        $this->add_setting(
            'html',
            'iconset-heading',
            get_string('iconset_settings', 'theme_remui'),
            $panel,
            [
                'content' => '
                    <div class="d-flex justify-content-between align-items-center iconset-heading-row">
                        <div class="d-flex align-items-center flex-gap-d5">
                            <label class="col-form-label mb-0">' . get_string('iconset_settings', 'theme_remui') . '</label>
                            ' . $helpicon . '
                        </div>
                        <button type="button" name="icon-apply" id="id_icon-apply"
                            class="btn btn-secondary btn-sm" disabled>' .
                            get_string('iconset_apply', 'theme_remui') . '
                        </button>
                    </div>
                ',
            ]
        );

        $this->add_setting(
            'radio_modified',
            'quicksetup-iconset',
            '',
            $panel,
            [
                'label'   => '',
                'default' => 'iconset-default',
                'options' => $options,
            ]
        );
    }

    /**
     * Add Radius Settings panel under Global (below Typography).
     * Uses the same setting names as quicksetup so they share one config value.
     *
     * @return void
     */
    public function add_global_radius_settings() {
        $panel = 'global-radius-settings';
        $this->add_panel($panel, get_string('radius', 'theme_remui'), 'global');

        $this->add_setting(
            'number',
            'quicksetup-card-radius',
            get_string('quicksetup_card_radius', 'theme_remui') . ' (px)',
            $panel,
            [
                'help'    => get_string('quicksetup_card_radius_help', 'theme_remui'),
                'default' => 8,
                'options' => [
                    'min'  => 0,
                    'step' => 1,
                ],
            ]
        );

        $this->add_setting(
            'number',
            'quicksetup-btn-radius',
            get_string('quicksetup_btn_radius', 'theme_remui') . ' (px)',
            $panel,
            [
                'help'    => get_string('quicksetup_btn_radius_help', 'theme_remui'),
                'default' => 5,
                'options' => [
                    'min'  => 0,
                    'step' => 1,
                ],
            ]
        );
    }

    /**
     * Add Icon Set panel under Global (below Typography).
     * Uses the same setting name as quicksetup so they share one config value.
     *
     * @return void
     */
    public function add_global_icon_settings() {
        global $CFG, $OUTPUT;
        $panel = 'global-icon-settings';
        $this->add_panel($panel, get_string('iconsettings', 'theme_remui'), 'global');

        $helpdata = new \stdClass();
        $helpdata->ltr = !right_to_left();
        $helpdata->text = get_string('iconset_settings_help', 'theme_remui');
        $helpicon = $OUTPUT->render_from_template('theme_remui/customizer/help_icon', $helpdata);

        $this->add_setting(
            'html',
            'global-iconset-heading',
            get_string('iconset_settings', 'theme_remui'),
            $panel,
            [
                'content' => '
                    <div class="d-flex justify-content-between align-items-center iconset-heading-row">
                        <div class="d-flex align-items-center flex-gap-d5">
                            <label class="col-form-label mb-0">' . get_string('iconset_settings', 'theme_remui') . '</label>
                            ' . $helpicon . '
                        </div>
                        <button type="button" name="icon-apply" id="id_global-icon-apply"
                            class="btn btn-secondary btn-sm" disabled>' .
                            get_string('iconset_apply', 'theme_remui') . '
                        </button>
                    </div>
                ',
            ]
        );

        $iconsets = [
            [
                'name'  => 'iconset-default',
                'label' => get_string('iconset_default', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/icons-default.png',
            ],
            [
                'name'  => 'iconset-set1',
                'label' => get_string('iconset_set1', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/icons-set1.png',
            ],
            [
                'name'  => 'iconset-set2',
                'label' => get_string('iconset_set2', 'theme_remui'),
                'image' => $CFG->wwwroot . '/theme/remui/pix/customizer/icons-set2.svg',
            ],
        ];

        $options = [];
        foreach ($iconsets as $iconset) {
            $content  = "<div class='iconset-preview'>";
            $content .= "<img class='iconset-image' src='" . $iconset['image'] . "' alt='" . $iconset['label'] . "'>";
            $content .= "</div>";
            $options[] = [
                'name'    => $iconset['name'],
                'class'   => 'iconset-option',
                'label'   => $iconset['label'],
                'content' => $content,
                'data'    => [],
            ];
        }

        $this->add_setting(
            'radio_modified',
            'quicksetup-iconset',
            '',
            $panel,
            [
                'label'     => '',
                'default'   => 'iconset-default',
                'options'   => $options,
                'groupname' => 'global-quicksetup-iconset',
            ]
        );
    }
}
