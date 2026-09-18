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
 * Accessibility widget helper module for RemUI theme.
 * Provides helper functions for accessibility widget functionality.
 *
 * @module     theme_remui/edw_aw_helper
 * @copyright  (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/str', 'core/toast', 'theme_remui/feedbackcollection','core_user/repository'], function ($, Ajax, Str, Toast, feedbackcollection,UserRepository) {

    /**
     * Selectors
     */
    var SELECTOR = {
        ENABLEAW: 'a[href="#enable_aw"]',
        DISABLEAW: 'a[href="#disable_aw"]',
        ASWMENUBTN: '.asw-menu-btn',
        ASWMENUSETTING: '.asw-menu-setting',
        ASWMENUSETTINGDROPDOWNBTN: '#aw_settingsDropdown',
        ADMINSETTINGURL: '.adminsettingurl',
        PREFSETTINGURL: '.prefsettingurl',
        ASWCONTAINER: '.asw-container',
    };

    const registerCommonEvents = (issiteadmin, feedbackstatus) => {

        $(document).on("click", SELECTOR.ENABLEAW + "," + SELECTOR.DISABLEAW, function (e) {
            e.preventDefault(); // Prevent default anchor behavior

            var $this = $(this); // Store reference to clicked element
            let message = '';

            if ($this.attr("href") == "#enable_aw") {
                const newState = {
                    href: "#disable_aw",
                    string: 'enable-aw-for-me',
                    value: false
                };
                updateAWState($this, newState);
                message = M.util.get_string('enable-aw-for-me-notice', 'theme_remui');
            } else {
                const newState = {
                    href: "#enable_aw",
                    string: 'disable-aw-for-me',
                    value: true
                };
                updateAWState($this, newState);
                message = M.util.get_string('disable-aw-for-me-notice', 'theme_remui');
            }
            // Store the notification message in localStorage
            localStorage.setItem("awNotification", message);
            // Reload the page
            setTimeout(() => {
                window.location.reload();
            }, 200);
        });

        $(document).on("click", `${SELECTOR.ASWCONTAINER},${SELECTOR.ASWMENUSETTINGDROPDOWNBTN}`, function () {

            var siteadminurl = M.cfg.wwwroot + "/admin/settings.php?section=themesettingremui#admin-enableaccessibilitytools";
            var prefurl = M.cfg.wwwroot + "/user/preferences.php";

            // Update the href attributes
            $(SELECTOR.ADMINSETTINGURL).attr('href', siteadminurl);
            $(SELECTOR.PREFSETTINGURL).attr('href', prefurl);

            // Show/hide admin settings based on issiteadmin
            if (issiteadmin) {
                $(SELECTOR.ADMINSETTINGURL).show();
            } else {
                $(SELECTOR.ADMINSETTINGURL).hide();
            }

            if (!feedbackstatus) {
                setTimeout(() => {
                    $(".asw-footer").removeClass('d-none');
                }, 3000);
            }
        });

        $(document).on("click", '#aw_feedbackcollection-form .aw-feedback-close', async function() {
            removeFeedbackOption();
        });

        $(document).on("change", '#aw_feedbackcollection-form .feedback-item input[type="radio"]', async function() {
            var question = $('#aw_feedbackcollection-form input[name="question"]').val();
            var questionName = $('#aw_feedbackcollection-form input[name="questionname"]').val();
            var answer = $('#aw_feedbackcollection-form input[name="answer"]:checked').val();

            // Create form data object
            var submitData = {
                question: question,
                answer: answer,
                usercomment: ""
            };

            removeFeedbackOption(true);

            feedbackHandler(questionName, submitData);
        });

        $(document).on("submit", '#aw_feedbackcollection-form', async function(e) {
            e.preventDefault();
            // Get form data using FormData
            const formData = new FormData(this);

            // Access individual values
            const question = formData.get('question');
            const questionName = formData.get('questionname');
            const answer = $('#aw_feedbackcollection-form input[name="answer"]:checked').val();

            const submitType = $(e.target).find('button[type="submit"]:focus').data('type');
            const feedback = submitType === "submit" ? formData.get('feedback') : "";

            // Create form data object
            var submitData = {
                question: question,
                answer: answer,
                usercomment: feedback,
            };

            feedbackHandler(questionName, submitData, true);
        });
    };
    function update_aw_feedback(submiteddata, isgetfeedback = false) {
        return Ajax.call([{
            methodname: 'theme_remui_do_feedbackcollection_action',
            args: {
                action: "update_aw_feedback",
                config: JSON.stringify({
                    "feedback": submiteddata,
                    "isgetfeedback": isgetfeedback
                })
            }
        }])[0];
    }
    // Helper function to update the AW state
    function updateAWState($element, state) {
        $element.attr("href", state.href).text(M.util.get_string(state.string, 'theme_remui'));
            require(['core_user/repository'], function(UserRepository) {
                UserRepository.setUserPreference('acs-widget-status', state.value);
            });
    }

    function removeFeedbackOption(onlyHide = false) {
        if (onlyHide) {

            $(".asw-footer").css("padding", 0);
            $(".feedback-question-wrapper").removeClass("d-flex").addClass("d-none");

            return;
        }

        var feedback_option = $(".asw-footer");
        feedback_option.remove();
    }

    async function feedbackHandler(questionName, submitData, isgetfeedback = false) {
        var aw_feedbacks = await update_aw_feedback(submitData, isgetfeedback);

        aw_feedbacks = JSON.parse(aw_feedbacks);

        // Convert aw_feedbacks to string before parsing
        var feedbackString = String(aw_feedbacks);
        aw_feedbacks = JSON.parse(feedbackString);

        feedbackString = Object.entries(aw_feedbacks["counts"])
            .map(([key, value]) => `${key}: ${value}`)
            .join(", ");

        submitData.answer = feedbackString;

        // Call the submit_feedback method from feedbackcollection module
        feedbackcollection.submit_feedback(questionName, submitData, true);
        var feedbackcommentdata  = {
            question: "Accessibility widget feedbacks",
            answer: JSON.stringify(aw_feedbacks["accessibilityfeedbacks"]),
        };

        feedbackcollection.submit_feedback("accessibilitywidgetfeedback", feedbackcommentdata, true);
        UserRepository.setUserPreference('acs-feedback-status', true);
        if (isgetfeedback) {
            removeFeedbackOption();
        }
    }

    /**
     * Dynamically load the accessibility widget script (edw_aw.js).
     * Uses a global flag to prevent duplicate loads.
     *
     * @returns {Promise<void>}
     */
    function loadWidget() {
        return new Promise(function(resolve, reject) {
            if (window.__edw_aw_loaded) {
                resolve();
                return;
            }
            window.__edw_aw_loaded = true;

            var script = document.createElement('script');
            script.src = M.cfg.wwwroot + '/theme/remui/js/edw_aw.js';
            script.async = true;
            script.onload = function() {
                // edw_aw.js registers a readystatechange listener to initialize.
                // When loaded dynamically after the page is already complete,
                // that event has already fired and the widget never starts.
                // Dispatch it manually so initialization is guaranteed.
                var state = document.readyState;
                if (state === 'complete' || state === 'interactive') {
                    document.dispatchEvent(new Event('readystatechange'));
                }
                resolve();
            };
            script.onerror = function() {
                window.__edw_aw_loaded = false;
                reject(new Error('Failed to load accessibility widget.'));
            };
            document.head.appendChild(script);
        });
    }

    /**
     * Check whether the user has previously interacted with the widget.
     * Uses the `asw` cookie (set by the widget) or localStorage fallback.
     *
     * @returns {boolean}
     */
    function hasUsedWidget() {
        return document.cookie.indexOf('asw=') !== -1 || localStorage.getItem('asw_used') === '1';
    }

    /**
     * Remove the lightweight lazy-load placeholder button once the real
     * widget has initialised and rendered its own .asw-widget container.
     */
    function removeLazyButton() {
        var lazyBtn = document.querySelector('.asw-lazy-btn');
        if (lazyBtn) {
            lazyBtn.remove();
        }
    }

    /**
     * Poll for the real widget container and remove the placeholder.
     */
    function waitForWidgetAndCleanup() {
        var attempts = 0;
        var maxAttempts = 50; // 5 seconds max
        var interval = setInterval(function() {
            attempts++;
            if (document.querySelector('.asw-widget')) {
                clearInterval(interval);
                removeLazyButton();
            } else if (attempts >= maxAttempts) {
                clearInterval(interval);
            }
        }, 100);
    }

    /**
     * Render a lightweight placeholder floating button that looks like the
     * real accessibility widget but loads the heavy JS only on first click.
     */
    function renderLazyButton() {
        // Avoid duplicate placeholders or conflicts with the real widget.
        if (document.querySelector('.asw-widget, .asw-lazy-btn')) {
            return;
        }

        var wrapper = document.querySelector('.floating-buttons-wrapper');
        var gotop = wrapper ? wrapper.querySelector('#gotop') : null;

        var widget = document.createElement('div');
        widget.className = 'asw-widget asw-lazy-btn';

        var btn = document.createElement('a');
        btn.href = '#';
        btn.className = 'asw-menu-btn btn-floating';
        btn.title = 'Open Accessibility Menu';
        btn.setAttribute('role', 'button');
        btn.setAttribute('aria-label', 'Open Accessibility Menu');
        btn.innerHTML = '<span class="edw-icon edw-icon-Accessibility"></span>' +
            '<span class="btn-floating-text">Open Accessibility</span>';

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            btn.classList.add('asw-loading');

            loadWidget().then(function() {
                // Persist flag so next page loads the widget immediately.
                localStorage.setItem('asw_used', '1');
                waitForWidgetAndCleanup();
            }).catch(function() {
                btn.classList.remove('asw-loading');
            });
        });

        widget.appendChild(btn);

        if (gotop) {
            gotop.after(widget);
        } else if (wrapper) {
            wrapper.appendChild(widget);
        } else {
            document.body.appendChild(widget);
        }
    }

    // Check for notification after reload
    $(document).ready(function () {
        let notificationMessage = localStorage.getItem("awNotification");
        if (notificationMessage) {
            Toast.add(notificationMessage, {
                delay: 5000,
                closeButton: true,
                type: 'warning edw_aw_toast'
            });
            localStorage.removeItem("awNotification"); // Clear the flag
        }
    });
    return {
        init: function (issiteadmin = false, feedbackstatus = false, isloggedin = false, widgetenabled = false) {
            registerCommonEvents(issiteadmin, feedbackstatus, isloggedin);

            if (!widgetenabled) {
                return;
            }

            if (hasUsedWidget()) {
                loadWidget();
            } else {
                renderLazyButton();
            }
        },
    };
});
