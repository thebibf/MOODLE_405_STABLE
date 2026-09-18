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
 * Get focus mode sections Service
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_remui\external;

use external_function_parameters;
use external_value;
use context_course;
use theme_remui\utility;

/**
 * Get focus mode sections service trait
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait get_focus_mode_sections {
    /**
     * Describes the parameters for get_focus_mode_sections
     * @return external_function_parameters
     */
    public static function get_focus_mode_sections_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'coursemoduleid' => new external_value(PARAM_INT, 'Course Module ID', VALUE_DEFAULT, 0),
            ]
        );
    }

    /**
     * Recursively convert objects to arrays and moodle_url objects to strings.
     * @param  mixed $data Data to sanitize
     * @return mixed Sanitized data
     */
    private static function sanitize_for_json($data) {
        if (is_object($data)) {
            if (method_exists($data, 'out')) {
                return $data->out();
            }
            $data = (array) $data;
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize_for_json($value);
            }
        }
        return $data;
    }

    /**
     * Return focus mode activity sections and prev/next links as JSON
     * @param  int $courseid Course ID
     * @param  int $coursemoduleid Course Module ID
     * @return array Sections data
     */
    public static function get_focus_mode_sections($courseid, $coursemoduleid = 0) {
        global $DB;

        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
        $context = context_course::instance($courseid);
        self::validate_context($context);

        if ($coursemoduleid > 0) {
            [$sections, $active, $previous, $next] = utility::get_focus_mode_sections($course, $coursemoduleid);
        } else {
            [$sections, $active, $previous, $next] = utility::get_focus_mode_sections($course);
        }

        return [
            'sections' => json_encode(self::sanitize_for_json($sections)),
            'active'   => $active,
            'previous' => $previous,
            'next'     => $next,
            'status'   => true,
        ];
    }

    /**
     * Describes the get_focus_mode_sections return value
     * @return external_function_parameters
     */
    public static function get_focus_mode_sections_returns() {
        return new external_function_parameters(
            [
                'sections' => new external_value(PARAM_RAW, 'Sections array in JSON'),
                'active'   => new external_value(PARAM_RAW, 'Active activity name'),
                'previous' => new external_value(PARAM_RAW, 'Previous activity URL'),
                'next'     => new external_value(PARAM_RAW, 'Next activity URL'),
                'status'   => new external_value(PARAM_BOOL, 'Success status'),
            ]
        );
    }
}
