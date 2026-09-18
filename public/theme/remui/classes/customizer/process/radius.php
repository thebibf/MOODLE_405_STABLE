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
 * Theme customizer radius process trait
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui\customizer\process;

/**
 * Radius processing trait.
 *
 * Applies quicksetup card and button radius values as SCSS variables.
 * Only emits overrides when the settings have been explicitly saved to the
 * database — unset configs fall through to the SCSS !default values.
 */
trait radius {
    /**
     * Process radius settings and inject SCSS variables.
     *
     * Sets:
     *  - $card-border-radius  — used by course cards, Bootstrap cards, forms, modules
     *  - $btn-border-common   — base for Bootstrap $btn-border-radius-*
     *  - $button-{sm,md,lg}-border-radius — used by _buttons.scss .btn rules
     *
     * @param array $variables SCSS variables accumulator (passed by reference).
     */
    private function process_radius(&$variables) {
        // Card / block radius.
        $cardraw = get_config('theme_remui', 'quicksetup-card-radius');
        if ($cardraw !== false && $cardraw !== '') {
            $cardpx = max(0, (int)$cardraw);
            $variables['card-border-radius'] = $cardpx . 'px';
            $variables['container-main-area-rad'] = $cardpx . 'px';
        }

        // Button / tag radius.
        // Skip when value is 0 — treat as "use theme default" to avoid clobbering
        // the per-button-size defaults set by process_global_buttons.
        $btnraw = get_config('theme_remui', 'quicksetup-btn-radius');
        if ($btnraw !== false && $btnraw !== '') {
            $btnpx = (int)$btnraw;
            // Bootstrap-level button radius variable (preset/remui.scss).
            $variables['btn-border-common'] = $btnpx . 'px';
            // Theme-level per-size variables used in _buttons.scss.
            $variables['button-sm-border-radius'] = $btnpx;
            $variables['button-md-border-radius'] = $btnpx;
            $variables['button-lg-border-radius'] = $btnpx;
            
	    // Bootstrap root CSS vars --bs-border-radius* are derived from these.
            $variables['border-radius'] = $btnpx . 'px';
            $variables['border-radius-sm'] = $btnpx . 'px';
            $variables['border-radius-lg'] = $btnpx . 'px';
        }
    }
}
