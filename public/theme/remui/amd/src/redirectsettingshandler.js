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
 * Redirect settings handler module for RemUI theme.
 * Handles redirection to specific settings sections and removes homepage importer tab.
 *
 * @module     theme_remui/redirectsettingshandler
 * @copyright  (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {

    return {
        init: function() {
            $(window).on('load', function() {
                var urlParams = new URLSearchParams(window.location.search);
                var sectionId = urlParams.get('settingsectionname');

                setTimeout(() => {
                    if (sectionId) {
                        var sectionElement = $('#' + sectionId);

                        if (sectionElement.length > 0) {
                            $('html, body').animate({
                                scrollTop: sectionElement.offset().top - 100
                            }, 0);
                        }
                    }
                }, 100);
            });

            function removeHomepageImporterTab() {
                $('#importer-homepage').remove();
                $('.nav-tabs .nav-item .nav-link[aria-controls="courses"]').click();
            }

            $(window).on('load', function() {
                setTimeout(() => {
                    removeHomepageImporterTab();
                }, 300);

                $(document).on("click", ".nav-link.remuitab[href=\"#edwisersiteimporter\"]", () => {
                    setTimeout(() => {
                        removeHomepageImporterTab();
                    }, 600);
                });
            });
        }
    };
});
