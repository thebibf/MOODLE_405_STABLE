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
 * Javascript to initialise the myoverview block.
 *
 * @copyright  2018 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import * as View from './blockmyoverview/view';
import * as ViewNav from './blockmyoverview/view_nav';

/**
 * Move course action buttons (Manage/Create) into the block header beside the title.
 *
 * @param {object} root The root jQuery element for the overview block.
 */
const moveCourseActionsToHeader = (root) => {
    const actionsEl = document.getElementById(root[0].id + '-actions');
    if (!actionsEl) {
        return;
    }
    const section = root[0].closest('section[data-block="myoverview"]');
    if (!section) {
        return;
    }
    const headerWrapper = section.querySelector('.block-header-wrapper');
    if (!headerWrapper) {
        return;
    }
    actionsEl.classList.remove('d-none');
    actionsEl.classList.add('d-flex');
    const controlsWrapper = headerWrapper.querySelector('.controls-wrapper');
    headerWrapper.insertBefore(actionsEl, controlsWrapper || null);
};

/**
 * Initialise all of the modules for the overview block.
 *
 * @param {object} root The root element for the overview block.
 * @param {String} cardanimationsetting animation of the cards.
 */
export const init = (root, cardanimationsetting) => {
    // Initialise the course navigation elements.
    ViewNav.init(root);
    // Initialise the courses view modules.
    View.init(root, cardanimationsetting);
    moveCourseActionsToHeader(root);
};
