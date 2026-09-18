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
 * Theme customizer iconset process trait
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui\customizer\process;

/**
 * Iconset processing trait.
 *
 * Maps the saved icon set selection to the $remui-icon-font-family SCSS variable.
 */
trait iconset {
    /**
     * Process iconset setting and inject the icon font-family SCSS variable.
     *
     * Sets $remui-icon-font-family to 'Remui-v1' for Set 1, 'Remui' for default.
     *
     * @param array $variables SCSS variables accumulator (passed by reference).
     */
    private function process_iconset(&$variables) {
        $iconset = get_config('theme_remui', 'quicksetup-iconset');
        if ($iconset === 'iconset-set1') {
            $variables['remui-icon-font-family'] = 'Remui-v1';
        }
        elseif ($iconset === 'iconset-set2') {
            $variables['remui-icon-font-family'] = 'Remui-v2';
        }
        else {
            $variables['remui-icon-font-family'] = 'Remui';
        }
    }
}
