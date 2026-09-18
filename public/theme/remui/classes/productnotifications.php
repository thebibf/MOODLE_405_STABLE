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
 * Defines the cache usage
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui;

/**
 * Product notifications class.
 *
 * Handles enrollment and completion history tracking for product notifications.
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class productnotifications {
    /** @var string Current year */
    private $year;
    /** @var string Current month */
    private $month;
    /** @var string Completion history config prefix */
    private $hcompletion = "course_completion_history_";
    /** @var string Enrollment history config prefix */
    private $henrollment = "course_enrollment_history_";
    /** @var string Notification seen config prefix */
    private $notificationseen = "has_notification_seen_";
    /** @var array Month-wise history data */
    private $defaulthistory = [
        "01" => 0,
        "02" => 0,
        "03" => 0,
        "04" => 0,
        "05" => 0,
        "06" => 0,
        "07" => 0,
        "08" => 0,
        "09" => 0,
        "10" => 0,
        "11" => 0,
        "12" => 0,
    ];

    /**
     * Constructor.
     */
    public function __construct() {
        $this->year = date('Y');
        $this->month = date('m');
    }

    /**
     * Get current year.
     *
     * @return string Current year
     */
    public function get_curr_year() {
        return $this->year;
    }

    /**
     * Get current month.
     *
     * @return string Current month
     */
    public function get_curr_month() {
        return $this->month;
    }

    /**
     * Get config name by year.
     *
     * @param string $configname Config name prefix
     * @param string|null $year Year to append, null for current year
     * @return string Config name with year suffix
     */
    public function get_config_name_by_year($configname, $year = null) {
        if ($year == null) {
            $year = $this->year;
        }

        return $configname . $year;
    }

    /**
     * Get history config value.
     *
     * @param string $configname Config name
     * @return string|false Config value or false if not set
     */
    public function get_history_config($configname) {
        if ($config = get_config("theme_remui", $configname)) {
            return $config;
        }
        return false;
    }

    /**
     * Initialize history config with default values.
     *
     * @return void
     */
    public function init_history_config() {

        // Course Completion Default Data Set.
        $configname = $this->get_config_name_by_year($this->hcompletion);

        if (!$this->get_history_config($configname)) {
            set_config($configname, json_encode($this->defaulthistory), "theme_remui");
        }

        // Course Enrollment Default Data Set.
        $configname = $this->get_config_name_by_year($this->henrollment);

        if (!$this->get_history_config($configname)) {
            set_config($configname, json_encode($this->defaulthistory), "theme_remui");
        }
    }

    /**
     * Update enrollment history for current month.
     *
     * @return void
     */
    public function update_enrollment_history() {
        // Course Enrollment Default Data Set.
        $configname = $this->get_config_name_by_year($this->henrollment);

        if (!$config = $this->get_history_config($configname)) {
            $config = json_encode($this->defaulthistory);
            set_config($configname, $config, "theme_remui");
        }

        $config = json_decode($config, true);

        // Save record for current month as this works on event basis.
        $config[$this->month] = $config[$this->month] + 1;

        set_config($configname, json_encode($config), "theme_remui");
    }

    /**
     * Update course completion history for current month.
     *
     * @return void
     */
    public function update_completion_history() {
        global $DB;
        // Course Enrollment Default Data Set.
        $configname = $this->get_config_name_by_year($this->hcompletion);

        // Set the config again if not available.
        if (!$config = $this->get_history_config($configname)) {
            // Set default config if not present.
            $config = json_encode($this->defaulthistory);
            set_config($configname, $config, "theme_remui");
        }

        $config = json_decode($config, true);

        // Course Completion.
        $date = "1"; // No need to write it as 01.
        $year = $this->year;

        $todate = strtotime($year . "/" . $this->month . "/" . $date);

        // This will make sure to add leading '0' to number less than 10.
        $prevmonth = sprintf('%02d', $this->month - 1);

        // To handle the year change.
        if ($this->month == "01") {
            $prevmonth = "12";
            $year = $year - 1;
        }

        // Converting Human time to epoch.
        $fromdate = strtotime($year . "/" . $prevmonth . "/" . $date);

        // Fetching the completion records from table.
        $sql = "SELECT * FROM {course_completions} WHERE timecompleted BETWEEN $fromdate AND $todate";
        $records = count($DB->get_records_sql($sql));

        $config[$prevmonth] = $records; // Save the record in last month for completion.

        set_config($configname, json_encode($config), "theme_remui");
    }

    /**
     * Generate notification message to show.
     *
     * @return \stdClass Notification data object
     */
    public function get_notification_msg() {
        // Select current year configuration.
        $month = $this->month;
        $year = $this->year;

        // Its for new year change.
        if ($this->month == '01' || $this->month == '1') {
            $month = '12';
            $year = $this->year - 1;
        } else {
            $month = sprintf('%02d', $month - 1); // Fetch Last month data.
        }

        // Completion.
        $completioncount = $this->can_show_notification($this->hcompletion, $year, $month);

        // Enrollment.
        $enrollmentcount = $this->can_show_notification($this->henrollment, $year, $month);

        $lang = current_language();
        $data = new \stdClass();
        $data->msg = null; // Initially value is null.
        $data->img = null; // Initially value is null.
        if ($completioncount && $enrollmentcount) {
            $data->msg = "noti_enrolandcompletion";
            $data->param = ['completion' => $completioncount, 'enrolment' => $enrollmentcount];
            $data->img = 'graphics-3';
        } else if ($completioncount) {
            // Course Completion.
            $data->msg = "noti_completion";
            $data->param = ['completion' => $completioncount];
            $data->img = 'graphics-2'; // Course completion image.
        } else if ($enrollmentcount) {
            // Course Enrollment.
            $data->msg = "noti_enrol";
            $data->param = ['enrolment' => $enrollmentcount];
            $data->img = 'graphics-1'; // Course completion image.
        }

        return $data;
    }

    /**
     * Check if notification should be shown based on config values.
     *
     * @param string $configname Config name prefix
     * @param string $year Year to check
     * @param string $month Month to check
     * @return int|false Count value or false if not significant
     */
    private function can_show_notification($configname, $year, $month) {
        $configname = $this->get_config_name_by_year($configname, $year);
        $config = $this->get_history_config($configname);
        if ($config != null) {
            $config = json_decode($config, true);
            // We must have non-zero value to show the notification.
            if ($config[$month] > 0) {
                return $config[$month];
            }
        }
        return false;
    }
}
