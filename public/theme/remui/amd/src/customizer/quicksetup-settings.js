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
 * Theme customizer quick setup module.
 * Handles quick setup functionality for rapid theme configuration including color schemes and fonts.
 *
 * @module     theme_remui/customizer/quicksetup-settings
 * @copyright  (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Yogesh Shirsath
 */

import $ from 'jquery';
import footer from 'theme_remui/customizer/footer';
import Utils from 'theme_remui/customizer/utils';

/**
 * MAIN settings list.
 */
var MAIN = {
    PRIMARY: '[name="sitecolorhex"]',
    SECONDARY: '[name="secondarycolor"]',
    TEXT: '[name="themecolors-textcolor"]',
    BORDER: '[name="themecolors-bordercolor"]'
};

/**
 * Secondary colors list.
 */
var SECONDARY = [
    {key: 'bg', target: '[name="global-colors-pagebackgroundcolor"]'},
    {key: 'ascentbg', target: '[name="global-colors-ascentbackgroundcolor"]'},
    {key: 'elementbg', target: '[name="global-colors-elementbackgroundcolor"]'},

    // Border color.
    {key: 'lightborder', target: '[name="themecolors-lightbordercolor"]'},
    {key: 'mediumborder', target: '[name="themecolors-mediumbordercolor"]'},

    // Link colors.
    {key: 'link', target: '[name="global-typography-body-linkcolor"]'},
    {key: 'linkhover', target: '[name="global-typography-body-linkhovercolor"]'},

    // Heading colors.
    {key: 'headingstext', target: '[name="typography-heading-all-textcolor"]'},
    {key: 'headingstext', target: '[name="typography-heading-h1-textcolor"]'},
    {key: 'headingstext', target: '[name="typography-heading-h2-textcolor"]'},
    {key: 'headingstext', target: '[name="typography-heading-h3-textcolor"]'},
    {key: 'headingstext', target: '[name="typography-heading-h4-textcolor"]'},
    {key: 'headingstext', target: '[name="typography-heading-h5-textcolor"]'},
    {key: 'headingstext', target: '[name="typography-heading-h6-textcolor"]'},

    // Primary button colors.
    {key: 'primarybuttonbg', target: '[name="button-primary-color-background"]'},
    {key: 'primarybuttonbghover', target: '[name="button-primary-color-background-hover"]'},
    {key: 'primarybuttonborder', target: '[name="button-primary-border-color"]'},
    {key: 'primarybuttonborderhover', target: '[name="button-primary-border-color-hover"]'},
    {key: 'primarybuttontext', target: '[name="button-primary-color-text"]'},
    {key: 'primarybuttontext', target: '[name="button-primary-color-text-hover"]'},
    {key: 'primarybuttonicon', target: '[name="button-primary-color-icon"]'},
    {key: 'primarybuttonicon', target: '[name="button-primary-color-icon-hover"]'},

    // Secondary button colors.
    {key: 'secondarybuttontext', target: '[name="button-secondary-color-text"]'},
    {key: 'secondarybuttontexthover', target: '[name="button-secondary-color-text-hover"]'},
    {key: 'secondarybuttonborder', target: '[name="button-secondary-border-color"]'},
    {key: 'secondarybuttonborderhover', target: '[name="button-secondary-border-color-hover"]'},
    {key: 'secondarybuttonicon', target: '[name="button-secondary-color-icon"]'},
    {key: 'secondarybuttoniconhover', target: '[name="button-secondary-color-icon-hover"]'},
    {key: 'secondarybuttonbg', target: '[name="button-secondary-color-background"]'},
    {key: 'secondarybuttonbg', target: '[name="button-secondary-color-background-hover"]'},

    // Header colors.
    {key: 'headerbg', target: '[name="logo-bg-color"]'},
    {key: 'primary', target: '[name="sitenamecolor"]'},
    {key: 'headerbg', target: '[name="header-menu-background-color"]'},
    {key: 'headertext', target: '[name="header-menu-text-color"]'},
    {key: 'headertexthover', target: '[name="header-menu-text-hover-color"]'},
    {key: 'headertextactive', target: '[name="header-menu-text-active-color"]'},
    {key: 'headerelementbg', target: '[name="header-menu-element-bg-color"]'},
    {key: 'headerdividercolordark', target: '[name="header-menu-divider-bg-color"]'},
    {key: 'headericons', target: '[name="hds-icon-color"]'},
    {key: 'headericonshover', target: '[name="hds-icon-hover-color"]'},
    {key: 'headericonsactive', target: '[name="hds-icon-active-color"]'},

    // Footer colors.
    {key: 'footerbg', target: '[name="footer-background-color"]'},
    {key: 'footermainbg', target: '[name="main-footer-area-background-color"]'},
    {key: 'footerbottombg', target: '[name="bottom-footer-area-background-color"]'},
    {key: 'footertext', target: '[name="footer-text-color"]'},
    {key: 'footermaintext', target: '[name="main-footer-area-text-color"]'},
    {key: 'footerbottomtext', target: '[name="bottom-footer-area-text-color"]'},
    {key: 'footertext', target: '[name="footer-columntitle-color"]'},
    {key: 'footerlinktext', target: '[name="footer-link-text"]'},
    {key: 'footerlinktext', target: '[name="footer-link-hover-text"]'},
    {key: 'footerdivider', target: '[name="footer-divider-color"]'},
    {key: 'footericons', target: '[name="footer-icon-color"]'},
    {key: 'footericonshover', target: '[name="footer-icon-hover-color"]'},
    {key: 'footericonbg', target: '[name="footer-icon-bg-color"]'},

    // Login page colors.
    {key: 'loginbg', target: '[name="loginpanelbackgroundcolor"]'},
    {key: 'loginpaneltextcolor', target: '[name="loginpaneltextcolor"]'},
    {key: 'loginpanelcontentcolor', target: '[name="loginpanelcontentcolor"]'},
    {key: 'loginpanellinkcolor', target: '[name="loginpanellinkcolor"]'},
    {key: 'loginpanellinkhovercolor', target: '[name="loginpanellinkhovercolor"]'},
    {key: 'loginpagebackgroundcolor', target: '[name="signuptextcolor"]'}
];

/**
 * Per-design default footer colors sourced from the CDN footer assets.
 * Keyed by the value returned by data('flayout') on the checked footer-design-selector radio.
 * Only includes colors that exist for each design; missing keys are simply not applied.
 */
var FOOTER_DESIGN_DEFAULTS = {
    footerdesign0: {
        'footer-background-color':            '#000819',
        'footer-text-color':                   '#FFFFFF',
        'footer-columntitle-color':            '#f5f8ff',
        'footer-link-text':                    '#f5f8ff',
        'footer-link-hover-text':              '#f5f8ff',
        'footer-divider-color':                '#00184b',
        'footer-icon-color':                   '#949cab',
        'footer-icon-hover-color':             '#0051f9',
    },
    footerdesign1: {
        'footer-background-color':             '#3E86F5',
        'main-footer-area-background-color':   '#F1F7FF',
        'bottom-footer-area-background-color': '#3E86F5',
        'footer-text-color':                   '#FFFFFF',
        'main-footer-area-text-color':         '#444444',
        'bottom-footer-area-text-color':       '#FFFFFF',
        'footer-columntitle-color':            '#444444',
        'footer-link-text':                    '#444444',
        'footer-link-hover-text':              '#3E86F5',
        'footer-divider-color':                '#34495E',
        'footer-icon-color':                   '#FFFFFF',
        'footer-icon-hover-color':             '#0051f9',
        'footer-icon-bg-color':                '#3e86f5',
        'emailinputbordercolor4':              '#3e86f5',
        'focusedemailinputoutlinecolor4':      '#3e86f5',
        'subscribebuttontextcolor4':            '#ffffff',
        'subscribebuttontexthovercolor4':       '#ffffff',
        'subscribebtnbgcolor4':                 '#3e86f5',
        'subscribebtnbghovercolor4':            '#0051f9',
    },
    footerdesign2: {
        'footer-background-color':             '#222222',
        'main-footer-area-background-color':   '#9A3CDF',
        'bottom-footer-area-background-color': '#222222',
        'footer-text-color':                   '#FFFFFF',
        'main-footer-area-text-color':         '#FFFFFF',
        'bottom-footer-area-text-color':       '#FFFFFF',
        'footer-columntitle-color':            '#FFFFFF',
        'footer-link-text':                    '#FFFFFF',
        'footer-link-hover-text':              '#FFFFFF',
        'footer-divider-color':                '#34495E',
        'footer-icon-color':                   '#FFFFFF',
        'footer-icon-hover-color':             '#9A3CDF',
    },
    footerdesign3: {
        'footer-background-color':             '#222222',
        'main-footer-area-background-color':   '#222222',
        'bottom-footer-area-background-color': '#222222',
        'footer-text-color':                   '#FFFFFF',
        'main-footer-area-text-color':         '#FFFFFF',
        'footer-columntitle-color':            '#FFFFFF',
        'footer-link-text':                    '#FFFFFF',
        'footer-link-hover-text':              '#FFFFFF',
        'footer-divider-color':                '#CBCBCB',
        'footer-icon-color':                   '#FFFFFF',
        'footer-icon-hover-color':             '#FFFFFF',
        'footer-icon-bg-color':                '#222222',
        'emailinputbordercolor1':              '#cbcbcb',
        'focusedemailinputoutlinecolor1':      '#cbcbcb',
        'subscribebuttontextcolor1':            '#444444',
        'subscribebuttontexthovercolor1':       '#444444',
        'subscribebtnbgcolor1':                 '#ffffff',
        'subscribebtnbghovercolor1':            '#ffffff',
    },
    footerdesign4: {
        'footer-background-color':             '#12162e',
        'footer-text-color':                   '#FFFFFF',
        'footer-columntitle-color':            '#FFFFFF',
        'footer-link-text':                    '#D9D9D9',
        'footer-link-hover-text':              '#FA9816',
        'footer-divider-color':                '#3A3A3A',
        'footer-icon-color':                   '#FFFFFF',
        'footer-icon-hover-color':             '#3498DB',
    },
    footerdesign5: {
        'footer-background-color':             '#07141f',
        'footer-text-color':                   '#E2E2E2',
        'footer-columntitle-color':            '#FFFFFF',
        'footer-link-text':                    '#E2E2E2',
        'footer-link-hover-text':              '#5CFF85',
        'footer-divider-color':                '#006455',
        'footer-icon-color':                   '#FFFFFF',
        'footer-icon-hover-color':             '#5cff85',
        'emailinputbordercolor1':              '#006455',
        'focusedemailinputoutlinecolor1':      '#ffffff',
        'subscribebuttontextcolor1':            '#ffffff',
        'subscribebuttontexthovercolor1':       '#010b14',
        'subscribebtnbgcolor1':                 '#006455',
        'subscribebtnbghovercolor1':            '#5cff85',
    },
    footerdesign6: {
        'footer-background-color':             '#1b1440',
        'footer-text-color':                   '#FFFFFF',
        'footer-columntitle-color':            '#FFFFFF',
        'footer-link-text':                    '#C1C1C1',
        'footer-link-hover-text':              '#FFFFFF',
        'footer-divider-color':                '#30285D',
        'footer-icon-color':                   '#C1C1C1',
        'footer-icon-hover-color':             '#ffffff',
        'emailinputbordercolor0':              '#c5c2d6',
        'focusedemailinputoutlinecolor0':      '#c5c2d6',
        'subscribebuttontextcolor0':            '#1b1440',
        'subscribebuttontexthovercolor0':       '#ffffff',
        'subscribebtnbgcolor0':                 '#ffffff',
        'subscribebtnbghovercolor0':            '#6541cc',
    },
};

/**
 * Selectors list.
 */
var SELECTOR = {
    BASE: 'quicksetup',
    PALLETAPPLY: '[name="pallet-apply"]',
    FONTSELECTOR: '.font-selector',
    FONTAPPLY: '[name="font-apply"]',
    FONTSETTING: '[name="global-typography-body-fontfamily"]',
    PALLET: '[name="radio_colorpallet"]',
    CURRENTPALLET: '.current-pallete',
    CURRENTFONT: '.current-font',
    NAVBARINVERSE: '[name="navbarinverse"]',
    FONTVIEWER: '.current-pallete.font-pallet',
    EDITPALLET: '.current-pallete .edw-icon-Edit',
    RESETPALLET: '.current-pallete .edw-icon-Refresh',
    BRANDCOLORS: '#quicksetup #heading_brandcolors-heading',
    BRANDCOLORSCANCEL: '#heading_brandcolors-heading .edw-icon-Cancel',
    SETTINGTYPEHTML: '#quicksetup .setting-type-html',
    PRESET: '[name="radio_themepreset"]',
    PRESETAPPLY: '[name="preset-apply"]',
    PRESETRESET: '[name="preset-reset"]',
    ICON: '[name="quicksetup-iconset"]',
    ICONAPPLY: '#id_icon-apply',
    IFRAME_OVERLAY: '#preview-overlay',
};

/**
 * Reset preset — restores colors, font, radius, and iconset to the last saved values.
 * @param {string} presetValue The preset radio value to re-apply (e.g. 'preset-default').
 */
function resetPreset(presetValue) {
    $(SELECTOR.PRESET + '[value="' + presetValue + '"]').prop('checked', true).trigger('change');
    $(SELECTOR.PRESETAPPLY).attr('disabled', false).trigger('click');
}

/**
 * Apply card and button radius live to the preview iframe.
 */
function applyRadiusPreview() {
    var cardRadius = $('[name="quicksetup-card-radius"]').val();
    var btnRadius  = $('[name="quicksetup-btn-radius"]').val();
    var css = '';

    if (cardRadius !== '') {
        var cr = parseInt(cardRadius) + 'px';
        css += `
            :root:not([data-block="edwiseradvancedblock"]) {
                --bs-card-border-radius: ${cr} !important;
            }

            .card,
            .block,
            .course-card,
            #page-content .main-content-area,
            #region-main,
            .block .edw-block-body,
            .single-section-page .course-content .section-list .section,
            .section-list .section .section-item,
            body:not(.limitedwidth) .edw-course-list-container .edw-course-list,
            .edw-course-summary-container .edw-course-list,
            .main-area-bg:not(.pagelayout-login) div[role="main"],
            .edw-course-list .edw-course-img-wrapper .card-img,
            .block_timeline .block-timeline [data-region="timeline-view"] {
                border-radius: ${cr} !important;
            }

            .card .edw-course-img-wrapper {
                border-top-left-radius: ${cr} !important;
                border-top-right-radius: ${cr} !important;
            }
            
            .calendarwrapper {
                border-radius: ${cr} !important;
                table thead tr:first-child {
                    border-top-left-radius: ${cr} !important;
                }
                table thead tr:first-child th:first-child {
                    border-top-left-radius: ${cr} !important;
                }
                table thead tr:first-child {
                    border-top-right-radius: ${cr} !important;
                }            
                table thead tr:first-child th:last-child {
                    border-top-right-radius: ${cr} !important;
                }            
                table tbody tr:last-child td:first-child {
                    border-bottom-left-radius: ${cr} !important;
                }            
                table tbody tr:last-child td:last-child {
                    border-bottom-right-radius: ${cr} !important;
                }
            }
        `;
    }

    if (btnRadius !== '') {
        var br = parseInt(btnRadius) + 'px';
        css += `
        .btn-primary:not(#gotop):not(.newcourseindexicon-toggle):not(.newrightsidebaricon-toggle),
        .btn-outline-primary,
        .btn-secondary,
        .btn.btn-sm:not(.dropdown-toggle),
        .btn.btn-lg:not(.floating-add-block-button):not(.add-block-split-icon-btn),
        .edw-course-list-container .edw-course-list .edw-list-body span.categoryname,
        .course-card-body-wrapper span.categoryname,
        .edw-course-img-wrapper .skilltag.badge,
        .hasbackground.design-1 .badge,
        .tag_list .badge.badge-primary,
        span.categoryname.small-info-semibold {
            border-radius: ${br} !important;
        }

        #page-footer .subscribe-box input {
            border-radius: ${br} 0 0 ${br} !important;
        }

        #page-footer .subscribe-box .subscribe-btn {
            border-radius: 0 ${br} ${br} 0 !important;
        }
    `;
    }

    Utils.putStyle('remui-radius', css);
}

/**
 * Apply icon set to the preview iframe.
 * @param {string} iconset - 'iconset-set1' or 'iconset-default'
 */
function applyIconSet(iconset) {
    var fontFamily = {
        'iconset-set1': 'Remui-v1',
        'iconset-set2': 'Remui-v2'
    }[iconset] || 'Remui';

    Utils.putStyle('remui-iconset',
        '[class^="edw-icon-"], [class*="edw-icon-"] { font-family: "' + fontFamily + '" !important; }' +
        '.dropdown-toggle::after { font-family: "' + fontFamily + '" !important; }'
    );
}

/**
 * Re-apply live preview state after iframe navigation.
 * Called by the customizer core on every iframe load.
 */
function apply() {
    var $checked = $(SELECTOR.PRESET + ':checked');
    var isModern = $checked.length && $checked.val() === 'preset-modern';
    $(Utils.getDocument()).find('body').toggleClass('preset-modern', isModern);

    var isClassic = $checked.length && $checked.val() === 'preset-classic';
    $(Utils.getDocument()).find('body').toggleClass('preset-classic', isClassic);

    var $checkedIcon = $(SELECTOR.ICON + ':checked');
    if ($checkedIcon.length) {
        applyIconSet($checkedIcon.val());
    }

    applyRadiusPreview();
}

function applyColors(colors) {
    $(MAIN.PRIMARY).spectrum('set', colors.primary).trigger('color.changed');
    $(MAIN.SECONDARY).spectrum('set', colors.secondary).trigger('color.changed');
    $(MAIN.TEXT).spectrum('set', colors.text).trigger('color.changed');
    $(MAIN.BORDER).spectrum('set', colors.border).trigger('color.changed');
    SECONDARY.forEach(setting => {
        var val = colors[setting.key];
        if (val !== undefined && val !== null) {
            $(setting.target).spectrum('set', val).trigger('color.changed');
        }
    });
}

/**
 * Sync color on load.
 */
function syncColor() {
    $($(SELECTOR.CURRENTPALLET).find('span').get(0)).css(
        'background',
        $(MAIN.PRIMARY).spectrum('get').toString()
    );
    $($(SELECTOR.CURRENTPALLET).find('span').get(1)).css(
        'background',
        $(MAIN.SECONDARY).spectrum('get').toString()
    );
    $($(SELECTOR.CURRENTPALLET).find('span').get(2)).css(
        'background',
        $(MAIN.TEXT).spectrum('get').toString()
    );
    $($(SELECTOR.CURRENTPALLET).find('span').get(3)).css(
        'background',
        $(MAIN.BORDER).spectrum('get').toString()
    );
}


/**
 * Restore pallet colors.
 */
function restorePalletColors() {
    const $container = $('#quicksetup #heading_brandcolors-heading .heading-content');
    const $themecolorscontainer = $('#themecolors #heading_brandcolors-heading .heading-content');

    if (!$container.length) {
        return;
    }

    // Track if any of the four watched settings needed to be reset (value !== default)
    let anyWatchedSettingNeededReset = false;

    // Process reset buttons in quicksetup container
    $container.find('.color-reset').each((index, button) => {
        const neededReset = handleColorResetClick(button);
        if (neededReset) {
            anyWatchedSettingNeededReset = true;
        }
    });

    // Process reset buttons in themecolors container
    $themecolorscontainer.find('.color-reset').each((index, button) => {
        const neededReset = handleColorResetClick(button);
        if (neededReset) {
            anyWatchedSettingNeededReset = true;
        }
    });

    // Only trigger smart-colors-button if at least one of the four watched settings needed resetting
    if (anyWatchedSettingNeededReset) {
        $container.find('[name="smart-colors-button"]').trigger('click');
    }

    setTimeout(function () {
        $('[data-region="modal-container"] [data-region="body"]')
            .text('Color palette reset successfully.');
    }, 200);
}

/**
 * Handle reset button click for color pickers, but avoid triggering the reset
 * when the current value already equals `data-default` for specific fields.
 *
 * Affects only these four settings:
 * - sitecolorhex
 * - secondarycolor
 * - themecolors-textcolor
 * - themecolors-bordercolor
 *
 * For all other color fields, the reset button is always triggered.
 *
 * @param {HTMLElement} button The reset button element (div with class "color-reset")
 * @returns {boolean} Returns true if this was a watched setting that needed resetting (value !== default), false otherwise
 */
function handleColorResetClick(button) {
    const $button = $(button);
    const $formGroup = $button.closest('.form-group');
    const $input = $formGroup.find('input[type="hidden"]');

    if (!$input.length) {
        // No input found, trigger reset anyway (not one of the watched settings)
        $button.trigger('click');
        return false;
    }

    const name = $input.attr('name');
    const value = $input.val();
    const defaultValue = $button.data('default');

    const watchedNames = [
        'sitecolorhex',
        'secondarycolor',
        'themecolors-textcolor',
        'themecolors-bordercolor'
    ];

    const isWatchedSetting = watchedNames.indexOf(name) !== -1;

    // If this is one of the four watched fields and the value already equals
    // the default, skip triggering the reset handler.
    if (isWatchedSetting && defaultValue && value === defaultValue) {
        return false; // Skipped because value equals default
    }

    // Trigger the reset button click
    $button.trigger('click');
    // Return true if this was a watched setting that needed resetting (value !== default)
    return isWatchedSetting;
}

/**
 * Sync font selected with current font item.
 */
function syncFont() {
    let font = $(SELECTOR.FONTSETTING).val();
    if (font.toLowerCase() == 'standard') {
        font = 'Inter';
    }
    // let url = "https://staging.edwiser.org/remuifonts/images/";
    // $(SELECTOR.CURRENTFONT).find('img').attr('src', url + font + '.png');

    $(SELECTOR.FONTVIEWER).find('.font-sample .font-name').text(font);
    $(SELECTOR.FONTVIEWER).find('.font-sample').css('font-family', font);
}

/**
 * Load font on quick setup when page is loaded.
 */
function onLoadFont() {
    // First time font sync.
    let font = $(SELECTOR.FONTSETTING).val();
    if (font.toLowerCase() == 'standard') {
        font = 'Inter';
    }
    $('select' + SELECTOR.FONTSELECTOR).val(font).trigger('change');
    $(SELECTOR.FONTAPPLY).attr('disabled', false);
    loadDemoFont(font);
}

/**
 * Initialize events.
 */
function init() {
    syncColor();
    syncFont();
    onLoadFont();
    $(SELECTOR.PALLET).on('change', function() {
        $(SELECTOR.PALLET).each((index, input) => {
            if (input.checked) {
                $(SELECTOR.PALLETAPPLY).attr('disabled', false);
            }
        });
    });
    $(SELECTOR.PALLETAPPLY).on('click', function() {
        $(this).attr('disabled', true);
        $(SELECTOR.PALLET).each((index, input) => {
            if (input.checked) {
                $(input).prop('checked', false).trigger('change');
                applyColors($(input).data('colors'));
            }
        });
    });

    //initial load to check what radio button was selected
    var $initiallyChecked = $(SELECTOR.PRESET + ':checked');
    if ($initiallyChecked.length) {
        $initiallyChecked.closest('.cust-sele').addClass('applied');
        $(SELECTOR.PRESETAPPLY).attr('disabled', true);
        var initiallyModern = $initiallyChecked.val() === 'preset-modern';
        $(Utils.getDocument()).find('body').toggleClass('preset-modern', initiallyModern);
        var initiallyClassic = $initiallyChecked.val() === 'preset-classic';
        $(Utils.getDocument()).find('body').toggleClass('preset-classic', initiallyClassic);
    }
    // Theme preset — enable Apply button when a preset is selected.
    $(SELECTOR.PRESET).on('change', function () {
        var $presetContainer = $(this).closest('.cust-sele');
        if ($presetContainer.hasClass('applied')) {
            $(SELECTOR.PRESETAPPLY).attr('disabled', true);
        } else {
            $(SELECTOR.PRESETAPPLY).attr('disabled', false);
        }
    });

    // Theme preset — apply colors, font, and radius on button click.
     $(SELECTOR.PRESETAPPLY).on('click', function() {
        var $checked = $(SELECTOR.PRESET + ':checked');
        if (!$checked.length) {
            return;
        }
        var preset = $checked.data('preset');
        if (!preset) {
            return;
        }

        $(this).attr('disabled', true);
        Utils.showLoader();

        setTimeout(function() {
            // Apply all colors.
            applyColors(preset.colors);

            // For Default preset: restore per-design footer defaults (each design has its own color scheme).
            if ($checked.val() === 'preset-default') {
                var $designRadio = $('input[name="footer-design-selector"]:checked');
                var currentDesign = ($designRadio.length && $designRadio.data('flayout'))
                    ? $designRadio.data('flayout')
                    : 'footerdesign0';
                var designDefaults = FOOTER_DESIGN_DEFAULTS[currentDesign];
                if (designDefaults) {
                    $.each(designDefaults, function(fieldName, color) {
                        var $el = $('[name="' + fieldName + '"]');
                        if ($el.length) {
                            $el.spectrum('set', color).trigger('color.changed');
                        }
                    });
                }
            }

            // For Modern preset: design-specific and fixed color overrides.
            if ($checked.val() === 'preset-modern') {
                var $modernDesignRadio = $('input[name="footer-design-selector"]:checked');
                var modernDesign = ($modernDesignRadio.length && $modernDesignRadio.data('flayout'))
                    ? $modernDesignRadio.data('flayout')
                    : 'footerdesign0';

                // Override icon colors for footer designs 3–6.
                if (['footerdesign3', 'footerdesign4', 'footerdesign5', 'footerdesign6'].indexOf(modernDesign) !== -1) {
                    $('[name="footer-icon-bg-color"]').spectrum('set', '#F0E5FF').trigger('color.changed');
                    $('[name="footer-icon-color"]').spectrum('set', '#7C3AED').trigger('color.changed');
                    $('[name="footer-icon-hover-color"]').spectrum('set', '#4C10B2').trigger('color.changed');
                }

                // Apply subscribe button colors.
                for (var i = 0; i <= 5; i++) {
                    $('[name="subscribebtnbgcolor' + i + '"]').spectrum('set', '#7C3AED').trigger('color.changed');
                    $('[name="subscribebtnbghovercolor' + i + '"]').spectrum('set', '#4C10B2').trigger('color.changed');
                    $('[name="subscribebuttontextcolor' + i + '"]').spectrum('set', '#FFFFFF').trigger('color.changed');
                    $('[name="subscribebuttontexthovercolor' + i + '"]').spectrum('set', '#FFFFFF').trigger('color.changed');
                    $('[name="subscribebuttontextcolor' + i + '"]').spectrum('set', '#FFFFFF').trigger('color.changed');
                    $('[name="emailinputbordercolor' + i + '"]').spectrum('set', '#7C3AED').trigger('color.changed');
                    $('[name="focusedemailinputoutlinecolor' + i + '"]').spectrum('set', '#7C3AED').trigger('color.changed');
                }

                $('[name="hds-icon-color"]').spectrum('set', '#5C6980').trigger('color.changed');
                $('[name="global-colors-elementbackgroundcolor"]').spectrum('set', '#F0E5FF').trigger('color.changed');
            }

            // For Classic preset: design-specific and fixed color overrides.
            if ($checked.val() === 'preset-classic') {
                var $modernDesignRadio = $('input[name="footer-design-selector"]:checked');
                var modernDesign = ($modernDesignRadio.length && $modernDesignRadio.data('flayout'))
                    ? $modernDesignRadio.data('flayout')
                    : 'footerdesign0';

                // Override icon colors for footer designs 3–6.
                if (['footerdesign0','footerdesign3', 'footerdesign4', 'footerdesign5', 'footerdesign6'].indexOf(modernDesign) !== -1) {
                    $('[name="footer-icon-bg-color"]').spectrum('set', '#F0E5FF').trigger('color.changed');
                    $('[name="footer-icon-color"]').spectrum('set', '#E00040').trigger('color.changed');
                    $('[name="footer-icon-hover-color"]').spectrum('set', '#AD0031').trigger('color.changed');
                }

                if (['footerdesign1', 'footerdesign4', 'footerdesign5', 'footerdesign6'].indexOf(modernDesign) !== -1) {
                    $('[name="footer-link-hover-text"]').spectrum('set', '#E00040').trigger('color.changed');
                }

                // Apply subscribe button colors.
                for (var i = 0; i <= 5; i++) {
                    $('[name="subscribebtnbgcolor' + i + '"]').spectrum('set', '#E00040').trigger('color.changed');
                    $('[name="subscribebtnbghovercolor' + i + '"]').spectrum('set', '#99002B').trigger('color.changed');
                    $('[name="subscribebuttontexthovercolor' + i + '"]').spectrum('set', '#FFFFFF').trigger('color.changed');
                    $('[name="subscribebuttontextcolor' + i + '"]').spectrum('set', '#FFFFFF').trigger('color.changed');
                    $('[name="emailinputbordercolor' + i + '"]').spectrum('set', '#E00040').trigger('color.changed');
                    $('[name="focusedemailinputoutlinecolor' + i + '"]').spectrum('set', '#E00040').trigger('color.changed');
                }

                //icon color
                $('[name="hds-icon-hover-color"]').spectrum('set', '#E00040').trigger('color.changed');

                $('[name="footer-link-hover-text"]').spectrum('set', '#E00040').trigger('color.changed');

            }

            // Apply font: set the font selector value then trigger the font-apply button.
            if (preset.font) {
                $('select' + SELECTOR.FONTSELECTOR).val(preset.font).trigger('change');
                $(SELECTOR.FONTAPPLY).attr('disabled', false).trigger('click');

                $('[name="heading-adv-setting"]').prop('checked', true).trigger('change');
                $('[name="heading-semibold-fontweight"]').val('400').trigger('change');
                $('[name="heading-bold-fontweight"]').val('500').trigger('change');
                $('[name="heading-exbold-fontweight"]').val('600').trigger('change');
            }

            // Apply radius values.
            if (preset.cardRadius !== undefined) {
                $('[name="quicksetup-card-radius"]').val(preset.cardRadius).trigger('input').trigger('change');
            }
            if (preset.btnRadius !== undefined) {
                $('[name="quicksetup-btn-radius"]').val(preset.btnRadius).trigger('input').trigger('change');
            }

            // Apply icon set.
            if (preset.iconset) {
                $(SELECTOR.ICON).each(function() {
                    var $input = $(this);
                    var $sele = $input.closest('.cust-sele');
                    if ($input.val() === preset.iconset) {
                        $input.prop('checked', true).addClass('active');
                        $sele.addClass('active');
                    } else {
                        $input.prop('checked', false).removeClass('active');
                        $sele.removeClass('active');
                    }
                });
                applyIconSet(preset.iconset);
                window.remuiAppliedIconset = preset.iconset;
                $(SELECTOR.ICONAPPLY).attr('disabled', true);
                $('#id_global-icon-apply').attr('disabled', true);
            }

            // Update applied state.
            $(SELECTOR.PRESET).closest('.cust-sele').removeClass('applied');
            $checked.closest('.cust-sele').addClass('applied');

            // Toggle modern-preset class on iframe body.
            var isModern = $checked.val() === 'preset-modern';
            $(Utils.getDocument()).find('body').toggleClass('preset-modern', isModern);
            // Toggle preset-classic class on iframe body.
            var isClassic = $checked.val() === 'preset-classic';
            $(Utils.getDocument()).find('body').toggleClass('preset-classic', isClassic);

            var IsModernOrClassic = isModern || isClassic;
            footer.useFooterWidgetLogo(true, IsModernOrClassic);
            footer.applyBackgroundImgURL(true, $checked.val());

            Utils.hideLoader();
        }, 0);
    });

    // Preset reset — stop propagation so the click doesn't select the radio, then delegate.
    $(SELECTOR.PRESETRESET).on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var presetValue = $(this).closest('.cust-sele').find(SELECTOR.PRESET).val();
        resetPreset(presetValue);
    });


    //ICON LOGIC:- Initial load to check what radio button was selected
    var currentlyAppliedIcon = '';
    var $initiallyCheckedIcon = $(SELECTOR.ICON + ':checked');
    if ($initiallyCheckedIcon.length) {
        currentlyAppliedIcon = $initiallyCheckedIcon.val();
        $(SELECTOR.ICONAPPLY).attr('disabled', true);
    } else {
        $(SELECTOR.ICONAPPLY).attr('disabled', false);
    }
    // Shared applied-icon state read by icon-settings.js as well.
    window.remuiAppliedIconset = currentlyAppliedIcon;

    // --- Icon Change Event ---
    // Radius — sync all instances of the same input and apply live preview on change.
    $('[name="quicksetup-card-radius"], [name="quicksetup-btn-radius"]').on('change', function() {
        var name = $(this).attr('name');
        var value = $(this).val();
        $('[name="' + name + '"]').not(this).val(value);

        if (name === 'quicksetup-btn-radius') {

            [
                'button-sm-settings-border-radius',
                'button-md-settings-border-radius',
                'button-lg-settings-border-radius',
                'button-common-border-radius'
            ].forEach(setting => {

                const $el = $(`[name="${setting}"]`);

                $el
                    .val(value)
                    .attr('value', value)
                    .trigger('input')
                    .trigger('change');
            });
        }
        applyRadiusPreview();
    });

    $(SELECTOR.ICON).on('change', function () {
        var selectedValue = $(this).val();
        var appliedIcon = window.remuiAppliedIconset || '';

        // Keep global icon panel radio in sync.
        $('[name="global-quicksetup-iconset"]').each(function() {
            var $input = $(this);
            var isMatch = $input.val() === selectedValue;
            $input.prop('checked', isMatch);
            $input.closest('.cust-sele').toggleClass('active', isMatch);
            $input.toggleClass('active', isMatch);
        });

        var isDifferent = selectedValue !== appliedIcon;
        $(SELECTOR.ICONAPPLY).attr('disabled', !isDifferent);
        $('#id_global-icon-apply').attr('disabled', !isDifferent);
    });
    // Icon set — apply font to preview iframe on Apply click.
    $(SELECTOR.ICONAPPLY).on('click', function() {
        var $checked = $(SELECTOR.ICON + ':checked');
        if (!$checked.length) {
            return;
        }
        var selected = $checked.val();
        applyIconSet(selected);
        window.remuiAppliedIconset = selected;
        $(this).attr('disabled', true);
        $('#id_global-icon-apply').attr('disabled', true);

        // Sync global panel radio to the applied value.
        $('[name="global-quicksetup-iconset"]').each(function() {
            var $input = $(this);
            var isMatch = $input.val() === selected;
            $input.prop('checked', isMatch);
            $input.closest('.cust-sele').toggleClass('active', isMatch);
            $input.toggleClass('active', isMatch);
        });
    });

    $(SELECTOR.BRANDCOLORS).addClass('d-none');

    // Handling current pallet colors.
    $(MAIN.PRIMARY).on('color.changed', function() {
        $($(SELECTOR.CURRENTPALLET).find('span').get(0)).css('background', $(this).spectrum('get').toString());
    });
    $(MAIN.SECONDARY).on('color.changed', function() {
        $($(SELECTOR.CURRENTPALLET).find('span').get(1)).css('background', $(this).spectrum('get').toString());
    });
    $(MAIN.TEXT).on('color.changed', function() {
        $($(SELECTOR.CURRENTPALLET).find('span').get(2)).css('background', $(this).spectrum('get').toString());
    });
    $(MAIN.BORDER).on('color.changed', function() {
        $($(SELECTOR.CURRENTPALLET).find('span').get(3)).css('background', $(this).spectrum('get').toString());
    });

    // Observ font settings change.
    $(SELECTOR.FONTSETTING).on('change', syncFont);

    // Observer font.
    $(SELECTOR.FONTSELECTOR).on('change', function() {
        $(SELECTOR.FONTAPPLY).attr('disabled', false);
    });

    // Apply font.
    $(SELECTOR.FONTAPPLY).on('click', function() {
        var selectedfont = $(SELECTOR.FONTSELECTOR).selectpicker('val') + '';
        $(SELECTOR.FONTSETTING).val(selectedfont).trigger('input').trigger('change');
        loadDemoFont(selectedfont);
        // $(SELECTOR.FONTVIEWER).find('.option-label').css('font-family', selectedfont);
        // $(SELECTOR.FONTVIEWER).find('.sample-text').css('font-family', selectedfont);
        syncFont();
        let inheritFont = [
            '[name="global-typography-smallpara-fontfamily"]',
            '[name="global-typography-smallinfo-fontfamily"]',
            '[name="button-common-fontfamily"]',
            '[name="hds-menu-font-family"]',
            '[name="footerfontfamily"]',
            '[name="footer-columntitle-fontfamily"]'
        ];
        ['all', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']
        .forEach(heading => {
            inheritFont.push('[name="typography-heading-' + heading + '-fontfamily"]');
        });
        inheritFont.forEach(id => {
            if ($(id).find('option[value="Inherit"]').length) {
                $(id).val("Inherit").trigger('input').trigger('change');
            } else if ($(id).find('option[value="inherit"]').length) {
                $(id).val("inherit").trigger('input').trigger('change');
            }
        });
        $(this).attr('disabled', true);
    });

    $(SELECTOR.EDITPALLET).on('click', function() {
        $(SELECTOR.BRANDCOLORS).removeClass('d-none');
        $(SELECTOR.BRANDCOLORS).find(".group-item .edw-icon")
            .removeClass("edw-icon-Down-Arrow")
            .addClass("edw-icon-Cancel");

        $(SELECTOR.SETTINGTYPEHTML+":has(.color-pallet)").addClass('d-none');
        $(SELECTOR.SETTINGTYPEHTML+":has(#id_pallet-apply)").addClass('d-none');
        $(".setting-type-radio:has(#fitem_id_colorpallet)").addClass('d-none');
    });

    $('#quicksetup').on('click', SELECTOR.BRANDCOLORSCANCEL, function() {
        $(SELECTOR.BRANDCOLORS).addClass('d-none');

        $(SELECTOR.SETTINGTYPEHTML+":has(.color-pallet)").removeClass('d-none');
        $(SELECTOR.SETTINGTYPEHTML+":has(#id_pallet-apply)").removeClass('d-none');
        $(".setting-type-radio:has(#fitem_id_colorpallet)").removeClass('d-none');
    });

    $(SELECTOR.RESETPALLET).on('click', restorePalletColors);
}

/**
 * Load font on iframe.
 * @param {string} fontName Font name
 */
function loadDemoFont(fontName) {
    let id = fontName.replace(' ', '');
    id += '_js';
    if ($('body').find('#' + id).length != 0) {
        return;
    }
    let js = document.createElement('script');
    js.type = 'text/javascript';
    js.id = id;
    js.textContent = `require(['theme_remui/webfont'], function(webFont) {
        webFont.load({
            google: {
                families: ['${fontName}:100,200,300,400,500,600,700,800,900']
            }
        });
    });`;
    $('body').append(js);
}

export default {
    init,
    apply
};
