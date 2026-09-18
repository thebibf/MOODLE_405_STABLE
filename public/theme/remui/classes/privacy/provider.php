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
 * Privacy Subsystem implementation for theme_remui.
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui\privacy;

use core_privacy\local\metadata\collection;

/**
 * The remui theme stores a user preference data.
 *
 * @copyright  2018 Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,
    // This plugin has some sitewide user preferences to export.
    \core_privacy\local\request\user_preference_provider {
    /** The user preference for the navigation drawer. */
    public const DRAWER_OPEN_NAV = 'drawer-open-nav';

    /** The user preferences for the course index. */
    public const DRAWER_OPEN_INDEX = 'drawer-open-index';

    /** The user preferences for the blocks drawer. */
    public const DRAWER_OPEN_BLOCK = 'drawer-open-block';

    /** The user preference for course view state. */
    public const COURSE_VIEW_STATE = 'course_view_state';

    /** The user preference for dismissed announcement. */
    public const REMUI_DISMISED_ANNOUNCEMENT = 'remui_dismised_announcement';

    /** The user preference for quick menu. */
    public const EDW_QUICK_MENU = 'edw-quick-menu';

    /** The user preference for in-product notifications. */
    public const EDWISER_INPRODUCT_NOTIFICATION = 'edwiser_inproduct_notification';

    /** The user preference for focus mode. */
    public const ENABLE_FOCUS_MODE = 'enable_focus_mode';

    /** The user preference for homepage deprecation seen. */
    public const HOMEPAGE_DEPRICATED_SEEN = 'homepagedepricatedseen';

    /** The user preference for dark mode customizer warning visibility. */
    public const DARKMODE_CUSTOMIZER_WARN_NOT_VISIBLE = 'darkmodecustomizerwarnnotvisible';

    /** The user preference for forceful migrate modal seen. */
    public const FORCEFUL_MIGRATE_MODAL_SEEN = 'forcefulmigratemodalseen';

    /** The user preference for homepage available modal seen. */
    public const HOMEPAGE_AVAILABLE_MODAL_SEEN = 'homepageavailablemodalseen';

    /** The user preference for accessibility widget status. */
    public const ACS_WIDGET_STATUS = 'acs-widget-status';

    /** The user preference for accessibility feedback status. */
    public const ACS_FEEDBACK_STATUS = 'acs-feedback-status';

    /** The user preference for course cache reset. */
    public const COURSE_CACHE_RESET = 'course_cache_reset';

    /** The user preference for course reset time. */
    public const COURSE_RESET_TIME = 'course_reset_time';

    /** The user preference for animate dark mode icon. */
    public const ANIMATE_DM_ICON = 'animate_dm_icon';

    /**
     * Returns meta data about this system.
     *
     * @param  collection $items The initialised item collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $items): collection {
        // User preferences.
        $items->add_user_preference(self::DRAWER_OPEN_NAV, 'privacy:metadata:preference:draweropennav');
        $items->add_user_preference(self::DRAWER_OPEN_INDEX, 'privacy:metadata:preference:draweropenindex');
        $items->add_user_preference(self::DRAWER_OPEN_BLOCK, 'privacy:metadata:preference:draweropenblock');
        $items->add_user_preference(self::COURSE_VIEW_STATE, 'privacy:metadata:preference:courseviewstate');
        $items->add_user_preference(self::REMUI_DISMISED_ANNOUNCEMENT, 'privacy:metadata:preference:remuidismisedannouncement');
        $items->add_user_preference(self::EDW_QUICK_MENU, 'privacy:metadata:preference:edwquickmenu');
        $items->add_user_preference(
            self::EDWISER_INPRODUCT_NOTIFICATION,
            'privacy:metadata:preference:edwiserinproductnotification'
        );
        $items->add_user_preference(self::ENABLE_FOCUS_MODE, 'privacy:metadata:preference:enablefocusmode');
        $items->add_user_preference(
            self::HOMEPAGE_DEPRICATED_SEEN,
            'privacy:metadata:preference:homepagedepricatedseen'
        );
        $items->add_user_preference(
            self::DARKMODE_CUSTOMIZER_WARN_NOT_VISIBLE,
            'privacy:metadata:preference:darkmodecustomizerwarnnotvisible'
        );
        $items->add_user_preference(self::FORCEFUL_MIGRATE_MODAL_SEEN, 'privacy:metadata:preference:forcefulmigratemodalseen');
        $items->add_user_preference(self::HOMEPAGE_AVAILABLE_MODAL_SEEN, 'privacy:metadata:preference:homepageavailablemodalseen');
        $items->add_user_preference(self::ACS_WIDGET_STATUS, 'privacy:metadata:preference:acswidgetstatus');
        $items->add_user_preference(self::ACS_FEEDBACK_STATUS, 'privacy:metadata:preference:acsfeedbackstatus');
        $items->add_user_preference(self::COURSE_CACHE_RESET, 'privacy:metadata:preference:coursecachereset');
        $items->add_user_preference(self::COURSE_RESET_TIME, 'privacy:metadata:preference:courseresettime');
        $items->add_user_preference(self::ANIMATE_DM_ICON, 'privacy:metadata:preference:animatedmicon');

        // External API disclosure.
        // Document the data fields sent to edwiser.org for usage tracking, license validation, and feedback.
        $items->add_external_location_link(
            'edwiser.org',
            [
                'siteurl' => 'privacy:metadata:external:edwiserorg:siteurl',
                'product_name' => 'privacy:metadata:external:edwiserorg:productname',
                'system_version' => 'privacy:metadata:external:edwiserorg:systemversion',
                'total_courses' => 'privacy:metadata:external:edwiserorg:totalcourses',
                'total_users' => 'privacy:metadata:external:edwiserorg:totalusers',
                'installed_plugins' => 'privacy:metadata:external:edwiserorg:installedplugins',
                'license_key' => 'privacy:metadata:external:edwiserorg:licensekey',
                'feedback_data' => 'privacy:metadata:external:edwiserorg:feedbackdata',
            ],
            'privacy:metadata:external:edwiserorg'
        );

        return $items;
    }

    /**
     * Store all user preferences for the plugin.
     *
     * @param int $userid The userid of the user whose data is to be exported.
     */
    public static function export_user_preferences(int $userid) {
        $draweropennavpref = get_user_preferences(self::DRAWER_OPEN_NAV, null, $userid);

        if (isset($draweropennavpref)) {
            $preferencestring = get_string('privacy:drawernavclosed', 'theme_remui');
            if ($draweropennavpref == 'true') {
                $preferencestring = get_string('privacy:drawernavopen', 'theme_remui');
            }
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::DRAWER_OPEN_NAV,
                $draweropennavpref,
                $preferencestring
            );
        }

        $draweropenindexpref = get_user_preferences(self::DRAWER_OPEN_INDEX, null, $userid);

        if (isset($draweropenindexpref)) {
            $preferencestring = get_string('privacy:drawerindexclosed', 'theme_remui');
            if ($draweropenindexpref == 1) {
                $preferencestring = get_string('privacy:drawerindexopen', 'theme_remui');
            }
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::DRAWER_OPEN_INDEX,
                $draweropenindexpref,
                $preferencestring
            );
        }

        $draweropenblockpref = get_user_preferences(self::DRAWER_OPEN_BLOCK, null, $userid);

        if (isset($draweropenblockpref)) {
            $preferencestring = get_string('privacy:drawerblockclosed', 'theme_remui');
            if ($draweropenblockpref == 1) {
                $preferencestring = get_string('privacy:drawerblockopen', 'theme_remui');
            }
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::DRAWER_OPEN_BLOCK,
                $draweropenblockpref,
                $preferencestring
            );
        }

        // Export course view state preference.
        $courseviewstatepref = get_user_preferences(self::COURSE_VIEW_STATE, null, $userid);
        if (isset($courseviewstatepref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::COURSE_VIEW_STATE,
                $courseviewstatepref,
                get_string('privacy:metadata:preference:courseviewstate', 'theme_remui')
            );
        }

        // Export dismissed announcement preference.
        $remuidismisedannouncementpref = get_user_preferences(self::REMUI_DISMISED_ANNOUNCEMENT, null, $userid);
        if (isset($remuidismisedannouncementpref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::REMUI_DISMISED_ANNOUNCEMENT,
                $remuidismisedannouncementpref,
                get_string('privacy:metadata:preference:remuidismisedannouncement', 'theme_remui')
            );
        }

        // Export quick menu preference.
        $edwquickmenupref = get_user_preferences(self::EDW_QUICK_MENU, null, $userid);
        if (isset($edwquickmenupref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::EDW_QUICK_MENU,
                $edwquickmenupref,
                get_string('privacy:metadata:preference:edwquickmenu', 'theme_remui')
            );
        }

        // Export in-product notification preference.
        $edwiserinproductnotificationpref = get_user_preferences(self::EDWISER_INPRODUCT_NOTIFICATION, null, $userid);
        if (isset($edwiserinproductnotificationpref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::EDWISER_INPRODUCT_NOTIFICATION,
                $edwiserinproductnotificationpref,
                get_string('privacy:metadata:preference:edwiserinproductnotification', 'theme_remui')
            );
        }

        // Export focus mode preference.
        $enablefocusmodepref = get_user_preferences(self::ENABLE_FOCUS_MODE, null, $userid);
        if (isset($enablefocusmodepref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::ENABLE_FOCUS_MODE,
                $enablefocusmodepref,
                get_string('privacy:metadata:preference:enablefocusmode', 'theme_remui')
            );
        }

        // Export homepage deprecation seen preference.
        $homepagedepricatedseenpref = get_user_preferences(self::HOMEPAGE_DEPRICATED_SEEN, null, $userid);
        if (isset($homepagedepricatedseenpref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::HOMEPAGE_DEPRICATED_SEEN,
                $homepagedepricatedseenpref,
                get_string('privacy:metadata:preference:homepagedepricatedseen', 'theme_remui')
            );
        }

        // Export dark mode customizer warning preference.
        $darkmodecustomizerwarnnotvisiblepref = get_user_preferences(self::DARKMODE_CUSTOMIZER_WARN_NOT_VISIBLE, null, $userid);
        if (isset($darkmodecustomizerwarnnotvisiblepref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::DARKMODE_CUSTOMIZER_WARN_NOT_VISIBLE,
                $darkmodecustomizerwarnnotvisiblepref,
                get_string('privacy:metadata:preference:darkmodecustomizerwarnnotvisible', 'theme_remui')
            );
        }

        // Export forceful migrate modal seen preference.
        $forcefulmigratemodalseenpref = get_user_preferences(self::FORCEFUL_MIGRATE_MODAL_SEEN, null, $userid);
        if (isset($forcefulmigratemodalseenpref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::FORCEFUL_MIGRATE_MODAL_SEEN,
                $forcefulmigratemodalseenpref,
                get_string('privacy:metadata:preference:forcefulmigratemodalseen', 'theme_remui')
            );
        }

        // Export homepage available modal seen preference.
        $homepageavailablemodalseenpref = get_user_preferences(self::HOMEPAGE_AVAILABLE_MODAL_SEEN, null, $userid);
        if (isset($homepageavailablemodalseenpref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::HOMEPAGE_AVAILABLE_MODAL_SEEN,
                $homepageavailablemodalseenpref,
                get_string('privacy:metadata:preference:homepageavailablemodalseen', 'theme_remui')
            );
        }

        // Export accessibility widget status preference.
        $acswidgetstatuspref = get_user_preferences(self::ACS_WIDGET_STATUS, null, $userid);
        if (isset($acswidgetstatuspref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::ACS_WIDGET_STATUS,
                $acswidgetstatuspref,
                get_string('privacy:metadata:preference:acswidgetstatus', 'theme_remui')
            );
        }

        // Export accessibility feedback status preference.
        $acsfeedbackstatuspref = get_user_preferences(self::ACS_FEEDBACK_STATUS, null, $userid);
        if (isset($acsfeedbackstatuspref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::ACS_FEEDBACK_STATUS,
                $acsfeedbackstatuspref,
                get_string('privacy:metadata:preference:acsfeedbackstatus', 'theme_remui')
            );
        }

        // Export course cache reset preference.
        $coursecacheresetpref = get_user_preferences(self::COURSE_CACHE_RESET, null, $userid);
        if (isset($coursecacheresetpref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::COURSE_CACHE_RESET,
                $coursecacheresetpref,
                get_string('privacy:metadata:preference:coursecachereset', 'theme_remui')
            );
        }

        // Export course reset time preference.
        $courseresettimepref = get_user_preferences(self::COURSE_RESET_TIME, null, $userid);
        if (isset($courseresettimepref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::COURSE_RESET_TIME,
                $courseresettimepref,
                get_string('privacy:metadata:preference:courseresettime', 'theme_remui')
            );
        }

        // Export animate dark mode icon preference.
        $animatedmiconpref = get_user_preferences(self::ANIMATE_DM_ICON, null, $userid);
        if (isset($animatedmiconpref)) {
            \core_privacy\local\request\writer::export_user_preference(
                'theme_remui',
                self::ANIMATE_DM_ICON,
                $animatedmiconpref,
                get_string('privacy:metadata:preference:animatedmicon', 'theme_remui')
            );
        }
    }
}
