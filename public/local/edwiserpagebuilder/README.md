# Edwiser Page Builder

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

Edwiser Page Builder (`local_edwiserpagebuilder`) is a Moodle local plugin that provides a visual page building experience for creating and managing custom pages within Moodle. It offers a block-based editor with pre-built content blocks, layout templates, media management, and a draft/publish workflow. The plugin also includes a set of dashboard blocks (RemuiBlck) for displaying course progress, analytics, task lists, and other student/teacher-focused widgets.

## Features

- **Visual Page Builder** - Drag-and-drop editor for creating custom pages with content blocks and layout templates.
- **Block Management** - Add, update, delete, and organize content blocks with category-based filtering.
- **Pre-built Layout Templates** - Multiple layout options and card designs for rapid page creation.
- **Dashboard Blocks (RemuiBlck)** - Specialized blocks including course progress, course analytics, quiz attempts, task/todo lists, recent feedback, latest members, enrolled users, and recent forums.
- **Draft/Publish Workflow** - Create and edit page drafts (custom pages) before publishing with version control.
- **Import/Export** - Import and export blocks and page configurations.
- **SEO Support** - SEO tags and meta descriptions for custom pages.
- **Access Control** - Capability-based permissions for page management and viewing.
- **Multi-language Support** - Translations available in 8 languages.

## Privacy & External API Disclosure

The plugin implements Moodle's Privacy API (`classes/privacy/provider.php`) and complies with GDPR requirements. It declares metadata for the `local_edwiserpagebuilder_taskslist` table (which stores user-created and user-assigned tasks) and supports data export, deletion for individual users, and deletion for all users in a context.

This plugin communicates with the following external services:

- **Edwiser License Server** (`edwiser.org`) - For license activation, deactivation, and validation.
- **Edwiser Analytics** (`edwiser.org/analytics`) - For plugin installation telemetry.
- **Edwiser CDN** (`staticcdn.edwiser.org`) - For block content and template delivery.
- **Google Fonts** (`fonts.googleapis.com`) - For loading web fonts. Data sent: IP address.

All external service declarations are registered in the privacy provider metadata.

## Plugin Version

- **Version**: 4.2.24
- **Version Code**: 2026020600
- **Maturity**: Stable

## Required Version of Moodle

- **Minimum Moodle Version**: 4.0 (2022041900)

## Support

For support, please contact:

- **Email**: support@wisdmlabs.com
- **Website**: [https://wisdmlabs.com/](https://wisdmlabs.com/)

## Installation

1. Download the plugin and extract it.
2. Copy the `edwiserpagebuilder` folder to `<moodle_root>/local/edwiserpagebuilder`.
3. Log in to your Moodle site as an administrator.
4. Navigate to **Site administration** to trigger the plugin installation.
5. Follow the on-screen instructions to complete the installation.


## Uninstallation

1. Navigate to **Site administration > Plugins > Plugins overview**.
2. Find **Edwiser Page Builder** in the list and click **Uninstall**.  
    - Before Uninstalling **Edwiser Page Builder**, **Edwiser Advanced Block** and **Edwiser Page Builder Filter** must be uninstalled.
3. Confirm the Uninstallation.


## Files Information

### Languages

The `local/edwiserpagebuilder/lang/` folder contains language files for the plugin.

Language files are available in:

- Arabic (ar)
- German (de)
- English (en)
- Spanish (es)
- Spanish - Mexico (es_mx)
- French (fr)
- Polish (pl)
- Portuguese - Brazil (pt_br)

Language strings follow Moodle's standard language file format and can be customized as needed.

### Styles

The plugin uses SCSS (Sass) for styling. The main SCSS files are located in:

- `local/edwiserpagebuilder/scss/` - Main SCSS files including styles, variables, block cards, custom modals, custom pages, editor styles, and course formatting.

Compiled CSS files are generated in:

- `local/edwiserpagebuilder/styles.css` - Main compiled CSS file
- `local/edwiserpagebuilder/styles/` - Additional compiled CSS files for the editor and helpers

### JavaScript

The plugin uses AMD (Asynchronous Module Definition) for JavaScript:

- `local/edwiserpagebuilder/amd/src/` - Source JavaScript files
- `local/edwiserpagebuilder/amd/build/` - Compiled JavaScript files


### Templates

Mustache templates are located in:

- `local/edwiserpagebuilder/templates/` - Main plugin templates for the editor interface, block cards, page forms, modals, and layout selection.
- `local/edwiserpagebuilder/templates/remuiblck/` - Dashboard block templates for course progress, analytics, task lists, quiz stats, forums, and member listings.

### Third Party Libraries

Third-party libraries are declared in `thirdpartylibs.xml` and located in the `libs/` and `resources/` directories.

**Core Libraries:**

| Library | Version | License | Location |
|---------|---------|---------|----------|
| VvvebJs | 0.1 | Apache-2.0 | `libs/builder` |
| Swiper | 11.0.5 | MIT | `libs/swiper` |
| AOS - Animate On Scroll | 2.3.4 | MIT | `libs/aos` |
| CodeMirror | 5.36.0 | MIT | `libs/codemirror` |
| JSZip | 3.7.1 | MIT OR GPL-3.0 | `libs/jszip/jszip.js` |
| FileSaver.js | 2.0.0 | MIT | `libs/jszip/filesaver.js` |
| jQuery Autocomplete | 1.0 | LGPL-3.0-or-later | `libs/autocomplete` |
| Bootstrap Colorpicker | 3.3.0 | MIT | `libs/bootstrap-colorpicker` |

**Icon Libraries:**

| Library | Version | License |
|---------|---------|---------|
| Font Awesome | 6.0 | CC-BY-4.0 |
| Heroicons | 1.0 | MIT |
| Eva Icons | 1.1 | MIT |
| Material Design Icons | 7.0 | Apache-2.0 |
| Ant Design Icons | 4.0 | MIT |
| Boxicons | 2.1 | MIT |
| Clarity Icons | 2.0 | MIT |
| CoreUI Icons | 2.0 | CC-BY-4.0 |
| Feather Icons | 4.29 | MIT |
| Ionicons | 6.0 | MIT |
| Tabler Icons | 1.0 | MIT |
| Unicons | 4.0 | Apache-2.0 |
| Iconoir | 5.0 | MIT |
| css.gg | 2.0 | MIT |
| Dripicons | 2.0 | CC-BY-SA-4.0 |
| Jam Icons | 2.0 | MIT |
| Octicons | 17.0 | MIT |
| System UIcons | 1.0 | Unlicense |
| Line Awesome | 1.3 | CC-BY-4.0 |
| Remix Icon | 2.5.0 | Apache-2.0 |
| Open Iconic | 1.1.1 | MIT |
| Olicons | 2.0.1 | SIL OFL 1.1 / MIT |
| Ikonate | 1.0 | MIT |
| Linea Iconset | 1.0 | CC-BY-4.0 |
| Elegant Font | 1.0 | MIT OR GPL-2.0 |
| Themify Icons | 1.0.1 | SIL OFL / MIT |
| 150 Outlined Icons | 1.0 | Freeware |
| 77 Essential Icons | 1.0 | CC-BY |
| PE Icon 7 Stroke | 1.0 | Freeware |
| Iconsax | 1.0 | Custom (free) |

All icon libraries are located in `resources/svg/icons/`.

## Roadmap

Future enhancements and features planned for Edwiser Page Builder:

1. Enhanced mobile experience improvements
2. Additional block template designs
3. More layout presets and card designs
4. Advanced typography and styling controls
5. Integration with more third-party services
6. Performance optimizations
7. Accessibility improvements

For the latest roadmap information, please visit [Edwiser Forums](https://forums.edwiser.org/).

## History
### Recent Versions

#### v4.2.24 (2026-02-06)
- **Feature** - Added two new layout templates:
  - *Medical Layout:* Header Design 8, Feature Design 18, Team Design 9, Stats Design 7, Vertical Slider 4, Partners Design 4, Testimonial Design 7
  - *Safety Layout:* Header Design 9, About Us Design 5, Team Design 10, Feature Design 19, Image Gallery Design 3, Stats Design 8, Testimonial Design 8, Connect With Us Design 7
- **Feature** - Added new dynamic slider components for the Live Customizer: Vertical Slider 4, Testimonial Design 7, Testimonial Design 8, Image Slider with Indicators 2, Profile Slider 4, Profile Slider 5
- **Fix** - Fixed an issue where the font-family was not applying to blocks.

#### v4.2.23
- **Tweak** - Added license handling when the maximum activation limit is reached.
- **Fix** - Fixed an issue with the custom skill level on featured courses block.

#### v4.2.22
- **Feature** - Added compatibility of Page Builder with Adaptable, Moove, Boost, Snap, Trema.
- **Tweak** - The license has been added to allow upgrading from the Free version to the Pro version.
- **Tweak** - Updated the existing library for editing blocks in the Live Customizer.
- **Tweak** - Introduced Free and Pro versions of Page Builder.
- **Fix** - Minor UI/UX improvements.

#### v4.2.21
- **Feature** - Added compatibility with Moodle 5.1.
- **Fix** - Resolved minor UI issues.
- **Fix** - Fixed an issue where the image upload icon was not working and size unit options were visible.

#### v4.2.20
- **Fix** - Improved installation process of Edwiser Page Builder.
- **Fix** - Resolved an issue where the Edwiser Filter Plugin enable alert not closing.
- **Fix** - Enhanced advanced blocks fetching logic, blocks will now be fetched when "Add a block" is opened for the first time.

For complete version history, refer to the `changes.txt` file.

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
