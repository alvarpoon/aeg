=== USP Pro ===

Plugin Name: USP Pro
Plugin URI: https://plugin-planet.com/usp-pro/
Description: Create unlimited forms and let visitors submit content, register, and much more from the front-end of your site.
Tags: submit, public, share, upload, images, files, posts, users, submit, front-end, submissions, contact, register, login, custom fields
Author: Jeff Starr
Author URI: http://monzilla.biz/
Donate link: http://m0n.co/donate
Contributors: specialk
Requires at least: 3.9
Tested up to: 4.2
Stable tag: trunk
Version: 2.1
Text Domain: usp
Domain Path: /languages/
License: the USP Pro license is comprised of two parts (see "License" section below for details)

Create unlimited forms and let visitors submit content, register, log in, and much more from the front-end of your site.



== Description ==

USP Pro is your complete front-end forms solution, enabling you to create unlimited forms and let visitors submit content, register, and much more.



== Installation ==

Installing USP Pro is simple:

1. Unzip the downloaded plugin and upload the `/usp-pro/` folder to the WordPress `/wp-content/plugins/` directory.
2. Done. Visit the Plugins screen in the WP Admin to activate the plugin, then visit the USP Pro settings to configure the plugin and get started.

For more detailed information on installing plugins, [visit the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

Note: The USP Pro settings page includes complete information about configuring USP Pro. Visit the Tools tab for more information.

Additional documentation available at [Plugin-Planet.com](https://plugin-planet.com/usp-pro/).



== Upgrade Notice ==

Upgrades: Your purchase of USP Pro includes free lifetime upgrades, which include new features, bug fixes, and other improvements. 

__Important!__ Before upgrading, visit "Tools > Backup & Restore" to back up your current settings. That way if something should go wrong, you can always restore your original settings and start over.



== Frequently Asked Questions ==

Getting started:

* [USP Pro Quick-Start Guide](https://plugin-planet.com/usp-pro-quick-start/)
* [USP Pro Settings](https://plugin-planet.com/usp-pro-settings/)
* [USP Pro Shortcodes](https://plugin-planet.com/usp-pro-shortcodes/)
* [USP Pro Template Tags](https://plugin-planet.com/usp-pro-template-tags/)
* [USP Pro FAQs](https://plugin-planet.com/usp-pro-faqs/)

Further resources:

* [USP Pro Docs](https://plugin-planet.com/docs/usp/)
* [USP Pro Forum](https://plugin-planet.com/forum/usp/)
* [USP Pro Tutorials](https://plugin-planet.com/tuts/)
* [USP Pro News](https://plugin-planet.com/news/)

Feedback and downloads:

* [Bug reports, help requests, and feedback](https://plugin-planet.com/usp-pro/#contact)
* [Log in to your account for current downloads](https://plugin-planet.com/wp/wp-login.php)

You can learn more about USP Pro at [Plugin-Planet.com](https://plugin-planet.com/usp-pro/).



== Screenshots ==

Screenshots and more available at [Plugin-Planet.com](https://plugin-planet.com/usp-pro/).



== License ==

License: USP Pro is comprised of two parts:
	
* Part 1: Its PHP code is licensed under the GPL (v2 or later), like WordPress. More info @ http://www.gnu.org/licenses/
* Part 2: Everything else (e.g., CSS, HTML, JavaScript, images, design) is licensed according to the purchased license. More info @ https://plugin-planet.com/usp-pro/

Without prior written consent from Monzilla Media, you must NOT directly or indirectly: license, sub-license, sell, resell, or provide for free any aspect or component of Part 2.

Further license information is available in the plugin directory, /license/, and online @ https://plugin-planet.com/wp/files/usp-pro/license.txt

Upgrades: Your purchase of USP Pro includes free lifetime upgrades, which include new features, bug fixes, and other improvements. 

Copyright: © 2015 Monzilla Media 



== Changelog ==

= 2.1 =

* Fixes XSS vulnerability with add_query_arg()
* Tested with WP 4.2 + 4.3 (alpha)
* Changed a few "http" links to "https"
* Adds a bit of box-shadow to Quicktag dialog box
* Fixes some PHP warnings related to file uploads
* Adds usp_post_array filter to customize submitted post
* Fixes missing fields when post-formatting is disabled
* Adds %%post_custom%% variable to display custom fields in email alerts
* Extends "Replace Author" setting to %%user_name%% email alert variable
* Refines readme.txt and file header information
* Fixes empty div output for auto-display images
* Adds "default" attribute to taxonomy shortcode to customize "Please select.."
* Adds "all_include_empty" to taxonomy shortcode to display all tax terms
* Fixes custom fields not included in email messages
* Adds "File Name" to Advanced > Primary Form Fields and More > Primary Field Errors
* New translation .pot file

= 2.0 =

**General**

* Tested with latest version of WP (4.1)
* Advanced testing on WordPress version 4.2 (beta)
* Streamline/fine-tune plugin code, fixed typos, etc.
* Updates plugin documentation in plugin and on site

**Language**

* Adds Text Domain and Domain Path to file header
* Updates plugin file headers with better information
* Replaces default .mo/.po templates with usp.pot template

**Bug Fixes**

* Fixes incorrect path to custom stylesheet for TinyMCE
* Fixes login/register form shortcode not working
* Fixes USP Meta Box displaying with empty values
* Fixes alt metadata attached as custom field
* Fixes echo to return for various select() tags
* Fixes required checkbox fields not working
* Fixes "Please select" not displayed on some tag fields
* Fixes custom post status not displaying on front-end
* Fixes uploading of disallowed file types (e.g., .php)

**Improvements**

* Improves file previews, thumbnails for other file types
* Strips whitespace from Custom Field key settings
* Improves About > Plugin Information section
* Removes some unnecessary attributes from Form Demos
* Improves Quicktag dialogs with lightbox-style UI
* Updates plugin updater class to latest version

**New Features**

* Adds Media Title custom field functionality
* Adds drag_drop_upload functionality to Visual Editor
* Adds filename field for custom media metadata
* Adds usp_filename_default filter hook
* Adds usp_mediatitle_default filter hook
* Adds error-specific class names to errors
* Adds .usp-error-custom to the default custom-field error
* Adds .usp-error-taxonomy to the default taxonomy error
* Adds ability to use HTML in Default Post Content
* Adds support for user-specified taxonomy terms
* Adds fieldset attribute to field shortcodes
* Adds "type" attribute to category shortcode
* Adds "default" attribute to category shortcode
* Adds "type" attribute to tag shortcode
* Adds "default" attribute to tag shortcode
* Adds "exclude" attrubute to tag shortcode
* Adds "all" value for terms attribute to display all tax terms
* Adds "exclude" attribute for taxonomy shortcode
* Adds custom post status per form via custom field
* Adds "future" post status to global/per-form options
* Adds usp_post_date_offset filter hooks
* Adds Schedule Posts and set dates for past posts
* Adds User Shortcode to display any image from URL
* Adds preview thumbnails for alternate file types
* Adds preview thumbnails for all file select methods
* Adds a bit of top padding to image preview container
* Adds new filter hook: usp_custom_fields_files
* Adds new filter hook: usp_files_combined_array
* Adds support for multiple file upload fields
* Adds custom file upload fields plus new attributes
* Adds way to disable file previews for file upload fields
* Adds local min/max file atts to usp_files shortcode

= 1.9 =

* Bug: empty custom prefix causing duplicate error messages
* Bug: custom prefix as name not working when field name is prefix only
* Bug: email alerts sent even when disable in the settings
* Bug: null value for labels and placeholders not working on custom fields
* Bug: empty value for custom prefix interferes with default custom field prefix
* Bug: email included Additional Information heading even when no custom fields included
* Bug: email alerts not sending for denied USP Posts (CPT)
* Bug: email alerts sending for posts submitted from backend
* Bug: type attribute displayed on custom field textareas
* New: Backup and restore theme options (under "Tools" tab)
* New: Content filter to blacklist forbidden terms and phrases from post content (under "Admin" tab)
* New: function to replace author name when called with the_author_meta (see "Replace Author" option)
* New: function to replace author URL when called with the_author_meta (see "Replace Author" option)
* New: option to whitelist contact, registration, submission forms (see "Form  Security" in General settings)
* New: meta box support for submitted custom field data (works with all Post Types)
* New: live preview feature for post content
* New: user shortcodes: `[url]`, `[link]`, `[name]`, `[file-1]` thru `[file-3]`, `[item-1]` thru `[item-3]`
* New: template tag and shortcode to add a tabbed login/register/password form
* New: select/option fields via custom fields
* New: multiple checkbox fields via custom fields
* New: radio inputs for custom fields
* New: added %%post_content%% shortcode to admin/user email alerts and contact email
* New: added attribute to disable fieldset for any custom field
* New: added a bunch of new action and filter hooks (list @ [Plugin Planet](https://plugin-planet.com/usp-pro-action-filter-hooks/)
* Moved "Contact Forms" option from Admin settings to "Form  Security" in General settings
* Deprecates `usp-send-mail` hidden field, replaces with usp-is-contact
* Changes defaults to none (no value) for input class and label class on custom fields
* Streamlines send_email_form() function with improved logic
* Adds two new filter hooks: usp_send_email_custom_heading and usp_send_email_custom_fields
* Adds some new styles to usp-admin.css (meta box styles)
* Changed paragraph tags to span tags in user shortcodes for images
* Refactored USP_Custom_Fields Class
* Replaced some instances of get_bloginfo('url') with home_url()
* Retested with Plugin Check plugin
* Updated localization templates (mo/po) files
* Updated plugin documentation in plugin and on site
* Advance testing on WP 4.1 (alpha)
* General code check and clean

= 1.8 =

* Bugfix: callback_dropdown() now displays correct value for select options (user roles)
* Bugfix: duplicate errors for alt, caption, description fields
* Bugfix: missing error class for alt, caption, description fields
* Bugfix: undefined index for categories array in usp-forms.php
* Bugfix: global redirect success not working when redirect failure not specified
* Bugfix: local redirect success not working properly
* Bugfix: custom CSS class included in attribute without preceding space
* Bugfix: approval email not sent to user, replaced get_userdata() with get_the_author_meta()
* Bugfix: unpredictable results with max-character functionality
* Bugfix: custom fields errors and session remembering not working
* Bugfix: custom content not added to contact emails when post is not submitted
* Bugfix: category checkboxes not remembering values
* Bugfix: usp_style setting was not included in uninstall.php
* New feature: unlimited *custom* custom fields :)
* New feature: forms now support multiple category fields (chained/combo categories)
* New feature: user shortcodes (`[img-1]` thru `[img-5]`) to auto-display uploaded images
* New feature: specify size of auto-display images (thumbnail, medium, large, or full)
* New feature: restore default plugin options (added to "More" settings)
* New feature: usp-subject now attached to submitted post as custom field
* New feature: over 50 new action and filter hooks (list @ [Plugin Planet](https://plugin-planet.com/usp-pro-action-filter-hooks/)
* New feature: option to send email in HTML or plain-text format for contact forms and post alerts
* New feature: option to add custom attributes to the form tag
* New feature: options to specify default values for post title and content
* New feature: "custom" attribute for all form Quicktags/Shortcodes
* New feature: input types for custom fields now include: url, search, email, tel, month, week, time, datetime, datetime-local, color, date, range, number
* Enhancement: integrated support for Parsley 2 [ref](https://plugin-planet.com/usp-pro-form-validation-with-parsley-2/)
* Enhancement: removed menu location for usp_form/usp_post for better compatibility with plugins that do declare a menu location
* Enhancement: improved logic of submission_redirect() function
* Enhancement: improved logic of registration hooks and options calls in usp-pro.php
* Enhancement: improved logic of send_email_alert() function
* Enhancement: replaced UTF-8 with get_option('blog_charset') in several functions
* Enhancement: removed @charset "UTF-8"; from all stylesheets
* Enhancement: replaced htmlentities() and htmlspecialchars() with sanitize_text_field()
* Enhancement: removed paragraph tags from table cells on plugin settings page
* Enhancement: moved settings section descriptions into their own file
* Enhancement: increased minimum required version to WP 3.7
* Enhancement: added reply-to headers to contact-form emails
* Enhancement: cleaned up plain-text emails formatting etc.
* Enhancement: improved some settings descriptions
* Enhancement: changed the way default email from address is handled (see option Admin: Contact Form: "Email From")
* Enhancement: added default template demo of the classic USP form (from the free version of USP)
* Tested with Meta Slider plugin, works great together out of the box :)
* Note: %%post_date%% shortcut seems now to be working with submit-post email alerts (as of WP 4.0)
* Removed from form tag (replaced with option): data-validate="parsley" data-persist="garlic" novalidate
* Added .exclude class to #verify field
* Changed default width of input fields and textarea
* Removed inline documentation (replaced with link to online docs)
* Removed usp-tables.php
* Updated localization templates (mo/po) files
* Updated plugin documentation in plugin and on site
* Tested with latest version of WordPress (4.0)
* Advance testing on WP 4.1 (alpha)
* General code check and clean

= 1.7 =

* Bugfix: custom field names now properly recognized for all field types
* Bugfix: multiple category select fields now remember values (when enabled)
* Bugfix: custom fields now included properly in contact emails (when enabled)
* Bugfix: "no mail" setting now includes email alerts (so possible to disable all mail)
* Bugfix: custom user fields now may be required or not required (e.g., nicename, firstname, et al)
* Bugfix: for combo submit/register forms, post was being submitted even when registration fails
* New feature: use hidden field to make any form a submit, register, contact form, or combo form
* New feature: support for custom taxonomies (custom categories), includes shortcode and quicktag
* New feature: set submitted posts to "Publish Private" status
* New feature: set submitted posts to "Publish with Password" status (auto-sends password)
* New feature: added custom post status for the Posts screen (sort by custom status)
* New feature: custom email recipients for individual contact forms (via hidden field)
* New feature: carbon copy emails for admin submission, approval, and denied alerts
* New feature: shortcodes for email alerts (submitted, approved, denied)
* New feature: email alerts for submitter and admins when post is approved (published)
* New feature: email alerts for submitter and admins when post is denied (moved to trash)
* New feature: contact-form emails may include custom content with dynamic post variables
* New feature: customize all error messages (visit new "More" settings tab)
* Enhancement: post ID now included in return URL query string
* Enhancement: post ID now attached to submitted posts as custom field "usp-post-id"
* Enhancement: approval email alerts now work for scheduled/future-published posts
* Enhancement: contact email includes custom user fields if included in the form (e.g., nicename, firstname, et al)
* Enhancement: improved data handling in session variable and post content
* Enhancement: streamlined insert_post function for better performance
* Enhancement: increased textarea widths in plugin settings
* Enhancement: error class added to primary form fields for custom styling
* Enhancement: now using wp_generate_password() for user registration passwords
* Enhancement: replaced sanitize_text_field() with sanitize_email() for the email field
* Enhancement: added width and height to allowed attributes in post content
* Enhancement: tweaked the settings description for external stylesheet and JavaScript files
* Enhancement: tweaked the inline styles used for the USP Filter button on Trash screen
* Added localization for French (Thanks to [Christophe Glaudel](http://chrisglaudel.com/))
* Updated form demos with new hidden fields for register and contact
* Updated localization templates (mo/po) files
* Updated plugin documentation in plugin and on site
* Advance testing on WP 4.0 (alpha)
* General code check and clean

= 1.6 =

* Added support for unlimited custom post types (per-form post types)
* New feature: specify your own prefix for custom fields
* Added field support for parsley.js form validation
* Added support for Garlic (remember form values via jQuery)
* Bugfix: added conditional check to usp_get_art_directed (resolves an error with BuddyPress)
* Bugfix: tweaked code for excluding categories (resolved Illegal string offset error)
* Custom field names may now include dashes/hyphens
* Cleaned up custom field processing and field remembering
* Bugfix: added headers to approval emails (thanks to [Mike Edwards](http://leanintuit.com/))
* Bugfix: corrected wording of "from" options in contact form settings
* Updated localization templates (mo/po) files
* Updated plugin documentation in plugin and on site
* Further testing with WP version 3.9
* General code check and clean

= 1.5 =

* Renamed "usp_init" to "usp_pro_init" in usp-pro.php
* Added "exclude" parameter to category shortcode and quicktag
* Bugfix: defined default category-level class in category field
* Added display_usp_form template tag for displaying USP Forms
* Now supports forms that post content and send email via usp-send-mail
* Added option to include or not any custom fields in email content
* Bugfix: nested shortcodes for usp_is_submission, usp_access, usp_visitor, usp_member
* Added html_entity_decode to email message for post approvals, admin alerts, user alerts
* Bugfix: usp_error_post parameter was not being cleared on successful form submission
* Added fallback functionality for exif_imagetype
* New feature: create new categories on form submission
* Bugfix: textarea custom fields remember their input values
* Increased the maximum number of images from 20 to 99
* Updated plugin documentation in plugin and on site
* Advance testing on development WP version 3.9-beta2
* General code check and clean

= 1.4 =

* Bugfix: renamed "init" to "usp_init" in usp-pro.php
* usp_require_wp_version() now runs only on plugin activation
* Bugfix: removed "'test '." string from usp_get_meta() output
* Changed "POST NAME" to "AUTHOR NAME" in process.php line 882
* Bugfix: resolved error when using "No Limit" for file uploads
* New Feature: WP Visual Editor now works with custom textareas
* New Feature: Now can has multiple Visual Editors per USP Form
* New Feature: Option to use the Name field as the post author
* Advance testing on development WP version 3.9-alpha-nightly
* Updated localization mo/po templates
* General code check and clean

= 1.3 =

* New feature: added option to use Google reCAPTCHA instead of default Challenge Question
* New feature: added option to require post titles to be unique
* New feature: added option to specify the "From" address for contact forms
* Bugfix: max_files setting now updates correctly
* Bugfix: files input now handles single-file uploads properly
* Bugfix: custom fields work with any alphanumeric value for input "name" attribute
* Bugfix: auto-display images now applies only to user-submitted
* Bugfix: contact form now working properly for multiple forms and custom fields
* Improved code for min_files setting for better functionality
* Improved logic for error handling and markup for default messages
* Increased default number of characters for file upload input field
* Replaced sanitize_text_field with htmlspecialchars for custom field input
* Retested email/alert functionality with Gmail
* Updated inline and online documentation
* Generated new translation (mo/po) files
* General code check and clean

= 1.2 =

* New feature: let users choose their own password when registering
* New feature: custom form redirects (overrides default setting) - includes new USP Quicktag and Shortcode
* New feature: option to use unique file names or overwrite existing files - more info @ http://m0n.co/usp-1
* Bugfix: WYSIWYG Editor was not displaying correctly - more info @ http://m0n.co/usp-2
* Added `href`, `rel`, and `target` attributes to `$allowed_atts` in `usp-pro.php`
* Resolved some lingering PHP strict notices
* General code check and clean

= 1.1 =

* Testing automatic updates

= 1.0 =

* Initial release
