Edwiser Advanced Block for Moodle
==============================================

# Table of Contents

- [Description](#description)
- [Features](#features)
- [Privacy & External API Disclosure](#privacy--external-api-disclosure)
- [Plugin Version](#plugin-version)
- [Required version of Moodle](#required-version-of-moodle)
- [Dependencies](#dependencies)
- [Support](#support)
- [Installation](#installation)
- [Uninstallation](#uninstallation)
- [Files Information](#files-information)
- [Roadmap](#roadmap)
- [History](#history)
- [Author](#author)
- [Provided by](#provided-by)

# Description

Edwiser Advanced Block is a dynamic content block plugin for Moodle that integrates with the Edwiser Page Builder to allow site administrators and teachers to create custom HTML, CSS, and JavaScript blocks for embedding rich, dynamic content within Moodle courses and site pages.

With the Edwiser Advanced Block, you can build visually engaging content blocks without leaving Moodle. The plugin supports live customization, multilingual content, dynamic shortcodes, and RTL layouts, making it a versatile tool for creating professional-looking content across your Moodle site.

The block supports multiple instances per page, allowing you to place as many custom content blocks as needed. Each block can contain its own HTML, CSS, and JavaScript, giving you full control over the appearance and behavior of your content.

**Documentation**: For detailed setup and configuration instructions, visit [Edwiser Page Builder Documentation](https://edwiser-pagebuilder-docs.edwiser.org/)

[(Back to top)](#table-of-contents)

# Features

## Custom Content Creation
* **HTML Content**: Create rich HTML content directly within Moodle blocks
* **Custom CSS**: Add custom CSS styling scoped to individual block instances using SCSS processing
* **Custom JavaScript**: Embed custom JavaScript for interactive and dynamic block content
* **Multiple Instances**: Add multiple advanced blocks per page with independent configurations

## Live Customizer
* **Real-time Editing**: Edit block content in a live customization interface without navigating away from the page
* **Instant Preview**: See changes immediately as you edit HTML, CSS, and JavaScript
* **Capability-based Access**: Only users with the `cancustomizelive` capability can access the live editor
* **Page Builder Integration**: Full integration with Edwiser Page Builder for drag-and-drop content creation

## Dynamic Blocks & Shortcodes
* **Shortcode Support**: Use `[edwiser-...]` shortcodes to embed dynamic content within blocks
* **Filter Integration**: Works with the Edwiser Page Builder Filter (`filter_edwiserpbf`) for shortcode processing
* **Automatic Notifications**: Displays helpful messages to administrators when the filter plugin is disabled or not installed

## Multilingual Support
* **Multilang Content**: Full support for Moodle's `{mlang}` syntax for multilingual content
* **Multilang v2 Compatibility**: Works with the Multilang v2 filter for seamless language switching
* **8 Language Translations**: Built-in translations for English, Spanish, Spanish (Mexico), German, French, Portuguese (Brazil), Polish, and Arabic

## Layout & Display
* **Hidden Header**: Block header is hidden for a clean, seamless appearance
* **Limited Width Support**: Blocks automatically adapt to limited-width layout regions
* **RTL Support**: Full right-to-left language layout support
* **Carousel Indicators**: Built-in CSS for circle, dash, and square carousel indicator styles
* **CDN Asset Management**: Automatic CDN URL replacement for serving assets from the Edwiser CDN

## Web Services API
* **External API**: Programmatic access via `block_edwiseradvancedblock_set_block_config` web service
* **AJAX Support**: AJAX-enabled web service for saving block configurations without page reloads
* **Config Management**: Set HTML, CSS, and JavaScript content for any block instance via the API

## Additional Features
* **"Log in as" Support**: Proper content rendering when using Moodle's "Log in as" functionality
* **Standard Backup/Restore**: Block configurations are included in Moodle's standard course backup and restore
* **Instance ID Placeholders**: Use `[[inst]]` placeholder in content to reference the current block instance ID

[(Back to top)](#table-of-contents)

# Privacy & External API Disclosure

## External API Calls

The Edwiser Advanced Block plugin uses CDN URLs from **edwiser.org** for serving block assets (images and media). The CDN URL placeholder `{{>cdnurl}}` is replaced at render time with the actual CDN path.

## Data Storage

* Block configurations (HTML, CSS, JavaScript) are stored in Moodle's standard `block_instances` table
* No custom database tables are created by this plugin
* No personally identifiable information is collected or transmitted by this plugin
* All data remains within your Moodle installation

## Compliance

The plugin uses Moodle's standard block storage mechanisms. All block content data is managed through Moodle's built-in block instance configuration system.

[(Back to top)](#table-of-contents)

# Plugin Version

v4.2.4 - Current Release

[(Back to top)](#table-of-contents)

# Required version of Moodle

This version works with Moodle 4.0 and above until the next release.

Please ensure that your hardware and software complies with 'Requirements' in 'Installing Moodle' on
'https://docs.moodle.org/39/en/Step-by-step_Installation_Guide_for_Ubuntu'.

[(Back to top)](#table-of-contents)

# Dependencies

This plugin requires the following Moodle plugins to be installed:

* **Edwiser Page Builder** (`local_edwiserpagebuilder`) - **Required**. The Advanced Block integrates with the Page Builder for content creation and editing. Without this plugin, the block will display a dependency notification to site administrators and will be disabled for all other users.

* **Edwiser Page Builder Filter** (`filter_edwiserpbf`) - **Recommended**. Required for dynamic block shortcodes (`[edwiser-...]`) to be processed and rendered correctly. If not enabled, the block will display a notification to administrators.

[(Back to top)](#table-of-contents)

# Support

For all support queries related to Edwiser Advanced Block you could email us at support@wisdmlabs.com

Apart from that you could raise your support queries in this forum too - https://forums.edwiser.org/

And if you wish to see any new features as part of the product then you could share your feature requests here
forum https://forums.edwiser.org/ for support.
Together we could make this solution better for your Moodle.

**Documentation**: For comprehensive documentation, visit [Edwiser Page Builder Documentation](https://edwiser-pagebuilder-docs.edwiser.org/)

[(Back to top)](#table-of-contents)

# Installation

## Minimum Requirements
* PHP version 7.4 or greater (PHP 8.0+ recommended)
* Moodle 4.0 or higher
* Edwiser Page Builder plugin (`local_edwiserpagebuilder`) installed and active

## Moodle Block Automatic Installation
* Download the Edwiser Advanced Block plugin from your Edwiser account or the distribution package.
* Go to Site administration > Plugins > Install plugins in Moodle.
* Upload the plugin zip file.
* Click on "Install plugin from the Zip file".
* Follow the on-screen installation prompts.
* Once installed, the block will be available to add to any course or site page.

## Moodle Block Manual Installation
* Download the Edwiser Advanced Block plugin package.
* Extract the zip file.
* Upload the 'edwiseradvancedblock' folder to the 'blocks' directory of your Moodle installation using FTP or file manager.
* Navigate to Site administration > Notifications to complete the installation.
* The block will be available to add to any course or site page.

## Post-Installation Setup
* Ensure the Edwiser Page Builder plugin (`local_edwiserpagebuilder`) is installed and active.
* Optionally enable the Edwiser Page Builder Filter (`filter_edwiserpbf`) for dynamic shortcode support at Site administration > Plugins > Filters > Manage filters.
* Add the block to any page by turning on editing mode and selecting "Edwiser Advanced Block" from the block picker.

**Documentation**: For detailed installation and setup instructions, visit [Edwiser Page Builder Documentation](https://edwiser-pagebuilder-docs.edwiser.org/)

[(Back to top)](#table-of-contents)

# Uninstallation

1. Go to Site administration > Plugins > Plugin overview.
2. Navigate to the Blocks section and find "Edwiser Advanced Block".
3. Click on the "Uninstall" link for the Edwiser Advanced Block.
4. Follow the uninstallation prompts.
5. The plugin folder can be removed from the 'blocks' directory if desired.

**Note**: Uninstalling the plugin will remove all block instances and their configurations from your site. Make sure to backup your site before uninstallation if you want to preserve content.

[(Back to top)](#table-of-contents)

# Files Information

## Languages
---------
The `blocks/edwiseradvancedblock/lang/` folder contains language files for the plugin.

Language files are available in multiple languages including:
* English (en)
* Spanish (es)
* Spanish - Mexico (es_mx)
* German (de)
* French (fr)
* Portuguese - Brazil (pt_br)
* Polish (pl)
* Arabic (ar)

Language strings follow Moodle's standard language file format and can be customized as needed.

## Styles
------
The plugin uses a single CSS file for base styling:
* `blocks/edwiseradvancedblock/style.css` - Carousel indicator styles (circle, dash, square)

Custom CSS entered per block instance is processed using Moodle's SCSS compiler and scoped to the individual block instance.

## Templates
------
Mustache templates are located in:
* `blocks/edwiseradvancedblock/templates/` - All plugin templates

Templates include:
* `blockcontent.mustache` - Main block content display with live editor button, CSS, HTML, and JavaScript rendering
* `blockpage.mustache` - Full page editor template for live customization
* `edwiserpbf_disabled_message.mustache` - Notification when the Page Builder Filter is disabled
* `plugin_not_available.mustache` - Notification when the Page Builder plugin is not installed

## Classes
------
PHP classes are located in:
* `blocks/edwiseradvancedblock/classes/external/` - External API classes for web services

## Capabilities
------
The plugin defines the following capabilities in `db/access.php`:
* `block/edwiseradvancedblock:addinstance` - Add block to courses and pages (default: Editing Teachers, Managers)
* `block/edwiseradvancedblock:myaddinstance` - Add block to the user dashboard (default: Users)
* `block/edwiseradvancedblock:cancustomizelive` - Use the live customizer to edit block content (default: Editing Teachers, Managers)

[(Back to top)](#table-of-contents)

# Roadmap

Future enhancements and features planned for Edwiser Advanced Block:

1. Enhanced live customizer interface
2. Additional pre-built content templates
3. Improved shortcode library
4. Performance optimizations
5. Extended accessibility support

For the latest roadmap information, please visit [Edwiser Forums](https://forums.edwiser.org/)

[(Back to top)](#table-of-contents)

# History

See `changes.txt` file in the plugin directory for detailed version history.

## Recent Versions

### Version 4.2.4
* Fix - Minor UI/UX improvements

### Version 4.2.3
* Fix - Minor UI/UX improvements

### Version 4.2.2
* Fix - Multilang v2 content was not editable when revisiting the live customizer
* Fix - Information now added in dynamic blocks when the filter plugin is disabled

### Version 4.2.1
* Feature - Added language translation files for Spanish (Spain), German, Spanish (Mexico), Portuguese (Brazil), French, Polish, and Arabic

### Version 4.2.0
* Tweak - UI/UX improvements with the block plugin
* Tweak - Extended support for Edwiser Page Builder

### Version 4.0.1
* Tweak - Improvement in save functionality of live customizer
* Tweak - Further UI/UX improvements done

### Version 4.0.0
* Moodle 4.0 compatible version of Edwiser Advanced Block
* Integration with Edwiser Page Builder plugin for dynamic HTML block creation

For complete version history, refer to the `changes.txt` file.

[(Back to top)](#table-of-contents)

# Author

**WisdmLabs**

WisdmLabs is a leading provider of Moodle solutions and WordPress integrations. We specialize in creating powerful, user-friendly plugins and themes that enhance the learning experience.

**Website**: https://wisdmlabs.com/
**Email**: support@wisdmlabs.com

[(Back to top)](#table-of-contents)

# Provided by

[![Edwiser](https://edwiser.org/wp-content/uploads/2025/11/edwiser-logo.webp)](https://edwiser.org)

**Edwiser** - Empowering Online Learning

Edwiser provides comprehensive Moodle solutions including themes, plugins, and integrations to help you create the perfect online learning platform.

**Website**: https://edwiser.org
**Documentation**: https://edwiser-pagebuilder-docs.edwiser.org/
**Support**: https://forums.edwiser.org/
**My Account**: https://edwiser.org/my-account/

[(Back to top)](#table-of-contents)
