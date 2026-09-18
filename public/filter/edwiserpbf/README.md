# Edwiser Page Builder Filter

## Table of Contents

- [Description](#description)
- [Features](#features)
- [Privacy & External API Disclosure](#privacy--external-api-disclosure)
- [Plugin Version](#plugin-version)
- [Required Version of Moodle](#required-version-of-moodle)
- [Support](#support)
- [Installation](#installation)
- [Uninstallation](#uninstallation)
- [Files Information](#files-information)
- [Roadmap](#roadmap)
- [History](#history)
- [Author](#author)
- [Provided by](#provided-by)

## Description

Edwiser Page Builder Filter is a Moodle text filter plugin that works alongside the [Edwiser Page Builder](https://edwiser.org/page-builder/) (`local_edwiserpagebuilder`) plugin. It intercepts content containing Edwiser shortcodes and dynamically replaces them with rendered course listings, category displays, and featured course blocks.

## Features

- **Course Listings** - Render course cards with images, descriptions, instructor info, and enrolment links from shortcode tags.
- **Category Displays** - Show category cards with course counts, descriptions, and navigation links.
- **Courses & Categories Combined (CNC)** - Tabbed navigation that filters courses by category with lazy-loading support for large catalogues.
- **Featured Courses** - Curated course showcase blocks with admin-selectable course IDs.

## Privacy & External API Disclosure

This plugin **does not store any personal user data**. It implements the Moodle Privacy API as a null provider (`\core_privacy\local\metadata\null_provider`).

**External services:** This plugin does not directly call any external APIs or services. It may reference a CDN URL (`BLOCKS_CDN_URL`) for static assets (e.g., icons), but this is defined and managed by the parent `local_edwiserpagebuilder` plugin, not by this filter.

**Third-party libraries:** This plugin does not bundle any third-party libraries. It uses the Slick carousel and jQuery libraries provided by `local_edwiserpagebuilder` and Moodle core respectively.

## Plugin Version

- **Current version:** 4.2.10
- **Release date:** 2025-11-26
- **Maturity:** Stable

## Required Version of Moodle

- **Minimum Moodle version:** 4.0 (2022041900)
- **Required plugin:** `local_edwiserpagebuilder` (any version)

## Support

For support, feature requests, or bug reports:

- **Email:** support@wisdmlabs.com
- **Website:** [https://edwiser.org/](https://edwiser.org/)

## Installation

1. Download the plugin archive or clone the repository.
2. Copy the `edwiserpbf` folder to your Moodle installation:
   ```
   {Moodle root}/filter/edwiserpbf/
   ```
3. Ensure the required dependency `local_edwiserpagebuilder` is already installed.
4. Log in to your Moodle site as an administrator.
5. Navigate to **Site administration** to trigger the plugin installation.
6. Navigate to **Site administration > Plugins > Filters > Manage filters**.
7. Enable the **Edwiser Page Builder Filter** and set it to **On**.

## Uninstallation

1. Navigate to **Site administration > Plugins > Filters > Manage filters**.
2. Disable the **Edwiser Page Builder Filter**.
3. Navigate to **Site administration > Plugins > Plugins overview**.
4. Find **filter_edwiserpbf** and click **Uninstall**.
5. Confirm the uninstallation and follow the on-screen prompts.
6. Optionally, delete the `{Moodle root}/filter/edwiserpbf/` directory from the server.

## Files Information

### Languages

The `filter/edwiserpbf/lang/` folder contains language files for the plugin.

Language files are available in:

- English (en)
- Arabic (ar)
- German (de)
- Spanish (es)
- Spanish - Mexico (es_mx)
- French (fr)
- Polish (pl)
- Portuguese - Brazil (pt_br)

Language strings follow Moodle's standard language file format and can be customized as needed.

### Templates

Mustache templates are located in:

- `filter/edwiserpbf/templates/` - All plugin templates

## Roadmap

- Enhanced accessibility compliance for carousel components.
- Additional layout templates for course and category displays.
- Improved lazy-loading and performance optimizations for large course catalogues.

## History

| Version | Changes |
|---------|---------|
| 4.2.10 | Removed dependencies from RemUI. Minor UI/UX improvements. |
| 4.2.9 | Added compatibility with Moodle 5.1. |
| 4.2.8 | Compatibility with Moodle 4.5. Fixed conflict between featured courses and courses/categories. |
| 4.2.7 | Dynamic block colors synchronized with RemUI theme colors. |
| 4.2.6 | Compatibility with Moodle 4.5. |
| 4.2.5 | Added featured courses block functionality. Minor improvements to courses and categories block. |
| 4.2.4 | Added language translations (es, de, es_mx, pt_br, fr, pl, ar). Multi-lang filter support. |
| 4.2.3 | Minor improvement in courses and categories block. |
| 4.2.2 | UI/UX improvement. |
| 4.2.1 | UI/UX improvement. |
| 4.2.0 | UI/UX improvement. Added course and category block component handling. |
| 4.0.0 | Initial Moodle 4.0 compatible release. |

## Author

WisdmLabs

WisdmLabs is a leading provider of Moodle solutions and WordPress integrations. We specialize in creating powerful, user-friendly plugins and themes that enhance the learning experience.

- Website: https://wisdmlabs.com/ 
- Email: support@wisdmlabs.com

## Provided by

[![Edwiser](https://edwiser.org/wp-content/uploads/2025/11/edwiser-logo.webp)](https://edwiser.org)

**Edwiser** - Empowering Online Learning

Edwiser provides comprehensive Moodle solutions including themes, plugins, and integrations to help you create the perfect online learning platform.

- **Website**: https://edwiser.org
- **Documentation**: https://remui-docs.edwiser.org/
- **My Account**: https://edwiser.org/my-account/

[(Back to top)](#table-of-contents)