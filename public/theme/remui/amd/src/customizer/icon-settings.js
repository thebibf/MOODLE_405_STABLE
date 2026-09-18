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
 * Theme customizer icon settings module.
 * Handles icon customization settings for the theme.
 *
 * @module     theme_remui/customizer/icon-settings
 * @copyright  (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Gourav G
 */

define('theme_remui/customizer/icon-settings', ['jquery', './utils'], function($, Utils) {

    var SELECTOR = {
        ICON: '[name="global-quicksetup-iconset"]',
        ICONAPPLY: '#id_global-icon-apply',
        QUICKSETUP_ICON: '[name="quicksetup-iconset"]',
        QUICKSETUP_APPLY: '#id_icon-apply',
    };

    function applyIconSet(iconset) {
        var fontFamily = 'Remui';
        if(iconset === 'iconset-set1') {
            fontFamily = 'Remui-v1';
        }
        else if(iconset === 'iconset-set2') {
            fontFamily = 'Remui-v2';
        }
        Utils.putStyle('remui-iconset',
            '[class^="edw-icon-"], [class*="edw-icon-"] { font-family: "' + fontFamily + '" !important; }'
        );
    }

    /**
     * Apply settings.
     */
    function apply() {}

    /**
     * Initialize events.
     */
    function init() {
        // Use event delegation so the handler fires even when the panel is not yet visible.
        $(document).on('change', SELECTOR.ICON, function() {
            var selectedValue = $(this).val();
            // window.remuiAppliedIconset is set by quicksetup-settings.js init (runs after this module).
            var appliedIcon = window.remuiAppliedIconset || '';

            // Keep quicksetup panel radio in sync (prop does not fire change, no loop).
            $(SELECTOR.QUICKSETUP_ICON).each(function() {
                var $input = $(this);
                var isMatch = $input.val() === selectedValue;
                $input.prop('checked', isMatch);
                $input.closest('.cust-sele').toggleClass('active', isMatch);
                $input.toggleClass('active', isMatch);
            });

            var isDifferent = selectedValue !== appliedIcon;
            $(SELECTOR.ICONAPPLY).attr('disabled', !isDifferent);
            $(SELECTOR.QUICKSETUP_APPLY).attr('disabled', !isDifferent);
        });

        $(SELECTOR.ICONAPPLY).on('click', function() {
            var $checked = $(SELECTOR.ICON + ':checked');
            if (!$checked.length) {
                return;
            }
            var selected = $checked.val();
            applyIconSet(selected);
            window.remuiAppliedIconset = selected;
            $(SELECTOR.ICONAPPLY).attr('disabled', true);
            $(SELECTOR.QUICKSETUP_APPLY).attr('disabled', true);

            // Sync quicksetup panel radio to the applied value.
            $(SELECTOR.QUICKSETUP_ICON).each(function() {
                var $input = $(this);
                var isMatch = $input.val() === selected;
                $input.prop('checked', isMatch);
                $input.closest('.cust-sele').toggleClass('active', isMatch);
                $input.toggleClass('active', isMatch);
            });
        });
    }

    return {
        init: init,
        apply: apply
    };
});
