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
 * Lazy-load focus mode dropdown content via AJAX.
 *
 * @module     theme_remui/focusmode_dropdown
 * @copyright  (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    'use strict';

    /**
     * Build accordion HTML from sections array.
     * @param  {Array} sections Sections data
     * @return {String} HTML string
     */
    var buildAccordionHtml = function(sections) {
        var html = '<div class="accordion md-accordion" id="accordionEx1" role="tablist" ' +
            'aria-label="Activity List" aria-multiselectable="true">';

        sections.forEach(function(section) {
            html += '<div class="card">' +
                '<div class="card-header border-0" role="tab" id="heading' + section.sectionid + '">' +
                '<a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" ' +
                'href="#collapse' + section.sectionid + '" aria-expanded="false" ' +
                'aria-controls="collapse' + section.sectionid + '">' +
                '<h5 class="mb-0 d-flex justify-content-between text-link-semibold">' +
                section.name + ' ';

            if (section.hasactivites) {
                html += '<i class="edw-icon edw-icon-UpArrow"></i>';
            }

            html += '</h5></a></div>';

            if (section.hasactivites) {
                html += '<div id="collapse' + section.sectionid + '" class="collapse ' + (section.active || '') + '" ' +
                    'role="tabpanel" aria-labelledby="heading' + section.sectionid + '" ' +
                    'data-parent="#accordionEx1">' +
                    '<div class="card-body m-0 p-0">';

                if (section.activities && section.activities.length) {
                    section.activities.forEach(function(activity) {
                        if (activity.isdelegatedsection) {
                            // Delegated subsection.
                            html += '<div class="fm-dropdown-subsections">' +
                                '<div class="accordion md-accordion" id="accordionEx1_' + activity.sectionid + '" ' +
                                'role="tablist" aria-label="Activity List" aria-multiselectable="true">' +
                                '<div class="card">' +
                                '<div class="subsection-header border-0" role="tab" ' +
                                'id="heading_subsection' + activity.sectionid + '">' +
                                '<a class="collapsed" data-toggle="collapse" ' +
                                'data-parent="#accordionEx1_' + activity.sectionid + '" ' +
                                'href="#collapse_subsection' + activity.sectionid + '" ' +
                                'aria-expanded="false" ' +
                                'aria-controls="collapse_subsection' + activity.sectionid + '">' +
                                '<h5 class="mb-0 d-flex justify-content-between text-link-semibold">' +
                                activity.name + ' ';

                            if (activity.hasactivites) {
                                html += '<i class="edw-icon edw-icon-UpArrow"></i>';
                            }

                            html += '</h5></a></div>' +
                                '<div id="collapse_subsection' + activity.sectionid + '" ' +
                                'class="collapse ' + (activity.active || '') + '" ' +
                                'role="tabpanel" ' +
                                'aria-labelledby="heading_subsection' + activity.sectionid + '" ' +
                                'data-parent="#accordionEx1_' + activity.sectionid + '">' +
                                '<div class="card-body m-0 p-0">';

                            if (activity.activities && activity.activities.length) {
                                activity.activities.forEach(function(subActivity) {
                                    html += buildActivityCard(subActivity);
                                });
                            }

                            html += '</div></div></div></div></div>';
                        } else {
                            html += buildActivityCard(activity);
                        }
                    });
                }

                html += '</div></div>';
            }

            html += '</div>';
        });

        html += '</div>';
        return html;
    };

    /**
     * Build a single activity card HTML.
     * @param  {Object} activity Activity data
     * @return {String} HTML string
     */
    var buildActivityCard = function(activity) {
        var html = '<div class="card ' + (activity.active || '') + '">' +
            '<div class="card-header d-flex activity-item" role="tab">' +
            '<a href="' + (activity.url || '#') + '" ' +
            'class="w-100 d-inline-flex justify-content-left align-items-center">';

        if (activity.completionstate) {
            html += '<span class="activity-completion-indicator complete_icon icon fa fa-check"></span>';
        } else {
            html += '<img src="' + activity.icon + '" class="iconlarge activityicon mr-2" ' +
                'alt="" role="presentation" aria-hidden="true">';
        }

        html += '<h5 class="mb-0 text-link-regular">' + activity.name + '</h5>' +
            '</a></div></div>';

        return html;
    };

    /**
     * Initialize lazy-load for focus mode dropdown.
     */
    var init = function() {
        var $toggle = $('[data-lazyloadfocus="1"]');
        if (!$toggle.length) {
            return;
        }

        var $dropdown = $('#courseActivities');
        var $menu = $dropdown.find('.focus-dropdown-menu');
        var $loader = $menu.find('.focus-dropdown-loader');
        var $content = $menu.find('.focus-dropdown-content');

        // Prevent duplicate init.
        if ($('body').attr('data-focusmode-init') === '1') {
            return;
        }
        $('body').attr('data-focusmode-init', '1');

        $toggle.on('click', function() {
            // Already loaded — let existing slideToggle handle it.
            if ($toggle.attr('data-loaded') === '1') {
                return;
            }

            // Show loader.
            $loader.show();

            // Call WS.
            var promise = Ajax.call([{
                methodname: 'theme_remui_get_focus_mode_sections',
                args: {
                    courseid: $toggle.data('courseid') || 0,
                    coursemoduleid: $toggle.data('coursemoduleid') || 0
                }
            }]);

            promise[0].done(function(response) {
                if (response.status && response.sections) {
                    var sections = JSON.parse(response.sections);
                    var html = buildAccordionHtml(sections);
                    $content.html(html);

                    // Update Previous button.
                    var $prev = $('#coursePrevious a');
                    if (response.previous) {
                        $prev.attr('href', response.previous).removeClass('disabled').removeAttr('aria-disabled');
                    } else {
                        $prev.attr('href', '#').addClass('disabled').attr('aria-disabled', 'true');
                    }

                    // Update Next button.
                    var $next = $('#courseNext a');
                    if (response.next) {
                        $next.attr('href', response.next).removeClass('disabled').removeAttr('aria-disabled');
                    } else {
                        $next.attr('href', '#').addClass('disabled').attr('aria-disabled', 'true');
                    }

                    // Update active text only if we have a real value.
                    if (response.active) {
                        $toggle.find('span.text-link-regular').text(response.active);
                    }

                    $loader.hide();
                    $toggle.attr('data-loaded', '1');
                } else {
                    $loader.hide();
                    $content.empty();
                }
            }).fail(function(error) {
                $loader.hide();
                $content.html('<div class="text-center p-3 text-danger">' +
                    M.util.get_string('error', 'moodle') + '</div>');
                Notification.exception(error);
            });
        });
    };

    return {
        init: init
    };
});
