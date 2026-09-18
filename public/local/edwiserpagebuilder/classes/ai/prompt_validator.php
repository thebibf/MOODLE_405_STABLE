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
 * Prompt quality validator for AI block generation.
 *
 * Validates user prompts for quality, security, and relevance before
 * sending to the AI API. Implements scoring, injection detection, and PII filtering.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2026 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edwiserpagebuilder\ai;

/**
 * Validates AI prompts for quality, security, and relevance.
 *
 * @package local_edwiserpagebuilder
 */
class prompt_validator {
    /** @var int Minimum prompt length. */
    private const MIN_LENGTH = 10;

    /** @var int Maximum prompt length. */
    private const MAX_LENGTH = 2000;

    /** @var int Score threshold below which prompts are rejected. */
    private const REJECT_THRESHOLD = 40;

    /** @var int Score threshold below which optimization is suggested. */
    private const OPTIMIZE_THRESHOLD = 70;

    /** @var array Action verbs that indicate an actionable request. */
    private const ACTION_VERBS = [
        'create', 'generate', 'design', 'build', 'make', 'add', 'show',
        'display', 'render', 'draw', 'construct', 'develop', 'produce',
        'compose', 'arrange', 'layout', 'format', 'style', 'modify',
        'update', 'change', 'edit', 'improve', 'enhance', 'fix',
    ];

    /** @var array Design context keywords. */
    private const DESIGN_KEYWORDS = [
        'block', 'section', 'card', 'layout', 'header', 'footer', 'banner',
        'hero', 'grid', 'list', 'table', 'form', 'button', 'navigation',
        'sidebar', 'menu', 'gallery', 'slider', 'carousel', 'accordion',
        'tab', 'modal', 'popup', 'tooltip', 'badge', 'alert', 'notice',
        'panel', 'widget', 'component', 'container', 'wrapper', 'row',
        'column', 'image', 'icon', 'text', 'heading', 'paragraph', 'link',
        'page', 'content', 'template', 'theme', 'color', 'font', 'style',
        'responsive', 'mobile', 'desktop', 'animation', 'transition',
        'course', 'student', 'teacher', 'enrollment', 'progress', 'dashboard',
    ];

    /** @var array Patterns that indicate prompt injection attempts. */
    private const INJECTION_PATTERNS = [
        '/ignore\s+(all\s+)?previous\s+instructions/i',
        '/ignore\s+(all\s+)?above/i',
        '/disregard\s+(all\s+)?previous/i',
        '/forget\s+(all\s+)?(previous|prior|above)/i',
        '/you\s+are\s+now\s+a/i',
        '/act\s+as\s+(if\s+you\s+are\s+)?a\s+(different|new)/i',
        '/system\s*:/i',
        '/\buser\s*:\s*/i',
        '/\bassistant\s*:\s*/i',
        '/do\s+not\s+follow\s+(any\s+)?(previous|prior)/i',
        '/override\s+(your\s+)?(instructions|rules|guidelines)/i',
        '/new\s+instructions?\s*:/i',
        '/reveal\s+(your\s+)?(system|instructions|prompt)/i',
        '/what\s+(are|is)\s+your\s+(system\s+)?(instructions|prompt)/i',
        '/repeat\s+(your\s+)?(system\s+)?(instructions|prompt)/i',
        '/\[\/?(?:HTML|CSS|JS)\]/i',
        '/return.*(?:system|initial).*prompt/i',
    ];

    /** @var array Patterns that match PII (Personally Identifiable Information). */
    private const PII_PATTERNS = [
        'email' => '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/',
        'phone' => '/(\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/',
        'credit_card' => '/\b(?:\d{4}[-\s]?){3}\d{4}\b/',
        'ssn' => '/\b\d{3}[-\s]?\d{2}[-\s]?\d{4}\b/',
    ];

    /** @var array Keywords that indicate image-related requests (BR-010). */
    private const IMAGE_KEYWORDS = [
        'image', 'images', 'photo', 'photos', 'picture', 'pictures', 'img',
        'photograph', 'photographs', 'png', 'jpg', 'jpeg', 'gif', 'bitmap',
        'webp', 'icon', 'icons', 'illustration', 'illustrations', 'logo',
        'logos', 'graphic', 'graphics', 'thumbnail', 'thumbnails',
    ];

    /**
     * Validate a prompt and return structured result.
     *
     * @param string $prompt The user's prompt text.
     * @return array {valid: bool, score: int, issues: string[], suggestions: string[]}
     */
    public function validate(string $prompt): array {
        $issues = [];
        $suggestions = [];
        $scores = [];

        // Length check.
        $lengthresult = $this->check_length($prompt);
        $scores['length'] = $lengthresult['score'];
        if (!empty($lengthresult['issue'])) {
            $issues[] = $lengthresult['issue'];
        }
        if (!empty($lengthresult['suggestion'])) {
            $suggestions[] = $lengthresult['suggestion'];
        }

        // Hard reject on length bounds (use mb_strlen to count characters, not bytes).
        if (mb_strlen(trim($prompt), 'UTF-8') < self::MIN_LENGTH) {
            return [
                'valid' => false,
                'score' => 0,
                'issues' => [\get_string('ai_prompt_too_short', 'local_edwiserpagebuilder', self::MIN_LENGTH)],
                'suggestions' => [\get_string('ai_prompt_add_detail', 'local_edwiserpagebuilder')],
            ];
        }

        if (mb_strlen(trim($prompt), 'UTF-8') > self::MAX_LENGTH) {
            return [
                'valid' => false,
                'score' => 0,
                'issues' => [\get_string('ai_prompt_too_long', 'local_edwiserpagebuilder', self::MAX_LENGTH)],
                'suggestions' => [\get_string('ai_prompt_shorten', 'local_edwiserpagebuilder')],
            ];
        }

        // Injection detection (25% weight).
        $injectionresult = $this->check_injection($prompt);
        $scores['injection'] = $injectionresult['score'];
        if (!empty($injectionresult['issue'])) {
            $issues[] = $injectionresult['issue'];
        }

        // Hard reject on injection.
        if ($injectionresult['score'] === 0) {
            return [
                'valid' => false,
                'score' => 0,
                'issues' => $issues,
                'suggestions' => [\get_string('ai_prompt_rephrase', 'local_edwiserpagebuilder')],
            ];
        }

        // PII filtering.
        $piiresult = $this->check_pii($prompt);
        if (!empty($piiresult['issue'])) {
            return [
                'valid' => false,
                'score' => 0,
                'issues' => [$piiresult['issue']],
                'suggestions' => [\get_string('ai_prompt_remove_pii', 'local_edwiserpagebuilder')],
            ];
        }

        // Action verb check (20% weight).
        $verbresult = $this->check_action_verb($prompt);
        $scores['verb'] = $verbresult['score'];
        if (!empty($verbresult['suggestion'])) {
            $suggestions[] = $verbresult['suggestion'];
        }

        // Design context keywords (20% weight).
        $contextresult = $this->check_design_context($prompt);
        $scores['context'] = $contextresult['score'];
        if (!empty($contextresult['suggestion'])) {
            $suggestions[] = $contextresult['suggestion'];
        }

        // Specificity/detail level (20% weight).
        $specificityresult = $this->check_specificity($prompt);
        $scores['specificity'] = $specificityresult['score'];
        if (!empty($specificityresult['suggestion'])) {
            $suggestions[] = $specificityresult['suggestion'];
        }

        // BR-010: Image keyword detection (suggestion only, no score impact).
        $imageresult = $this->check_image_keywords($prompt);
        if (!empty($imageresult['suggestion'])) {
            $suggestions[] = $imageresult['suggestion'];
        }

        // Calculate weighted total score.
        $totalscore = (int) round(
            ($scores['length'] * 0.15) +
            ($scores['verb'] * 0.20) +
            ($scores['context'] * 0.20) +
            ($scores['specificity'] * 0.20) +
            ($scores['injection'] * 0.25)
        );

        $valid = $totalscore >= self::REJECT_THRESHOLD;

        if (!$valid) {
            $issues[] = \get_string('ai_prompt_low_quality', 'local_edwiserpagebuilder');
        }

        return [
            'valid' => $valid,
            'score' => $totalscore,
            'issues' => $issues,
            'suggestions' => $suggestions,
        ];
    }

    /**
     * Check if optimization should be suggested based on score.
     *
     * @param int $score The validation score.
     * @return bool True if optimization should be suggested.
     */
    public function should_suggest_optimization(int $score): bool {
        return $score >= self::REJECT_THRESHOLD && $score < self::OPTIMIZE_THRESHOLD;
    }

    /**
     * Check prompt length adequacy.
     *
     * @param string $prompt The prompt text.
     * @return array {score: int, issue: string|null, suggestion: string|null}
     */
    private function check_length(string $prompt): array {
        $length = mb_strlen(trim($prompt), 'UTF-8');

        if ($length < self::MIN_LENGTH) {
            return ['score' => 0, 'issue' => null, 'suggestion' => null];
        }

        if ($length < 30) {
            return [
                'score' => 30,
                'issue' => null,
                'suggestion' => \get_string('ai_prompt_more_detail', 'local_edwiserpagebuilder'),
            ];
        }

        if ($length < 80) {
            return ['score' => 60, 'issue' => null, 'suggestion' => null];
        }

        if ($length < 200) {
            return ['score' => 80, 'issue' => null, 'suggestion' => null];
        }

        return ['score' => 100, 'issue' => null, 'suggestion' => null];
    }

    /**
     * Check for prompt injection patterns.
     *
     * @param string $prompt The prompt text.
     * @return array {score: int, issue: string|null}
     */
    private function check_injection(string $prompt): array {
        foreach (self::INJECTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $prompt)) {
                return [
                    'score' => 0,
                    'issue' => \get_string('ai_prompt_injection_detected', 'local_edwiserpagebuilder'),
                ];
            }
        }
        return ['score' => 100, 'issue' => null];
    }

    /**
     * Check for PII in the prompt.
     *
     * @param string $prompt The prompt text.
     * @return array {issue: string|null}
     */
    private function check_pii(string $prompt): array {
        foreach (self::PII_PATTERNS as $type => $pattern) {
            if (preg_match($pattern, $prompt)) {
                if ($type === 'email') {
                    return [
                        'issue' => \get_string('ai_prompt_pii_email_detected', 'local_edwiserpagebuilder'),
                    ];
                }
                return [
                    'issue' => \get_string('ai_prompt_pii_numbers_detected', 'local_edwiserpagebuilder'),
                ];
            }
        }
        return ['issue' => null];
    }

    /**
     * Check for action verbs in the prompt.
     *
     * @param string $prompt The prompt text.
     * @return array {score: int, suggestion: string|null}
     */
    private function check_action_verb(string $prompt): array {
        $lower = strtolower($prompt);
        foreach (self::ACTION_VERBS as $verb) {
            if (strpos($lower, $verb) !== false) {
                return ['score' => 100, 'suggestion' => null];
            }
        }
        return [
            'score' => 20,
            'suggestion' => \get_string('ai_prompt_add_action_verb', 'local_edwiserpagebuilder'),
        ];
    }

    /**
     * Check for design context keywords.
     *
     * @param string $prompt The prompt text.
     * @return array {score: int, suggestion: string|null}
     */
    private function check_design_context(string $prompt): array {
        $lower = strtolower($prompt);
        $matches = 0;
        foreach (self::DESIGN_KEYWORDS as $keyword) {
            if (strpos($lower, $keyword) !== false) {
                $matches++;
            }
        }

        if ($matches >= 3) {
            return ['score' => 100, 'suggestion' => null];
        }
        if ($matches >= 1) {
            return ['score' => 60, 'suggestion' => null];
        }
        return [
            'score' => 10,
            'suggestion' => \get_string('ai_prompt_add_design_context', 'local_edwiserpagebuilder'),
        ];
    }

    /**
     * Check prompt specificity and detail level.
     *
     * @param string $prompt The prompt text.
     * @return array {score: int, suggestion: string|null}
     */
    private function check_specificity(string $prompt): array {
        $wordcount = str_word_count($prompt);

        // Check for specific details: colors, sizes, counts, etc.
        $colorpattern = '/(#[0-9a-fA-F]{3,6}|\b(red|blue|green|purple|orange|yellow|white|black|gray|grey|pink)\b)/i';
        $hascolor = preg_match($colorpattern, $prompt);
        $hasnumber = preg_match('/\b\d+\b/', $prompt);
        $hassize = preg_match('/\b(px|em|rem|%|small|medium|large|wide|narrow|tall)\b/i', $prompt);

        $score = 0;

        if ($wordcount >= 20) {
            $score += 40;
        } else if ($wordcount >= 10) {
            $score += 20;
        }

        if ($hascolor) {
            $score += 20;
        }
        if ($hasnumber) {
            $score += 20;
        }
        if ($hassize) {
            $score += 20;
        }

        $score = min(100, $score);

        $suggestion = null;
        if ($score < 60) {
            $suggestion = \get_string('ai_prompt_add_specifics', 'local_edwiserpagebuilder');
        }

        return ['score' => $score, 'suggestion' => $suggestion];
    }

    /**
     * Check for image-related keywords in the prompt (BR-010).
     *
     * Returns a suggestion (not a rejection) informing the user that
     * images will be rendered as inline SVG.
     *
     * @param string $prompt The prompt text.
     * @return array {suggestion: string|null}
     */
    private function check_image_keywords(string $prompt): array {
        $lower = strtolower($prompt);
        foreach (self::IMAGE_KEYWORDS as $keyword) {
            // Use word boundary check to avoid false positives (e.g. "imagine" matching "image").
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $lower)) {
                return [
                    'suggestion' => \get_string('ai_prompt_images_as_svg', 'local_edwiserpagebuilder'),
                ];
            }
        }
        return ['suggestion' => null];
    }
}
