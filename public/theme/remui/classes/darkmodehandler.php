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
 * Dark mode handler class.
 *
 * This class handles enabling/disabling dark mode styling for the site.
 * It loads the necessary JavaScript to apply the dark mode CSS.
 *
 * Usage:
 * $dm = new \theme_remui\darkmodehandler();
 * $dm->init();
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Dark mode handler class.
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_remui_darkmodehandler {
    /** @var bool Dark mode status */
    private $status = false;
    /** @var moodle_url Dark mode library URL */
    private $dmlibrary;
    /** @var bool Whether to allow JS loading */
    private $allowjsloading = true;

    /**
     * Constructor.
     *
     * @param bool $loadjs Whether to allow JS loading
     */
    public function __construct($loadjs = false) {
        $this->allowjsloading = $loadjs;
        $this->status = get_config('theme_remui', 'enabledarkmode');
        $this->dmlibrary = new moodle_url("/theme/remui/js/bundle.js");
    }

    /**
     * Get dark mode status.
     *
     * @return bool Dark mode status
     */
    public function get_status() {
        return $this->status;
    }

    /**
     * Initialize the dark mode handler.
     *
     * This handles loading the necessary JavaScript to apply dark mode styling.
     * It will only run for logged in users.
     *
     * Dark mode can be forced by passing the forcedm URL parameter.
     *
     * @return bool True if the dark mode JavaScript was loaded, false otherwise.
     */
    public function init() {
        global $PAGE;

        // Only allowed for logged in users.
        if (!isloggedin()) {
            return false;
        }

        // Include JS forcefully without any prior check.
        $forcedm = optional_param('forcedm', null, PARAM_BOOL);

        if (isset($forcedm)) {
            if ($forcedm) {
                return $this->load_dm_js();
            }
            return false;
        }

        if (!$this->status) {
            $this->status = get_config('theme_remui', 'enabledarkmode');
        }

        if (!$this->status) {
            $this->status = "allowonallpages";
        }

        $functioncall = "trigger_dm_" . $this->status;

        return $this->$functioncall();
    }

    /**
     * Trigger dark mode disable.
     *
     * @return bool Always returns false
     */
    private function trigger_dm_disable() {
        return false;
    }

    /**
     * Trigger dark mode on all pages.
     *
     * @return bool True if JS loaded successfully
     */
    private function trigger_dm_allowonallpages() {
        global $PAGE;

        return $this->load_dm_js();
    }

    /**
     * Trigger dark mode with page exclusions.
     *
     * @return bool True if JS loaded, false if page is excluded
     */
    private function trigger_dm_excludepages() {
        global $PAGE, $CFG;

        $pages = trim(get_config("theme_remui", "darkmodeexcludepages"));

        // Replacing consecutive commas with one.
        $pages = preg_replace("/,+/", ",", $pages);
        // Trimming the leading and trailing commas.
        $pages = trim($pages, ",");

        // In include page setting, if no page url is provided, it will always return true.
        if ($pages === "") {
            return  $this->load_dm_js();
        }

        $currpage = $PAGE->url;

        $pages = explode(',', $pages);

        foreach ($pages as $key => $page) {
            $page = trim(str_replace(trim($CFG->wwwroot), "", $page));

            if ($page == "/my/" && $PAGE->pagelayout == "mydashboard") {
                return false;
            }

            if ($this->get_matching_url($page, $currpage)) {
                return false;
            }
        }
        return $this->load_dm_js();
    }

    /**
     * Trigger dark mode with page inclusions.
     *
     * @return bool True if JS loaded, false if page is not included
     */
    private function trigger_dm_includepages() {
        global $PAGE, $CFG;

        $pages = trim(get_config("theme_remui", "darkmodeincludepages"));

        // Replacing consecutive commas with one.
        $pages = preg_replace("/,+/", ",", $pages);
        // Trimming the leading and trailing commas.
        $pages = trim($pages, ",");

        // In include page setting, if no page url is provided, it will always return false.
        if ($pages === "") {
            return false;
        }

        $currpage = $PAGE->url;

        if ($currpage->__toString() === $CFG->wwwroot . "/course/index.php") {
            $currpage = new moodle_url($currpage->__toString(), ['categoryid' => 'all']);
        }

        $pages = explode(',', $pages);

        foreach ($pages as $key => $page) {
            $page = trim(str_replace(trim($CFG->wwwroot), "", $page));

            // Custom check. Had no other option but to use strict url here for dashboard.
            if ($page == "/my/" && $PAGE->pagelayout == "mydashboard") {
                return $this->load_dm_js();
            }

            // Checking url match.
            if ($this->get_matching_url($page, $currpage)) {
                return $this->load_dm_js();
            }
        }

        return false;
    }

    /**
     * Load dark mode JavaScript.
     *
     * @return bool True if JS loaded or loading is allowed
     */
    private function load_dm_js() {
        global $PAGE;

        if ($this->allowjsloading) {
            $PAGE->requires->js($this->dmlibrary);
        }

        return true;
    }

    /**
     * Check if page URL matches the pattern.
     *
     * @param string $pagepattern URL pattern to match
     * @param \moodle_url $targetmatch Target URL to check
     * @return bool True if URL matches pattern
     */
    public function get_matching_url($pagepattern, \moodle_url $targetmatch) {
        $target = $targetmatch->out_as_local_url();

        $pattern = preg_quote($pagepattern, '@');

        if (strpos($pattern, '%') !== false) {
            // The URL match format is something like: /my/%.
            // We need to find all the URLs which match the first part of the pattern.

            $pattern = str_replace('%', '.*', $pattern);
        } else {
            // The URL match format is something like: /my/courses.php.
            // We need to find all the URLs which match with whole pattern.
            $pattern .= '$';
        }

        return !!preg_match("@{$pattern}@", $target);
    }

    /**
     * Check if dark mode icon animation should be shown.
     *
     * @return bool True if animation should be shown
     */
    public function show_icon_animation() {

        if (get_user_preferences("animate_dm_icon")) {
            unset_user_preference("animate_dm_icon");
            return true;
        }

        return false;
    }
}
