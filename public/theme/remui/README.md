Edwiser RemUI Theme for Moodle
==============================================

# Table of Contents

- [Description](#description)
- [Features](#features)
- [Privacy & External API Disclosure](#privacy--external-api-disclosure)
- [Plugin Version](#plugin-version)
- [Required version of Moodle](#required-version-of-moodle)
- [Support](#support)
- [Installation](#installation)
- [Uninstallation](#uninstallation)
- [Files Information](#files-information)
- [Roadmap](#roadmap)
- [History](#history)
- [Author](#author)
- [Provided by](#provided-by)

# Description

Edwiser RemUI - A modern, responsive, and feature-rich theme for Moodle that transforms your learning management system into a beautiful and intuitive platform.

Edwiser RemUI is a premium Moodle theme designed to enhance the user experience with a modern interface, extensive customization options, and powerful features. Whether you're running a small training program or a large educational institution, RemUI provides the tools you need to create an engaging and accessible learning environment.

The theme offers a comprehensive visual personalizer, dark mode support, focus mode for distraction-free learning, accessibility features, and much more. With its responsive design, RemUI ensures your Moodle site looks great on all devices.

**Documentation**: For detailed setup and configuration instructions, visit [RemUI Theme Documentation](https://remui-docs.edwiser.org/remui-theme-setup/basic-setting)

[(Back to top)](#table-of-contents)

# Features

## Visual Personalizer
* **Color Customization**: Extensive color palette customization for all theme elements including primary, secondary, text, borders, backgrounds, and buttons
* **Quick Setup**: Pre-defined color schemes for quick theme setup
* **Brand Colors**: Customize brand colors to match your organization's identity
* **Typography**: Choose from standard fonts or Google Fonts with full typography control
* **Real-time Preview**: See changes instantly with live preview functionality

**Documentation**: [Visual Personalizer (Colour Customization)](https://remui-docs.edwiser.org/remui-theme-setup/visual-personalizer-colour-customization)

## Dark Mode
* **System-wide Dark Mode**: Enable dark mode across your entire Moodle site
* **Flexible Control**: Configure dark mode to be available on all pages, specific pages, or excluded from certain pages
* **User Preference**: Users can toggle dark mode on/off based on their preference
* **Automatic Detection**: Respects user's system dark mode preference
* **Custom Dark Mode Logo**: Upload separate logos for dark mode display

**Documentation**: [Dark Mode](https://remui-docs.edwiser.org/remui-theme-setup/dark-mode)

## Focus Mode
* **Distraction-Free Learning**: Enable focus mode to provide a clean, distraction-free learning environment
* **Course Page Integration**: Focus mode button appears on course pages for easy access
* **Force Focus Mode**: Option to force focus mode for all courses by default
* **User Control**: Learners can toggle focus mode on/off as needed

**Documentation**: [Focus Mode Enhancements](https://remui-docs.edwiser.org/remui-theme-setup/focus-mode-enhancements)

## Header Customization
* **Multiple Logo Formats**: Choose from icon only, icon with site name, logo only, or mini logo
* **Custom Logo Upload**: Upload your own logo with support for regular and dark mode versions
* **Site Name Color**: Customize the color of your site name/icon
* **Navigation Menu**: Customize top navigation menu with sign-up button option
* **Recent Courses Menu**: Display recently accessed courses in the header

**Documentation**: [Header](https://remui-docs.edwiser.org/remui-theme-setup/visual-personalizer-colour-customization/header)

## Footer Customization
* **Multiple Footer Templates**: Choose from 6 different footer design templates
* **Widget Areas**: Customize footer widget areas with logos, links, and content
* **Social Media Icons**: Add social media links with icons
* **Email Subscription**: Include email subscription forms in footer templates
* **Background Images**: Add custom background images to footer sections
* **Export/Import**: Backup and restore footer configurations in JSON format

**Documentation**: [Footer](https://remui-docs.edwiser.org/remui-theme-setup/visual-personalizer-colour-customization/footer)

## Login Page Customization
* **Custom Login Background**: Upload custom background images or videos for login page
* **Login Panel Logo**: Add your logo to the login panel
* **Color Customization**: Customize login page colors including background, text, and button colors
* **Video Background Support**: Use video backgrounds for a more engaging login experience

**Documentation**: [Login Page](https://remui-docs.edwiser.org/remui-theme-setup/visual-personalizer-colour-customization/login-page)

## Site-Wide Announcements
* **Announcement System**: Display important announcements to all users
* **Multiple Types**: Choose from Info, Success, Warning, or Danger announcement types
* **Dismissible**: Option to allow users to dismiss announcements
* **Rich Text Editor**: Use the built-in editor to create formatted announcements

**Documentation**: [Basic Setting - Site-wide Announcement](https://remui-docs.edwiser.org/remui-theme-setup/basic-setting#site-wide-announcement)

## SEO Settings
* **Favicon Support**: Upload custom favicon for your site
* **Google Analytics**: Integrate Google Analytics tracking (compatible with GA4)
* **Site Loader**: Custom site loader image for better user experience
* **Meta Tags**: Optimized meta tags for better SEO

**Documentation**: [Basic Setting - SEO Settings](https://remui-docs.edwiser.org/remui-theme-setup/basic-setting#seo-settings)

## Accessibility Features
* **Accessibility Widget**: Comprehensive accessibility widget with multiple tools
* **Dictionary Feature**: Enable dictionary to help users understand word meanings by selecting text
* **Keyboard Navigation**: Full keyboard navigation support
* **Screen Reader Support**: Optimized for screen readers
* **High Contrast Mode**: Support for high contrast displays

**Documentation**: [Accessibility Widget](https://remui-docs.edwiser.org/remui-theme-setup/accessibility-widget)

## Advanced Features
* **Custom CSS**: Add your own custom CSS for advanced styling
* **Layout Options**: Choose between narrow width (800px) or full width layouts
* **Font Selection**: Choose from standard fonts or Google Fonts
* **Quick Menu**: Floating quick menu for quick access to important links
* **Edwiser Support & Feedback**: Built-in feedback collection system
* **Usage Tracking**: Optional anonymous usage tracking to help improve the product
* **Setup Wizard**: Guided setup wizard for easy theme configuration

**Documentation**: [Basic Setting - Advanced Features](https://remui-docs.edwiser.org/remui-theme-setup/basic-setting#advanced-features)

## Privacy & External API Disclosure

### External API Calls

The RemUI theme makes external API calls to **edwiser.org** for the following purposes:

#### 1. Usage Tracking (Optional)
When usage tracking is enabled, the theme sends anonymous site data to edwiser.org for product improvement purposes. This includes:
* Site URL
* Moodle version
* PHP version
* Installed plugins information
* System settings (anonymized)

**Opt-Out**: You can disable usage tracking in the theme settings at Site administration > Appearance > Themes > RemUI Theme Settings.

#### 2. License Validation
License activation and validation requests are sent to edwiser.org to verify your license key and manage license status. This is required for theme activation and updates.

#### 3. User Feedback Collection
When users submit feedback through the built-in feedback collection system, the feedback data may be sent to edwiser.org to help improve the product. This is optional and can be controlled through theme settings.

#### 4. Bug Reports
Bug reports submitted through the theme's bug reporting feature are sent to edwiser.org for analysis and resolution.

### Data Privacy

* All data transmission to edwiser.org is done securely using HTTPS
* Usage tracking data is anonymized and does not contain personally identifiable information
* License validation only sends the license key and site URL
* User feedback is submitted voluntarily and may contain user-provided information
* You can opt out of usage tracking at any time through theme settings

### Compliance

The theme complies with Moodle's Privacy API requirements. All external API calls are disclosed in the Privacy API metadata, and users can review this information through Moodle's privacy settings.

For more information about data collection and privacy, please refer to Moodle's Privacy API documentation and the theme's Privacy API implementation.

[(Back to top)](#table-of-contents)
## Course Features
* **Course Cards**: Beautiful course card layouts with ratings and reviews
* **Course Archive**: Enhanced course archive page with filters
* **Enrollment Page**: Customizable enrollment page with pricing and instructor information
* **Course Progress**: Visual course progress indicators
* **Activity Navigation**: Next/Previous activity buttons for seamless navigation

**Documentation**: [Course Setup](https://remui-docs.edwiser.org/remui-theme-setup/course-setup)

## Dashboard Features
* **Custom Dashboard**: Enhanced dashboard with statistics and widgets
* **My Overview Block**: Improved course overview with filtering options
* **Dashboard Stats**: Real-time statistics for courses, activities, and progress
* **Recent Courses**: Quick access to recently accessed courses

**Documentation**: [Dashboard Page Setup](https://remui-docs.edwiser.org/remui-theme-setup/dashboard-page-setup)

## Additional Features
* **Export Block**: Export course content and blocks
* **Course Rating & Review**: Built-in rating and review system for courses
* **Site Sync**: Synchronize settings across multiple Moodle installations
* **RTL Support**: Full right-to-left language support
* **Multi-language**: Support for multiple languages
* **Responsive Design**: Fully responsive design for all devices

[(Back to top)](#table-of-contents)

# Plugin Version

v5.1.2 - Current Release

[(Back to top)](#table-of-contents)

# Required version of Moodle

This version works with Moodle 5.0 and above until the next release.

Please ensure that your hardware and software complies with 'Requirements' in 'Installing Moodle' on
'https://docs.moodle.org/39/en/Step-by-step_Installation_Guide_for_Ubuntu'.


[(Back to top)](#table-of-contents)

# Support

For all support queries related to Edwiser RemUI theme you could email us at support@wisdmlabs.com

Apart from that you could raise your support queries in this forum too - https://forums.edwiser.org/

And if you wish to see any new features as part of the product then you could share your feature requests here
forum https://forums.edwiser.org/ for support.
Together we could make this solution better for your Moodle.

**Documentation**: For comprehensive documentation, visit [RemUI Theme Documentation](https://remui-docs.edwiser.org/)

[(Back to top)](#table-of-contents)

# Installation

## Minimum Requirements
* PHP version 7.4 or greater (PHP 8.0+ recommended)
* Moodle 5.0 or higher
* MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 10+

## Moodle Theme Automatic Installation
* Download the Edwiser RemUI theme from your Edwiser account or the distribution package.
* Go to Site administration > Appearance > Themes > Theme selector in Moodle.
* Click on "Install theme" or navigate to Plugins > Install plugins.
* Upload the theme zip file.
* Click on "Install plugin from the Zip file".
* Once installed, go to Site administration > Appearance > Themes > Theme selector.
* Select "RemUI" as your site theme and save changes.

## Moodle Theme Manual Installation
* Download the Edwiser RemUI theme package.
* Extract the zip file.
* Upload the 'remui' folder to the 'theme' directory of your Moodle installation using FTP or file manager.
* The theme can then be activated by navigating to Site administration > Appearance > Themes > Theme selector.
* Select "RemUI" as your site theme and save changes.

## Post-Installation Setup
* After installation, you can access the theme settings at Site administration > Appearance > Themes > RemUI Theme Settings.
* Use the Setup Wizard for guided configuration (available on first installation).
* Configure basic settings, visual personalizer, and other features as needed.

**Documentation**: For detailed installation and setup instructions, visit [RemUI Theme Setup](https://remui-docs.edwiser.org/remui-theme-setup/basic-setting)

[(Back to top)](#table-of-contents)

# Uninstallation

1. Go to Site administration > Plugins > Plugin overview.
2. Navigate to the Themes section and find "RemUI".
3. Click on the "Uninstall" link for the RemUI theme.
4. Follow the uninstallation prompts.
5. The theme folder can be removed from the 'theme' directory if desired.

**Note**: Uninstalling the theme will remove all theme-specific settings and configurations. Make sure to backup your site before uninstallation if you want to preserve settings.

[(Back to top)](#table-of-contents)

# Files Information

## Languages
---------
The `theme/remui/lang/` folder contains language files for the theme.

Language files are available in multiple languages including:
* English (en)
* Spanish (es, es_mx)
* Portuguese (pt_br)
* French (fr)
* German (de)
* Polish (pl)
* Arabic (ar)

Language strings follow Moodle's standard language file format and can be customized as needed.

## Styles
------
The theme uses SCSS (Sass) for styling. The main SCSS files are located in:
* `theme/remui/scss/` - Main SCSS files
* `theme/remui/scss/bootstrap/` - Bootstrap framework files
* `theme/remui/scss/remui/` - RemUI-specific styles

Compiled CSS files are generated in:
* `theme/remui/style/` - Compiled CSS files

## JavaScript
------
The theme uses AMD (Asynchronous Module Definition) for JavaScript:
* `theme/remui/amd/src/` - Source JavaScript files
* `theme/remui/amd/build/` - Compiled JavaScript files

## Templates
------
Mustache templates are located in:
* `theme/remui/templates/` - All theme templates

## Third-Party Libraries
------
Third-party libraries are documented in:
* `theme/remui/thirdpartylibs.xml` - XML file listing all third-party libraries used

Libraries included:
* Twitter Bootstrap 5.3.3
* Font Awesome 6.7.2
* HTML2Canvas 2.0.1
* Sienna Accessibility Widget 2.0.1
* NightEye Dark Mode Library

[(Back to top)](#table-of-contents)

# Roadmap

Future enhancements and features planned for Edwiser RemUI:

1. Enhanced mobile experience improvements
2. Additional footer template designs
3. More color scheme presets
4. Advanced typography controls
5. Integration with more third-party services
6. Performance optimizations
7. Accessibility improvements

For the latest roadmap information, please visit [Edwiser Forums](https://forums.edwiser.org/)

[(Back to top)](#table-of-contents)

# History

See `changes.txt` file in the theme directory for detailed version history.

## Recent Versions

### Version 5.1.2
* Feature - Added a collapsible menu for floating icons
* Tweak - Added a setting to show a Sign Up button on the home page for non-logged-in users
* Tweak - Added option to set and reset the theme colors through Quick setup color pallet in the Visual Personalizer
* Fix - User added custom skill level is supported on course cards and enrollment page

### Version 5.1.1
* Fix - Minor UI/UX improvements

### Version 5.1.0
* Feature - Added compatibility with Moodle 5.1
* Tweak - Adjusted the Focus Mode toast message position
* Tweak - Updated the "Add an activity" modal UI for Moodle 5.1
* Fix - Resolved minor UI issues in the theme
* Fix - Fixed deprecation warnings appearing on PHP 8.4
* Fix - Corrected dashboard stats update issues

For complete version history, refer to the `changes.txt` file.

[(Back to top)](#table-of-contents)

# Author

**WisdmLabs**

WisdmLabs is a leading provider of Moodle solutions and WordPress integrations. We specialize in creating powerful, user-friendly plugins and themes that enhance the learning experience.

**Website**: https://wisdmlabs.com/
**Email**: support@wisdmlabs.com

[(Back to top)](#table-of-contents)

# Provided by

[![Edwiser](https://edwiser.org/wp-content/uploads/2021/06/edwiser-logo.png)](https://edwiser.org)

**Edwiser** - Empowering Online Learning

Edwiser provides comprehensive Moodle solutions including themes, plugins, and integrations to help you create the perfect online learning platform.

**Website**: https://edwiser.org
**Documentation**: https://remui-docs.edwiser.org/
**Support**: https://forums.edwiser.org/
**My Account**: https://edwiser.org/my-account/

[(Back to top)](#table-of-contents)
