<?php // USP Pro - Settings Section Descriptions

// LICENSE TAB
function section_license_desc() {
	$license 	= get_option('usp_license_key');
	$status 	= get_option('usp_license_status');
	echo '<p class="intro">'. __('USP Pro License Information', 'usp') . '</p>';
	echo '<h3>'. __('License Status', 'usp') .'</h3>';
	echo '<p>'. __('Your purchase of USP Pro entitles you to free automatic updates according to the license terms. ', 'usp');
	echo __('To enable this feature, visit the', 'usp') .' <a href="'. get_admin_url() .'plugins.php?page=usp-pro-license">'. __('USP License Page', 'usp') .'</a> ';
	echo __('to enter your License Key and activate the plugin. Note: to view your License Key at any time,', 'usp');
	echo ' <a href="'. USP_URL .'/wp/wp-admin/" target="_blank">'. __('log in to your account at Plugin Planet.', 'usp') .'</a></p>';
	if ($status !== false && $status == 'valid') {
		echo '<p><strong>'. __('License Status:', 'usp') .'</strong> <span style="color:green;">'. __('Your USP Pro License is currently active.', 'usp') .'</span></p>';
		echo '<p><strong>'. __('License Key:', 'usp') .'</strong> <code style="padding:3px 5px;text-shadow:1px 1px 1px #fff;">'. $license .'</code></p>';
		echo '<p><strong>'. __('License Domain:', 'usp') .'</strong> <code style="padding:3px 5px;text-shadow:1px 1px 1px #fff;">'. $_SERVER['SERVER_NAME'] .'</code></p>';
		echo '<p><strong>'. __('License Admin:', 'usp') .'</strong> <code style="padding:3px 5px;text-shadow:1px 1px 1px #fff;">'. get_bloginfo('admin_email') .'</code></p>';
		echo '<p><a href="'. get_admin_url() .'plugins.php?page=usp-pro-license">Deactivate License &raquo;</a></p>';
	} else {
		echo '<p><strong>'. __('License Status:', 'usp') .'</strong> <span style="color:red;">'. __('Your USP Pro License is currently inactive.', 'usp') .'</span></p>';
		echo '<p><a href="'. get_admin_url() .'plugins.php?page=usp-pro-license">Activate License &raquo;</a></p>';
	}
}
// GENERAL TAB
function section_general_0_desc() {
	echo '<p class="intro">'. __('Welcome to USP Pro. Here are the General Settings. Visit the Tools tab for a quick-start guide and more info.', 'usp') .'</p>'; 
}
function section_general_1_desc() { 
	echo '<p>'. __('Basic settings for USP Pro. Please examine these settings before publishing any forms.', 'usp') .'</p>'; 
}
function section_general_2_desc() { 
	echo '<p>'. __('User settings determine how visitors and users and handled when submitting form content.', 'usp') .'</p>'; 
}
function section_general_3_desc() { 
	echo '<p>'. __('Here you may customize the optional antispam/challenge question.', 'usp') .'</p>'; 
}
function section_general_4_desc() { 
	echo '<p>'. __('Category settings determine how categories are handled with submitted content.', 'usp') .'</p>'; 
}
function section_general_5_desc() { 
	echo '<p>'. __('Tag settings determine how tags are handled with submitted content.', 'usp') .'</p>'; 
}
function section_general_6_desc() { 
	echo '<p>'. __('After setting up your forms, enter their IDs (comma-separated) in the proper fields below. This is not necessary but is recommended as an extra security measure. ', 'usp');
	echo __('Tip: add your form IDs as you build them, then once everything is ready, check the box to &ldquo;Enable this feature&rdquo; before going live.', 'usp') .'</p>'; 
}
// STYLE TAB
function section_style_0_desc() { 
	echo '<p class="intro">'. __('Customize the appearance (CSS) and behavior (JavaScript) of USP Forms. Note that these options apply to all USP Forms.', 'usp');
	echo __('To define per-post CSS/JS, use the <code>usp-css</code> and <code>usp-js</code> custom-fields (visit the Tools tab for more info).', 'usp') . '</p>';
}
function section_style_1_desc() { 
	echo '<p>'. __('Here you may include an external CSS/stylesheet, select a set of CSS styles for all USP Forms, define your own custom styles, or disable styles.', 'usp') . '</p>';
}
function section_style_2_desc() { 
	echo '<p>'. __('Include the default USP JavaScript file, and/or add some custom JavaScript to be included with all USP Forms.', 'usp') . '</p>';
}
function section_style_3_desc() { 
	echo '<p>'. __('Here you can optimize site performance by specifying exactly which URLs require the external CSS and JavaScript files.', 'usp') . '</p>';
}
// UPLOADS TAB
function section_uploads_0_desc() { 
	echo '<p class="intro">'. __('Configure file uploads. Advanced configuration is possible via the', 'usp') . ' <code>usp_file</code> ' . __('shortcode. Visit the Tools tab for more info.', 'usp') . '</p>';
}
function section_uploads_1_desc() { 
	echo '<p>'. __('Here are the main settings for file uploads. If in doubt with anything, go with the default option.', 'usp') .'</p>'; 
}
// ADMIN TAB
function section_admin_0_desc() { 
	echo '<p class="intro">'. __('Customize email alerts and contact forms.', 'usp') .'</p>'; 
}
function section_admin_1_desc() { 
	echo '<p>'. __('Here are you may specify your email settings, which are used for email alerts when enabled.', 'usp') .'</p>'; 
}
function section_admin_2_desc() { 
	echo '<p>'. __('Here are you may specify how email alerts should be sent. Note: &ldquo;Disable email alerts&rdquo; overrides individual settings for &ldquo;Admin&rdquo; and &ldquo;User&rdquo; (below). ', 'usp');
	echo __('User-registration emails will not be overridden (can not be disabled) and will be sent automatically to the user and admin when &ldquo;auto-registration&rdquo; is enabled.', 'usp') .'</p>';  
}
function section_admin_3_desc() { 
	echo '<p>'. __('Here are you may customize email alerts sent to the admin (based on previous &ldquo;Email Settings&rdquo;). ', 'usp');
	echo '<strong>'. __('Variables:', 'usp') .'</strong> '. __('Use any of the following shortcuts in your messages (submissions, approvals, and denied) to display dynamic bits of information. ', 'usp');
	echo '<a id="usp-toggle-regex-1" class="usp-toggle-regex-1" href="#usp-toggle-regex-1">' . __('Show/Hide Variables &raquo;', 'usp') . '</a> ';
	echo '<pre class="usp-regex-1 usp-toggle default-hidden">blog url      = %%blog_url%%	
blog name     = %%blog_name%%
admin name    = %%admin_name%%
admin email   = %%admin_email%%
user name     = %%user_name%%
user email    = %%user_email%%
post title    = %%post_title%%
post date     = %%post_date%%
post url      = %%post_url%%
post id       = %%post_id%%
post content  = %%post_content%%
custom fields = %%post_custom%%</pre></p>';
}
function section_admin_4_desc() { 
	echo '<p>'. __('Here are you may customize email alerts sent to the user/submitter. Note that if &ldquo;auto-registration&rdquo; is enabled, WordPress handles the registration emails sent to the admin and user. ', 'usp'); 
	echo '<strong>'. __('Variables:', 'usp') .'</strong> '. __('Use any of the following shortcuts in your messages (submissions, approvals, and denied) to display dynamic bits of information. ', 'usp');
	echo '<a id="usp-toggle-regex-2" class="usp-toggle-regex-2" href="#usp-toggle-regex-2">' . __('Show/Hide Variables &raquo;', 'usp') . '</a> ';
	echo '<pre class="usp-regex-2 usp-toggle default-hidden">blog url      = %%blog_url%%	
blog name     = %%blog_name%%
admin name    = %%admin_name%%
admin email   = %%admin_email%%
user name     = %%user_name%%
user email    = %%user_email%%
post title    = %%post_title%%
post date     = %%post_date%%
post url      = %%post_url%%
post id       = %%post_id%%
post content  = %%post_content%%
custom fields = %%post_custom%%</pre></p>';
}
function section_admin_5_desc() { 
	echo '<p>'. __('Here are you may customize the contact form functionality that&rsquo;s built-in to USP Pro. Usage information available from the &ldquo;Tools&rdquo; tab. ', 'usp');
	echo '<strong>'. __('Variables:', 'usp') .'</strong> '. __('Use any of the following shortcuts for &ldquo;Custom Content&rdquo; to display dynamic bits of information. ', 'usp');
	echo '<a id="usp-toggle-regex-3" class="usp-toggle-regex-3" href="#usp-toggle-regex-3">' . __('Show/Hide Variables &raquo;', 'usp') . '</a> ';
	echo '<pre class="usp-regex-3 usp-toggle default-hidden">blog url      = %%blog_url%%	
blog name     = %%blog_name%%
admin name    = %%admin_name%%
admin email   = %%admin_email%%
user name     = %%user_name%%
user email    = %%user_email%%
post title    = %%post_title%%
post date     = %%post_date%%
post url      = %%post_url%%
post id       = %%post_id%%
post content  = %%post_content%%
custom fields = %%post_custom%%</pre></p>';
}
function section_admin_6_desc() { 
	echo '<p>'. __('By default, contact-form email is sent to the address specified under Admin &gt; &ldquo;Email Settings&rdquo;. Here you may specify a custom &ldquo;To&rdquo; address (or multiple addresses) for any contact form. ', 'usp');
	echo __('Just add <code>&lt;input name="usp-contact-ids" value="1,3" type="hidden" /&gt;</code> to any contact form, where 1 and 3 refer to fields #1 and #3 below. ', 'usp');
	echo __('When this hidden field is included in a contact form, the email will be sent to the specified custom address(es).', 'usp') . '</p>';
}
function section_admin_7_desc() { 
	echo '<p>'. __('Here you can specify any terms or phrases that should not appear in any post content. Put each term on its own line.', 'usp');
}
// ADVANCED TAB
function section_advanced_0_desc() {
	echo '<p class="intro">'. __('Customize formatting, post types, custom fields, error messages, and more.', 'usp') .'</p>'; 
}
function section_advanced_1_desc() { 
	echo '<p>'. __('Here are some key settings for configuring USP Forms, including resources, and various automatic functionality.', 'usp') .'</p>'; 
}
function section_advanced_2_desc() { 
	echo '<p>'. __('Here you may customize options for USP Posts. The &ldquo;USP Posts&rdquo; option uses a custom post type provided by the USP Pro plugin, and works with the option &ldquo;Slug for USP Post Type&rdquo;. ', 'usp'); 
	echo __('The &ldquo;Existing Post Type&rdquo; uses one of your own post types and works with the option &ldquo;Specify Existing Post Type&rdquo;. If in doubt, roll with the default option :)', 'usp') .'</p>';
}
function section_advanced_3_desc() { 
	echo '<p>'. __('Here you may specify any custom text and/or markup to appear before and after all USP forms. Leave blank to disable.', 'usp') .'</p>'; 
}
function section_advanced_4_desc() { 
	echo '<p>'. __('Here you may customize the various success messages and specify any custom content to be included before/after. Basic HTML/markup allowed. Leave the before/after fields blank to disable.', 'usp') .'</p>'; 
}
function section_advanced_5_desc() { 
	echo '<p>'. __('Here you may specify any custom text and/or markup to appear before and after the list of error message(s). Leave blank to disable. ', 'usp');
	echo __('Note that individual errors may be customized further via the &ldquo;More&rdquo; settings.', 'usp') .'</p>'; 
}
function section_advanced_6_desc() { 
	echo '<p>'. __('Here you may customize input labels for primary form fields (i.e., those that have their own quicktag in the USP Form Editor). ', 'usp');
	echo __('These names are used for contact-form custom-fields, and elsewhere. Note: to customize error messages for primary fields, visit the &ldquo;More&rdquo; settings.', 'usp') .'</p>'; 
}
function section_advanced_7_desc() { 
	echo '<p>'. __('Here you may customize input labels for the optional set of user-registration fields (available when the option to &ldquo;auto-register users&rdquo; is enabled). ', 'usp');
	echo __('These names are used for contact-form custom-fields, and elsewhere. Note: to customize error messages for user-registration fields, visit the &ldquo;More&rdquo; settings.', 'usp') .'</p>'; 
}
function section_advanced_8_desc() { 
	echo '<p>'. __('Here you may specify the maximum number of custom fields that will be used by any form. The number specifed below is used for two things: 1) it determines how many custom fields are added to newly created ', 'usp');
	echo __('forms, and 2) it determines how many options to generate for the next group of settings, &ldquo;Custom Field Names&rdquo;. So for example, if three custom form fields are enabled, all new forms will be equipped ', 'usp');
	echo __('with three custom form fields, each with its own customizable field label (in the &ldquo;Custom Field Names&rdquo; settings). In this example, it would be possible manually to add a fourth custom field to a form, ', 'usp');
	echo __('however its corresponding field label would not exist, causing the default label to be used for error messages and elsewhere. Best advice is to set the number of forms as low as possible, and then increase it as ', 'usp');
	echo __('needed in the future. Note also that unused custom form fields are perfectly fine; the idea is to have them readily available as needed.', 'usp') . '</p>'; 
}
function section_advanced_9_desc() {
	echo '<p>'. __('Here you may specify names for the optional set of custom-field inputs. These names are used for error messages, contact-form custom-fields, and elsewhere. ', 'usp');
	echo '<strong>'. __('Important:', 'usp') .' </strong> '. __('these names apply only to custom fields that are named numerically. For example, the &ldquo;Name for Custom Field #1&rdquo; applies to any custom field that uses', 'usp');
	echo ' <code>name#1|for#1</code> '. __('as its', 'usp') .' <code>name</code> '. __('and', 'usp') .' <code>for</code> '. __('attributes.', 'usp') .'</p>'; 
}
function section_advanced_10_desc() { 
	echo '<p>'. __('Here you may specify a unique prefix to use for custom field names. For example, if you specify &ldquo;foo_&rdquo; for this setting, you can create unique custom fields by including the parameter &ldquo;name#foo_whatever&rdquo; in your custom-field definition. ', 'usp');
	echo __('Note that the custom prefix may contain lowercase/uppercase alphanumeric characters, plus underscores and dashes.', 'usp') .' <strong>'. __('Important:', 'usp') .'</strong> '. __('do not use &ldquo;usp-&rdquo; or &ldquo;usp_&rdquo; for the custom prefix (these are reserved for default custom fields).', 'usp') .'</p>';
}
function section_advanced_11_desc() { 
	echo '<p>'. __('Here you may specify any of your own custom field names (separated by commas). Note that there are two types of fields, optional and required. Required fields will trigger an error if empty when the form is submitted, whereas optional fields will not trigger an error. ', 'usp');
	echo __('Note that custom field names may contain lowercase/uppercase alphanumeric characters, plus underscores and dashes. ', 'usp') .'<strong>' . __('Important:', 'usp') . '</strong> ' . __('your custom field names must NOT begin with &ldquo;usp-&rdquo; or &ldquo;usp_&rdquo; (these are reserved for default custom fields).', 'usp') .'</p>';
}
function section_advanced_12_desc() { 
	echo '<p>'. __('Here you may define custom labels to use for any', 'usp') .' <em>'. __('custom', 'usp') .'</em> '. __('custom fields. These labels are used for error messages, contact-form custom-fields, and elsewhere. ', 'usp');
	echo __('Note: to add some custom field names, first enter some custom fields in the previous setting and click save; name fields will be automatically generated for any custom custom fields.', 'usp') .'</p>';
}
// MORE TAB
function section_more_0_desc() {
	echo '<p class="intro">'. __('Here you may customize error messages, set default form fields, and restore default options.', 'usp') .'</p>'; 
}
function section_more_1_desc() { 
	echo '<p>'. __('Here you may customize the text and markup used to display various error messages.', 'usp') . '</p>'; 
}
function section_more_2_desc() { 
	echo '<p>'. __('Here you may customize the text and markup used to display error messages for primary form fields. Primary fields are form fields that have their own shortcodes, as described for each of the following settings.', 'usp') . '</p>'; 
}
function section_more_3_desc() { 
	echo '<p>'. __('Here you may customize the text and markup used to display error messages related to form submission. This includes several errors related to user registration and post-submission, as described below.', 'usp') . '</p>'; 
}
function section_more_4_desc() { 
	echo '<p>'. __('Here you may customize the text and markup used to display error messages for file uploads.', 'usp') . '</p>'; 
}
function section_more_5_desc() { 
	echo '<p>'. __('Here you may customize the text and markup used to display error messages for custom user-registration fields (Nicename, Display Name, Description, et al).', 'usp') . '</p>'; 
}
function section_more_6_desc() { 
	echo '<p>'. __('Here you may specify custom default values for the post title and post content. This enables you to exclude title and content fields on the form and just use these values instead. ', 'usp');
	echo __('Note that default form fields also may be specified on a per-form basis by', 'usp') .' <a target="_blank" href="https://plugin-planet.com/usp-pro-set-values-with-hidden-fields/">setting values with hidden fields</a>.</p>'; 
}
function section_more_7_desc() { 
	echo '<p><strong>'. __('Important: ', 'usp') .'</strong> '. __('To restore all default options follow these steps:', 'usp') .'</p>'; 
	echo '<ol>';
	echo '<li>'. __('Check the box below and click &ldquo;Save Changes&rdquo;.', 'usp') .'</li>';
	echo '<li><a href="'. get_admin_url() .'options-general.php?page=usp_options&tab=usp_license">'. __('Deactivate your USP License', 'usp') .'</a> '. __('(make a note of your License Key before deactivation; you will need it to reactivate the plugin).', 'usp') .'</li>';
	echo '<li>'. __('Deactivate and then reactivate the plugin to make it so.', 'usp') .'</li>';
	echo '</ol>';
	echo '<p><strong>'. __('Note: ', 'usp') .'</strong> '. __('restoring default plugin options does not affect any Custom Post Type data.', 'usp') .'</p>';
}
// TOOLS TAB
function section_tools_desc() {
	echo '<p class="intro">'. __('Here you will find a quick-start guide, shortcodes, template tags, and other helpful resources.', 'usp') .'</p>'; 
	
	echo '<h3><a id="usp-toggle-s1" class="usp-toggle-s1" href="#usp-toggle-s1" title="'. __('Show/Hide Intro', 'usp') .'">' . __('Intro / Quick Start', 'usp') . '</a></h3>';
	echo '<div class="usp-s1 usp-toggle">' . usp_tools_intro() . '</div>';
	
	echo '<h3><a id="usp-toggle-s2" class="usp-toggle-s2" href="#usp-toggle-s2" title="'. __('Show/Hide Shortcodes Info', 'usp') .'">' . __('Shortcodes', 'usp') . '</a></h3>';
	echo '<div class="usp-s2 usp-toggle default-hidden">' . usp_tools_shortcodes() . '</div>';
	
	echo '<h3><a id="usp-toggle-s3" class="usp-toggle-s3" href="#usp-toggle-s3" title="'. __('Show/Hide Template Tags Info', 'usp') .'">' . __('Template Tags', 'usp') . '</a></h3>';
	echo '<div class="usp-s3 usp-toggle default-hidden">' . usp_tools_tags() . '</div>';
	
	echo '<h3><a id="usp-toggle-s4" class="usp-toggle-s4" href="#usp-toggle-s4" title="'. __('Show/Hide Helpful Resources', 'usp') .'">' . __('Helpful Resources', 'usp') . '</a></h3>';
	echo '<div class="usp-s4 usp-toggle default-hidden">' . usp_tools_resources() . '</div>';
	
	echo '<h3><a id="usp-toggle-s5" class="usp-toggle-s5" href="#usp-toggle-s5" title="'. __('Show/Hide Tips &amp; Tricks', 'usp') .'">' . __('Tips &amp; Tricks', 'usp') . '</a></h3>';
	echo '<div class="usp-s5 usp-toggle default-hidden">' . usp_tools_tips() . '</div>';
	
	echo '<h3><a id="usp-toggle-s6" class="usp-toggle-s6" href="#usp-toggle-s6" title="'. __('Backup &amp; Restore Settings', 'usp') .'">' . __('Backup &amp; Restore', 'usp') . '</a></h3>';
	usp_display_options_page();
}
// ABOUT TAB
function section_about_desc() {
	echo '<p class="intro">'. __('About USP Pro, WordPress, the server and current user.', 'usp') .'</p>';
	
	echo '<h3><a id="usp-toggle-s1" class="usp-toggle-s1" href="#usp-toggle-s1" title="'. __('Show/Hide Plugin Info', 'usp') .'">' . __('Plugin Information', 'usp') . '</a></h3>';
	echo '<div class="usp-s1 usp-toggle">' . usp_about_plugin() . '</div>';
	
	echo '<h3><a id="usp-toggle-s2" class="usp-toggle-s2" href="#usp-toggle-s2" title="'. __('Show/Hide WordPress Info', 'usp') .'">' . __('WordPress Information', 'usp') . '</a></h3>';
	echo '<div class="usp-s2 usp-toggle default-hidden">' . usp_about_wp() . '</div>';
	
	echo '<h3><a id="usp-toggle-s3" class="usp-toggle-s3" href="#usp-toggle-s3" title="'. __('Show/Hide WP Contants Info', 'usp') .'">' . __('WordPress Contants', 'usp') . '</a></h3>';
	echo '<div class="usp-s3 usp-toggle default-hidden">' . usp_about_constants() . '</div>';
	
	echo '<h3><a id="usp-toggle-s4" class="usp-toggle-s4" href="#usp-toggle-s4" title="'. __('Show/Hide Server Info', 'usp') .'">' . __('Server Information', 'usp') . '</a></h3>';
	echo '<div class="usp-s4 usp-toggle default-hidden">' . usp_about_server() . '</div>';
	
	echo '<h3><a id="usp-toggle-s5" class="usp-toggle-s5" href="#usp-toggle-s5" title="'. __('Show/Hide User Info', 'usp') .'">' . __('User Information', 'usp') . '</a></h3>';
	echo '<div class="usp-s5 usp-toggle default-hidden">' . usp_about_user() . '</div>';
}
