<?php // USP Pro - Tools (Settings Tab)

if (!defined('ABSPATH')) die();

/*
	Tools - Intro/Quick Start
*/
if (!function_exists('usp_tools_intro')) : 
function usp_tools_intro() {
	$tools_intro = '<p>' . __('USP Pro lets you create customized front-end submission forms for post content, user registration, contact forms, and much more. ', 'usp');
	$tools_intro .= __('The default plugin settings are optimized for quick implementation, and provide options to help you configure and customize just about everything. ', 'usp');
	$tools_intro .= __('To get started, follow this quick-start guide to add one of the demo forms to your site. After seeing how it works, revisit this screen for shortcodes, template tags, and further resources.', 'usp') . '</p>';
	
	$tools_intro .= '<p><strong>' . __('To add a USP Form to your site:', 'usp') . '</strong></p><ol>';
	$tools_intro .= '<li>' . __('From the WP menu, visit &ldquo;USP Forms&rdquo;', 'usp') . '</li>';
	$tools_intro .= '<li>' . __('From the &ldquo;Shortcodes&rdquo; column, copy the shortcode of the form you would like to display', 'usp') . '</li>';
	$tools_intro .= '<li>' . __('Add the shortcode to any Post or Page to display the form', 'usp') . '</li>';
	$tools_intro .= '</ol>';
	
	$tools_intro .= '<p><strong>' . __('To create a new USP Form:', 'usp') . '</strong></p><ol>';
	$tools_intro .= '<li>' . __('From the WP menu, visit &ldquo;USP Forms&rdquo;', 'usp') . '</li>';
	$tools_intro .= '<li>' . __('At the top of the page, click the &ldquo;Add New&rdquo; button', 'usp') . '</li>';
	$tools_intro .= '<li>' . __('Give the form a title and then use the shortcode quicklinks to add some input fields', 'usp') . '</li>';
	$tools_intro .= '<li>' . __('Once the form is complete, preview and then publish just like a regular WP post', 'usp') . '</li>';
	$tools_intro .= '<li>' . __('Once your new form is published, follow the steps above to add the form to any post or page', 'usp') . '</li>';
	$tools_intro .= '</ol>';
	
	$tools_intro .= '<p>' . __('To go further with USP Pro, visit the', 'usp') . ' <a href="https://plugin-planet.com/docs/usp/" target="_blank">USP Docs</a> or explore the shortcodes and template tags provided below.</p>';
	return $tools_intro;
}
endif;

/*
	Tools - Shortcodes
*/
if (!function_exists('usp_tools_shortcodes')) : 
function usp_tools_shortcodes() {
	$tools_shortcodes = '<p>' . __('USP Pro provides shortcodes that make it easy to display forms and submitted content virtually anywhere. ', 'usp');
	$tools_shortcodes .= __('To get started,', 'usp') . ' <a href="http://codex.wordpress.org/Shortcode_API" target="_blank">' . __('learn how to use Shortcodes', 'usp') . '</a> ';
	$tools_shortcodes .= __('and then include USP Shortcodes as needed in your Posts and Pages.', 'usp') .'</p>';
	$tools_shortcodes .= '<p><a href="https://plugin-planet.com/usp-pro-shortcodes/" target="_blank">'. __('Check out the complete list of USP Shortcodes &raquo;', 'usp') .'</a></p>';
	$tools_shortcodes .= '<p>' . __('In addition to those provided by USP Pro, there are numerous', 'usp') . ' <a href="http://codex.wordpress.org/Shortcode" target="_blank">' . __('default WP shortcodes', 'usp') . '</a>, ';
	$tools_shortcodes .= __('as well as any shortcodes that may be included with your theme and/or plugin(s). Also, FYI, more information about shortcodes may be found in the USP source code (as inline comments), ', 'usp');
	$tools_shortcodes .= __('specifically see', 'usp') . ' <code>/inc/usp-functions.php</code>.</p>';
	return $tools_shortcodes;	
}
endif;

/*
	Tools - Template Tags
*/
if (!function_exists('usp_tools_tags')) : 
function usp_tools_tags() {
	
	$tools_tags = '<p>' . __('USP Pro provides template tags for displaying submitted post content, author information, file uploads and more. ', 'usp');
	$tools_tags .= __('To get started,', 'usp') . ' <a href="http://codex.wordpress.org/Template_Tags" target="_blank">' . __('learn how to use Template Tags', 'usp') . '</a> ';
	$tools_tags .= __('and then include USP Template Tags as needed in your theme template.', 'usp') . '</p>';
	$tools_tags .= '<p><a href="https://plugin-planet.com/usp-pro-template-tags/" target="_blank">'. __('Check out the complete list of USP Template Tags &raquo;', 'usp') .'</a></p>';
	$tools_tags .= '<p>' . __('In addition to those provided by USP Pro, there are a great many template tags provided by WordPress, making it possible to display just about any information anywhere on your site. ', 'usp');
	$tools_tags .= __('Also, FYI, more information about each of these template tags may be found in the USP source code (as inline comments), specifically see', 'usp') . ' <code>/inc/usp-functions.php</code>.</p>';
	
	return $tools_tags;
}
endif;

/*
	Tools - Helpful Resources
*/
if (!function_exists('usp_tools_resources')) : 
function usp_tools_resources() {
	$tools_resources = '<h4>'. __('Useful resources and places to get help with USP Pro:', 'usp') .'</h4>';
	$tools_resources .= '<ul>';
	$tools_resources .= '<li><a target="_blank" href="https://plugin-planet.com/docs/usp/">'.                         __('USP Pro Docs', 'usp')                                 .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://plugin-planet.com/tuts/">'.                             __('USP Pro Tutorials', 'usp')                            .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://plugin-planet.com/forum/usp/">'.                        __('USP Pro Forum', 'usp')                                .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://plugin-planet.com/news/">'.                             __('USP Pro News', 'usp')                                 .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://plugin-planet.com/usp-pro/#contact">'.                  __('Bug reports, help requests, and feedback', 'usp')     .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://plugin-planet.com/wp/wp-login.php">'.                   __('Log in to your account for current downloads', 'usp') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://digwp.com/2011/09/where-to-get-help-with-wordpress/">'. __('Where to Get Help with WordPress', 'usp')             .'</a></li>';
	$tools_resources .= '</ul>';
	$tools_resources .= '<h4>'. __('Key resources at the WordPress Codex:', 'usp') .'</h4>';
	$tools_resources .= '<ul>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/Templates">'.         __('WP Theme Templates', 'usp')       .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/WordPress_Widgets">'. __('WP Widgets', 'usp')               .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/Shortcode_API">'.     __('WP Shortcodes', 'usp')            .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/Template_Tags">'.     __('WP Template Tags', 'usp')         .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/Quicktags_API">'.     __('WP Quicktags', 'usp')             .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/Post_Types">'.        __('WP Custom Post Types', 'usp')     .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/The_Loop">'.          __('The WordPress Loop', 'usp')       .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://codex.wordpress.org/Troubleshooting">'.   __('WP Troubleshooting Guide', 'usp') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="http://www.wordpress.org/support/">'.            __('WP Help Forum', 'usp')            .'</a></li>';
	$tools_resources .= '</ul>';
	$tools_resources .= '<h4>'. __('Other WordPress material by the author of USP Pro:', 'usp') .'</h4>';
	$tools_resources .= '<ul>';
	$tools_resources .= '<li><a target="_blank" href="https://digwp.com/">'.                        __('Digging Into WordPress, by Chris Coyier and Jeff Starr', 'usp')                      .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://wp-tao.com/">'.                       __('The Tao of WordPress &ndash; Complete guide for users, admins, and everyone', 'usp') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://wp-tao.com/wordpress-themes-book/">'. __('WordPress Themes In Depth &ndash; Complete guide to building awesome themes', 'usp') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://htaccessbook.com/">'.                 __('.htaccess made easy &ndash; Configure, optimize, and secure your site', 'usp')       .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://perishablepress.com/">'.              __('Perishable Press &ndash; WordPress, Web Design, Code &amp; Tutorials', 'usp')        .'</a></li>';
	$tools_resources .= '<li><a target="_blank" href="https://wp-mix.com/">'.                       __('WP-Mix &ndash; Useful code snippets for WordPress and more', 'usp')                  .'</a></li>';
	$tools_resources .= '</ul>';
	return $tools_resources;	
}
endif;

/*
	Tools - Tips & Tricks
*/
if (!function_exists('usp_tools_tips')) : 
function usp_tools_tips() {
	$tools_tips = '<p>' . __('Here is a growing collection of useful notes, tips &amp; tricks for working with USP Pro.', 'usp') . '</p>';
	$tools_tips .= '<dl>';
	$tools_tips .= '<dt>' . __('Post Type Bug Fix', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('As explained in the ', 'usp') . '<a href="http://codex.wordpress.org/Taxonomies#404_Error" target="_blank">WP Codex</a>' . __(', an extra step is required to get WordPress to recognize theme templates for custom post types ', 'usp');
	$tools_tips .= __('(e.g., &ldquo;single-post_type.php&rdquo; and &ldquo;archive-post_type.php&rdquo;). So if/when you get a 404 &ldquo;Not Found&rdquo; error when trying to view a custom post type ', 'usp');
	$tools_tips .= __('(e.g., at &ldquo;/usp_post/example/&rdquo;), try the well-known fix, which is to simply', 'usp') . ' <a href="' . get_admin_url() . 'options-permalink.php" target="_blank">' . __('visit the WP Permalinks Settings', 'usp') . '</a>. ';
	$tools_tips .= __('After doing that, things should be working normally again. If not, try clicking the &ldquo;Save Changes&rdquo; button on the Permalink Settings page, which is another reported solution. ', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('Template Tags Best Practice', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('When including template tags provided by a plugin or theme, it&rsquo;s good practice to precede the tag with a conditional check to make sure that the function exists. For example: ', 'usp');
	$tools_tips .= __('Instead of writing this:', 'usp') . ' <code>echo usp_get_images();</code>, ' . __('we can write this:', 'usp') . ' <code>if (function_exists(&quot;usp_get_images&quot;)) echo usp_get_images();</code>. ';
	$tools_tips .= __('The first method works fine, but PHP will throw an error if the plugin is not installed or otherwise available. So to avoid the site-breaking error, the second method is preferred.', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('Force Forms to Clear Contents', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('If you are savvy with CSS, it is trivial to style forms however and get them to clear preceding/parent elements. If you&rsquo;re new to the game and just want a sure-fire way to get form fields to line up ', 'usp');
	$tools_tips .= __('and look right, here is a well-known snippet of HTML/CSS that you can add to any form:', 'usp') . ' <code>&lt;div style="clear:both;"&gt;&lt;/div&gt;</code>. ' . __('Just add that snippet after the last item in your form.', 'usp');
	$tools_tips .= __('It&rsquo;s not exactly best-practices design-wise, but it&rsquo;s pretty much guaranteed to do the job. Then later on you can replace the snippet with some proper CSS.', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('Minimum Posting Requirements', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('There are basically four types of USP Forms: user-registration, content posting, contact form, and combo registration/posting. The minimum form requirements (in terms of input fields) for the contact form are ', 'usp');
	$tools_tips .= __('email, subject, and content. The minimum requirements for user registration are name and email address. The minimum for content posting is the content/textarea field. For the combo registration/posting, the minimum ', 'usp');
	$tools_tips .= __('requirements are determined by the plugin settings. Likewise, other requirements may vary depending on how the plugin settings have been configured.', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('Shortcodes in Widgets', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('By default, shortcodes do not work when included in widgets. To make them work, just add this snippet to your theme&rsquo;s', 'usp') . ' <code>functions.php</code> ' . __('file:', 'usp');
	$tools_tips .= ' <code>add_filter(&quot;widget_text&quot;, &quot;do_shortcode&quot;);</code> ' . __('Nothing more to do, but remember to re-add the snippet if/when you change themes.', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('On Install, On Uninstall', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('Just FYI: when USP Pro is installed it creates four new options in the wp_options table. That&rsquo;s it. No new database tables are created. While the plugin is active, new content (post data, user info) may ', 'usp');
	$tools_tips .= __('be added to the database, but no other changes are made anywhere by the plugin. Lastly, when the plugin is uninstalled (deleted from the server), the four options it created are removed from the database. ', 'usp');
	$tools_tips .= __('Note that the plugin does not delete any posted/submitted content or registered users. If any posts or users were added, it is up to the admin whether or not to remove them.', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('Alternate Way to Reset Form', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('USP Pro includes a shortcode/quicktag for a link that will reset the form. To use a button instead, add this code to any form:', 'usp') . ' <code>&lt;input type="reset" value="Reset all form values"&gt;</code>';
	$tools_tips .= __('Note that the shortcode requires the form URL as one of its attributes, but the reset <code>&lt;input&gt;</code> tag works without requiring any URL.', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('Custom Field Recipes', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('USP Pro provides unlimited custom fields that may be configured to be virtually any type of input field. Here is a list of custom-field recipes for various types of form elements:', 'usp');
	$tools_tips .= '<ul>';
	$tools_tips .= '<li>Textarea &ndash; <code>field#textarea</code></li>';
	$tools_tips .= '<li>Text input &ndash; <code>type#text|placeholder#Orange|label#Orange</code></li>';
	$tools_tips .= '<li>Radio select &ndash; <code>type#radio|name#1|for#1|value#Oranges</code></li>';
	$tools_tips .= '<li>Checkbox &ndash; <code>type#checkbox|value#Oranges</code></li>';
	$tools_tips .= '<li>Password input &ndash; <code>type#password</code></li>';
	$tools_tips .= '<li>Email input &ndash; (use <code>usp_email</code> shortcode)</li>';
	$tools_tips .= '<li>Select/option field &ndash; (see next section for manual configuration)</li>';
	$tools_tips .= '<li>Other fields &ndash; (use available shortcodes)</li>';
	$tools_tips .= '</ul></dd>';
	
	$tools_tips .= '<dt>' . __('Add a Select Field', 'usp') . '</dt>';
	$tools_tips .= '<dd><p>' . __('To add a <code>&lt;select&gt;</code> field to any USP Form, include the following code and customize as needed:', 'usp') . '</p>';
	$tools_tips .= '<p><code>&lt;select name="usp-custom-1"&gt;</code>' . '<br />';
	$tools_tips .= '<code>&lt;option value="apples"&gt;Apples&lt;/option&gt;</code>' . '<br />';
	$tools_tips .= '<code>&lt;option value="oranges"&gt;Oranges&lt;/option&gt;</code>' . '<br />';
	$tools_tips .= '<code>&lt;option value="bananas"&gt;Bananas&lt;/option&gt;</code>' . '<br />';
	$tools_tips .= '<code>&lt;/select&gt;</code></p>';
	$tools_tips .= '<p>' . __('Note that as written, this example creates a single-item select field. To create a multiple-item select field, just change the first line to this:', 'usp') . '</p>';
	$tools_tips .= '<p><code>&lt;select name="usp-custom-1[]" multiple="multiple"&gt;</code></p>';
	$tools_tips .= '<p>' . __('Then to make it a required form field, add this line after the select input:', 'usp') . '</p>';
	$tools_tips .= '<p><code>&lt;input name="usp-custom-1-required" value="1" type="hidden" /&gt;</code></p>';
	$tools_tips .= '<p>' . __('Other markup/tags may be added as needed; for example, you may want to add a <code>&lt;fieldset&gt;</code> and/or <code>&lt;label&gt;</code> element. ', 'usp');
	$tools_tips .= __('The key thing to keep in mind is that the select tag&rsquo;s name must correspond with an existing custom form field (e.g., <code>usp-custom-1</code>).', 'usp') . '</p></dd>';
	$tools_tips .= '</dl>';
	
	$tools_tips .= '<dt>' . __('Custom CSS and JavaScript for Forms', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('Via the &ldquo;CSS/JS&rdquo; settings tab, it&rsquo;s possible to add custom CSS and/or JavaScript to all USP Forms. It&rsquo;s also possible to add CSS/JS on a per-post basis using custom fields. ', 'usp');
	$tools_tips .= __('For example, to add some custom JavaScript to a form, create a new custom field named &ldquo;usp-js&rdquo; and add your JavaScript code. Likewise for CSS, just create a custom field named &ldquo;usp-css&rdquo; ', 'usp');
	$tools_tips .= __('and add whatever CSS is required. Note that custom CSS is automatically wrapped with <code>&lt;style&gt;</code> tags and custom JavaScript is automatically wrapped with <code>&lt;script&gt;</code> tags.', 'usp') . '</dd>';
	
	$tools_tips .= '<dt>' . __('Custom CSS and JavaScript for Posts', 'usp') . '</dt>';
	$tools_tips .= '<dd>' . __('Using custom form-fields, it&rsquo;s possible to include/display custom content (aka &ldquo;art-directed&rdquo; content) with any/all submitted posts. For more information, check out the', 'usp');
	$tools_tips .= ' <code>usp_get_art_directed()</code> ' . __('function in the Template Tags section on this page. The art-directed custom fields work whether included via submitted post or added manually to posts.', 'usp') . '</dd>';
	
	return $tools_tips;	
}
endif;


