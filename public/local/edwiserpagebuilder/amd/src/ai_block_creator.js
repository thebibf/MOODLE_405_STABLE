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
 * AI Block Creator module for Edwiser Page Builder.
 *
 * Handles the AI prompt modal lifecycle: prompt submission, validation,
 * optimization, content generation, preview, and block insertion.
 *
 * Migrated from blocks/edwiseradvancedblock/amd/src/prompt_handler.js
 * and enhanced with validation, optimization, and preview capabilities.
 *
 * @module     local_edwiserpagebuilder/ai_block_creator
 * @copyright  (c) 2026 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'local_edwiserpagebuilder/jquery';
import ModalFactory from 'local_edwiserpagebuilder/remuiblck/modal_factory';
import ModalEvents from 'core/modal_events';
import Templates from 'core/templates';
import Ajax from 'core/ajax';
import Notification from 'core/notification';
import {get_string as getString} from 'core/str';

let aiModal = null;
let contextId = null;
let lastGeneratedContent = null;
let lastPrompt = '';
let generationCancelled = false;

/**
 * Regex pattern to match the full default "Create custom block" (html block) div.
 * Matches the complete <div class="container-fluid">...</div> structure including
 * inner tags, dynamic IDs, and the default text content.
 * @type {RegExp}
 */
const HTML_BLOCK_PATTERN = /<div[^>]*container-fluid[^>]*>[\s\S]*?Create your custom blocks[\s\S]*?improved page builder[\s\S]*?<\/div>/i;

/**
 * Initialize AI Block Creator event listeners.
 *
 * @param {number} ctxId The Moodle context ID.
 */
export const init = (ctxId) => {
    contextId = ctxId;

    // Bind click handler for AI badge button and block header AI buttons.
    $(document).off('click.aiblockcreator', '[data-action="open-ai-prompt"]');
    $(document).on('click.aiblockcreator', '[data-action="open-ai-prompt"]', async function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var blockId = $btn.data('blockid') || '';

        // Issue 3: Check if AI provider is configured before opening modal.
        if ($btn.data('provider-configured') === 0 || $btn.data('provider-configured') === '0') {
            var settingsUrl = $btn.data('ai-settings-url') || '#';
            var noProviderMsg = await getString('ai_no_provider_configured', 'local_edwiserpagebuilder');
            var configureLabel = await getString('ai_configure_provider', 'local_edwiserpagebuilder');
            await showNoProviderModal(noProviderMsg, configureLabel, settingsUrl);
            return;
        }

        openAiModal(blockId);
    });

    // Issue 1: Show AI buttons only on blocks with default html block content.
    filterAiButtons();
};

/**
 * Filter AI prompt buttons to only show on "Create custom block" (html) blocks.
 * Checks each block's content against the default html block pattern from staticcdn.
 */
const filterAiButtons = () => {
    $('.epb-ai-prompt-btn').each(function() {
        var $btn = $(this);
        var blockId = $btn.data('blockid');
        if (!blockId) {
            return;
        }

        // Find the block section element in the DOM.
        var $blockSection = $('section#inst' + blockId);
        if (!$blockSection.length) {
            return;
        }

        // Get the block's rendered content.
        var blockContent = $blockSection.find('.block-content').html() || '';

        // Check if content matches the default html block pattern.
        if (!HTML_BLOCK_PATTERN.test(blockContent)) {
            return;
        }

        // Ensure there's no meaningful content added outside the default block.
        // Strip the default block, style/script blocks, and remaining HTML tags.
        var remaining = blockContent.replace(HTML_BLOCK_PATTERN, '');
        remaining = remaining.replace(/<style[^>]*>[\s\S]*?<\/style>/gi, '');
        remaining = remaining.replace(/<script[^>]*>[\s\S]*?<\/script>/gi, '');
        var remainingText = remaining.replace(/<[^>]*>/g, '').trim();
        if (remainingText.length === 0) {
            $btn.removeClass('d-none');
        }
    });
};

/**
 * Show a modal informing the user that no AI provider is configured.
 *
 * @param {string} message The "no providers" message.
 * @param {string} configureLabel The label for the configure link.
 * @param {string} settingsUrl The URL to the AI provider settings page.
 */
const showNoProviderModal = async (message, configureLabel, settingsUrl) => {
    try {
        var bodyHtml = '<div class="epb-ai-no-provider-info">'
            + '<div class="alert alert-warning mb-3">'
            + '<i class="fa fa-exclamation-triangle mr-2"></i>'
            + escapeHtml(message)
            + '</div>'
            + '<a href="' + escapeHtml(settingsUrl) + '" class="btn btn-primary">'
            + '<i class="fa fa-cog mr-1"></i> '
            + escapeHtml(configureLabel)
            + '</a>'
            + '</div>';

        var modal = await ModalFactory.create({
            type: ModalFactory.types.DEFAULT,
            title: await getString('ai_modal_title', 'local_edwiserpagebuilder'),
            body: bodyHtml,
        });

        modal.getRoot().on(ModalEvents.hidden, () => {
            modal.destroy();
        });

        modal.show();
    } catch (error) {
        Notification.exception(error);
    }
};

/**
 * Open the AI prompt modal.
 *
 * @param {string} blockId Optional block ID for editing existing blocks.
 */
const openAiModal = async (blockId = '') => {
    try {
        const modalBody = await Templates.render('local_edwiserpagebuilder/ai_prompt_modal', {
            contextid: contextId,
            blockid: blockId,
        });

        const modal = await ModalFactory.create({
            type: ModalFactory.types.DEFAULT,
            title: await getString('ai_modal_title', 'local_edwiserpagebuilder'),
            body: modalBody,
            large: true,
        });

        aiModal = modal;
        lastGeneratedContent = null;
        lastPrompt = '';

        modal.getRoot().addClass('epb-ai-modal');

        modal.getRoot().on(ModalEvents.hidden, () => {
            generationCancelled = true;
            resetModal();
            modal.destroy();
            aiModal = null;
        });

        modal.show();

        // Bind events after modal is shown.
        setTimeout(async() => {
            bindModalEvents(modal);
            // Fetch and display remaining requests count on modal open.
            fetchAndDisplayRequestsRemaining(modal.getRoot());
            // Show default tips on modal open.
            const tips = await Promise.all([
                getString('ai_prompt_add_action_verb', 'local_edwiserpagebuilder'),
                getString('ai_prompt_add_design_context', 'local_edwiserpagebuilder'),
                getString('ai_prompt_add_detail', 'local_edwiserpagebuilder'),
                getString('ai_prompt_add_specifics', 'local_edwiserpagebuilder'),
            ]);
            showValidationFeedback(modal.getRoot(), [], tips);
        }, 100);

    } catch (error) {
        Notification.exception(error);
    }
};

/**
 * Bind event handlers within the AI modal.
 *
 * @param {Object} modal The modal instance.
 */
const bindModalEvents = (modal) => {
    const $root = modal.getRoot();

    // Character counter.
    $root.find('#epb-ai-prompt-textarea').on('input', function() {
        const len = $(this).val().length;
        $root.find('.epb-ai-char-current').text(len);
    });

    // Enable refine prompt link when textarea is focused, disable on blur.
    // Do not re-enable if requests are exhausted.
    $root.find('#epb-ai-prompt-textarea').on('focus', function() {
        if ($root.find('#epb-ai-requests-exhausted').hasClass('d-none')) {
            $root.find('#epb-ai-optimize-link').removeClass('disabled').removeAttr('aria-disabled');
        }
    });
    $root.find('#epb-ai-prompt-textarea').on('blur', function() {
        $root.find('#epb-ai-optimize-link').addClass('disabled').attr('aria-disabled', 'true');
    });

    // Generate button.
    $root.find('#epb-ai-generate-btn').on('click', async function() {
        const promptText = $root.find('#epb-ai-prompt-textarea').val().trim();
        if (!promptText) {
            showValidationFeedback($root, [await getString('ai_prompt_required', 'local_edwiserpagebuilder')], []);
            return;
        }
        await handleGenerate($root, promptText);
    });

    // Cancel (close modal) button.
    $root.find('#epb-ai-cancel-btn').on('click', function() {
        modal.hide();
    });

    // Cancel generation button (inside loading overlay).
    $root.find('#epb-ai-cancel-generate-btn').on('click', function() {
        generationCancelled = true;
        showLoading($root, false);
    });

    // Prevent textarea blur when clicking the optimize link.
    $root.find('#epb-ai-optimize-link').on('mousedown', function(e) {
        if (!$(this).hasClass('disabled')) {
            e.preventDefault();
        }
    });

    // Optimize link.
    $root.find('#epb-ai-optimize-link').on('click', async function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) {
            return;
        }
        const promptText = $root.find('#epb-ai-prompt-textarea').val().trim();
        if (promptText) {
            await handleOptimize($root, promptText);
        }
    });

    // Regenerate button.
    $root.find('#epb-ai-regenerate-btn').on('click', async function() {
        const promptText = $root.find('#epb-ai-prompt-textarea').val().trim();
        if (promptText) {
            await handleGenerate($root, promptText);
        }
    });

    // Insert button.
    $root.find('#epb-ai-insert-btn').on('click', async function() {
        if (lastGeneratedContent) {
            await handleInsert($root);
        }
    });
};

/**
 * Handle prompt validation and generation flow.
 *
 * @param {jQuery} $root The modal root element.
 * @param {string} promptText The user's prompt.
 */
const handleGenerate = async ($root, promptText) => {
    // Step 1: Validate the prompt.
    generationCancelled = false;
    hideValidationFeedback($root);
    showLoading($root, true);

    try {
        const validationResult = await callValidatePrompt(promptText);

        if (generationCancelled) {
            return;
        }

        if (!validationResult.valid) {
            showLoading($root, false);
            const issues = validationResult.issues || [];
            const suggestions = validationResult.suggestions || [];
            showValidationFeedback($root, issues, suggestions);
            return;
        }

        // Enable optimize link if score is between 40-70.
        if (validationResult.suggest_optimize) {
            $root.find('#epb-ai-optimize-link').removeClass('disabled').removeAttr('aria-disabled');
        }

        // Step 2: Build the final prompt with scoping instructions.
        const blockId = $root.find('#epb-ai-block-id').val();
        let finalPrompt = addScopingInstructions(promptText, blockId);

        // Step 3: Generate content.
        const result = await callGenerateBlock(finalPrompt, promptText);

        if (generationCancelled) {
            return;
        }

        showLoading($root, false);

        if (!result.success) {
            // BR-008: Update counter on failure only if requests exhausted.
            updateRequestsRemaining($root, result.requests_remaining);
            const errorMsg = result.error || await getString('ai_generation_failed', 'local_edwiserpagebuilder');
            showValidationFeedback($root, [errorMsg], []);
            return;
        }

        // Store for insert.
        lastGeneratedContent = result;
        lastPrompt = promptText;

        // BR-008: Confirm generation to increment request count (only if not cancelled).
        const confirmResult = await callConfirmGeneration();
        updateRequestsRemaining($root, confirmResult.requests_remaining);

        // Hide suggestions and show preview on successful generation.
        hideValidationFeedback($root);
        showPreview($root, result.combined || result.html);

    } catch (error) {
        if (generationCancelled) {
            return;
        }
        showLoading($root, false);
        Notification.exception(error);
    }
};

/**
 * Handle prompt optimization.
 *
 * @param {jQuery} $root The modal root element.
 * @param {string} promptText The user's prompt.
 */
const handleOptimize = async ($root, promptText) => {
    $root.find('#epb-ai-optimize-link').addClass('d-none');
    $root.find('#epb-ai-optimize-loading').removeClass('d-none');

    try {
        const result = await callOptimizePrompt(promptText);

        $root.find('#epb-ai-optimize-loading').addClass('d-none');
        $root.find('#epb-ai-optimize-link').removeClass('d-none');

        if (result.success && result.optimized_prompt) {
            $root.find('#epb-ai-prompt-textarea').val(result.optimized_prompt);
            $root.find('.epb-ai-char-current').text(result.optimized_prompt.length);
        } else {
            const errorMsg = result.error || await getString('ai_optimization_failed', 'local_edwiserpagebuilder');
            showValidationFeedback($root, [errorMsg], []);
        }
    } catch (error) {
        $root.find('#epb-ai-optimize-loading').addClass('d-none');
        $root.find('#epb-ai-optimize-link').removeClass('d-none');
        Notification.exception(error);
    }
};

/**
 * Handle inserting the generated block.
 *
 * @param {jQuery} $root The modal root element.
 */
const handleInsert = async ($root) => {
    if (!lastGeneratedContent) {
        return;
    }

    showLoading($root, true);

    try {
        const blockId = $root.find('#epb-ai-block-id').val();
        if (!blockId) {
            showLoading($root, false);
            showValidationFeedback($root, ['Block ID is missing.'], []);
            return;
        }

        // Save AI-generated content to block_instances.configdata.
        const result = await Ajax.call([{
            methodname: 'local_edwiserpagebuilder_update_ai_content',
            args: {
                instanceid: parseInt(blockId, 10),
                html: lastGeneratedContent.html || '',
                css: lastGeneratedContent.css || '',
                js: lastGeneratedContent.js || '',
            },
        }])[0];

        showLoading($root, false);

        if (result.success) {
            // Close modal and reload page to show the updated block content.
            if (aiModal) {
                aiModal.hide();
            }
            location.reload();
        } else {
            const errorMsg = result.message
                || await getString('ai_update_content_failed', 'local_edwiserpagebuilder');
            showValidationFeedback($root, [errorMsg], []);
        }

    } catch (error) {
        showLoading($root, false);
        Notification.exception(error);
    }
};

/**
 * Add CSS scoping instructions to the prompt.
 * Adapted from POC prompt_handler.js.
 *
 * @param {string} promptText The user's prompt.
 * @param {string} blockId The block ID.
 * @return {string} The prompt with scoping instructions.
 */
const addScopingInstructions = (promptText, blockId) => {
    let finalPrompt = promptText;
    const lower = promptText.toLowerCase();

    // Note: CSS scoping is handled automatically by the block renderer
    // (edwiseradvancedblock/lib.php wraps CSS in #inst{id} via SCSS).
    // Do NOT add section#inst scoping here — it would cause double-scoping.

    // If user doesn't mention CSS/JS, tell AI not to modify them.
    if (!lower.includes('css') && !lower.includes('style') &&
        !lower.includes('js') && !lower.includes('javascript')) {
        finalPrompt += '\nDo not modify the CSS or JS unless explicitly requested.';
    }

    // BR-010: If user mentions image-related keywords, reinforce SVG-only output.
    const imageKeywords = [
        'image', 'photo', 'picture', 'img', 'photograph', 'png', 'jpg', 'jpeg',
        'gif', 'bitmap', 'webp', 'icon', 'illustration', 'logo', 'graphic', 'thumbnail',
    ];
    const hasImageKeyword = imageKeywords.some(kw => {
        const regex = new RegExp('\\b' + kw + 's?\\b', 'i');
        return regex.test(lower);
    });
    if (hasImageKeyword) {
        finalPrompt += '\nAny images, icons, or illustrations must be inline SVG elements. '
            + 'Do not use <img> tags or reference external image files.';
    }

    return finalPrompt;
};

/**
 * Show the preview area with generated content.
 *
 * @param {jQuery} $root The modal root element.
 * @param {string} htmlContent The HTML content to preview.
 */
const showPreview = ($root, htmlContent) => {
    const $preview = $root.find('#epb-ai-preview-section');
    const $container = $root.find('#epb-ai-preview-container');

    // Modal was closed/destroyed while generation was in progress.
    if (!$container.length) {
        return;
    }

    $container.empty();

    // Render in a sandboxed iframe to prevent XSS from AI-generated content.
    const iframe = document.createElement('iframe');
    iframe.setAttribute('sandbox', 'allow-same-origin');
    iframe.style.width = '100%';
    iframe.style.border = 'none';
    iframe.style.minHeight = '300px';
    $container.append(iframe);

    const doc = iframe.contentDocument || iframe.contentWindow.document;
    doc.open();
    doc.write('<div class="block-content">' + htmlContent + '</div>');
    doc.close();

    // Auto-resize iframe to content height.
    iframe.onload = function() {
        try {
            iframe.style.height = doc.body.scrollHeight + 'px';
        } catch (e) {
            // Cross-origin restriction fallback.
            iframe.style.height = '400px';
        }
    };

    $preview.removeClass('d-none');
};

/**
 * Show/hide the loading overlay.
 *
 * @param {jQuery} $root The modal root element.
 * @param {boolean} show Whether to show or hide.
 */
const showLoading = ($root, show) => {
    const $overlay = $root.find('#epb-ai-loading-overlay');
    const $generateBtn = $root.find('#epb-ai-generate-btn');

    if (show) {
        $overlay.removeClass('d-none');
        $generateBtn.prop('disabled', true);
    } else {
        $overlay.addClass('d-none');
        $generateBtn.prop('disabled', false);
    }
};

/**
 * Show validation feedback messages.
 *
 * @param {jQuery} $root The modal root element.
 * @param {Array} issues Array of issue strings.
 * @param {Array} suggestions Array of suggestion strings.
 */
const showValidationFeedback = ($root, issues, suggestions) => {
    const $feedback = $root.find('#epb-ai-validation-feedback');
    const $issues = $root.find('#epb-ai-validation-issues');
    const $suggestions = $root.find('#epb-ai-validation-suggestions');

    $issues.empty();
    $suggestions.empty();

    if (issues.length) {
        issues.forEach(issue => {
            const escaped = escapeHtml(issue);
            if (escaped !== 'You have used all your free AI generation requests. Upgrade to Pro for unlimited access.') {
                $issues.append(
                    '<div class="epb-ai-feedback-item epb-ai-feedback-issue">' + escaped + '</div>'
                );
            }
        });
    }

    if (suggestions.length) {
        let html = '<div class="epb-ai-feedback-item epb-ai-feedback-suggestion">'
            + '<strong class="epb-ai-suggestion-title">'
            + $root.find('#epb-ai-tips-title').val()
            + '</strong>'
            + '<ul class="epb-ai-suggestion-list">';
        suggestions.forEach(suggestion => {
            html += '<li>' + escapeHtml(suggestion) + '</li>';
        });
        html += '</ul></div>';
        $suggestions.append(html);
    }

    if (issues.length || suggestions.length) {
        $feedback.removeClass('d-none');
    } else {
        $feedback.addClass('d-none');
    }
};

/**
 * Hide validation feedback.
 *
 * @param {jQuery} $root The modal root element.
 */
const hideValidationFeedback = ($root) => {
    $root.find('#epb-ai-validation-feedback').addClass('d-none');
};

/**
 * Update the free tier requests remaining counter in the modal (BR-008).
 *
 * @param {jQuery} $root The modal root element.
 * @param {number} remaining Requests remaining (-1 = unlimited/pro, 0+ = free tier count).
 */
const updateRequestsRemaining = async($root, remaining) => {
    if (typeof remaining === 'undefined' || remaining === null) {
        return;
    }

    const $info = $root.find('#epb-ai-requests-info');
    const $exhausted = $root.find('#epb-ai-requests-exhausted');
    const $generateBtn = $root.find('#epb-ai-generate-btn');
    const $regenerateBtn = $root.find('#epb-ai-regenerate-btn');
    const $optimizeLink = $root.find('#epb-ai-optimize-link');

    // Pro users: hide all limit-related UI.
    if (remaining === -1) {
        $info.addClass('d-none');
        $exhausted.addClass('d-none');
        return;
    }

    if (remaining <= 0) {
        // Requests exhausted.
        $info.addClass('d-none');
        $exhausted.removeClass('d-none');
        $generateBtn.prop('disabled', true);
        $regenerateBtn.prop('disabled', true);
        $optimizeLink.addClass('disabled').attr('aria-disabled', 'true');
    } else {
        // Show remaining count.
        const msg = await getString('ai_requests_remaining', 'local_edwiserpagebuilder', remaining);
        $root.find('#epb-ai-requests-remaining-text').text(msg);
        $info.removeClass('d-none');
        $exhausted.addClass('d-none');
    }
};

/**
 * Reset the modal to its initial state.
 */
const resetModal = () => {
    lastGeneratedContent = null;
    lastPrompt = '';
};

/**
 * Escape HTML to prevent XSS in feedback messages.
 *
 * @param {string} text The text to escape.
 * @return {string} Escaped HTML string.
 */
const escapeHtml = (text) => {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};

// === AJAX Service Calls ===

/**
 * Call the validate_ai_prompt web service.
 *
 * @param {string} promptText The prompt to validate.
 * @return {Promise<Object>} The validation result.
 */
const callValidatePrompt = (promptText) => {
    return Ajax.call([{
        methodname: 'local_edwiserpagebuilder_validate_ai_prompt',
        args: {prompttext: promptText},
    }])[0];
};

/**
 * Call the generate_ai_block web service.
 *
 * @param {string} promptText The full prompt with scoping instructions.
 * @param {string} rawPrompt The raw user prompt before scoping.
 * @return {Promise<Object>} The generation result.
 */
const callGenerateBlock = (promptText, rawPrompt) => {
    return Ajax.call([{
        methodname: 'local_edwiserpagebuilder_generate_ai_block',
        args: {
            contextid: contextId,
            prompttext: promptText,
            rawprompt: rawPrompt,
        },
    }])[0];
};

/**
 * Call the confirm_ai_generation web service to increment the request count.
 *
 * @return {Promise<Object>} The confirmation result with requests_remaining.
 */
const callConfirmGeneration = () => {
    return Ajax.call([{
        methodname: 'local_edwiserpagebuilder_confirm_ai_generation',
        args: {
            contextid: contextId,
        },
    }])[0];
};

/**
 * Fetch remaining AI requests and display the count in the modal.
 *
 * @param {jQuery} $root The modal root element.
 */
const fetchAndDisplayRequestsRemaining = async($root) => {
    try {
        const result = await Ajax.call([{
            methodname: 'local_edwiserpagebuilder_get_ai_requests_remaining',
            args: {},
        }])[0];
        updateRequestsRemaining($root, result.requests_remaining);
    } catch (error) {
        // Silently fail — the counter will remain hidden.
        return;
    }
};

/**
 * Call the optimize_ai_prompt web service.
 *
 * @param {string} promptText The prompt to optimize.
 * @return {Promise<Object>} The optimization result.
 */
const callOptimizePrompt = (promptText) => {
    return Ajax.call([{
        methodname: 'local_edwiserpagebuilder_optimize_ai_prompt',
        args: {
            contextid: contextId,
            prompttext: promptText,
        },
    }])[0];
};
