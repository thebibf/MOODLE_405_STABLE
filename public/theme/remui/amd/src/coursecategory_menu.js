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
 * Lazy-load course category dropdown menu via AJAX.
 *
 * @module     theme_remui/coursecategory_menu
 * @copyright  (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    'use strict';

    /**
     * Initialize lazy-load for category dropdowns.
     */
    var init = function() {
        // Guard against duplicate initialization.
        if ($('body').attr('data-coursecategory-init') === '1') {
            return;
        }
        $('body').attr('data-coursecategory-init', '1');

        var $toggles = $('[data-lazyloadcategory="1"]');

        $toggles.each(function() {
            var $toggle = $(this);

            // Desktop: inside a Bootstrap dropdown.
            if ($toggle.closest('.dropdown').length) {
                var $dropdown = $toggle.closest('.dropdown');
                var $menu = $dropdown.find('.dropdown-menu').first();

                // Prevent the dropdown from closing when clicking a collapse toggle inside it.
                $dropdown.on('hide.bs.dropdown', function(e) {
                    var clickEvent = e.clickEvent || (e.relatedTarget && e.relatedTarget.clickEvent);
                    if (clickEvent && $(clickEvent.target).closest('[data-toggle="collapse"]').length) {
                        e.preventDefault();
                    }
                });

                $dropdown.on('show.bs.dropdown', function() {
                    // Already loaded.
                    if ($toggle.attr('data-loaded') === '1') {
                        return;
                    }

                    // Inject loader.
                    $menu.html(
                        '<div class="d-flex justify-content-center align-items-center p-3" style="min-height:120px;">' +
                            '<img src="' + M.cfg.wwwroot + '/theme/remui/pix/siteinnerloader.svg" ' +
                                'style="width:24px;height:24px;" alt="">' +
                        '</div>'
                    );

                    // Call WS.
                    var promise = Ajax.call([{
                        methodname: 'theme_remui_get_course_category_menu',
                        args: {}
                    }], true, false);

                    promise[0].done(function(response) {
                        if (response.status && response.html) {
                            $menu.html(response.html);
                            $toggle.attr('data-loaded', '1');
                        } else {
                            $menu.empty();
                        }
                    }).fail(function(error) {
                        $menu.empty();
                        Notification.exception(error);
                    });
                });
            }

            // Mobile: inside a Bootstrap collapse.
            if ($toggle.hasClass('collapse')) {
                var $collapse = $toggle;

                $collapse.on('show.bs.collapse', function() {
                    // Already loaded.
                    if ($collapse.attr('data-loaded') === '1') {
                        return;
                    }

                    // Inject loader.
                    $collapse.html(
                        '<div class="d-flex justify-content-center align-items-center p-3" style="min-height:120px;">' +
                            '<img src="' + M.cfg.wwwroot + '/theme/remui/pix/siteinnerloader.svg" ' +
                                'style="width:24px;height:24px;" alt="">' +
                        '</div>'
                    );

                    // Call WS.
                    var promise = Ajax.call([{
                        methodname: 'theme_remui_get_course_category_menu',
                        args: {}
                    }], true, false);

                    promise[0].done(function(response) {
                        if (response.status && response.html) {
                            $collapse.html(response.html);
                            $collapse.attr('data-loaded', '1');
                        } else {
                            $collapse.empty();
                        }
                    }).fail(function(error) {
                        $collapse.empty();
                        Notification.exception(error);
                    });
                });
            }
        });
    };

    return {
        init: init
    };
});
