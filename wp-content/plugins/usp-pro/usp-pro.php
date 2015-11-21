<?php 
/*
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
	
	License: USP Pro is comprised of two parts:
	
	* Part 1: Its PHP code is licensed under the GPL (v2 or later), like WordPress. More info @ http://www.gnu.org/licenses/
	* Part 2: Everything else (e.g., CSS, HTML, JavaScript, images, design) is licensed according to the purchased license. More info @ https://plugin-planet.com/usp-pro/
	
	Without prior written consent from Monzilla Media, you must NOT directly or indirectly: license, sub-license, sell, resell, or provide for free any aspect or component of Part 2.
	
	Further license information is available in the plugin directory, /license/, and online @ https://plugin-planet.com/wp/files/usp-pro/license.txt
	
	Upgrades: Your purchase of USP Pro includes free lifetime upgrades, which include new features, bug fixes, and other improvements. 
	
	Copyright: © 2015 Monzilla Media
*/

if (!defined('ABSPATH')) die();

define('USP_NAME', 'USP Pro');
define('USP_VERSION', '2.1');
define('USP_REQUIRES', '3.9');
define('USP_TESTED', '4.2');
define('USP_AUTHOR', 'Jeff Starr');
define('USP_URL', 'https://plugin-planet.com');
define('USP_DOMAIN', '/languages/');
define('USP_PATH', WP_PLUGIN_DIR . '/usp-pro');
define('USP_FILE', plugin_basename(__FILE__));

if (!class_exists('USP_Pro')) {
	class USP_Pro {
		private $settings_about    = 'usp_about';
		private $settings_admin    = 'usp_admin';
		private $settings_advanced = 'usp_advanced';
		private $settings_general  = 'usp_general';
		private $settings_license  = 'usp_license';
		private $settings_style    = 'usp_style';
		private $settings_tools    = 'usp_tools';
		private $settings_uploads  = 'usp_uploads';
		private $settings_more     = 'usp_more';
		
		private $settings_page = 'usp_options';
		private $settings_tabs = array();

		public function __construct() {
			add_action('init', array(&$this, 'load_settings'));
			add_action('admin_init', array(&$this, 'register_general_settings'));
			add_action('admin_init', array(&$this, 'register_style_settings'));
			add_action('admin_init', array(&$this, 'register_uploads_settings'));
			add_action('admin_init', array(&$this, 'register_admin_settings'));
			add_action('admin_init', array(&$this, 'register_advanced_settings'));
			add_action('admin_init', array(&$this, 'register_more_settings'));
			add_action('admin_init', array(&$this, 'register_tools_settings'));
			add_action('admin_init', array(&$this, 'register_about_settings'));
			add_action('admin_init', array(&$this, 'register_license_settings'));
			add_action('admin_init', array(&$this, 'register_post_status'));
			
			if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
				add_action('admin_init', array(&$this, 'require_wp_version'));
			}
			add_action('admin_menu', array(&$this, 'add_admin_menus'));
			add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_scripts'));
			add_action('plugins_loaded', array(&$this, 'usp_i18n_init'));
			add_action('parse_query', array(&$this, 'add_status_clause'));
			add_action('restrict_manage_posts', array(&$this, 'add_post_filter_button'));
			add_action('admin_print_styles', array(&$this, 'add_admin_styles'));

			add_filter('plugin_action_links', array(&$this, 'plugin_link_settings'), 10, 2);
			add_filter('post_stati', array(&$this, 'add_post_status'));
			add_filter('the_author', array(&$this, 'usp_replace_author'));
			add_filter('author_link', array(&$this, 'usp_replace_author_link'), 10, 3);
			add_filter('get_the_author_display_name', array(&$this, 'usp_replace_author_display_name'));
			add_filter('get_the_author_user_url', array(&$this, 'usp_replace_author_user_url'));
			
			require_once(sprintf("%s/inc/usp-shortcodes.php", dirname(__FILE__)));
			$USP_Custom_Fields = new USP_Custom_Fields();
			require_once(sprintf("%s/inc/usp-forms.php", dirname(__FILE__)));
			$USP_Pro_Forms = new USP_Pro_Forms();
			require_once(sprintf("%s/inc/usp-posts.php", dirname(__FILE__)));
			$USP_Pro_Posts = new USP_Pro_Posts();
			require_once(sprintf("%s/inc/usp-process.php", dirname(__FILE__)));
			$USP_Pro_Process = new USP_Pro_Process();
			
			require_once(sprintf("%s/inc/usp-functions.php", dirname(__FILE__)));
			require_once(sprintf("%s/inc/usp-about.php", dirname(__FILE__)));
			require_once(sprintf("%s/inc/usp-tools.php", dirname(__FILE__)));
			require_once(sprintf("%s/inc/usp-settings.php", dirname(__FILE__)));
			require_once(sprintf("%s/inc/usp-backup.php", dirname(__FILE__)));
			require_once(sprintf("%s/updates/usp-updates.php", dirname(__FILE__)));
		}
		public static function deactivate() { 
			flush_rewrite_rules();
		}
		public static function activate() {
			require_once(sprintf("%s/inc/usp-forms.php", dirname(__FILE__)));
			USP_Pro_Forms::create_post_type();
			flush_rewrite_rules();
			require_once(sprintf("%s/inc/usp-posts.php", dirname(__FILE__)));
			USP_Pro_Posts::create_post_type();
			flush_rewrite_rules();
			
			$role_obj = get_role('administrator');
			$caps_form = USP_Pro_Forms::default_caps();
			$caps_post = USP_Pro_Posts::default_caps();
			foreach ($caps_form as $cap) $role_obj->add_cap($cap);
			foreach ($caps_post as $cap) $role_obj->add_cap($cap);
		}
		function usp_i18n_init() {
			load_plugin_textdomain('usp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}
		function require_wp_version() {
			global $wp_version;
			$required_min_version = '3.5';
			if (version_compare($wp_version, $required_min_version, '<')) {
				if (is_plugin_active(USP_FILE)) {
					deactivate_plugins(USP_FILE);
					$msg =  '<strong>' . USP_NAME . '</strong> ' . __('requires WordPress ', 'usp') . $required_min_version . __(' or higher, and has been deactivated!', 'usp') . '<br />';
					$msg .= __('Please return to the ', 'usp') . '<a href="' . admin_url() . '">' . __('WordPress Admin area', 'usp') . '</a> ' . __('to upgrade WordPress and try again.', 'usp');
					wp_die($msg);
				}
			}
		}
		function add_post_filter_button() {
			global $pagenow, $typenow, $post_status;
			if (is_admin() && $pagenow == 'edit.php' && $typenow !== 'usp_post' && $typenow !== 'usp_form') {
				if ($post_status == 'trash') $style = 'style="float:right;margin:1px 0 0 8px;"';
				else $style = 'style="float:right;margin:1px 8px 0 0;"';
				echo '<a id="usp-admin-filter" class="button" href="'. admin_url('edit.php?post_status=pending&amp;user_submitted=1') .'" '. $style .'>'. __('USP', 'usp') .'</a>';
			}
		}
		function add_post_status($postStati) {
			$postStati['submitted'] = array(__('Submitted', 'usp'), __('User Submitted Posts', 'usp'), _n_noop('Submitted', 'Submitted'));
			return $postStati;
		}
		function add_status_clause($wp_query) {
			global $pagenow, $usp_general;
			if (intval($usp_general['number_approved']) == -2) $post_status = 'pending';
			else $post_status = 'draft';
			if (isset($_GET['user_submitted']) && $_GET['user_submitted'] == '1') {
				if (is_admin() && $pagenow == 'edit.php') {
					set_query_var('meta_key', 'is_submission');
					set_query_var('meta_value', 1);
					set_query_var('post_status', $post_status);
				}
			}
		}
		function register_post_status(){
			global $usp_general;
			$custom_status = __('Undefined', 'usp');
			if (!empty($usp_general['custom_status'])) {
				$custom_status = $usp_general['custom_status'];
			}
			$enable_status = $usp_general['number_approved'];
			if ($enable_status == -3) {
				register_post_status($custom_status, array(
					'label'                     => $custom_status,
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					'label_count'               => _n_noop($custom_status .' <span class="count">(%s)</span>', $custom_status .' <span class="count">(%s)</span>'),
					// note: Custom Post Status is not yet fully implemented in WP, see: https://codex.wordpress.org/Function_Reference/register_post_status
					// at this point, we can register CPS and use them for submitted posts, but they will not be displayed in the Admin Area until WP adds it	 
				));
			}
		}
		function add_admin_styles() {
			global $pagenow, $current_screen, $post_type;
			$usp_options = false;
			if (isset($_GET['page']) && $_GET['page'] == 'usp_options') $usp_options = true;
			if (is_admin()) {
				if ($usp_options || !empty($current_screen->post_type)) {
					wp_enqueue_style('usp_style_admin', WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) .'/css/usp-admin.css', false, USP_VERSION, 'all');
				}
				if ('usp_form' == $post_type) {
					wp_enqueue_style('usp_quicktags', WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) .'/css/usp-quicktags.css', false, USP_VERSION, 'all');
				}
			}
		}
		function enqueue_admin_scripts($hook) {
			global $post_type;
			if ('usp_form' == $post_type) {
				wp_enqueue_script('usp_quicktags', WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) .'/js/usp-quicktags.js', array('jquery'), USP_VERSION, false);
			}
		}
		function plugin_link_settings($links, $file) {
			if ($file == USP_FILE) {
				$usp_links = '<a href="'. get_admin_url() .'options-general.php?page='. $this->settings_page .'">'. __('Settings', 'usp') .'</a>';
				array_unshift($links, $usp_links);
			}
			return $links;
		}
		function usp_replace_author($author) {
			global $post, $usp_general;
			$is_submission = get_post_meta($post->ID, 'is_submission', true);
			$usp_author    = get_post_meta($post->ID, 'usp-author', true);
			if ($usp_general['replace_author']) {
				if ($is_submission && !empty($usp_author)) return $usp_author;
			}
			return $author;
		}
		function usp_replace_author_link($link, $author_id, $author_nicename) {
			global $post, $usp_general;
			$is_submission = get_post_meta($post->ID, 'is_submission', true);
			$usp_url       = get_post_meta($post->ID, 'usp-url', true);
			if ($usp_general['replace_author']) {
				if ($is_submission && !empty($usp_url)) return $usp_url;
			}
			return $link;
		}
		function usp_replace_author_display_name($value, $user_id = null) {
			global $post, $usp_general;
			if (isset($post)) $is_submission = get_post_meta($post->ID, 'is_submission', true);
			if (isset($post)) $usp_author    = get_post_meta($post->ID, 'usp-author', true);
			if ($usp_general['replace_author']) {
				if (isset($is_submission) && isset($usp_author)) {
					if (!empty($usp_author)) return $usp_author;
				}
			}
			return $value;
		}
		function usp_replace_author_user_url($value, $user_id = null) {
			global $post, $usp_general;
			$is_submission = get_post_meta($post->ID, 'is_submission', true);
			$usp_url       = get_post_meta($post->ID, 'usp-url', true);
			if ($usp_general['replace_author']) {
				if ($is_submission && !empty($usp_url)) return $usp_url;
			}
			return $value;
		}
		function load_settings() {
			$this->admin_settings    = (array) get_option($this->settings_admin);
			$this->advanced_settings = (array) get_option($this->settings_advanced);
			$this->general_settings  = (array) get_option($this->settings_general);
			$this->style_settings    = (array) get_option($this->settings_style);
			$this->uploads_settings  = (array) get_option($this->settings_uploads);
			$this->more_settings     = (array) get_option($this->settings_more);
			//
			$this->admin_settings    = wp_parse_args($this->admin_settings,    $this->admin_defaults());
			$this->advanced_settings = wp_parse_args($this->advanced_settings, $this->advanced_defaults());
			$this->general_settings  = wp_parse_args($this->general_settings,  $this->general_defaults());
			$this->style_settings    = wp_parse_args($this->style_settings,    $this->style_defaults());
			$this->uploads_settings  = wp_parse_args($this->uploads_settings,  $this->uploads_defaults());
			$this->more_settings     = wp_parse_args($this->more_settings,     $this->more_defaults());
		}
		public static function admin_defaults() {
			$user_info = USP_Pro::get_user_infos();
			$defaults = array(
				'admin_email'            => $user_info['admin_email'],
				'admin_from'             => $user_info['admin_email'],
				'admin_name'             => $user_info['admin_name'],
				'cc_submit'              => '',
				'cc_approval'            => '',
				'cc_denied'              => '',
				'send_mail'              => 'wp_mail',
				'mail_format'            => 'text',
				'send_mail_admin'        => 1,
				'send_mail_user'         => 1,
				'send_approval_admin'    => 1,
				'send_approval_user'     => 1,
				'send_denied_user'       => 1, 
				'send_denied_admin'      => 1,
				'post_alert_admin'       => __('New user-submitted post at ', 'usp') . $user_info['admin_name'] . __('! URL: ', 'usp') . $user_info['admin_url'],
				'post_alert_user'        => __('Thank you for your submission at ', 'usp') . $user_info['admin_name'] . __('! If submissions require approval, you\'ll receive an email once it\'s approved.', 'usp'),
				'alert_subject_admin'    => __('New User Submitted Post!', 'usp'),
				'alert_subject_user'     => __('Thank you for your submitted post!', 'usp'),
				'approval_subject'       => __('Submitted Post Approved!', 'usp'),
				'approval_message'       => __('Congratulations, your submitted post has been approved at '. $user_info['admin_name'] .'!', 'usp'),
				'approval_subject_admin' => __('Submitted Post Approved!', 'usp'),
				'approval_message_admin' => __('Congratulations, a submitted post has been approved at '. $user_info['admin_name'] .'!', 'usp'),
				
				'denied_subject'         => __('Submitted Post Denied', 'usp'),
				'denied_message'         => __('Sorry, but your submission has been denied.', 'usp'),
				'denied_subject_admin'   => __('Submitted Post Denied', 'usp'),
				'denied_message_admin'   => __('A submitted post has been denied at '. $user_info['admin_name'], 'usp'),
				
				'contact_sub_prefix'     => __('Message sent from ', 'usp') . $user_info['admin_name'] . ': ',
				'contact_subject'        => __('Email Subject', 'usp'),
				'contact_cc'             => $user_info['admin_email'],
				'contact_cc_user'        => 0,
				'contact_cc_note'        => __('A copy of this message will be sent to the specified email address.', 'usp'),
				'contact_stats'          => 0,
				'contact_from'           => $user_info['admin_email'],
				'contact_custom'         => 1,
				'custom_content'         => '',
				'custom_contact_1'       => '',
				'custom_contact_2'       => '',
				'custom_contact_3'       => '',
				'custom_contact_4'       => '',
				'custom_contact_5'       => '',
				'blacklist_terms'        => '',
			);
			return $defaults;
		}
		public static function advanced_defaults() {
			$defaults = array(
				'enable_autop'       => 0,
				'submit_button'      => 1,
				'submit_text'        => __('Submit Post', 'usp'),
				'html_content'       => '',
				'fieldsets'          => 1,
				'form_demos'         => 1,
				'post_demos'         => 1,
				'post_type'          => 'post',
				'post_type_role'     => array('administrator'), 
				'form_type_role'     => array('administrator'), 
				'other_type'         => '',
				'post_type_slug'     => 'usp_post',
				'form_atts'          => 'data-validate="parsley" data-persist="garlic" novalidate',
				
				'custom_before'      => '<div class="usp-pro-form">',
				'custom_after'       => '</div>',
				'error_before'       => '<div class="usp-errors"><div class="usp-errors-heading"><strong>Important!</strong> Please fix the following issues:</div>',
				'error_after'        => '</div>',
				'success_post'       => __('Success! You have successfully submitted a post.', 'usp'),
				'success_reg'        => __('Congratulations, you have been registered with the site.', 'usp'),
				'success_both'       => __('Registration successful! Post Submission successful! You&rsquo;re golden.', 'usp'),
				'success_contact'    => __('Email sent! We&rsquo;ll get back to you as soon as possible.', 'usp'),
				'success_email_reg'  => __('Registration successful! Email sent! We&rsquo;ll get back to you as soon as possible.', 'usp'),
				'success_email_post' => __('Post Submitted! Email sent! We&rsquo;ll get back to you as soon as possible.', 'usp'),
				'success_email_both' => __('Post Submitted! Registration successful! Email sent! We&rsquo;ll get back to you as soon as possible.', 'usp'),
				'success_before'     => '<div class="usp-success">',
				'success_after'      => '</div>',
				'success_form'       => 0,
				
				'custom_fields'      => 3,
				'custom_names'       => '', // no default for usp_label_c{n}
				'custom_prefix'      => 'prefix_',
				'custom_optional'    => '',
				'custom_required'    => '',
				'custom_field_names' => '', // no default for usp_custom_field_{n}
				
				'usp_error_1'        => __('Your Name', 'usp'),
				'usp_error_2' 	      => __('Post URL', 'usp'),
				'usp_error_3' 	      => __('Post Title', 'usp'),
				'usp_error_4' 	      => __('Post Tags', 'usp'),
				'usp_error_5' 	      => __('Challenge Question', 'usp'),
				'usp_error_6' 	      => __('Post Category', 'usp'),
				'usp_error_7' 	      => __('Post Content', 'usp'),
				'usp_error_8' 	      => __('File(s)', 'usp'),
				'usp_error_9' 	      => __('Email Address', 'usp'),
				'usp_error_10'       => __('Email Subject', 'usp'),
				'usp_error_11'       => __('Alt text', 'usp'), 
				'usp_error_12'       => __('Caption', 'usp'), 
				'usp_error_13'       => __('Description', 'usp'), 
				'usp_error_14'       => __('Taxonomy', 'usp'),
				'usp_error_15'       => __('Post Format', 'usp'),
				'usp_error_16'       => __('Media Title', 'usp'),
				'usp_error_17'       => __('File Name', 'usp'),
				
				'usp_error_a'        => __('User Nicename', 'usp'),
				'usp_error_b'        => __('User Display Name', 'usp'),
				'usp_error_c'        => __('User Nickname', 'usp'),
				'usp_error_d'        => __('User First Name', 'usp'),
				'usp_error_e'        => __('User Last Name', 'usp'),
				'usp_error_f'        => __('User Description', 'usp'),
				'usp_error_g'        => __('User Password', 'usp'),
			);
			return $defaults;
		}
		public static function more_defaults() {
			$defaults = array(
				'tax_before'          => '<div class="usp-error usp-error-taxonomy">' . __('Required field: ', 'usp'),
				'tax_after'           => '</div>',
				'custom_field_before' => '<div class="usp-error usp-error-custom">' . __('Required field: ', 'usp'),
				'custom_field_after'  => '</div>',
				'error_sep'           => '',
				
				'usp_error_1_desc'  => '<div class="usp-error">' . __('Required field: Your Name', 'usp') . '</div>',
				'usp_error_2_desc'  => '<div class="usp-error">' . __('Required field: Post URL', 'usp') . '</div>',
				'usp_error_3_desc'  => '<div class="usp-error">' . __('Required field: Post Title', 'usp') . '</div>',
				'usp_error_4_desc'  => '<div class="usp-error">' . __('Required field: Post Tags', 'usp') . '</div>',
				'usp_error_5_desc'  => '<div class="usp-error">' . __('Required field: Challenge Question', 'usp') . '</div>',
				'usp_error_6_desc'  => '<div class="usp-error">' . __('Required field: Post Category', 'usp') . '</div>',
				'usp_error_7_desc'  => '<div class="usp-error">' . __('Required field: Post Content', 'usp') . '</div>',
				'usp_error_8_desc'  => '<div class="usp-error">' . __('Required field: File(s)', 'usp') . '</div>',
				'usp_error_9_desc'  => '<div class="usp-error">' . __('Required field: Email Address', 'usp') . '</div>',
				'usp_error_10_desc' => '<div class="usp-error">' . __('Required field: Email Subject', 'usp') . '</div>',
				'usp_error_11_desc' => '<div class="usp-error">' . __('Required field: Alt text', 'usp') . '</div>', 
				'usp_error_12_desc' => '<div class="usp-error">' . __('Required field: Caption', 'usp') . '</div>', 
				'usp_error_13_desc' => '<div class="usp-error">' . __('Required field: Description', 'usp') . '</div>', 
				'usp_error_14_desc' => '<div class="usp-error">' . __('Required field: Taxonomy', 'usp') . '</div>',
				'usp_error_15_desc' => '<div class="usp-error">' . __('Required field: Post Format', 'usp') . '</div>',
				'usp_error_16_desc' => '<div class="usp-error">' . __('Required field: Media Title', 'usp') . '</div>',
				'usp_error_17_desc' => '<div class="usp-error">' . __('Required field: File Name', 'usp') . '</div>',
				
				'usp_error_a_desc' => '<div class="usp-error">' . __('Required field: User Nicename', 'usp') . '</div>',
				'usp_error_b_desc' => '<div class="usp-error">' . __('Required field: User Display Name', 'usp') . '</div>',
				'usp_error_c_desc' => '<div class="usp-error">' . __('Required field: User Nickname', 'usp') . '</div>',
				'usp_error_d_desc' => '<div class="usp-error">' . __('Required field: User First Name', 'usp') . '</div>',
				'usp_error_e_desc' => '<div class="usp-error">' . __('Required field: User Last Name', 'usp') . '</div>',
				'usp_error_f_desc' => '<div class="usp-error">' . __('Required field: User Description', 'usp') . '</div>',
				'usp_error_g_desc' => '<div class="usp-error">' . __('Required field: User Password', 'usp') . '</div>',
				
				'error_username' => '<div class="usp-error">' . __('Username already registered. If that is your username, please log in to submit posts. Otherwise, please choose a different username.', 'usp') . '</div>',
				'error_email'    => '<div class="usp-error">' . __('Email already registered. If that is your address, please log in to submit content. Otherwise, please choose a different email address.', 'usp') . '</div>',
				'error_register' => '<div class="usp-error">' . __('User-registration is currently disabled. Please contact the admin for help.', 'usp') . '</div>',
				'user_exists'    => '<div class="usp-error">' . __('You are already registered with this site, please log in to submit content.', 'usp') . '</div>',
				'post_required'  => '<div class="usp-error">' . __('Post-submission required for user registration. Please try again.', 'usp') . '</div>',
				'post_duplicate' => '<div class="usp-error">' . __('Duplicate post title detected. Please enter a unique post title.', 'usp') . '</div>',
				'form_allowed'   => '<div class="usp-error">' . __('Incorrect form type. Please notify the administrator.', 'usp') . '</div>',
				
				'name_restrict'    => '<div class="usp-error">' . __('Restricted characters found in Name field. Please try again.', 'usp') . '</div>',
				'spam_response'    => '<div class="usp-error">' . __('Incorrect response for the spam check. Please try again.', 'usp') . '</div>',
				'content_min'      => '<div class="usp-error">' . __('Minimum number of characters not met in content field. Please try again.', 'usp') . '</div>',
				'content_max'      => '<div class="usp-error">' . __('Number of characters in content field exceeds maximum allowed. Please try again.', 'usp') . '</div>',
				'email_restrict'   => '<div class="usp-error">' . __('Restricted characters found in Email address. Please try again.', 'usp') . '</div>',
				'subject_restrict' => '<div class="usp-error">' . __('Restricted characters found in Subject field. Please try again.', 'usp') . '</div>',
				
				'files_required'  => '<div class="usp-error">' . __('File(s) required. Please check any required file(s) and try again.', 'usp') . '</div>',
				'file_type_not'   => '<div class="usp-error">' . __('File type not allowed. Please check the allowed file types and try again.', 'usp') . '</div>',
				'file_dimensions' => '<div class="usp-error">' . __('Image dimensions (width/height) exceed set limits. Please check the requirements and try again.', 'usp') . '</div>',
				'file_max_size'   => '<div class="usp-error">' . __('Maximum file-size limit exceeded. Please check the file requirements and try again.', 'usp') . '</div>',
				'file_min_size'   => '<div class="usp-error">' . __('Minimum file-size not met. Please check the file requirements and try again.', 'usp') . '</div>',
				'file_required'   => '<div class="usp-error">' . __('File(s) required. Please check any required file(s) and try again.', 'usp') . '</div>',
				'file_name'       => '<div class="usp-error">' . __('Length of filename exceeds allowed limit. Please check the requirements and try again.', 'usp') . '</div>',
				'min_req_files'   => '<div class="usp-error">' . __('Please ensure that you have met the minimum number of required files, and that any specific requirements have been met (e.g., size, dimensions).', 'usp') . '</div>',
				'max_req_files'   => '<div class="usp-error">' . __('Please ensure that you have not exceeded the maximum number of files, and that any specific requirements have been met (e.g., size, dimensions).', 'usp') . '</div>',
				
				'default_options' => 0,
				'default_title'   => 'Post Title',
				'default_content' => 'Post Content',
			);
			return $defaults;
		}
		public static function general_defaults() {
			$user_info = USP_Pro::get_user_infos();
			$defaults = array(
				'number_approved'    => -1,
				'custom_status'      => 'Custom',
				'categories'         => array(get_option('default_category')),
				'hidden_cats'        => 0,
				'cats_menu'          => 'dropdown',
				'cats_multiple'      => 0,
				'cats_nested'        => 1,
				'tags'               => array(),
				'hidden_tags'        => 0,
				'tags_order'         => 'name_asc',
				'tags_number'        => '-1',
				'tags_empty'         => 0,
				'tags_menu'          => 'dropdown',
				'tags_multiple'      => 0,
				'redirect_success'   => '',
				'redirect_failure'   => '',
				'redirect_post'      => 0,
				'enable_stats'       => 0,
				'character_max'      => '0',
				'character_min'      => '0',
				'titles_unique'      => 1,
				'sessions_on'        => 1,
				'sessions_scope'     => 0,
				'sessions_default'   => 1,
				'captcha_question'   => '1 + 1 =',
				'captcha_response'   => '2',
				'captcha_casing'     => 0,
				'recaptcha_public'   => '',
				'recaptcha_private'  => '',
				'assign_role'        => 'subscriber',
				'assign_author'      => $user_info['admin_id'],
				'use_author'         => 0,
				'replace_author'     => 0,
				'use_cat'            => 0,
				'use_cat_id'         => '',
				'submit_form_ids'    => '',
				'register_form_ids'  => '',
				'contact_form'       => 'contact',
				'enable_form_lock'   => 0,
			);
			return $defaults;
		}
		public static function style_defaults() {
			$user_info = USP_Pro::get_user_infos();
			$defaults = array(
				'form_style'    => 'minimal',
				'style_min'     => '.usp-fieldset { border: none; margin: 10px 0; padding: 0; } .usp-label, .usp-input, .usp-select, .usp-textarea { display: block; width: 90%; margin: 5px 0; } .usp-label { font-size: 12px; line-height: 12px; } .usp-input, .usp-textarea { padding: 5px; font: normal 13px/normal sans-serif; } .usp-select { width: auto; } .usp-submit { margin: 10px 0; } .usp-checkbox { display: inline-block; margin-right: 10px; } .usp-checkbox .usp-input { display: inline-block; width: auto; } .usp-input-files { margin: 3px 0; } .usp-add-another { float: left; clear: both; margin-top: 3px; } .usp-errors, .usp-error-warning { margin: 10px 0; } .usp-input-remember, .usp-label-remember { display: inline-block; width: auto; } .usp-preview { clear: both; width: 100%; overflow: hidden; padding-top: 5px; } .usp-preview div { float: left; width: 100px; height: 50px; overflow: hidden; margin: 3px; } .usp-preview a { display: block; width: 100%; height: 100%; }',
				'style_small'   => '.usp-fieldset { border: none; margin: 10px 0; padding: 0; } .usp-label { float: left; width: 20%; font-size: 12px; vertical-align: middle; } .usp-input, .usp-select, .usp-textarea, .usp-input-wrap { float: left; width: 70%; padding: 3px; font-size: 13px; } .usp-select { width: auto; } .usp-submit { margin: 10px 0; } .usp-checkbox { display: block; margin: 0 0 0 30%; font-size: 12px; } .usp-checkbox .usp-input, .usp-input-wrap .usp-input { display: inline-block; width: auto; } .usp-files label { vertical-align: top; padding-top: 10px; } .usp-add-another, .usp-clone { margin: 3px 0; } .usp-input-wrap { padding: 0; } .usp-errors, .usp-error-warning { margin: 10px 0; } .usp-input-remember, .usp-label-remember { display: inline-block; width: auto; } .usp-preview { clear: both; width: 100%; overflow: hidden; padding-top: 5px; } .usp-preview div { float: left; width: 100px; height: 50px; overflow: hidden; margin: 3px; } .usp-preview a { display: block; width: 100%; height: 100%; }',
				'style_large'   => '.usp-fieldset { border: none; margin: 15px 0; padding: 0; } .usp-label, .usp-input, .usp-select, .usp-textarea { display: block; width: 90%; font-size: 14px; } .usp-input, .usp-textarea { padding: 7px; font: normal 16px/normal sans-serif; } .usp-select { width: auto; } .usp-submit { margin: 10px 0; } .usp-checkbox { display: inline-block; margin-right: 10px; } .usp-checkbox .usp-input { display: inline-block; width: auto; } .usp-errors, .usp-error-warning { margin: 10px 0; } .usp-input-remember, .usp-label-remember { display: inline-block; width: auto; } .usp-input-files { width: 90%; margin: 5px 0; } .usp-add-another { float: left; clear: both; margin-top: 5px; } .usp-preview { clear: both; width: 100%; overflow: hidden; padding-top: 5px; } .usp-preview div { float: left; width: 100px; height: 50px; overflow: hidden; margin: 3px; } .usp-preview a { display: block; width: 100%; height: 100%; }',
				'style_custom'  => '.usp-fieldset { border: none; margin: 10px 0; padding: 0; } .usp-label { float: left; width: 20%; padding-top: 5px; font-size: 12px; } .usp-input, .usp-select, .usp-textarea { display: inline-block; width: 70%; } .usp-input, .usp-textarea { padding: 5px; font-size: 14px; } .usp-input-wrap { float: left; width: 70%; } .usp-select { width: auto; } .usp-submit { margin: 10px 0; } .usp-checkbox { display: block; margin: 0 0 0 30%; } .usp-checkbox .usp-input, .usp-input-wrap .usp-input { display: inline-block; width: auto; } .usp-errors, .usp-error-warning { margin: 10px 0; } .usp-input-remember, .usp-label-remember { display: inline-block; width: auto; } .usp-input-files { width: 90%; margin: 3px 0; } .usp-add-another { float: left; clear: both; margin-top: 3px; } .usp-preview { clear: both; width: 100%; overflow: hidden; padding-top: 5px; } .usp-preview div { float: left; width: 100px; height: 50px; overflow: hidden; margin: 3px; } .usp-preview a { display: block; width: 100%; height: 100%; }',
				'include_css'   => 0,
				'include_js'    => 1,
				'script_custom' => '',
				'include_url'   => '',
			);
			return $defaults;
		}
		public static function uploads_defaults() {
			$user_info = USP_Pro::get_user_infos();
			$defaults = array(
				'post_images'      => 'before',
				'min_files'        => 0,
				'max_files'        => 3,
				'max_height'       => 1500,
				'min_height'       => 0,
				'max_width'        => 1500,
				'min_width'        => 0,
				'max_size'         => 5242880, // bytes = 5 MB
				'min_size'         => 5, // = 5 bytes
				'files_allow'      => 'bmp, gif, ico, jpe, jpeg, jpg, png, tif, tiff',
				'featured_image'   => 1,
				'featured_key'     => '1',
				'unique_filename'  => 1,
				'user_shortcodes'  => 0,
				'display_size'     => 'thumbnail',
			);
			return $defaults;
		}
		public static function send_mail_options() {
			$send_mail = array(
				'wp_mail' => array(
					'value' => 'wp_mail',
					'label' => __('Send alert emails using WP&rsquo;s <code>wp_mail()</code> function', 'usp')
				),
				'php_mail' => array(
					'value' => 'php_mail',
					'label' => __('Send alert emails using PHP&rsquo;s <code>mail()</code> function', 'usp')
				),
				'no_mail' => array(
					'value' => 'no_mail',
					'label' => __('Disable email alerts', 'usp')
				),
			);
			return $send_mail;
		}
		public static function mail_format() {
			$mail_format = array(
				'text' => array(
					'value' => 'text',
					'label' => __('Plain-text format (default)', 'usp')
				),
				'html' => array(
					'value' => 'html',
					'label' => __('HTML format', 'usp')
				),
			);
			return $mail_format;
		}
		public static function post_type_options() {
			$post_type = array(
				'post' => array(
					'value' => 'post',
					'label' => __('Regular WP Posts (default)', 'usp'),
				),
				'page' => array(
					'value' => 'page',
					'label' => __('Regular WP Pages', 'usp'),
				),
				'usp_post' => array(
					'value' => 'usp_post',
					'label' => __('USP Posts', 'usp'),
				),
				'other' => array(
					'value' => 'other',
					'label' => __('Existing Post Type', 'usp'),
				),
			);
			return $post_type;
		}
		public static function cats_menu_options() {
			$cats_menu = array(
				'dropdown' => array(
					'value' => 'dropdown',
					'label' => __('Display categories as a dropdown menu (default)', 'usp')
				),
				'checkbox' => array(
					'value' => 'checkbox',
					'label' => __('Display categories as checkboxes (always allows users to select multiple categories)', 'usp')
				),
			);
			return $cats_menu;
		}
		public static function tags_order_options() {
			$tags_order = array(
				'name_asc' => array(
					'value' => 'name_asc',
					'label' => __('Ordered by name, ascending order (default)', 'usp')
				),
				'name_desc' => array(
					'value' => 'name_desc',
					'label' => __('Ordered by name, descending order', 'usp')
				),
				'id_asc' => array(
					'value' => 'id_asc',
					'label' => __('Ordered by tag ID, ascending order', 'usp')
				),
				'id_desc' => array(
					'value' => 'id_desc',
					'label' => __('Ordered by tag ID, descending order', 'usp')
				),
				'count_asc' => array(
					'value' => 'count_asc',
					'label' => __('Ordered by count, ascending order', 'usp')
				),
				'count_desc' => array(
					'value' => 'count_desc',
					'label' => __('Ordered by count, descending order', 'usp')
				),
			);
			return $tags_order;
		}
		public static function tags_menu_options() {
			$tags_menu = array(
				'dropdown' => array(
					'value' => 'dropdown',
					'label' => __('Display tags as a dropdown menu (default)', 'usp')
				),
				'checkbox' => array(
					'value' => 'checkbox',
					'label' => __('Display tags as checkboxes (always allows users to select multiple tags)', 'usp')
				),
				'input' => array(
					'value' => 'input',
					'label' => __('Display tags as a text-input (always allows users to enter multiple tags, separated with commas)', 'usp')
				),
			);
			return $tags_menu;
		}
		public static function style_options() {
			$style_option = array(
				'minimal' => array(
					'value' => 'minimal',
					'label' => __('Clean minimal style (default)', 'usp')
				),
				'small' => array(
					'value' => 'small',
					'label' => __('Smaller form style (e.g., widgets)', 'usp')
				),
				'large' => array(
					'value' => 'large',
					'label' => __('Larger form style', 'usp')
				),
				'custom' => array(
					'value' => 'custom',
					'label' => __('Define custom style', 'usp')
				),
				'disable' => array(
					'value' => 'disable',
					'label' => __('Disable styles', 'usp')
				),
			);
			return $style_option;
		}
		public static function display_images_options() {
			$display_images = array(
				'before' => array(
					'value' => 'before',
					'label' => __('Display uploaded images before post content (default)', 'usp')
				),
				'after' => array(
					'value' => 'after',
					'label' => __('Display uploaded images after post content', 'usp')
				),
				'disable' => array(
					'value' => 'disable',
					'label' => __('Do not auto-display uploaded images', 'usp')
				),
			);
			return $display_images;
		}
		public static function display_size_options() {
			$display_sizes = array(
				'thumbnail' => array(
					'value' => 'thumbnail',
					'label' => __('Thumbnail (default)', 'usp')
				),
				'medium' => array(
					'value' => 'medium',
					'label' => __('Medium', 'usp')
				),
				'large' => array(
					'value' => 'large',
					'label' => __('Large', 'usp')
				),
				'full' => array(
					'value' => 'full',
					'label' => __('Full', 'usp')
				),
			);
			return $display_sizes;
		}
		public static function get_user_infos() {
			global $current_user;
			if ($current_user) $admin_id = $current_user->ID;
			else $admin_id = '1';
			$admin_name  = get_bloginfo('name');
			$admin_email = get_bloginfo('admin_email');
			$admin_url   = home_url();
			$user_info = array('admin_id' => $admin_id, 'admin_name' => $admin_name, 'admin_email' => $admin_email, 'admin_url' => $admin_url);
			return $user_info;
		}
		// GENERAL SETTINGS
		function register_general_settings() {
			$this->settings_tabs[$this->settings_general] = 'General';
			register_setting($this->settings_general, $this->settings_general, array(&$this, 'validate_general'));
			add_settings_section('section_general_0', '', 'section_general_0_desc', $this->settings_general);
			// 1
			add_settings_section('section_general_1', __('Basic Settings', 'usp'), 'section_general_1_desc', $this->settings_general);
			add_settings_field('number_approved',  __('Auto Publish Posts', 'usp'),       array(&$this, 'callback_dropdown'),   $this->settings_general, 'section_general_1', array('id' => 'number_approved',  'type' => 'general'));
			add_settings_field('custom_status',    __('Custom Post Status', 'usp'),       array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_1', array('id' => 'custom_status',    'type' => 'general'));
			add_settings_field('redirect_success', __('Redirect URL for Success', 'usp'), array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_1', array('id' => 'redirect_success', 'type' => 'general'));
			add_settings_field('redirect_failure', __('Redirect URL for Failure', 'usp'), array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_1', array('id' => 'redirect_failure', 'type' => 'general'));
			add_settings_field('redirect_post',    __('Redirect to Post', 'usp'),         array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_1', array('id' => 'redirect_post',    'type' => 'general'));
			add_settings_field('enable_stats',     __('Enable Basic Statistics', 'usp'),  array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_1', array('id' => 'enable_stats',     'type' => 'general'));
			add_settings_field('character_min',    __('Minimum Character Limit', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_1', array('id' => 'character_min',    'type' => 'general'));
			add_settings_field('character_max',    __('Maximum Character Limit', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_1', array('id' => 'character_max',    'type' => 'general'));
			add_settings_field('titles_unique',    __('Unique Post Titles', 'usp'),       array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_1', array('id' => 'titles_unique',    'type' => 'general'));
			add_settings_field('sessions_on',      __('Remember Form Values', 'usp'),     array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_1', array('id' => 'sessions_on',      'type' => 'general'));
			add_settings_field('sessions_scope',   __('Memory Strength', 'usp'),          array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_1', array('id' => 'sessions_scope',   'type' => 'general'));
			add_settings_field('sessions_default', __('Memory Default', 'usp'),           array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_1', array('id' => 'sessions_default', 'type' => 'general'));
			// 2
			add_settings_section('section_general_2', __('User Settings', 'usp'), 'section_general_2_desc', $this->settings_general);
			add_settings_field('assign_author',   __('Default Assigned Author', 'usp'), array(&$this, 'callback_dropdown'), $this->settings_general, 'section_general_2', array('id' => 'assign_author',   'type' => 'general'));
			add_settings_field('assign_role',     __('Default Assigned Role', 'usp'),   array(&$this, 'callback_dropdown'), $this->settings_general, 'section_general_2', array('id' => 'assign_role',     'type' => 'general'));
			add_settings_field('use_author',      __('Use Registered Author', 'usp'),   array(&$this, 'callback_checkbox'), $this->settings_general, 'section_general_2', array('id' => 'use_author',      'type' => 'general'));
			add_settings_field('replace_author',  __('Replace Author', 'usp'),          array(&$this, 'callback_checkbox'), $this->settings_general, 'section_general_2', array('id' => 'replace_author',  'type' => 'general'));
			// 3
			add_settings_section('section_general_3', __('Antispam/Captcha', 'usp'), 'section_general_3_desc', $this->settings_general);
			add_settings_field('captcha_question',  __('Captcha Question', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_3', array('id' => 'captcha_question',  'type' => 'general'));
			add_settings_field('captcha_response',  __('Captcha Response', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_3', array('id' => 'captcha_response',  'type' => 'general'));
			add_settings_field('captcha_casing',    __('Case-sensitivity', 'usp'),  array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_3', array('id' => 'captcha_casing',    'type' => 'general'));
			add_settings_field('recaptcha_public',  __('reCAPTCHA Public', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_3', array('id' => 'recaptcha_public',  'type' => 'general'));
			add_settings_field('recaptcha_private', __('reCAPTCHA Private', 'usp'), array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_3', array('id' => 'recaptcha_private', 'type' => 'general'));
			// 4
			add_settings_section('section_general_4', __('Category Settings', 'usp'), 'section_general_4_desc', $this->settings_general);
			add_settings_field('cats_menu',     __('Category Menu', 'usp'),             array(&$this, 'callback_radio'),      $this->settings_general, 'section_general_4', array('id' => 'cats_menu',     'type' => 'general'));
			add_settings_field('cats_multiple', __('Allow Multiple Categories', 'usp'), array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_4', array('id' => 'cats_multiple', 'type' => 'general'));
			add_settings_field('cats_nested',   __('Nested Categories', 'usp'),         array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_4', array('id' => 'cats_nested',   'type' => 'general'));
			add_settings_field('hidden_cats',   __('Hide Category Field', 'usp'),       array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_4', array('id' => 'hidden_cats',   'type' => 'general'));
			add_settings_field('use_cat',       __('Use Hidden Category', 'usp'),       array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_4', array('id' => 'use_cat',       'type' => 'general'));
			add_settings_field('use_cat_id',    __('Use Hidden Category ID', 'usp'),    array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_4', array('id' => 'use_cat_id',    'type' => 'general'));
			add_settings_field('categories',    __('Post Categories', 'usp'),           array(&$this, 'callback_checkboxes'), $this->settings_general, 'section_general_4', array('id' => 'categories',    'type' => 'general'));
			// 5
			add_settings_section('section_general_5', __('Tag Settings', 'usp'), 'section_general_5_desc', $this->settings_general);
			add_settings_field('tags_menu',     __('Tag Menu', 'usp'),            array(&$this, 'callback_radio'),      $this->settings_general, 'section_general_5', array('id' => 'tags_menu',     'type' => 'general'));
			add_settings_field('tags_multiple', __('Allow Multiple Tags', 'usp'), array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_5', array('id' => 'tags_multiple', 'type' => 'general'));
			add_settings_field('hidden_tags',   __('Hide Tags Field', 'usp'),     array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_5', array('id' => 'hidden_tags',   'type' => 'general'));
			add_settings_field('tags_order',    __('Tag Order', 'usp'),           array(&$this, 'callback_radio'),      $this->settings_general, 'section_general_5', array('id' => 'tags_order',    'type' => 'general'));
			add_settings_field('tags',          __('Post Tags', 'usp'),           array(&$this, 'callback_checkboxes'), $this->settings_general, 'section_general_5', array('id' => 'tags',          'type' => 'general'));
			add_settings_field('tags_number',   __('Number of Tags', 'usp'),      array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_5', array('id' => 'tags_number',   'type' => 'general'));
			add_settings_field('tags_empty',    __('Hide Empty Tags', 'usp'),     array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_5', array('id' => 'tags_empty',    'type' => 'general'));
			// 6
			add_settings_section('section_general_6', __('Form Security', 'usp'), 'section_general_6_desc', $this->settings_general);
			add_settings_field('enable_form_lock',  __('Enable this feature', 'usp'),     array(&$this, 'callback_checkbox'),   $this->settings_general, 'section_general_6', array('id' => 'enable_form_lock',  'type' => 'general'));
			add_settings_field('submit_form_ids',   __('Post-Submission Forms', 'usp'),   array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_6', array('id' => 'submit_form_ids',   'type' => 'general'));
			add_settings_field('register_form_ids', __('User-Registration Forms', 'usp'), array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_6', array('id' => 'register_form_ids', 'type' => 'general'));
			add_settings_field('contact_form',      __('Contact Forms', 'usp'),           array(&$this, 'callback_input_text'), $this->settings_general, 'section_general_6', array('id' => 'contact_form',      'type' => 'general'));
		}
		// STYLE SETTINGS
		function register_style_settings() {
			$this->settings_tabs[$this->settings_style] = 'CSS/JS';
			register_setting($this->settings_style, $this->settings_style, array(&$this, 'validate_style'));
			add_settings_section('section_style_0', '', 'section_style_0_desc', $this->settings_style);
			// 1
			add_settings_section('section_style_1', 'CSS/Styles', 'section_style_1_desc', $this->settings_style);
			add_settings_field('include_css',  __('Include USP Stylesheet', 'usp'), array(&$this, 'callback_checkbox'), $this->settings_style, 'section_style_1', array('id' => 'include_css',  'type' => 'style'));
			add_settings_field('form_style',   __('Select Form Style', 'usp'),      array(&$this, 'callback_radio'),    $this->settings_style, 'section_style_1', array('id' => 'form_style',   'type' => 'style'));
			add_settings_field('style_min',    __('Minimal Style', 'usp'),          array(&$this, 'callback_textarea'), $this->settings_style, 'section_style_1', array('id' => 'style_min',    'type' => 'style'));
			add_settings_field('style_small',  __('Smaller Form', 'usp'),           array(&$this, 'callback_textarea'), $this->settings_style, 'section_style_1', array('id' => 'style_small',  'type' => 'style'));
			add_settings_field('style_large',  __('Larger Form', 'usp'),            array(&$this, 'callback_textarea'), $this->settings_style, 'section_style_1', array('id' => 'style_large',  'type' => 'style'));
			add_settings_field('style_custom', __('Custom Style', 'usp'),           array(&$this, 'callback_textarea'), $this->settings_style, 'section_style_1', array('id' => 'style_custom', 'type' => 'style'));
			// 2
			add_settings_section('section_style_2', 'JavaScript/jQuery', 'section_style_2_desc', $this->settings_style);
			add_settings_field('include_js',  __('Include USP JavaScript', 'usp'), array(&$this, 'callback_checkbox'), $this->settings_style, 'section_style_2', array('id' => 'include_js',    'type' => 'style'));
			add_settings_field('script_custom', __('Custom JavaScript', 'usp'),    array(&$this, 'callback_textarea'), $this->settings_style, 'section_style_2', array('id' => 'script_custom', 'type' => 'style'));
			// 3
			add_settings_section('section_style_3', 'Optimization', 'section_style_3_desc', $this->settings_style);
			add_settings_field('include_url', __('Targeted CSS/JS Loading', 'usp'), array(&$this, 'callback_input_text'), $this->settings_style, 'section_style_3', array('id' => 'include_url', 'type' => 'style'));
		}
		// UPLOADS SETTINGS
		function register_uploads_settings() {
			$this->settings_tabs[$this->settings_uploads] = 'Uploads';
			register_setting($this->settings_uploads, $this->settings_uploads, array(&$this, 'validate_uploads'));
			add_settings_section('section_uploads_0', '', 'section_uploads_0_desc', $this->settings_uploads);
			
			add_settings_section('section_uploads_1', 'File Uploads', 'section_uploads_1_desc', $this->settings_uploads);
			add_settings_field('post_images',     __('Auto-Display Images', 'usp'),     array(&$this, 'callback_radio'),      $this->settings_uploads, 'section_uploads_1', array('id' => 'post_images',     'type' => 'uploads'));
			add_settings_field('display_size',    __('Auto-Display Size', 'usp'),       array(&$this, 'callback_select'),     $this->settings_uploads, 'section_uploads_1', array('id' => 'display_size',    'type' => 'uploads'));
			add_settings_field('min_files',       __('Minimum number of files', 'usp'), array(&$this, 'callback_select'),     $this->settings_uploads, 'section_uploads_1', array('id' => 'min_files',       'type' => 'uploads'));
			add_settings_field('max_files',       __('Maximum number of files', 'usp'), array(&$this, 'callback_select'),     $this->settings_uploads, 'section_uploads_1', array('id' => 'max_files',       'type' => 'uploads'));
			add_settings_field('files_allow',     __('Allowed File Types', 'usp'),      array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'files_allow',     'type' => 'uploads'));
			add_settings_field('max_size',        __('Max file size', 'usp'),           array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'max_size',        'type' => 'uploads'));
			add_settings_field('min_size',        __('Min file size', 'usp'),           array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'min_size',        'type' => 'uploads'));
			add_settings_field('min_width',       __('Min width for images', 'usp'),    array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'min_width',       'type' => 'uploads'));
			add_settings_field('max_width',       __('Max width for images', 'usp'),    array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'max_width',       'type' => 'uploads'));
			add_settings_field('min_height',      __('Min height for images', 'usp'),   array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'min_height',      'type' => 'uploads'));
			add_settings_field('max_height',      __('Max height for images', 'usp'),   array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'max_height',      'type' => 'uploads'));
			add_settings_field('featured_image',  __('Featured Images', 'usp'),         array(&$this, 'callback_checkbox'),   $this->settings_uploads, 'section_uploads_1', array('id' => 'featured_image',  'type' => 'uploads'));
			add_settings_field('featured_key',    __('Featured Image Key', 'usp'),      array(&$this, 'callback_input_text'), $this->settings_uploads, 'section_uploads_1', array('id' => 'featured_key',    'type' => 'uploads'));
			add_settings_field('unique_filename', __('File Names', 'usp'),              array(&$this, 'callback_checkbox'),   $this->settings_uploads, 'section_uploads_1', array('id' => 'unique_filename', 'type' => 'uploads'));
			add_settings_field('user_shortcodes', __('User Shortcodes', 'usp'),         array(&$this, 'callback_checkbox'),   $this->settings_uploads, 'section_uploads_1', array('id' => 'user_shortcodes', 'type' => 'uploads'));
		}
		// ADMIN SETTINGS
		function register_admin_settings() {
			$this->settings_tabs[$this->settings_admin] = 'Admin';
			register_setting($this->settings_admin, $this->settings_admin, array(&$this, 'validate_admin'));
			add_settings_section('section_admin_0', '', 'section_admin_0_desc', $this->settings_admin);
			// 1
			add_settings_section('section_admin_1', 'Email Settings', 'section_admin_1_desc', $this->settings_admin);
			add_settings_field('admin_email', __('Admin Email To', 'usp'),   array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_1', array('id' => 'admin_email', 'type' => 'admin'));
			add_settings_field('admin_from',  __('Admin Email From', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_1', array('id' => 'admin_from',  'type' => 'admin'));
			add_settings_field('admin_name',  __('Admin Email Name', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_1', array('id' => 'admin_name',  'type' => 'admin'));
			// 2
			add_settings_section('section_admin_2', 'Email Alerts', 'section_admin_2_desc', $this->settings_admin);
			add_settings_field('send_mail',   __('Email Alerts', 'usp'), array(&$this, 'callback_radio'),  $this->settings_admin, 'section_admin_2', array('id' => 'send_mail',   'type' => 'admin'));
			add_settings_field('mail_format', __('Email Format', 'usp'), array(&$this, 'callback_select'), $this->settings_admin, 'section_admin_2', array('id' => 'mail_format', 'type' => 'admin'));
			// 3
			add_settings_section('section_admin_3', 'Email Alerts for Admin', 'section_admin_3_desc', $this->settings_admin);
			add_settings_field('send_mail_admin',        __('Send Post Alert to Admin', 'usp'),       array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_3', array('id' => 'send_mail_admin',        'type' => 'admin'));
			add_settings_field('send_approval_admin',    __('Send Approval Alert to Admin', 'usp'),   array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_3', array('id' => 'send_approval_admin',    'type' => 'admin'));
			add_settings_field('send_denied_admin',      __('Send Denied Alert to Admin', 'usp'),     array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_3', array('id' => 'send_denied_admin',      'type' => 'admin'));
			add_settings_field('alert_subject_admin',    __('Email Subject for Submissions', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_3', array('id' => 'alert_subject_admin',    'type' => 'admin'));
			add_settings_field('post_alert_admin',       __('Email Message for Submissions', 'usp'),  array(&$this, 'callback_textarea'),   $this->settings_admin, 'section_admin_3', array('id' => 'post_alert_admin',       'type' => 'admin'));
			add_settings_field('approval_subject_admin', __('Email Subject for Approvals', 'usp'),    array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_3', array('id' => 'approval_subject_admin', 'type' => 'admin'));
			add_settings_field('approval_message_admin', __('Email Message for Approvals', 'usp'),    array(&$this, 'callback_textarea'),   $this->settings_admin, 'section_admin_3', array('id' => 'approval_message_admin', 'type' => 'admin'));
			add_settings_field('denied_subject_admin',   __('Email Subject for Denied Posts', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_3', array('id' => 'denied_subject_admin',   'type' => 'admin'));
			add_settings_field('denied_message_admin',   __('Email Message for Denied Posts', 'usp'), array(&$this, 'callback_textarea'),   $this->settings_admin, 'section_admin_3', array('id' => 'denied_message_admin',   'type' => 'admin'));
			add_settings_field('cc_submit',              __('CC Post Alerts', 'usp'),                 array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_3', array('id' => 'cc_submit',              'type' => 'admin'));
			add_settings_field('cc_approval',            __('CC Approval Alerts', 'usp'),             array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_3', array('id' => 'cc_approval',            'type' => 'admin'));
			add_settings_field('cc_denied',              __('CC Denied Alerts', 'usp'),               array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_3', array('id' => 'cc_denied',              'type' => 'admin'));
			// 4
			add_settings_section('section_admin_4', 'Email Alerts for User', 'section_admin_4_desc', $this->settings_admin);
			add_settings_field('send_mail_user',     __('Send Post Alert to User', 'usp'),        array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_4', array('id' => 'send_mail_user',     'type' => 'admin'));
			add_settings_field('send_approval_user', __('Send Approval Alert to User', 'usp'),    array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_4', array('id' => 'send_approval_user', 'type' => 'admin'));
			add_settings_field('send_denied_user',   __('Send Denied Alert to User', 'usp'),      array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_4', array('id' => 'send_denied_user',   'type' => 'admin'));
			add_settings_field('alert_subject_user', __('Email Subject for Submissions', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_4', array('id' => 'alert_subject_user', 'type' => 'admin'));
			add_settings_field('post_alert_user',    __('Email Message for Submissions', 'usp'),  array(&$this, 'callback_textarea'),   $this->settings_admin, 'section_admin_4', array('id' => 'post_alert_user',    'type' => 'admin'));
			add_settings_field('approval_subject',   __('Email Subject for Approvals', 'usp'),    array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_4', array('id' => 'approval_subject',   'type' => 'admin'));
			add_settings_field('approval_message',   __('Email Message for Approvals', 'usp'),    array(&$this, 'callback_textarea'),   $this->settings_admin, 'section_admin_4', array('id' => 'approval_message',   'type' => 'admin'));
			add_settings_field('denied_subject',     __('Email Subject for Denied Posts', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_4', array('id' => 'denied_subject',     'type' => 'admin'));
			add_settings_field('denied_message',     __('Email Message for Denied Posts', 'usp'), array(&$this, 'callback_textarea'),   $this->settings_admin, 'section_admin_4', array('id' => 'denied_message',     'type' => 'admin'));
			// 5
			add_settings_section('section_admin_5', 'Contact Form', 'section_admin_5_desc', $this->settings_admin);
			add_settings_field('contact_sub_prefix', __('Subject Line Prefix', 'usp'),   array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_5', array('id' => 'contact_sub_prefix', 'type' => 'admin'));
			add_settings_field('contact_subject',    __('Subject Line', 'usp'),          array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_5', array('id' => 'contact_subject',    'type' => 'admin'));
			add_settings_field('contact_from',       __('Email From', 'usp'),            array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_5', array('id' => 'contact_from',       'type' => 'admin'));
			add_settings_field('custom_content',     __('Custom Content', 'usp'),        array(&$this, 'callback_textarea'),   $this->settings_admin, 'section_admin_5', array('id' => 'custom_content',     'type' => 'admin'));
			add_settings_field('contact_cc',         __('CC Emails', 'usp'),             array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_5', array('id' => 'contact_cc',         'type' => 'admin'));
			add_settings_field('contact_cc_user',    __('CC User', 'usp'),               array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_5', array('id' => 'contact_cc_user',    'type' => 'admin'));
			add_settings_field('contact_cc_note',    __('CC User Message', 'usp'),       array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_5', array('id' => 'contact_cc_note',    'type' => 'admin'));
			add_settings_field('contact_stats',      __('Include User Stats', 'usp'),    array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_5', array('id' => 'contact_stats',      'type' => 'admin'));
			add_settings_field('contact_custom',     __('Include Custom Fields', 'usp'), array(&$this, 'callback_checkbox'),   $this->settings_admin, 'section_admin_5', array('id' => 'contact_custom',     'type' => 'admin'));
			// 6
			add_settings_section('section_admin_6', 'Custom Recipients', 'section_admin_6_desc', $this->settings_admin);
			add_settings_field('custom_contact_1', __('Custom Address 1', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_6', array('id' => 'custom_contact_1', 'type' => 'admin'));
			add_settings_field('custom_contact_2', __('Custom Address 2', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_6', array('id' => 'custom_contact_2', 'type' => 'admin'));
			add_settings_field('custom_contact_3', __('Custom Address 3', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_6', array('id' => 'custom_contact_3', 'type' => 'admin'));
			add_settings_field('custom_contact_4', __('Custom Address 4', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_6', array('id' => 'custom_contact_4', 'type' => 'admin'));
			add_settings_field('custom_contact_5', __('Custom Address 5', 'usp'), array(&$this, 'callback_input_text'), $this->settings_admin, 'section_admin_6', array('id' => 'custom_contact_5', 'type' => 'admin'));
			// 7
			add_settings_section('section_admin_7', 'Content Filter', 'section_admin_7_desc', $this->settings_admin);
			add_settings_field('blacklist_terms', __('Blacklist terms', 'usp'), array(&$this, 'callback_textarea'), $this->settings_admin, 'section_admin_7', array('id' => 'blacklist_terms', 'type' => 'admin'));
		}
		// ADVANCED SETTINGS
		function register_advanced_settings() {
			global $usp_advanced;
			$this->settings_tabs[$this->settings_advanced] = 'Advanced';
			register_setting($this->settings_advanced, $this->settings_advanced, array(&$this, 'validate_advanced'));
			add_settings_section('section_advanced_0', '', 'section_advanced_0_desc', $this->settings_advanced);
			// 1
			add_settings_section('section_advanced_1', __('Form Configuration', 'usp'), 'section_advanced_1_desc', $this->settings_advanced);
			add_settings_field('enable_autop',   __('Enable auto-formatting', 'usp'),     array(&$this, 'callback_checkbox'),   $this->settings_advanced, 'section_advanced_1', array('id' => 'enable_autop',  'type' => 'advanced'));
			add_settings_field('fieldsets',      __('Auto-include fieldsets', 'usp'),     array(&$this, 'callback_checkbox'),   $this->settings_advanced, 'section_advanced_1', array('id' => 'fieldsets',     'type' => 'advanced'));
			add_settings_field('form_demos',     __('Auto-generate form demos', 'usp'),   array(&$this, 'callback_checkbox'),   $this->settings_advanced, 'section_advanced_1', array('id' => 'form_demos',    'type' => 'advanced'));
			add_settings_field('post_demos',     __('Auto-generate post demos', 'usp'),   array(&$this, 'callback_checkbox'),   $this->settings_advanced, 'section_advanced_1', array('id' => 'post_demos',    'type' => 'advanced'));
			add_settings_field('submit_button',  __('Auto-include submit button', 'usp'), array(&$this, 'callback_checkbox'),   $this->settings_advanced, 'section_advanced_1', array('id' => 'submit_button', 'type' => 'advanced'));
			add_settings_field('submit_text',    __('Text for submit button', 'usp'),     array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_1', array('id' => 'submit_text',   'type' => 'advanced'));
			add_settings_field('html_content',   __('Allowed HTML in post', 'usp'),       array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_1', array('id' => 'html_content',  'type' => 'advanced'));
			add_settings_field('form_atts',      __('Custom Form Attributes', 'usp'),     array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_1', array('id' => 'form_atts',     'type' => 'advanced'));
			// 2
			add_settings_section('section_advanced_2', __('Custom Post Type', 'usp'), 'section_advanced_2_desc', $this->settings_advanced);
			add_settings_field('post_type',      __('Submitted Post Type', 'usp'),        array(&$this, 'callback_radio'),      $this->settings_advanced, 'section_advanced_2', array('id' => 'post_type',      'type' => 'advanced'));
			add_settings_field('post_type_slug', __('Slug for USP Post Type', 'usp'),     array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_2', array('id' => 'post_type_slug', 'type' => 'advanced'));
			add_settings_field('other_type',     __('Specify Existing Post Type', 'usp'), array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_2', array('id' => 'other_type',     'type' => 'advanced'));
			add_settings_field('post_type_role', __('Roles for USP Post Types', 'usp'),   array(&$this, 'callback_checkboxes'), $this->settings_advanced, 'section_advanced_2', array('id' => 'post_type_role', 'type' => 'advanced'));
			add_settings_field('form_type_role', __('Roles for USP Form Types', 'usp'),   array(&$this, 'callback_checkboxes'), $this->settings_advanced, 'section_advanced_2', array('id' => 'form_type_role', 'type' => 'advanced'));
			// 3
			add_settings_section('section_advanced_3', __('Before/After USP Forms', 'usp'), 'section_advanced_3_desc', $this->settings_advanced);
			add_settings_field('custom_before', __('Custom Before Forms', 'usp'), array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_3', array('id' => 'custom_before', 'type' => 'advanced'));
			add_settings_field('custom_after',  __('Custom After Forms', 'usp'),  array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_3', array('id' => 'custom_after',  'type' => 'advanced'));
			// 4
			add_settings_section('section_advanced_4', __('Customize Success Message', 'usp'), 'section_advanced_4_desc', $this->settings_advanced);
			add_settings_field('success_reg',        __('Register User', 'usp'),                array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_reg',        'type' => 'advanced'));
			add_settings_field('success_post',       __('Submit Post', 'usp'),                  array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_post',       'type' => 'advanced'));
			add_settings_field('success_both',       __('Register and Submit', 'usp'),          array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_both',       'type' => 'advanced'));
			add_settings_field('success_contact',    __('Contact Form', 'usp'),                 array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_contact',    'type' => 'advanced'));
			add_settings_field('success_email_reg',  __('Contact Form and Register', 'usp'),    array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_email_reg',  'type' => 'advanced'));
			add_settings_field('success_email_post', __('Contact Form and Post', 'usp'),        array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_email_post', 'type' => 'advanced'));
			add_settings_field('success_email_both', __('Contact, Register, and Post', 'usp'),  array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_email_both', 'type' => 'advanced'));
			add_settings_field('success_before',     __('Custom Before Message', 'usp'),        array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_before',     'type' => 'advanced'));
			add_settings_field('success_after',      __('Custom After Message', 'usp'),         array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_after',      'type' => 'advanced'));
			add_settings_field('success_form',       __('Display Form on Success', 'usp'),      array(&$this, 'callback_checkbox'), $this->settings_advanced, 'section_advanced_4', array('id' => 'success_form',       'type' => 'advanced'));
			// 5
			add_settings_section('section_advanced_5', __('Customize Error Message', 'usp'), 'section_advanced_5_desc', $this->settings_advanced);
			add_settings_field('error_before', __('Custom Before Errors', 'usp'), array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_5', array('id' => 'error_before', 'type' => 'advanced'));
			add_settings_field('error_after',  __('Custom After Errors', 'usp'),  array(&$this, 'callback_textarea'), $this->settings_advanced, 'section_advanced_5', array('id' => 'error_after',  'type' => 'advanced'));
			// 6
			add_settings_section('section_advanced_6', __('Primary Form Fields', 'usp'), 'section_advanced_6_desc', $this->settings_advanced);
			for ( $i = 1; $i < 18; $i++ ) {
				add_settings_field('usp_error_'.strval($i), __('Primary Field ', 'usp').strval($i), array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_6', array('id' => 'usp_error_'.strval($i), 'type' => 'advanced'));
			}
			// 7
			add_settings_section('section_advanced_7', __('User-Registration Fields', 'usp'), 'section_advanced_7_desc', $this->settings_advanced);
			$user_fields = array('a' => __('Nicename', 'usp'), 'b' => __('Display Name', 'usp'), 'c' => __('Nickname', 'usp'), 'd' => __('First Name', 'usp'), 'e' => __('Last Name', 'usp'), 'f' => __('Description', 'usp'), 'g' => __('Password', 'usp'));
			foreach ($user_fields as $key => $value) {
				add_settings_field('usp_error_'.$key, $value, array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_7', array('id' => 'usp_error_'.$key, 'type' => 'advanced'));
			}
			// 8
			add_settings_section('section_advanced_8', __('Custom Form Fields', 'usp'), 'section_advanced_8_desc', $this->settings_advanced);
			add_settings_field('custom_fields', __('Custom Form Fields', 'usp'), array(&$this, 'callback_number'), $this->settings_advanced, 'section_advanced_8', array('id' => 'custom_fields', 'type' => 'advanced'));
			// 9
			add_settings_section('section_advanced_9', __('Custom Field Names', 'usp'), 'section_advanced_9_desc', $this->settings_advanced);
			if (isset($usp_advanced['custom_fields']) && is_numeric($usp_advanced['custom_fields'])) {
				$max = 1 + intval($usp_advanced['custom_fields']);
				if ($max > 0) {
					for ( $i = 1; $i < $max; $i++ ) {
						add_settings_field('usp_label_c'.strval($i), __('Custom Field ', 'usp').strval($i), array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_9', array('id' => 'usp_label_c'.strval($i), 'type' => 'advanced'));
					}
				}
			}
			// 10
			add_settings_section('section_advanced_10', __('Custom Field Prefix', 'usp'), 'section_advanced_10_desc', $this->settings_advanced);
			add_settings_field('custom_prefix', __('Custom Prefix', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_10', array('id' => 'custom_prefix', 'type' => 'advanced'));
			// 11
			add_settings_section('section_advanced_11', __('Custom Custom Fields', 'usp'), 'section_advanced_11_desc', $this->settings_advanced);
			add_settings_field('custom_optional', __('Optional Fields', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_11', array('id' => 'custom_optional', 'type' => 'advanced'));
			add_settings_field('custom_required', __('Required Fields', 'usp'),  array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_11', array('id' => 'custom_required', 'type' => 'advanced'));
			// 12
			add_settings_section('section_advanced_12', __('Custom Custom Field Names', 'usp'), 'section_advanced_12_desc', $this->settings_advanced);
			$custom_merged = usp_merge_custom_fields();
			if ($custom_merged) {
				foreach($custom_merged as $value) {
					$label_val = $value;
					if (strlen($value) > 24) $label_val = substr($value, 0, 25) .'&hellip;';
					add_settings_field('usp_custom_label_'. $value, 'Custom Field: '. __($label_val, 'usp'), array(&$this, 'callback_input_text'), $this->settings_advanced, 'section_advanced_12', array('id' => 'usp_custom_label_'. $value, 'type' => 'advanced'));
				}
			}
		}
		// MORE SETTINGS
		function register_more_settings() {
			global $usp_more;
			$this->settings_tabs[$this->settings_more] = 'More';
			register_setting($this->settings_more, $this->settings_more, array(&$this, 'validate_more'));
			add_settings_section('section_more_0', '', 'section_more_0_desc', $this->settings_more);
			// 1
			add_settings_section('section_more_1', __('Before/After Errors', 'usp'), 'section_more_1_desc', $this->settings_more);
			add_settings_field('error_sep',           __('Error Separator', 'usp'),           array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_1', array('id' => 'error_sep',           'type' => 'more'));
			add_settings_field('tax_before',          __('Before Taxonomy Error', 'usp'),     array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_1', array('id' => 'tax_before',          'type' => 'more'));
			add_settings_field('tax_after',           __('After Taxonomy Error', 'usp'),      array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_1', array('id' => 'tax_after',           'type' => 'more'));
			add_settings_field('custom_field_before', __('Before Custom Field Error', 'usp'), array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_1', array('id' => 'custom_field_before', 'type' => 'more'));
			add_settings_field('custom_field_after',  __('After Custom Field Error', 'usp'),  array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_1', array('id' => 'custom_field_after',  'type' => 'more'));
			// 2
			add_settings_section('section_more_2', __('Primary Field Errors', 'usp'), 'section_more_2_desc', $this->settings_more);
			add_settings_field('usp_error_1_desc',  __('Name', 'usp'),          array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_1_desc',  'type' => 'more'));
			add_settings_field('usp_error_2_desc',  __('URL', 'usp'),           array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_2_desc',  'type' => 'more'));
			add_settings_field('usp_error_3_desc',  __('Title', 'usp'),         array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_3_desc',  'type' => 'more'));
			add_settings_field('usp_error_4_desc',  __('Tags', 'usp'),          array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_4_desc',  'type' => 'more'));
			add_settings_field('usp_error_5_desc',  __('Captcha', 'usp'),       array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_5_desc',  'type' => 'more'));
			add_settings_field('usp_error_6_desc',  __('Category', 'usp'),      array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_6_desc',  'type' => 'more'));
			add_settings_field('usp_error_7_desc',  __('Content', 'usp'),       array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_7_desc',  'type' => 'more'));
			add_settings_field('usp_error_8_desc',  __('Files', 'usp'),         array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_8_desc',  'type' => 'more'));
			add_settings_field('usp_error_9_desc',  __('Email Address', 'usp'), array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_9_desc',  'type' => 'more'));
			add_settings_field('usp_error_10_desc', __('Email Subject', 'usp'), array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_10_desc', 'type' => 'more'));
			add_settings_field('usp_error_11_desc', __('Alt Text', 'usp'),      array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_11_desc', 'type' => 'more'));
			add_settings_field('usp_error_12_desc', __('Caption', 'usp'),       array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_12_desc', 'type' => 'more'));
			add_settings_field('usp_error_13_desc', __('Description', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_13_desc', 'type' => 'more'));
			add_settings_field('usp_error_14_desc', __('Taxonomy', 'usp'),      array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_14_desc', 'type' => 'more'));
			add_settings_field('usp_error_15_desc', __('Post Format', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_15_desc', 'type' => 'more'));
			add_settings_field('usp_error_16_desc', __('Media Title', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_16_desc', 'type' => 'more'));
			add_settings_field('usp_error_17_desc', __('File Name', 'usp'),     array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_2', array('id' => 'usp_error_17_desc', 'type' => 'more'));
			// 3
			add_settings_section('section_more_3', __('Form Submission Errors', 'usp'), 'section_more_3_desc', $this->settings_more);
			add_settings_field('error_username', __('Username Error', 'usp'),          array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'error_username',   'type' => 'more'));
			add_settings_field('error_email',    __('User Email Error', 'usp'),        array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'error_email',      'type' => 'more'));
			add_settings_field('user_exists',    __('User Exists', 'usp'),             array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'user_exists',      'type' => 'more'));
			add_settings_field('error_register', __('Registration Disabled', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'error_register',   'type' => 'more'));
			add_settings_field('post_required',  __('Post Required', 'usp'),           array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'post_required',    'type' => 'more'));
			add_settings_field('post_duplicate', __('Duplicate Post', 'usp'),          array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'post_duplicate',   'type' => 'more'));
			add_settings_field('name_restrict',    __('Name Restriction', 'usp'),      array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'name_restrict',    'type' => 'more'));
			add_settings_field('spam_response',    __('Incorrect Captcha', 'usp'),     array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'spam_response',    'type' => 'more'));
			add_settings_field('content_min',      __('Content Minimum', 'usp'),       array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'content_min',      'type' => 'more'));
			add_settings_field('content_max',      __('Content Maximum', 'usp'),       array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'content_max',      'type' => 'more'));
			add_settings_field('email_restrict',   __('Address Restriction', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'email_restrict',   'type' => 'more'));
			add_settings_field('subject_restrict', __('Subject Restriction', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'subject_restrict', 'type' => 'more'));
			add_settings_field('form_allowed',     __('Incorrect Form Type', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_3', array('id' => 'form_allowed',     'type' => 'more'));
			// 4
			add_settings_section('section_more_4', __('File Submission Errors', 'usp'), 'section_more_4_desc', $this->settings_more);
			add_settings_field('files_required',  __('Files Required', 'usp'),          array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'files_required',  'type' => 'more'));
			add_settings_field('file_required',   __('File Required', 'usp'),           array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'file_required',   'type' => 'more'));
			add_settings_field('file_type_not',   __('File Type Not Allowed', 'usp'),   array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'file_type_not',   'type' => 'more'));
			add_settings_field('file_dimensions', __('File Dimensions', 'usp'),         array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'file_dimensions', 'type' => 'more'));
			add_settings_field('file_max_size',   __('Maximum File Size', 'usp'),       array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'file_max_size',   'type' => 'more'));
			add_settings_field('file_min_size',   __('Minimum File Size', 'usp'),       array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'file_min_size',   'type' => 'more'));
			add_settings_field('file_name',       __('File Name Length', 'usp'),        array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'file_name',       'type' => 'more'));
			add_settings_field('min_req_files',   __('Minimum Number of Files', 'usp'), array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'min_req_files',   'type' => 'more'));
			add_settings_field('max_req_files',   __('Maximum Number of Files', 'usp'), array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_4', array('id' => 'max_req_files',   'type' => 'more'));
			// 5
			add_settings_section('section_more_5', __('Custom Registration Errors', 'usp'), 'section_more_5_desc', $this->settings_more);
			$user_fields = array('a' => __('Nicename', 'usp'), 'b' => __('Display Name', 'usp'), 'c' => __('Nickname', 'usp'), 'd' => __('First Name', 'usp'), 'e' => __('Last Name', 'usp'), 'f' => __('Description', 'usp'), 'g' => __('Password', 'usp'));
			foreach ($user_fields as $key => $value) {
				add_settings_field('usp_error_'.$key.'_desc', $value, array(&$this, 'callback_textarea'), $this->settings_more, 'section_more_5', array('id' => 'usp_error_'.$key.'_desc', 'type' => 'more'));
			}
			// 6
			add_settings_section('section_more_6', __('Default Form Fields', 'usp'), 'section_more_6_desc', $this->settings_more);
			add_settings_field('default_title',   __('Default Post Title', 'usp'),   array(&$this, 'callback_input_text'), $this->settings_more, 'section_more_6', array('id' => 'default_title',   'type' => 'more'));
			add_settings_field('default_content', __('Default Post Content', 'usp'), array(&$this, 'callback_textarea'),   $this->settings_more, 'section_more_6', array('id' => 'default_content', 'type' => 'more'));
			// 7 
			add_settings_section('section_more_7', __('Reset Default Plugin Options', 'usp'), 'section_more_7_desc', $this->settings_more);
			add_settings_field('default_options',  __('Reset all plugin options?', 'usp'), array(&$this, 'callback_checkbox'), $this->settings_more, 'section_more_7', array('id' => 'default_options', 'type' => 'more'));
		}
		// TOOLS SETTINGS
		function register_tools_settings() {
			$this->settings_tabs[$this->settings_tools] = 'Tools';
		}
		// ABOUT SETTINGS
		function register_about_settings() {
			$this->settings_tabs[$this->settings_about] = 'About';
		}
		// LICENSE SETTINGS
		function register_license_settings() {
			$this->settings_tabs[$this->settings_license] = 'License';
		}
		// GENERAL VALIDATE
		function validate_general($input) {

			$cats_menu = $this->cats_menu_options();
			if (!isset($input['cats_menu'])) $input['cats_menu'] = null;
			if (!array_key_exists($input['cats_menu'], $cats_menu)) $input['cats_menu'] = null;
			
			$tags_order = $this->tags_order_options();
			if (!isset($input['tags_order'])) $input['tags_order'] = null;
			if (!array_key_exists($input['tags_order'], $tags_order)) $input['tags_order'] = null;
			
			$tags_menu = $this->tags_menu_options();
			if (!isset($input['tags_menu'])) $input['tags_menu'] = null;
			if (!array_key_exists($input['tags_menu'], $tags_menu)) $input['tags_menu'] = null;
			
			if (isset($input['categories'])) $input['categories'] = is_array($input['categories']) && !empty($input['categories']) ? array_unique($input['categories']) : array(get_option('default_category'));
			if (isset($input['number_approved'])) $input['number_approved'] = is_numeric($input['number_approved']) ? intval($input['number_approved']) : - 1;
			if (isset($input['tags'])) $input['tags'] = is_array($input['tags']) && !empty($input['tags']) ? array_unique($input['tags']) : array();
			
			if (isset($input['tags_number'])) $input['tags_number'] = wp_filter_nohtml_kses($input['tags_number']);
			if (isset($input['redirect_success'])) $input['redirect_success'] = wp_filter_nohtml_kses($input['redirect_success']);
			if (isset($input['redirect_failure'])) $input['redirect_failure'] = wp_filter_nohtml_kses($input['redirect_failure']);
			if (isset($input['redirect_post'])) $input['redirect_post'] = wp_filter_nohtml_kses($input['redirect_post']);
			if (isset($input['character_max'])) $input['character_max'] = wp_filter_nohtml_kses($input['character_max']);
			if (isset($input['character_min'])) $input['character_min'] = wp_filter_nohtml_kses($input['character_min']);
			if (isset($input['captcha_question'])) $input['captcha_question'] = wp_filter_nohtml_kses($input['captcha_question']);
			if (isset($input['captcha_response'])) $input['captcha_response'] = wp_filter_nohtml_kses($input['captcha_response']);
			if (isset($input['recaptcha_public'])) $input['recaptcha_public'] = wp_filter_nohtml_kses($input['recaptcha_public']);
			if (isset($input['recaptcha_private'])) $input['recaptcha_private'] = wp_filter_nohtml_kses($input['recaptcha_private']);
			if (isset($input['enable_stats'])) $input['enable_stats'] = wp_filter_nohtml_kses($input['enable_stats']);
			if (isset($input['use_cat_id'])) $input['use_cat_id'] = wp_filter_nohtml_kses($input['use_cat_id']);
			if (isset($input['assign_author'])) $input['assign_author'] = wp_filter_nohtml_kses($input['assign_author']);
			if (isset($input['assign_role'])) $input['assign_role'] = wp_filter_nohtml_kses($input['assign_role']);
			if (isset($input['custom_status'])) $input['custom_status'] = wp_filter_nohtml_kses($input['custom_status']);

			if (!isset($input['hidden_tags'])) $input['hidden_tags'] = null;
			$input['hidden_tags'] = ($input['hidden_tags'] == 1 ? 1 : 0);
			
			if (!isset($input['tags_empty'])) $input['tags_empty'] = null;
			$input['tags_empty'] = ($input['tags_empty'] == 1 ? 1 : 0);
			
			if (!isset($input['tags_multiple'])) $input['tags_multiple'] = null;
			$input['tags_multiple'] = ($input['tags_multiple'] == 1 ? 1 : 0);
			
			if (!isset($input['captcha_casing'])) $input['captcha_casing'] = null;
			$input['captcha_casing'] = ($input['captcha_casing'] == 1 ? 1 : 0);
			
			if (!isset($input['use_author'])) $input['use_author'] = null;
			$input['use_author'] = ($input['use_author'] == 1 ? 1 : 0);
			
			if (!isset($input['replace_author'])) $input['replace_author'] = null;
			$input['replace_author'] = ($input['replace_author'] == 1 ? 1 : 0);
			
			if (!isset($input['use_cat'])) $input['use_cat'] = null;
			$input['use_cat'] = ($input['use_cat'] == 1 ? 1 : 0);
			
			if (!isset($input['hidden_cats'])) $input['hidden_cats'] = null;
			$input['hidden_cats'] = ($input['hidden_cats'] == 1 ? 1 : 0);
			
			if (!isset($input['cats_multiple'])) $input['cats_multiple'] = null;
			$input['cats_multiple'] = ($input['cats_multiple'] == 1 ? 1 : 0);
			
			if (!isset($input['cats_nested'])) $input['cats_nested'] = null;
			$input['cats_nested'] = ($input['cats_nested'] == 1 ? 1 : 0);
			
			if (!isset($input['sessions_on'])) $input['sessions_on'] = null;
			$input['sessions_on'] = ($input['sessions_on'] == 1 ? 1 : 0);
			
			if (!isset($input['sessions_scope'])) $input['sessions_scope'] = null;
			$input['sessions_scope'] = ($input['sessions_scope'] == 1 ? 1 : 0);
			
			if (!isset($input['sessions_default'])) $input['sessions_default'] = null;
			$input['sessions_default'] = ($input['sessions_default'] == 1 ? 1 : 0);
			
			if (!isset($input['titles_unique'])) $input['titles_unique'] = null;
			$input['titles_unique'] = ($input['titles_unique'] == 1 ? 1 : 0);
			
			if (!isset($input['enable_form_lock'])) $input['enable_form_lock'] = null;
			$input['enable_form_lock'] = ($input['enable_form_lock'] == 1 ? 1 : 0);
			
			if (isset($input['submit_form_ids'])) $input['submit_form_ids'] = wp_filter_nohtml_kses($input['submit_form_ids']);
			if (isset($input['register_form_ids'])) $input['register_form_ids'] = wp_filter_nohtml_kses($input['register_form_ids']);
			if (isset($input['contact_form'])) $input['contact_form'] = wp_filter_nohtml_kses($input['contact_form']);
			
			return $input;
		}
		// STYLE VALIDATE
		function validate_style($input) {
			$form_style = $this->style_options();
			if (!isset($input['form_style'])) $input['form_style'] = null;
			if (!array_key_exists($input['form_style'], $form_style)) $input['form_style'] = null;
			
			if (isset($input['style_min'])) $input['style_min'] = wp_filter_nohtml_kses($input['style_min']);
			if (isset($input['style_small'])) $input['style_small'] = wp_filter_nohtml_kses($input['style_small']);
			if (isset($input['style_large'])) $input['style_large'] = wp_filter_nohtml_kses($input['style_large']);
			if (isset($input['style_custom'])) $input['style_custom'] = wp_filter_nohtml_kses($input['style_custom']);
			if (isset($input['include_url'])) $input['include_url'] = wp_filter_nohtml_kses($input['include_url']);
			
			if (isset($input['script_custom'])) $input['script_custom'] = htmlspecialchars($input['script_custom'], ENT_QUOTES, get_option('blog_charset', 'UTF-8'));
			
			if (!isset($input['include_css'])) $input['include_css'] = null;
			$input['include_css'] = ($input['include_css'] == 1 ? 1 : 0);
			
			if (!isset($input['include_js'])) $input['include_js'] = null;
			$input['include_js'] = ($input['include_js'] == 1 ? 1 : 0);
			
			return $input;
		}
		// UPLOADS VALIDATE
		function validate_uploads($input) {
			global $usp_uploads;
			
			$display_images = $this->display_images_options();
			if (!isset($input['post_images'])) $input['post_images'] = null;
			if (!array_key_exists($input['post_images'], $display_images)) $input['post_images'] = null;
			
			$display_sizes = $this->display_size_options();
			if (!isset($input['display_size'])) $input['display_size'] = null;
			if (!array_key_exists($input['display_size'], $display_sizes)) $input['display_size'] = null;
			
			if (isset($input['min_files'])) $input['min_files'] = is_numeric($input['min_files']) ? intval($input['min_files']) : $usp_uploads['min_files'];
			if (isset($input['max_files'])) $input['max_files'] = is_numeric($input['max_files']) ? intval($input['max_files']) : $usp_uploads['max_files'];
			
			if (isset($input['min_width']))  $input['min_width']  = is_numeric($input['min_width'])  ? intval($input['min_width'])  : $usp_uploads['min_width'];
			if (isset($input['min_height'])) $input['min_height'] = is_numeric($input['min_height']) ? intval($input['min_height']) : $usp_uploads['min_height'];
	
			if (isset($input['max_width']))  $input['max_width']  = is_numeric($input['max_width'])  ? intval($input['max_width'])  : $usp_uploads['max_width'];
			if (isset($input['max_height'])) $input['max_height'] = is_numeric($input['max_height']) ? intval($input['max_height']) : $usp_uploads['max_height'];

			if (isset($input['max_size'])) $input['max_size'] = is_numeric($input['max_size']) ? intval($input['max_size']) : $usp_uploads['max_size'];
			if (isset($input['min_size'])) $input['min_size'] = is_numeric($input['min_size']) ? intval($input['min_size']) : $usp_uploads['min_size'];
			
			if (isset($input['files_allow']))  $input['files_allow']  = wp_filter_nohtml_kses($input['files_allow']);
			if (isset($input['featured_key'])) $input['featured_key'] = wp_filter_nohtml_kses($input['featured_key']);
			
			if (!isset($input['featured_image'])) $input['featured_image'] = null;
			$input['featured_image'] = ($input['featured_image'] == 1 ? 1 : 0);
			
			if (!isset($input['unique_filename'])) $input['unique_filename'] = null;
			$input['unique_filename'] = ($input['unique_filename'] == 1 ? 1 : 0);
			
			if (!isset($input['user_shortcodes'])) $input['user_shortcodes'] = null;
			$input['user_shortcodes'] = ($input['user_shortcodes'] == 1 ? 1 : 0);
			
			return $input;
		}
		// ADMIN VALIDATE
		function validate_admin($input) {
			$send_mail = $this->send_mail_options();
			if (!isset($input['send_mail'])) $input['send_mail'] = null;
			if (!array_key_exists($input['send_mail'], $send_mail)) $input['send_mail'] = null;
			
			$mail_format = $this->mail_format();
			if (!isset($input['mail_format'])) $input['mail_format'] = null;
			if (!array_key_exists($input['mail_format'], $mail_format)) $input['mail_format'] = null;
			
			// dealing with kses
			$allowed_atts = array(
				'align'    => array(), 
				'class'    => array(), 
				'type'     => array(), 
				'id'       => array(), 
				'dir'      => array(), 
				'lang'     => array(), 
				'style'    => array(), 
				'xml:lang' => array(), 
				'src'      => array(), 
				'alt'      => array(),
				'href'     => array(), 
				'rel'      => array(), 
				'target'   => array(),
			);
			$allowedposttags['script'] = $allowed_atts;
			$allowedposttags['strong'] = $allowed_atts;
			$allowedposttags['small'] = $allowed_atts;
			$allowedposttags['span'] = $allowed_atts;
			$allowedposttags['abbr'] = $allowed_atts;
			$allowedposttags['code'] = $allowed_atts;
			$allowedposttags['div'] = $allowed_atts;
			$allowedposttags['img'] = $allowed_atts;
			$allowedposttags['h1'] = $allowed_atts;
			$allowedposttags['h2'] = $allowed_atts;
			$allowedposttags['h3'] = $allowed_atts;
			$allowedposttags['h4'] = $allowed_atts;
			$allowedposttags['h5'] = $allowed_atts;
			$allowedposttags['ol'] = $allowed_atts;
			$allowedposttags['ul'] = $allowed_atts;
			$allowedposttags['li'] = $allowed_atts;
			$allowedposttags['em'] = $allowed_atts;
			$allowedposttags['p'] = $allowed_atts;
			$allowedposttags['a'] = $allowed_atts;
			
			if (isset($input['custom_content'])) $input['custom_content'] = wp_kses_post($input['custom_content']);
			
			if (isset($input['post_alert_user']))        $input['post_alert_user']        = wp_kses_post($input['post_alert_user']);
			if (isset($input['post_alert_admin']))       $input['post_alert_admin']       = wp_kses_post($input['post_alert_admin']);
			if (isset($input['approval_message']))       $input['approval_message']       = wp_kses_post($input['approval_message']);
			if (isset($input['approval_message_admin'])) $input['approval_message_admin'] = wp_kses_post($input['approval_message_admin']);
			if (isset($input['denied_message']))         $input['denied_message']         = wp_kses_post($input['denied_message']);
			if (isset($input['denied_message_admin']))   $input['denied_message_admin']   = wp_kses_post($input['denied_message_admin']);
			
			if (isset($input['alert_subject_user']))     $input['alert_subject_user']     = wp_filter_nohtml_kses($input['alert_subject_user']);
			if (isset($input['alert_subject_admin']))    $input['alert_subject_admin']    = wp_filter_nohtml_kses($input['alert_subject_admin']);
			if (isset($input['approval_subject']))       $input['approval_subject']       = wp_filter_nohtml_kses($input['approval_subject']);
			if (isset($input['approval_subject_admin'])) $input['approval_subject_admin'] = wp_filter_nohtml_kses($input['approval_subject_admin']);
			if (isset($input['denied_subject']))         $input['denied_subject']         = wp_filter_nohtml_kses($input['denied_subject']);
			if (isset($input['denied_subject_admin']))   $input['denied_subject_admin']   = wp_filter_nohtml_kses($input['denied_subject_admin']);
			
			if (isset($input['contact_sub_prefix'])) $input['contact_sub_prefix'] = wp_filter_nohtml_kses($input['contact_sub_prefix']);
			if (isset($input['contact_subject'])) $input['contact_subject'] = wp_filter_nohtml_kses($input['contact_subject']);
			if (isset($input['contact_cc'])) $input['contact_cc'] = wp_filter_nohtml_kses($input['contact_cc']);
			if (isset($input['contact_cc_note'])) $input['contact_cc_note'] = wp_filter_nohtml_kses($input['contact_cc_note']);
			if (isset($input['admin_email'])) $input['admin_email'] = wp_filter_nohtml_kses($input['admin_email']);
			if (isset($input['admin_from'])) $input['admin_from'] = wp_filter_nohtml_kses($input['admin_from']);
			if (isset($input['admin_name'])) $input['admin_name'] = wp_filter_nohtml_kses($input['admin_name']);
			if (isset($input['cc_submit'])) $input['cc_submit'] = wp_filter_nohtml_kses($input['cc_submit']);
			if (isset($input['cc_approval'])) $input['cc_approval'] = wp_filter_nohtml_kses($input['cc_approval']);
			if (isset($input['cc_denied'])) $input['cc_denied'] = wp_filter_nohtml_kses($input['cc_denied']);
			if (isset($input['contact_from'])) $input['contact_from'] = wp_filter_nohtml_kses($input['contact_from']);
			
			if (isset($input['custom_contact_1'])) $input['custom_contact_1'] = wp_filter_nohtml_kses($input['custom_contact_1']);
			if (isset($input['custom_contact_2'])) $input['custom_contact_2'] = wp_filter_nohtml_kses($input['custom_contact_2']);
			if (isset($input['custom_contact_3'])) $input['custom_contact_3'] = wp_filter_nohtml_kses($input['custom_contact_3']);
			if (isset($input['custom_contact_4'])) $input['custom_contact_4'] = wp_filter_nohtml_kses($input['custom_contact_4']);
			if (isset($input['custom_contact_5'])) $input['custom_contact_5'] = wp_filter_nohtml_kses($input['custom_contact_5']);
			
			if (isset($input['blacklist_terms'])) $input['blacklist_terms'] = wp_filter_nohtml_kses($input['blacklist_terms']);
			
			if (!isset($input['send_mail_admin'])) $input['send_mail_admin'] = null;
			$input['send_mail_admin'] = ($input['send_mail_admin'] == 1 ? 1 : 0);
			
			if (!isset($input['send_mail_user'])) $input['send_mail_user'] = null;
			$input['send_mail_user'] = ($input['send_mail_user'] == 1 ? 1 : 0);
			
			if (!isset($input['send_approval_user'])) $input['send_approval_user'] = null;
			$input['send_approval_user'] = ($input['send_approval_user'] == 1 ? 1 : 0);
			
			if (!isset($input['send_approval_admin'])) $input['send_approval_admin'] = null;
			$input['send_approval_admin'] = ($input['send_approval_admin'] == 1 ? 1 : 0);
			
			if (!isset($input['send_denied_user'])) $input['send_denied_user'] = null;
			$input['send_denied_user'] = ($input['send_denied_user'] == 1 ? 1 : 0);
			
			if (!isset($input['send_denied_admin'])) $input['send_denied_admin'] = null;
			$input['send_denied_admin'] = ($input['send_denied_admin'] == 1 ? 1 : 0);
			
			if (!isset($input['contact_cc_user'])) $input['contact_cc_user'] = null;
			$input['contact_cc_user'] = ($input['contact_cc_user'] == 1 ? 1 : 0);
			
			if (!isset($input['contact_stats'])) $input['contact_stats'] = null;
			$input['contact_stats'] = ($input['contact_stats'] == 1 ? 1 : 0);
			
			if (!isset($input['contact_custom'])) $input['contact_custom'] = null;
			$input['contact_custom'] = ($input['contact_custom'] == 1 ? 1 : 0);
			
			return $input;
		}
		// ADVANCED VALIDATE
		function validate_advanced($input) {
			global $allowedposttags;
			// dealing with kses
			$allowed_atts = array(
				'align'    => array(), 
				'class'    => array(), 
				'type'     => array(), 
				'id'       => array(), 
				'dir'      => array(), 
				'lang'     => array(), 
				'style'    => array(), 
				'xml:lang' => array(), 
				'src'      => array(), 
				'alt'      => array(),
				'href'     => array(), 
				'rel'      => array(), 
				'target'   => array(),
			);
			$allowedposttags['script'] = $allowed_atts;
			$allowedposttags['strong'] = $allowed_atts;
			$allowedposttags['small'] = $allowed_atts;
			$allowedposttags['span'] = $allowed_atts;
			$allowedposttags['abbr'] = $allowed_atts;
			$allowedposttags['code'] = $allowed_atts;
			$allowedposttags['div'] = $allowed_atts;
			$allowedposttags['img'] = $allowed_atts;
			$allowedposttags['h1'] = $allowed_atts;
			$allowedposttags['h2'] = $allowed_atts;
			$allowedposttags['h3'] = $allowed_atts;
			$allowedposttags['h4'] = $allowed_atts;
			$allowedposttags['h5'] = $allowed_atts;
			$allowedposttags['ol'] = $allowed_atts;
			$allowedposttags['ul'] = $allowed_atts;
			$allowedposttags['li'] = $allowed_atts;
			$allowedposttags['em'] = $allowed_atts;
			$allowedposttags['p'] = $allowed_atts;
			$allowedposttags['a'] = $allowed_atts;
			
			if (isset($input['custom_before']))  $input['custom_before']  = wp_kses_post($input['custom_before'], $allowedposttags);
			if (isset($input['custom_after']))   $input['custom_after']   = wp_kses_post($input['custom_after'], $allowedposttags);
			if (isset($input['success_before'])) $input['success_before'] = wp_kses_post($input['success_before'], $allowedposttags);
			if (isset($input['success_after']))  $input['success_after']  = wp_kses_post($input['success_after'], $allowedposttags);
			if (isset($input['error_before']))   $input['error_before']   = wp_kses_post($input['error_before'], $allowedposttags);
			if (isset($input['error_after']))    $input['error_after']    = wp_kses_post($input['error_after'], $allowedposttags);
			if (isset($input['custom_fields']))  $input['custom_fields']  = wp_kses_post($input['custom_fields'], $allowedposttags);
			
			if (isset($input['success_reg']))        $input['success_reg']        = wp_kses_post($input['success_reg'], $allowedposttags);
			if (isset($input['success_post']))       $input['success_post']       = wp_kses_post($input['success_post'], $allowedposttags);
			if (isset($input['success_both']))       $input['success_both']       = wp_kses_post($input['success_both'], $allowedposttags);
			if (isset($input['success_contact']))    $input['success_contact']    = wp_kses_post($input['success_contact'], $allowedposttags);
			if (isset($input['success_email_reg']))  $input['success_email_reg']  = wp_kses_post($input['success_email_reg'], $allowedposttags);
			if (isset($input['success_email_post'])) $input['success_email_post'] = wp_kses_post($input['success_email_post'], $allowedposttags);
			if (isset($input['success_email_both'])) $input['success_email_both'] = wp_kses_post($input['success_email_both'], $allowedposttags);
			
			// errors
			if (isset($input['usp_error_1'])) $input['usp_error_1'] = wp_filter_nohtml_kses($input['usp_error_1']);
			if (isset($input['usp_error_2'])) $input['usp_error_2'] = wp_filter_nohtml_kses($input['usp_error_2']);
			if (isset($input['usp_error_3'])) $input['usp_error_3'] = wp_filter_nohtml_kses($input['usp_error_3']);
			if (isset($input['usp_error_4'])) $input['usp_error_4'] = wp_filter_nohtml_kses($input['usp_error_4']);
			if (isset($input['usp_error_5'])) $input['usp_error_5'] = wp_filter_nohtml_kses($input['usp_error_5']);
			if (isset($input['usp_error_6'])) $input['usp_error_6'] = wp_filter_nohtml_kses($input['usp_error_6']);
			if (isset($input['usp_error_7'])) $input['usp_error_7'] = wp_filter_nohtml_kses($input['usp_error_7']);
			if (isset($input['usp_error_8'])) $input['usp_error_8'] = wp_filter_nohtml_kses($input['usp_error_8']);
			if (isset($input['usp_error_9'])) $input['usp_error_9'] = wp_filter_nohtml_kses($input['usp_error_9']);
			if (isset($input['usp_error_10'])) $input['usp_error_10'] = wp_filter_nohtml_kses($input['usp_error_10']);
			if (isset($input['usp_error_11'])) $input['usp_error_11'] = wp_filter_nohtml_kses($input['usp_error_11']);
			if (isset($input['usp_error_12'])) $input['usp_error_12'] = wp_filter_nohtml_kses($input['usp_error_12']);
			if (isset($input['usp_error_13'])) $input['usp_error_13'] = wp_filter_nohtml_kses($input['usp_error_13']);
			if (isset($input['usp_error_14'])) $input['usp_error_14'] = wp_filter_nohtml_kses($input['usp_error_14']);
			if (isset($input['usp_error_15'])) $input['usp_error_15'] = wp_filter_nohtml_kses($input['usp_error_15']);
			if (isset($input['usp_error_16'])) $input['usp_error_16'] = wp_filter_nohtml_kses($input['usp_error_16']);
			if (isset($input['usp_error_17'])) $input['usp_error_17'] = wp_filter_nohtml_kses($input['usp_error_17']);
			
			if (isset($input['usp_error_a'])) $input['usp_error_a'] = wp_filter_nohtml_kses($input['usp_error_a']);
			if (isset($input['usp_error_b'])) $input['usp_error_b'] = wp_filter_nohtml_kses($input['usp_error_b']);
			if (isset($input['usp_error_c'])) $input['usp_error_c'] = wp_filter_nohtml_kses($input['usp_error_c']);
			if (isset($input['usp_error_d'])) $input['usp_error_d'] = wp_filter_nohtml_kses($input['usp_error_d']);
			if (isset($input['usp_error_e'])) $input['usp_error_e'] = wp_filter_nohtml_kses($input['usp_error_e']);
			if (isset($input['usp_error_f'])) $input['usp_error_f'] = wp_filter_nohtml_kses($input['usp_error_f']);
			if (isset($input['usp_error_g'])) $input['usp_error_g'] = wp_filter_nohtml_kses($input['usp_error_g']);
			
			// custom fields
			foreach ($input as $key => $value) {
				if (preg_match("/^usp_label_c([0-9]+)$/i", $key, $match)) {
					if (isset($input['usp_label_c'. $match[1]])) $input['usp_label_c' .$match[1]] = wp_filter_nohtml_kses($input['usp_label_c'. $match[1]]);
					
				} elseif (preg_match("/^usp_custom_label_([0-9a-z_-]+)$/i", $key, $match)) {
					$custom_merged = usp_merge_custom_fields();
					if (in_array($match[1], $custom_merged)) $input['usp_custom_label_'. $match[1]] = wp_filter_nohtml_kses($input['usp_custom_label_'. $match[1]]);
				}
			}
			
			if (isset($input['submit_text'])) $input['submit_text'] = wp_filter_nohtml_kses($input['submit_text']);
			if (isset($input['html_content'])) $input['html_content'] = wp_filter_nohtml_kses($input['html_content']);
			if (isset($input['other_type'])) $input['other_type'] = wp_filter_nohtml_kses($input['other_type']);
			if (isset($input['post_type_slug'])) $input['post_type_slug'] = wp_filter_nohtml_kses($input['post_type_slug']);
			
			$post_type = $this->post_type_options();
			if (!isset($input['post_type'])) $input['post_type'] = null;
			if (!array_key_exists($input['post_type'], $post_type)) $input['post_type'] = null;
			
			if (isset($input['post_type_role'])) $input['post_type_role'] = is_array($input['post_type_role']) && !empty($input['post_type_role']) ? array_unique($input['post_type_role']) : array();
			if (isset($input['form_type_role'])) $input['form_type_role'] = is_array($input['form_type_role']) && !empty($input['form_type_role']) ? array_unique($input['form_type_role']) : array();
			
			if (!isset($input['success_form'])) $input['success_form'] = null;
			$input['success_form'] = ($input['success_form'] == 1 ? 1 : 0);
			
			if (!isset($input['enable_autop'])) $input['enable_autop'] = null;
			$input['enable_autop'] = ($input['enable_autop'] == 1 ? 1 : 0);

			if (!isset($input['submit_button'])) $input['submit_button'] = null;
			$input['submit_button'] = ($input['submit_button'] == 1 ? 1 : 0);

			if (!isset($input['fieldsets'])) $input['fieldsets'] = null;
			$input['fieldsets'] = ($input['fieldsets'] == 1 ? 1 : 0);
			
			if (!isset($input['form_demos'])) $input['form_demos'] = null;
			$input['form_demos'] = ($input['form_demos'] == 1 ? 1 : 0);
			
			if (!isset($input['post_demos'])) $input['post_demos'] = null;
			$input['post_demos'] = ($input['post_demos'] == 1 ? 1 : 0);
			
			if (isset($input['custom_prefix']))   $input['custom_prefix']   = preg_replace('/\s+/', '', wp_filter_nohtml_kses($input['custom_prefix']));
			if (isset($input['custom_optional'])) $input['custom_optional'] = preg_replace('/\s+/', '', wp_filter_nohtml_kses($input['custom_optional']));
			if (isset($input['custom_required'])) $input['custom_required'] = preg_replace('/\s+/', '', wp_filter_nohtml_kses($input['custom_required']));
			
			if (isset($input['form_atts'])) $input['form_atts'] = sanitize_text_field($input['form_atts']);
			
			return $input;
		}
		// MORE VALIDATE
		function validate_more($input) {
			global $allowedposttags;
			// dealing with kses
			$allowed_atts = array(
				'align'    => array(), 
				'class'    => array(), 
				'type'     => array(), 
				'id'       => array(), 
				'dir'      => array(), 
				'lang'     => array(), 
				'style'    => array(), 
				'xml:lang' => array(), 
				'src'      => array(), 
				'alt'      => array(),
				'href'     => array(), 
				'rel'      => array(), 
				'target'   => array(),
			);
			$allowedposttags['script'] = $allowed_atts;
			$allowedposttags['strong'] = $allowed_atts;
			$allowedposttags['small'] = $allowed_atts;
			$allowedposttags['span'] = $allowed_atts;
			$allowedposttags['abbr'] = $allowed_atts;
			$allowedposttags['code'] = $allowed_atts;
			$allowedposttags['div'] = $allowed_atts;
			$allowedposttags['img'] = $allowed_atts;
			$allowedposttags['h1'] = $allowed_atts;
			$allowedposttags['h2'] = $allowed_atts;
			$allowedposttags['h3'] = $allowed_atts;
			$allowedposttags['h4'] = $allowed_atts;
			$allowedposttags['h5'] = $allowed_atts;
			$allowedposttags['ol'] = $allowed_atts;
			$allowedposttags['ul'] = $allowed_atts;
			$allowedposttags['li'] = $allowed_atts;
			$allowedposttags['em'] = $allowed_atts;
			$allowedposttags['p'] = $allowed_atts;
			$allowedposttags['a'] = $allowed_atts;
			
			if (isset($input['tax_before']))          $input['tax_before']          = wp_kses_post($input['tax_before'], $allowedposttags);
			if (isset($input['tax_after']))           $input['tax_after']           = wp_kses_post($input['tax_after'], $allowedposttags);
			if (isset($input['custom_field_before'])) $input['custom_field_before'] = wp_kses_post($input['custom_field_before'], $allowedposttags);
			if (isset($input['custom_field_after']))  $input['custom_field_after']  = wp_kses_post($input['custom_field_after'], $allowedposttags);
			if (isset($input['error_sep']))           $input['error_sep']           = wp_kses_post($input['error_sep'], $allowedposttags);
			
			if (isset($input['usp_error_1_desc'])) $input['usp_error_1_desc'] = wp_kses_post($input['usp_error_1_desc'], $allowedposttags);
			if (isset($input['usp_error_2_desc'])) $input['usp_error_2_desc'] = wp_kses_post($input['usp_error_2_desc'], $allowedposttags);
			if (isset($input['usp_error_3_desc'])) $input['usp_error_3_desc'] = wp_kses_post($input['usp_error_3_desc'], $allowedposttags);
			if (isset($input['usp_error_4_desc'])) $input['usp_error_4_desc'] = wp_kses_post($input['usp_error_4_desc'], $allowedposttags);
			if (isset($input['usp_error_5_desc'])) $input['usp_error_5_desc'] = wp_kses_post($input['usp_error_5_desc'], $allowedposttags);
			if (isset($input['usp_error_6_desc'])) $input['usp_error_6_desc'] = wp_kses_post($input['usp_error_6_desc'], $allowedposttags);
			if (isset($input['usp_error_7_desc'])) $input['usp_error_7_desc'] = wp_kses_post($input['usp_error_7_desc'], $allowedposttags);
			if (isset($input['usp_error_8_desc'])) $input['usp_error_8_desc'] = wp_kses_post($input['usp_error_8_desc'], $allowedposttags);
			if (isset($input['usp_error_9_desc'])) $input['usp_error_9_desc'] = wp_kses_post($input['usp_error_9_desc'], $allowedposttags);
			if (isset($input['usp_error_10_desc'])) $input['usp_error_10_desc'] = wp_kses_post($input['usp_error_10_desc'], $allowedposttags);
			if (isset($input['usp_error_11_desc'])) $input['usp_error_11_desc'] = wp_kses_post($input['usp_error_11_desc'], $allowedposttags);
			if (isset($input['usp_error_12_desc'])) $input['usp_error_12_desc'] = wp_kses_post($input['usp_error_12_desc'], $allowedposttags);
			if (isset($input['usp_error_13_desc'])) $input['usp_error_13_desc'] = wp_kses_post($input['usp_error_13_desc'], $allowedposttags);
			if (isset($input['usp_error_14_desc'])) $input['usp_error_14_desc'] = wp_kses_post($input['usp_error_14_desc'], $allowedposttags);
			if (isset($input['usp_error_15_desc'])) $input['usp_error_15_desc'] = wp_kses_post($input['usp_error_15_desc'], $allowedposttags);
			if (isset($input['usp_error_16_desc'])) $input['usp_error_16_desc'] = wp_kses_post($input['usp_error_16_desc'], $allowedposttags);
			if (isset($input['usp_error_17_desc'])) $input['usp_error_17_desc'] = wp_kses_post($input['usp_error_17_desc'], $allowedposttags);
			
			if (isset($input['usp_error_a_desc'])) $input['usp_error_a_desc'] = wp_kses_post($input['usp_error_a_desc'], $allowedposttags);
			if (isset($input['usp_error_b_desc'])) $input['usp_error_b_desc'] = wp_kses_post($input['usp_error_b_desc'], $allowedposttags);
			if (isset($input['usp_error_c_desc'])) $input['usp_error_c_desc'] = wp_kses_post($input['usp_error_c_desc'], $allowedposttags);
			if (isset($input['usp_error_d_desc'])) $input['usp_error_d_desc'] = wp_kses_post($input['usp_error_d_desc'], $allowedposttags);
			if (isset($input['usp_error_e_desc'])) $input['usp_error_e_desc'] = wp_kses_post($input['usp_error_e_desc'], $allowedposttags);
			if (isset($input['usp_error_f_desc'])) $input['usp_error_f_desc'] = wp_kses_post($input['usp_error_f_desc'], $allowedposttags);
			
			if (isset($input['error_username']))  $input['error_username'] = wp_kses_post($input['error_username'], $allowedposttags);
			if (isset($input['error_email']))     $input['error_email']    = wp_kses_post($input['error_email'], $allowedposttags);
			if (isset($input['error_register']))  $input['error_register'] = wp_kses_post($input['error_register'], $allowedposttags);
			if (isset($input['user_exists']))     $input['user_exists']    = wp_kses_post($input['user_exists'], $allowedposttags);
			if (isset($input['post_required']))   $input['upost_required'] = wp_kses_post($input['post_required'], $allowedposttags);
			if (isset($input['post_duplicate']))  $input['post_duplicate'] = wp_kses_post($input['post_duplicate'], $allowedposttags);
			if (isset($input['form_allowed']))    $input['form_allowed']   = wp_kses_post($input['form_allowed'], $allowedposttags);
			
			if (isset($input['name_restrict']))    $input['name_restrict']    = wp_kses_post($input['name_restrict'], $allowedposttags);
			if (isset($input['spam_response']))    $input['spam_response']    = wp_kses_post($input['spam_response'], $allowedposttags);
			if (isset($input['content_min']))      $input['content_min']      = wp_kses_post($input['content_min'], $allowedposttags);
			if (isset($input['content_max']))      $input['content_max']      = wp_kses_post($input['content_max'], $allowedposttags);
			if (isset($input['email_restrict']))   $input['email_restrict']   = wp_kses_post($input['email_restrict'], $allowedposttags);
			if (isset($input['subject_restrict'])) $input['subject_restrict'] = wp_kses_post($input['subject_restrict'], $allowedposttags);
			
			if (isset($input['min_req_files'])) $input['min_req_files'] = wp_kses_post($input['min_req_files'], $allowedposttags);
			if (isset($input['max_req_files'])) $input['max_req_files'] = wp_kses_post($input['max_req_files'], $allowedposttags);
			
			if (isset($input['files_required']))  $input['files_required']  = wp_kses_post($input['files_required'], $allowedposttags);
			if (isset($input['file_type_not']))   $input['file_type_not']   = wp_kses_post($input['file_type_not'], $allowedposttags);
			if (isset($input['file_dimensions'])) $input['file_dimensions'] = wp_kses_post($input['file_dimensions'], $allowedposttags);
			if (isset($input['file_max_size']))   $input['file_max_size']   = wp_kses_post($input['file_max_size'], $allowedposttags);
			if (isset($input['file_min_size']))   $input['file_min_size']   = wp_kses_post($input['file_min_size'], $allowedposttags);
			if (isset($input['file_required']))   $input['file_required']   = wp_kses_post($input['file_required'], $allowedposttags);
			if (isset($input['file_name']))       $input['file_name']       = wp_kses_post($input['file_name'], $allowedposttags);
			
			if (isset($input['default_title']))   $input['default_title']   = wp_filter_nohtml_kses($input['default_title']);
			if (isset($input['default_content'])) $input['default_content'] = wp_kses_post($input['default_content'], $allowedposttags);
			
			if (!isset($input['default_options'])) $input['default_options'] = null;
			$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);
			
		return $input;
		}
		// CALLBACKS
		function callback_input_text($args) {
			global $usp_advanced;
			$id = $args['id'];
			$type = $args['type'];
			if     ($id == 'submit_text')  $label = __('Text for submit button when &ldquo;Auto-include submit button&rdquo; option is enabled', 'usp');
			elseif ($id == 'html_content') $label = __('By default no HTML tags are allowed in submitted post content. Here you may enter any specific tags that should be allowed (comma-separated list of tag names without brackets). 
												For example, some tags used in WP&rsquo;s visual/rich-text editor include: <code>&lt;strong&gt;</code>, <code>&lt;em&gt;</code>, and <code>&lt;code&gt;</code>. 
												To allow these three tags in submitted post content, this option should look like this: &ldquo;strong, em, code&rdquo; (without the quotes). 
												You may allow any HTML you wish, but please only do so if you are aware of any security risks that may be involved. 
												If at all in doubt, simply leave this option blank to disable all HTML tags in post content.', 'usp');

			elseif ($id == 'usp_error_1')  $label = __('Name for the &ldquo;Your Name&rdquo; field', 'usp');
			elseif ($id == 'usp_error_2')  $label = __('Name for the &ldquo;Post URL&rdquo; field', 'usp');
			elseif ($id == 'usp_error_3')  $label = __('Name for the &ldquo;Post Title&rdquo; field', 'usp');
			elseif ($id == 'usp_error_4')  $label = __('Name for the &ldquo;Post Tags&rdquo; field', 'usp');
			elseif ($id == 'usp_error_5')  $label = __('Name for the &ldquo;Challenge Question&rdquo; or &ldquo;reCAPTCHA&rdquo; field (whichever is enabled)', 'usp');
			elseif ($id == 'usp_error_6')  $label = __('Name for the &ldquo;Post Category&rdquo; field', 'usp');
			elseif ($id == 'usp_error_7')  $label = __('Name for the &ldquo;Post Content&rdquo; field', 'usp');
			elseif ($id == 'usp_error_8')  $label = __('Name for the &ldquo;File(s)&rdquo; field', 'usp');
			elseif ($id == 'usp_error_9')  $label = __('Name for the &ldquo;Email Address&rdquo; field', 'usp');
			elseif ($id == 'usp_error_10') $label = __('Name for the &ldquo;Email Subject&rdquo; field', 'usp');
			elseif ($id == 'usp_error_11') $label = __('Name for the &ldquo;Alt Text&rdquo; field', 'usp');
			elseif ($id == 'usp_error_12') $label = __('Name for the &ldquo;Caption&rdquo; field', 'usp');
			elseif ($id == 'usp_error_13') $label = __('Name for the &ldquo;Description&rdquo; field', 'usp');
			elseif ($id == 'usp_error_14') $label = __('Name for the &ldquo;Taxonomy&rdquo; field', 'usp');
			elseif ($id == 'usp_error_15') $label = __('Name for the &ldquo;Post-Format&rdquo; input field (available as a custom-field input, see Tools for more info)', 'usp');
			elseif ($id == 'usp_error_16') $label = __('Name for the &ldquo;Media Title&rdquo; field', 'usp');
			elseif ($id == 'usp_error_17') $label = __('Name for the &ldquo;File Name&rdquo; field', 'usp');

			elseif ($id == 'usp_error_a')  $label = __('Name for the &ldquo;User Nicename&rdquo; field', 'usp');
			elseif ($id == 'usp_error_b')  $label = __('Name for the &ldquo;User Display Name&rdquo; field', 'usp');
			elseif ($id == 'usp_error_c')  $label = __('Name for the &ldquo;User Nickname&rdquo; field', 'usp');
			elseif ($id == 'usp_error_d')  $label = __('Name for the &ldquo;User First Name&rdquo; field', 'usp');
			elseif ($id == 'usp_error_e')  $label = __('Name for the &ldquo;User Last Name&rdquo; field', 'usp');
			elseif ($id == 'usp_error_f')  $label = __('Name for the &ldquo;User Description&rdquo; field', 'usp');
			elseif ($id == 'usp_error_g')  $label = __('Name for the &ldquo;User Password&rdquo; field', 'usp');
			
			elseif ($id == 'contact_sub_prefix') $label = __('Here you may enter some text to prepend to the Subject line. Leave blank to disable.', 'usp');
			elseif ($id == 'contact_subject')    $label = __('Default Subject Line for messages sent via contact form. This is used when a "Subject" input is not included in the form.', 'usp');
			elseif ($id == 'contact_cc')         $label = __('List of email addresses that should be carbon-copied (Bcc) for messages sent via contact form (comma-separated list)', 'usp');
			elseif ($id == 'contact_from')       $label = __('Default Email address to be specified as the &ldquo;From&rdquo; address (to override the default address, include', 'usp') .' <code>[usp_email]</code> '. __('in the contact form).', 'usp');
			
			elseif ($id == 'redirect_success')   $label = __('Where should the visitor go after successful submission? Enter any complete URL (e.g., <code>http://example.com</code>) or leave blank to redirect to the current page.', 'usp');
			elseif ($id == 'redirect_failure')   $label = __('Where should the visitor go after failed submission? Enter any complete URL (e.g., <code>http://example.com</code>) or leave blank to redirect to the current page. 
													Note: this option is for advanced users; recommended to leave blank. See docs for more info.', 'usp');
			
			elseif ($id == 'captcha_question')  $label = __('To prevent spam, enter a question that users must answer before submitting the form. Note that the captcha field must be included in the form in order for this to work.', 'usp');
			elseif ($id == 'captcha_response')  $label = __('Enter the <em>only</em> correct answer to the challenge question.', 'usp');
			elseif ($id == 'recaptcha_public')  $label = __('Use Google reCAPTCHA instead of challenge question. To enable, enter your Public Key and Private Key, or leave both fields blank to use the default challenge question.', 'usp');
			elseif ($id == 'recaptcha_private') $label = __('Use Google reCAPTCHA instead of challenge question. To enable, enter your Public Key and Private Key, or leave both fields blank to use the default challenge question.', 'usp');
			elseif ($id == 'post_type_slug')    $label = __('Enter the slug to use when &ldquo;USP Posts (custom post type)&rdquo; is selected for the option &ldquo;Submitted Post Type&rdquo;. Default:', 'usp') .
													' <code>usp_post</code>. '. __('Important: the slug must not be the same as that for any existing Page (e.g., don&rsquo;t use &ldquo;about&rdquo; or &ldquo;contact&rdquo;). 
													Also, after specifying this option and saving your changes, visit your', 'usp') . ' <a href="'. get_admin_url() .'options-permalink.php">Permalinks Settings</a> ' . 
													__('to workaround a well-known WP bug). After that you should be good to go for custom post types.', 'usp');
			
			elseif ($id == 'use_cat_id')          $label = __('Specify a category ID to use as the default category when using the &ldquo;Use Hidden Category&rdquo; option (separate multiple ID&rsquo;s with commas)', 'usp');
			
			elseif ($id == 'admin_email')         $label = __('Email address to be notified when a new post is submitted', 'usp');
			elseif ($id == 'admin_name')          $label = __('Name of email recipient to be notified when a new post is submitted', 'usp');
			elseif ($id == 'admin_from')          $label = __('Email address specified in the &ldquo;From&rdquo; header of email alerts', 'usp');
			elseif ($id == 'cc_submit')           $label = __('List of email addresses that should be carbon-copied (Bcc) for submitted-post alerts (comma-separated list)', 'usp');
			elseif ($id == 'cc_approval')         $label = __('List of email addresses that should be carbon-copied (Bcc) when posts are approved (comma-separated list)', 'usp');
			elseif ($id == 'cc_denied')           $label = __('List of email addresses that should be carbon-copied (Bcc) when posts are denied (comma-separated list)', 'usp');
			
			elseif ($id == 'alert_subject_admin')    $label = __('Subject line for email alerts sent to the admin (and to any cc&rsquo;d addresses)', 'usp');
			elseif ($id == 'approval_subject_admin') $label = __('Subject line for email alert sent to user when post is approved/published', 'usp');
			elseif ($id == 'alert_subject_user')     $label = __('Subject line for email alerts sent to the user', 'usp');
			elseif ($id == 'approval_subject')       $label = __('Subject line for email alert sent to user when post is approved/published', 'usp');
			elseif ($id == 'denied_subject')         $label = __('Subject line for email alert sent to user when post is denied (sent to Trash)', 'usp');
			elseif ($id == 'denied_subject_admin')   $label = __('Subject line for email alert sent to the admin when post is denied (sent to Trash)', 'usp');
			
			elseif ($id == 'character_min')       $label = __('Specify the minimum number of characters allowed for the content-field. Leave set at &ldquo;0&rdquo; (without the quotes) for no minimum.', 'usp');
			elseif ($id == 'character_max')       $label = __('Specify the maximum number of characters allowed for the content-field. Leave set at &ldquo;0&rdquo; (without the quotes) for no maximum.', 'usp');
			elseif ($id == 'other_type')          $label = __('If you selected &ldquo;Existing Post Type&rdquo; from the previous option &ldquo;Submitted Post Type&rdquo;, 
													please enter an', 'usp') . ' <strong>' .__('existing', 'usp') . '</strong> ' . __('custom post type to use for submitted content. 
													Note: this option does nothing unless &ldquo;other&rdquo; is selected in the previous option.', 'usp');
													
			elseif ($id == 'tags_number')         $label = __('Enter the number of tags that should be displayed above (does not affect front-end display): Enter &ldquo;all&rdquo; (without quotes) to display all. Note: any tags not shown will be deselected.', 'usp');
			
			elseif ($id == 'min_width')           $label = __('Specify a', 'usp') .' <em>'. __('minimum width', 'usp')  .'</em> '. __('(in pixels) for uploaded images.', 'usp');
			elseif ($id == 'max_width')           $label = __('Specify a', 'usp') .' <em>'. __('maximum width', 'usp')  .'</em> '. __('(in pixels) for uploaded images.', 'usp');
			elseif ($id == 'min_height')          $label = __('Specify a', 'usp') .' <em>'. __('minimum height', 'usp') .'</em> '. __('(in pixels) for uploaded images.', 'usp');
			elseif ($id == 'max_height')          $label = __('Specify a', 'usp') .' <em>'. __('maximum height', 'usp') .'</em> '. __('(in pixels) for uploaded images.', 'usp');
			elseif ($id == 'max_size')            $label = __('Specify a', 'usp') .' <em>'. __('maximum size', 'usp') .'</em> '. __('(in bytes) for uploaded files (applies to all file types). Default: &ldquo;5242880&rdquo; (= 5 MB)', 'usp');
			elseif ($id == 'min_size')            $label = __('Specify a', 'usp') .' <em>'. __('minimum size', 'usp') .'</em> '. __('(in bytes) for uploaded files (applies to all file types). Default: &ldquo;25600&rdquo; (= 25 KB)', 'usp');
			
			elseif ($id == 'files_allow')         $label = __('Specify which file types should be allowed for uploads (comma-separated list). This list should contain all file types that will be allowed for any USP Form. 
														This list of allowed file-types may be further restricted on a per-form basis using the', 'usp') . ' <code>[usp_files]</code> ' . __(' shortcode. 
														Visit Tools &gt; Shortcodes for details. For a complete list of allowed types, visit the', 'usp') . 
														' <a href="http://codex.wordpress.org/Function_Reference/get_allowed_mime_types#Default_allowed_mime_types" title="Default allowed mime types" target="_blank">WP Codex</a>.';
			elseif ($id == 'contact_cc_note')     $label = __('Here you may customize the CC message that is displayed to the visitor (when the option &ldquo;CC User&rdquo; is enabled).', 'usp');
			elseif ($id == 'featured_key')        $label = __('If &ldquo;Featured Images&rdquo; is enabled, which uploaded image do want to use for the featured image? Default &ldquo;1&rdquo;. Note: if image doesn&rsquo;t exist, no featured image will be set.', 'usp');
			elseif ($id == 'include_url')         $label = __('When enabled, external CSS &amp; JavaScript files are loaded on every page. Here you may specify URL(s) for targeted loading of resources. Separate multiple URLs with a comma. Note: leave blank to load on all pages.', 'usp');
			elseif ($id == 'custom_status')       $label = __('Default Custom Post Status (when &ldquo;Auto Publish Posts&rdquo; is set to &ldquo;Always moderate via Custom Status&rdquo;)', 'usp');
			
			elseif ($id == 'custom_contact_1')    $label = __('Email address to use for custom contact #1', 'usp');
			elseif ($id == 'custom_contact_2')    $label = __('Email address to use for custom contact #2', 'usp');
			elseif ($id == 'custom_contact_3')    $label = __('Email address to use for custom contact #3', 'usp');
			elseif ($id == 'custom_contact_4')    $label = __('Email address to use for custom contact #4', 'usp');
			elseif ($id == 'custom_contact_5')    $label = __('Email address to use for custom contact #5', 'usp');
			
			elseif ($id == 'custom_prefix')       $label = __('Unique prefix to use for custom field names (leave blank to disable)', 'usp');
			elseif ($id == 'custom_optional')     $label = __('Enter your own optional custom field names (leave blank to disable)', 'usp');
			elseif ($id == 'custom_required')     $label = __('Enter your own required custom field names (leave blank to disable)', 'usp');
			elseif ($id == 'default_title')       $label = __('Specify the default post title', 'usp');
			elseif ($id == 'form_atts')           $label = __('Specify any custom attributes that should be added to the', 'usp') .' <code>&lt;form&gt;</code> '. __('tag', 'usp');
			elseif ($id == 'submit_form_ids')     $label = __('Specify the IDs of any live post-submission forms (include any combo forms)', 'usp');
			elseif ($id == 'register_form_ids')   $label = __('Specify the IDs of any live user-registration forms (include any combo forms)', 'usp');
			elseif ($id == 'contact_form')        $label = __('Specify the IDs of any live contact forms (include any combo forms)', 'usp');
			
			if ($type == 'more') {
				if ($id == 'default_title') {
					$value = esc_attr($this->more_settings[$id]);
				}
			} elseif ($type == 'admin') {
				if ($id == 'cc_submit' || $id == 'contact_cc') {
					$cc_submit_emails = trim(esc_attr($this->admin_settings[$id]));
					$cc_submit_emails = explode(",", $cc_submit_emails);
					$cc_submit_list = '';
					foreach ($cc_submit_emails as $email) $cc_submit_list .= trim($email) . ', ';
					$value = rtrim(trim($cc_submit_list), ',');
				} else {
					$value = esc_attr($this->admin_settings[$id]);
				}
			} elseif ($type == 'advanced') {
				if (preg_match("/^usp_label_c([0-9]+)$/i", $id, $match)) $label = __('Name for Custom Field #', 'usp') . $match[1];
				elseif (preg_match("/^usp_custom_label_([0-9a-z_-]+)$/i", $id, $match)) $label = __('Name for Custom Field:', 'usp') .' <code>'. $match[1] .'</code>';
				
				if (isset($this->advanced_settings[$id])) $value = esc_attr($this->advanced_settings[$id]);
				else $value = '';
	
			} elseif ($type == 'general') {
				if ($id == 'use_cat_id') {
					$cat_ids = trim(esc_attr($this->general_settings[$id]));
					$cat_ids = explode(",", $cat_ids);
					$cat_id_list = '';
					foreach ($cat_ids as $cat_id) $cat_id_list .= trim($cat_id) . ', ';
					$value = rtrim(trim($cat_id_list), ',');
				} else {
					$value = esc_attr($this->general_settings[$id]);
				}
			} elseif ($type == 'uploads') {
				$value = esc_attr($this->uploads_settings[$id]);
			} elseif ($type == 'style') {
				$value = esc_attr($this->style_settings[$id]);
			}
			if ($id == 'tags_number' || $id == 'use_cat_id' || $id == 'character_min' || $id == 'character_max') {
				$width = 'width:77px;';
				$break = ' ';
			} else {
				$width = 'width:377px;';
				$break = '<br />';
			}
			echo '<input name="usp_' . $type . '['. $id .']" type="text" value="' . $value . '" style="' . $width . '" />';
			echo $break . '<label for="usp_' . $type . '['. $id .']">' . $label . '</label>';
		}
		function callback_textarea($args) {
			$id   = $args['id'];
			$type = $args['type'];
			if     ($id == 'custom_before')          $label = __('Enter some text/markup to be included before all USP Forms', 'usp');
			elseif ($id == 'custom_after')           $label = __('Enter some text/markup to be included after all USP Forms', 'usp');
			
			elseif ($id == 'post_alert_admin')       $label = __('Message sent to admin for new submitted posts (markup allowed if HTML-formatting is enabled)', 'usp');
			elseif ($id == 'post_alert_user')        $label = __('Message sent to user for new submitted posts (markup allowed if HTML-formatting is enabled)', 'usp');
			elseif ($id == 'approval_message')       $label = __('Message sent to user when post is published (markup allowed if HTML-formatting is enabled)', 'usp');
			elseif ($id == 'approval_message_admin') $label = __('Message sent to admin when post is published (markup allowed if HTML-formatting is enabled)', 'usp');
			elseif ($id == 'denied_message')         $label = __('Message sent to user when post is sent to Trash (markup allowed if HTML-formatting is enabled)', 'usp');
			elseif ($id == 'denied_message_admin')   $label = __('Message sent to admin when post is sent to Trash (markup allowed if HTML-formatting is enabled)', 'usp');
			elseif ($id == 'custom_content')         $label = __('Custom content that should be included in email sent via contact forms (applies to all contact forms). ', 'usp') . 
														'<strong>'. __('Note:', 'usp') .'</strong> '. __('for combo contact/post-submit forms, you can use any of the shortcut variables defined above.', 'usp');
			
			elseif ($id == 'success_reg')            $label = __('Message displayed when a user is registered successfully', 'usp');
			elseif ($id == 'success_post')           $label = __('Message displayed when a post is submitted successfully', 'usp');
			elseif ($id == 'success_both')           $label = __('Message displayed when user is registered and post is submitted', 'usp');
			elseif ($id == 'success_contact')        $label = __('Message displayed when email is sent via contact form', 'usp');
			elseif ($id == 'success_email_reg')      $label = __('Message displayed when email is sent and user is registered', 'usp');
			elseif ($id == 'success_email_post')     $label = __('Message displayed when email is sent and post is submitted', 'usp');
			elseif ($id == 'success_email_both')     $label = __('Message displayed when email is sent, user is registered, and post is submitted', 'usp');
			
			elseif ($id == 'error_before')           $label = __('Custom text/markup to appear before the listed errors', 'usp');
			elseif ($id == 'error_after')            $label = __('Custom text/markup to appear after the listed errors', 'usp');
			elseif ($id == 'success_before')         $label = __('Custom text/markup to appear before the success message', 'usp');
			elseif ($id == 'success_after')          $label = __('Custom text/markup to appear after the success message', 'usp');

			elseif ($id == 'style_min')              $label = __('CSS for &ldquo;minimal&rdquo; form style (edit as needed to fine-tune for your theme)', 'usp');
			elseif ($id == 'style_small')            $label = __('CSS for &ldquo;small&rdquo; form style (edit as needed to fine-tune for your theme)', 'usp');
			elseif ($id == 'style_large')            $label = __('CSS for &ldquo;large&rdquo; form style (edit as needed to fine-tune for your theme)', 'usp');
			elseif ($id == 'style_custom')           $label = __('CSS for &ldquo;custom&rdquo; form style (edit as needed to fine-tune for your theme)', 'usp');
			elseif ($id == 'script_custom')          $label = __('Here you may add some custom JavaScript (included inline and separately from external JS file, leave blank to disable)', 'usp');
			
			elseif ($id == 'tax_before')             $label = __('Text/markup to appear before each taxonomy error', 'usp');
			elseif ($id == 'tax_after')              $label = __('Text/markup to appear after each taxonomy error', 'usp');
			elseif ($id == 'custom_field_before')    $label = __('Text/markup to appear before each custom field error', 'usp');
			elseif ($id == 'custom_field_after')     $label = __('Text/markup to appear after each custom field error', 'usp');
			elseif ($id == 'error_sep')              $label = __('Text/markup to appear between each error (e.g., <code>, </code> or <code>&lt;span class="usp-error-sep"&gt;, &lt;/span&gt;</code>)', 'usp');
			
			elseif ($id == 'usp_error_1_desc')       $label = __('Text/markup for Name errors &ndash; when using shortcode <code>[usp_name]</code>', 'usp');
			elseif ($id == 'usp_error_2_desc')       $label = __('Text/markup for URL errors &ndash; when using shortcode <code>[usp_url]</code>', 'usp');
			elseif ($id == 'usp_error_3_desc')       $label = __('Text/markup for Title errors &ndash; when using shortcode <code>[usp_title]</code>', 'usp');
			elseif ($id == 'usp_error_4_desc')       $label = __('Text/markup for Tags errors &ndash; when using shortcode <code>[usp_tags]</code>', 'usp');
			elseif ($id == 'usp_error_5_desc')       $label = __('Text/markup for Captcha errors &ndash; when using shortcode <code>[usp_captcha]</code>', 'usp');
			elseif ($id == 'usp_error_6_desc')       $label = __('Text/markup for Category errors &ndash; when using shortcode <code>[usp_category]</code>', 'usp');
			elseif ($id == 'usp_error_7_desc')       $label = __('Text/markup for Content errors &ndash; when using shortcode <code>[usp_content]</code>', 'usp');
			elseif ($id == 'usp_error_8_desc')       $label = __('Text/markup for File(s) errors &ndash; when using shortcode <code>[usp_files]</code>', 'usp');
			elseif ($id == 'usp_error_9_desc')       $label = __('Text/markup for Email Address errors &ndash; when using shortcode <code>[usp_email]</code>', 'usp');
			elseif ($id == 'usp_error_10_desc')      $label = __('Text/markup for Email Subject errors &ndash; when using shortcode <code>[usp_subject]</code>', 'usp');
			elseif ($id == 'usp_error_11_desc')      $label = __('Text/markup for Alt Text errors &ndash; when using shortcode <code>[usp_custom_field]</code> with attribute <code>name#alt-{id}</code>', 'usp');
			elseif ($id == 'usp_error_12_desc')      $label = __('Text/markup for Caption errors &ndash; when using shortcode <code>[usp_custom_field]</code> with attribute <code>name#caption-{id}</code>', 'usp');
			elseif ($id == 'usp_error_13_desc')      $label = __('Text/markup for Description errors &ndash; when using shortcode <code>[usp_custom_field]</code> with attribute <code>name#desc-{id}</code>', 'usp');
			elseif ($id == 'usp_error_14_desc')      $label = __('Text/markup for Taxonomy errors &ndash; when using shortcode <code>[usp_taxonomy]</code>', 'usp');
			elseif ($id == 'usp_error_15_desc')      $label = __('Text/markup for Post-Format errors (via', 'usp').' <a href="https://plugin-planet.com/usp-pro-shortcodes/#post-format">'.__('custom field', 'usp').'</a> '. __('or', 'usp').' <a href="https://plugin-planet.com/usp-pro-custom-post-formats/">'.__('hidden field', 'usp').'</a>)';
			elseif ($id == 'usp_error_16_desc')      $label = __('Text/markup for Media Title errors &ndash; when using shortcode <code>[usp_custom_field]</code> with attribute <code>name#mediatitle-{id}</code>', 'usp');
			elseif ($id == 'usp_error_17_desc')      $label = __('Text/markup for File Name errors &ndash; when using shortcode <code>[usp_custom_field]</code> with attribute <code>name#filename-{id}</code>', 'usp');
			
			elseif ($id == 'usp_error_a_desc')       $label = __('Text/markup for the &ldquo;User Nicename&rdquo; field', 'usp');
			elseif ($id == 'usp_error_b_desc')       $label = __('Text/markup for the &ldquo;User Display Name&rdquo; field', 'usp');
			elseif ($id == 'usp_error_c_desc')       $label = __('Text/markup for the &ldquo;User Nickname&rdquo; field', 'usp');
			elseif ($id == 'usp_error_d_desc')       $label = __('Text/markup for the &ldquo;User First Name&rdquo; field', 'usp');
			elseif ($id == 'usp_error_e_desc')       $label = __('Text/markup for the &ldquo;User Last Name&rdquo; field', 'usp');
			elseif ($id == 'usp_error_f_desc')       $label = __('Text/markup for the &ldquo;User Description&rdquo; field', 'usp');
			elseif ($id == 'usp_error_g_desc')       $label = __('Text/markup for the &ldquo;User Password&rdquo; field', 'usp');
		
			elseif ($id == 'error_username')         $label = __('Text/markup for Username errors (when using a form that registers users)', 'usp');
			elseif ($id == 'error_email')            $label = __('Text/markup for User Email errors (when using a form that registers users)', 'usp');
			elseif ($id == 'error_register')         $label = __('Text/markup for Registration Disabled errors (when using a form that registers users)', 'usp');
			elseif ($id == 'user_exists')            $label = __('Text/markup for User Exists errors (when using a form that registers users)', 'usp');
			elseif ($id == 'post_required')          $label = __('Text/markup for Post Required errors (when using a form that submits posts)', 'usp');
			elseif ($id == 'post_duplicate')         $label = __('Text/markup for Duplicate Post errors (when using a form that submits posts)', 'usp');
			elseif ($id == 'form_allowed')           $label = __('Text/markup for incorrect form type (applies when &ldquo;Form Security&rdquo; is enabled in General settings)', 'usp');
			
			elseif ($id == 'name_restrict')         $label = __('Text/markup for illegal characters in the Name field', 'usp');
			elseif ($id == 'spam_response')         $label = __('Text/markup for incorrect response for the anti-spam captcha/challenge question', 'usp');
			elseif ($id == 'content_min')           $label = __('Text/markup for the Content field when the minimum number of characters is not met', 'usp');
			elseif ($id == 'content_max')           $label = __('Text/markup for the Content field when the minimum number of characters is not met', 'usp');
			elseif ($id == 'email_restrict')        $label = __('Text/markup for illegal characters in the Email Address field', 'usp');
			elseif ($id == 'subject_restrict')      $label = __('Text/markup for illegal characters in the Email Subject field', 'usp');
			
			elseif ($id == 'files_required')        $label = __('Text/markup for required files (multiple selected files)', 'usp');
			elseif ($id == 'file_required')         $label = __('Text/markup for required file (single selected files)', 'usp');
			elseif ($id == 'file_type_not')         $label = __('Text/markup for disallowed file types', 'usp');
			elseif ($id == 'file_dimensions')       $label = __('Text/markup for file width and height', 'usp');
			elseif ($id == 'file_max_size')         $label = __('Text/markup for maximum file size', 'usp');
			elseif ($id == 'file_min_size')         $label = __('Text/markup for minimum file size', 'usp');
			elseif ($id == 'file_name')             $label = __('Text/markup for length of file name', 'usp');
			elseif ($id == 'min_req_files')         $label = __('Text/markup for minimum number of files', 'usp');
			elseif ($id == 'max_req_files')         $label = __('Text/markup for maximum number of files', 'usp');
			
			elseif ($id == 'default_content')       $label = __('Specify the default post content (HTML allowed)', 'usp');
			elseif ($id == 'blacklist_terms')       $label = __('List of terms that are not allowed in any submitted post content (put each term on its own line)', 'usp');
			
			if ($type == 'admin') {
				echo '<textarea name="usp_'. $type .'['. $id .']" rows="3" cols="70">'. esc_attr(stripslashes($this->admin_settings[$id])) .'</textarea>';
			} elseif ($type == 'advanced') {
				echo '<textarea name="usp_'. $type .'['. $id .']" rows="3" cols="70">'. esc_attr(stripslashes($this->advanced_settings[$id])) .'</textarea>';
			} elseif ($type == 'general') {
				echo '<textarea name="usp_'. $type .'['. $id .']" rows="3" cols="70">'. esc_attr(stripslashes($this->general_settings[$id])) .'</textarea>';
			} elseif ($type == 'style') {
				echo '<textarea name="usp_'. $type .'['. $id .']" rows="10" cols="70">'. esc_attr(stripslashes($this->style_settings[$id])) .'</textarea>';
			} elseif ($type == 'more') {
				echo '<textarea name="usp_'. $type .'['. $id .']" rows="3" cols="70">'. esc_attr(stripslashes($this->more_settings[$id])) .'</textarea>';
			}
			
			echo '<br /><label for="usp_'. $type .'['. $id .']">'. $label .'</label>';
		}
		function callback_select($args) {
			$id = $args['id'];
			$label = __('Undefined', 'usp');
			
			if     ($id == 'min_files')    $label = __('Specify the minimum number of files that may be uploaded (per file-upload field)', 'usp');
			elseif ($id == 'max_files')    $label = __('Specify the maximum number of files that may be uploaded (per file-upload field)', 'usp');
			elseif ($id == 'display_size') $label = __('Specify the size at which auto-displayed images should be displayed (see previous option)', 'usp');                        
			elseif ($id == 'mail_format')  $label = __('Specify the format to use for all emails (contact form and email alerts). ', 'usp') .'<br /><strong>'. __('Note:', 'usp') .'</strong> '.
											__('to allow users to include HTML markup in their email messages, the option &ldquo;Allowed HTML in post&rdquo; must be enabled (see Advanced &gt; Form Configuration).', 'usp');
			
			if ($id == 'min_files' || $id == 'max_files') {
				echo '<select name="usp_uploads['. $id .']">';
				echo '<option value="-1">'. __('No Limit', 'usp') .'</option>';
				foreach(range(0, 99) as $number) {
					echo '<option '. selected($number, $this->uploads_settings[$id], false) .' value="'. $number .'">'. $number .'</option>';
				}
			} elseif ($id == 'display_size') {
				echo '<select name="usp_uploads['. $id .']">';
				$display_sizes = $this->display_size_options();
				foreach ($display_sizes as $value) {
					echo '<option '. selected($value['value'], $this->uploads_settings[$id], false) .' value="'. $value['value'] .'">'. $value['label'] .'</option>';
				}
			} elseif ($id == 'mail_format') {
				echo '<select name="usp_admin['. $id .']">';
				$mail_format = $this->mail_format();
				foreach ($mail_format as $value) {
					echo '<option '. selected($value['value'], $this->admin_settings[$id], false) .' value="'. $value['value'] .'">'. $value['label'] .'</option>';
				}
			}
			echo '</select> ';
			echo '<label for="usp_uploads['. $id .']">'. $label .'</label>';
		}
		function callback_checkboxes($args) {
			global $usp_general;
			$id = $args['id'];
			if ($id == 'tags') {
				if (isset($usp_general['tags_order'])) $tags_order = $usp_general['tags_order'];
				else $tags_order = 'name_asc';
				if ($tags_order == 'id_asc' || $tags_order == 'name_asc' || $tags_order == 'count_asc') $order = 'ASC';
				else $order = 'DESC';

				if     ($tags_order == 'id_asc' || $tags_order == 'id_desc') $order_by = 'id';
				elseif ($tags_order == 'name_asc' || $tags_order == 'name_desc') $order_by = 'name';
				elseif ($tags_order == 'count_asc' || $tags_order == 'count_desc') $order_by = 'count';
				else $order_by = 'name';
				
				if (isset($usp_general['tags_number'])) $number = $usp_general['tags_number'];
				else $number = '-1';
				if ($number == '-1' || $number == '0' || $number == 'all') $number = '';
				
				if (isset($usp_general['tags_empty'])) $empty = $usp_general['tags_empty'];
				else $empty = 0;

				$args = array(
					'orderby'    => $order_by,
					'order'      => $order,
					'number'     => $number,
					'hide_empty' => $empty, 
				); 
				$tags = get_terms('post_tag', $args);
				echo '<p><label>' . __('Select which tags may be assigned to submitted posts: ', 'usp');
				echo '<a id="usp-toggle-tags" class="usp-toggle-tags" href="#usp-toggle-tags">'. __('Show/Hide Tags&nbsp;&raquo;', 'usp') .'</a></label></p>';
				echo '<div class="usp-tags default-hidden"><ul>';
				foreach ((array) $tags as $tag) {
					echo '<li><input type="checkbox" name="usp_general[tags][]" value="'. $tag->term_id .'" '. checked(true, in_array($tag->term_id, $this->general_settings['tags']), false) .' /> ';
					echo '<label for="usp_general[tags][]"><a href="'. get_tag_link($tag->term_id) .'" title="Tag ID: '. $tag->term_id .'" target="_blank">'. sanitize_text_field($tag->name) .'</a></label></li>';
				}
				echo '</ul></div>';
			} elseif ($id == 'categories') {
				
				$usp_cats = array();
				$cats = get_categories(array('parent' => 0, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 0));
				if (!empty($cats)) {
					echo '<style type="text/css">ul.usp-cats ul { margin: 5px 0 5px 30px; } ul.usp-cats li { margin: 0; }</style>';
					echo '<p><label>'. __('Select which categories may be assigned to submitted posts: ', 'usp');
					echo '<a id="usp-toggle-cats" class="usp-toggle-cats" href="#usp-toggle-cats">'. __('Show/Hide Categories&nbsp;&raquo;', 'usp') .'</a></label></p>';
					echo '<div class="usp-cats default-hidden"><ul>';
					foreach ($cats as $c) {

						// parents
						echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c->term_id .'" '. checked(true, in_array($c->term_id, $this->general_settings['categories']), false) .' /> ';
						echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c->term_id) .'" title="Cat ID: '. $c->term_id .'" target="_blank">'. $c->name .'</a></label></li>';
						$usp_cats['c'][] = array('id' => $c->term_id, 'c1' => array());
						$children = get_terms('category', array('parent' => $c->term_id, 'hide_empty' => 0));
						if (!empty($children)) {
							echo '<li><ul>';
							foreach ($children as $c1) {

								// children
								$usp_cats['c'][]['c1'][] = array('id' => $c1->term_id, 'c2' => array());
								$grandchildren = get_terms('category', array('parent' => $c1->term_id, 'hide_empty' => 0));
								if (!empty($grandchildren)) {
									echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c1->term_id .'" '. checked(true, in_array($c1->term_id, $this->general_settings['categories']), false) .' /> ';
									echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c1->term_id) .'" title="Cat ID: '. $c1->term_id .'" target="_blank">'. $c1->name .'</a></label>';
									echo '<ul>';
									foreach ($grandchildren as $c2) {

										// grandchildren
										$usp_cats['c'][]['c1'][]['c2'][] = array('id' => $c2->term_id, 'c3' => array());
										$great_grandchildren = get_terms('category', array('parent' => $c2->term_id, 'hide_empty' => 0));
										if (!empty($great_grandchildren)) {
											echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c2->term_id .'" '. checked(true, in_array($c2->term_id, $this->general_settings['categories']), false) .' /> ';
											echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c2->term_id) .'" title="Cat ID: '. $c2->term_id .'" target="_blank">'. $c2->name .'</a></label>';
											echo '<ul>';
											foreach ($great_grandchildren as $c3) {
												
												// great enkelkinder
												$usp_cats['c'][]['c1'][]['c2'][]['c3'][] = array('id' => $c3->term_id, 'c4' => array());
												$great_great_grandchildren = get_terms('category', array('parent' => $c3->term_id, 'hide_empty' => 0));
												if (!empty($great_great_grandchildren)) {
													echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c3->term_id .'" '. checked(true, in_array($c3->term_id, $this->general_settings['categories']), false) .' /> ';
													echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c3->term_id) .'" title="Cat ID: '. $c3->term_id .'" target="_blank">'. $c3->name .'</a></label>';
													echo '<ul>';
													foreach ($great_great_grandchildren as $c4) {
														
														// great great grandchildren
														$usp_cats['c'][]['c1'][]['c2'][]['c3'][]['c4'][] = array('id' => $c4->term_id);
														echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c4->term_id .'" '. checked(true, in_array($c4->term_id, $this->general_settings['categories']), false) .' /> ';
														echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c4->term_id) .'" title="Cat ID: '. $c4->term_id .'" target="_blank">'. $c4->name .'</a></label></li>';
													}
													echo '</ul></li>'; // great great grandchildren
												} else {
													echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c3->term_id .'" '. checked(true, in_array($c3->term_id, $this->general_settings['categories']), false) .' /> ';
													echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c3->term_id) .'" title="Cat ID: '. $c3->term_id .'" target="_blank">'. $c3->name .'</a></label></li>';
												}
											}
											echo '</ul></li>'; // great grandchildren
										} else {
											echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c2->term_id .'" '. checked(true, in_array($c2->term_id, $this->general_settings['categories']), false) .' /> ';
											echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c2->term_id) .'" title="Cat ID: '. $c2->term_id .'" target="_blank">'. $c2->name .'</a></label></li>';
										}
									}
									echo '</ul></li>'; // grandchildren
								} else {
									echo '<li><input type="checkbox" name="usp_general[categories][]" value="'. $c1->term_id .'" '. checked(true, in_array($c1->term_id, $this->general_settings['categories']), false) .' /> ';
									echo '<label for="usp_general[categories][]"><a href="'. get_category_link($c1->term_id) .'" title="Cat ID: '. $c1->term_id .'" target="_blank">'. $c1->name .'</a></label></li>';
								}
							}
							echo '</ul></li>'; // children
						}
					}
					echo '</ul></div>'; // parents
				}
			} elseif ($id == 'post_type_role') {
				$roles = array('administrator', 'editor', 'author', 'contributor');
				echo '<p><label>' . __('Which user roles should have access to USP Posts (custom post types, when enabled): ', 'usp') . '</label></p>';
				echo '<ul>';
				foreach ($roles as $role) {
					echo '<li><input type="checkbox" name="usp_advanced[post_type_role][]" value="'. $role .'" '. checked(true, in_array($role, $this->advanced_settings['post_type_role']), false) .' /> ';
					echo '<label for="usp_advanced[post_type_role][]">'. ucfirst(sanitize_text_field($role)) .'</label></li>';
				}
				echo '</ul>';
			} elseif ($id == 'form_type_role') {
				$roles = array('administrator', 'editor', 'author', 'contributor');
				echo '<p><label>' . __('Which user roles should have access to USP Forms (custom post types for forms): ', 'usp') . '</label></p>';
				echo '<ul>';
				foreach ($roles as $role) {
					echo '<li><input type="checkbox" name="usp_advanced[form_type_role][]" value="'. $role .'" '. checked(true, in_array($role, $this->advanced_settings['form_type_role']), false) .' /> ';
					echo '<label for="usp_advanced[form_type_role][]">'. ucfirst(sanitize_text_field($role)) .'</label></li>';
				}
				echo '</ul>';
			}
		}
		function callback_checkbox($args) {
			$id = $args['id'];
			$type = $args['type'];
			if ($type == 'admin') {
				if     ($id == 'send_mail_user')      $label = __('Send email alerts to the user when submitted posts are submitted', 'usp');
				elseif ($id == 'send_mail_admin')     $label = __('Send email alert to the admin when new posts are submitted', 'usp');
				elseif ($id == 'send_approval_user')  $label = __('Send email alerts to the user when submitted posts are approved', 'usp');
				elseif ($id == 'send_approval_admin') $label = __('Send email alerts to the admin when submitted posts are approved', 'usp');
				elseif ($id == 'send_denied_user')    $label = __('Send email alerts to the user when submitted posts are denied (sent to Trash)', 'usp');
				elseif ($id == 'send_denied_admin')   $label = __('Send email alerts to the admin when submitted posts are denied (sent to Trash)', 'usp');
				
				elseif ($id == 'contact_cc_user')     $label = __('Auto-send a copy of the contact-form message to the sender? (via CC)', 'usp');
				elseif ($id == 'contact_stats')       $label = __('Include user data (e.g., IP, Referrer, Request, et al) appended to messages sent via contact form', 'usp');
				elseif ($id == 'contact_custom')      $label = __('Include any custom field data in the form', 'usp');
				
				$checked = checked($this->admin_settings[$id], 1, false);
				
			} elseif ($type == 'style') {
				if ($id == 'include_css') {
					$label  = __('Include the external USP CSS file (disabled by default). Located @', 'usp') . ' <code>/usp-pro/css/usp-pro.css</code>. ';
					$label .= __('Note: this stylesheet is for testing purposes. It will be overwritten with each plugin upgrade, so not recommended for live sites. ', 'usp');
					$label .= __('It is recommended to use inline CSS for customizing USP Pro (see next option). ', 'usp');
				} elseif ($id == 'include_js') {
					$label  = __('Include the external USP JavaScript file (enabled by default). Located @ ', 'usp') . ' <code>/usp-pro/js/usp-pro.js</code>. ';
					$label .= __('Note: this JS file contains scripts required for USP Pro. It will be overwritten with each plugin upgrade, so not recommended for live sites. ', 'usp');
					$label .= __('It is recommended to use inline JS for customizing USP Pro (see next option). ', 'usp');
				}
				$checked = checked($this->style_settings[$id], 1, false);
				
			} elseif ($type == 'advanced') {
				if ($id == 'success_form') {
					$label = __('Display the submission form with the success message?', 'usp');
				
				} elseif ($id == 'enable_autop') {
					$label = __('Apply WP&rsquo;s auto-formatting to form content', 'usp');
					
				} elseif ($id == 'fieldsets') {
					$label = __('Automatically wrap form inputs with', 'usp') . ' &lt;fieldset&gt; ' . __('tags', 'usp');
					
				} elseif ($id == 'form_demos') {
					$label = __('Automatically regenerate the USP Form Demos', 'usp');
					
				} elseif ($id == 'post_demos') {
					$label = __('Automatically regenerate the USP Post Demos', 'usp');
					
				} elseif ($id == 'submit_button') {
					   $label = __('Automatically include a submit button to all USP Forms', 'usp');
				
				} else {
					$name = $this->advanced_settings[$id];
					if ($name) $label = __('Enabled', 'usp');
					else       $label = __('Disabled', 'usp');
				}
				$checked = checked($this->advanced_settings[$id], 1, false);

			} elseif ($type == 'general') {
				if ($id == 'use_author')      $label = '<strong>'. __('When this option is enabled:', 'usp') .'</strong> '. __('If the user is logged in, their username will be used as the post author. ', 'usp') .
												'<strong>'. __('When this option is disabled:', 'usp') .'</strong> '. __('If the user is logged in, the &ldquo;Default Assigned Author&rdquo; will be used as the post author. ', 'usp') . 
												__('In either case, if the user is logged out, the post author will be either the registered username (if the form is a registration form) or the &ldquo;Default Assigned Author&rdquo;.', 'usp');
													
				elseif ($id == 'replace_author')  $label = __('Always use the submitted author name, regardless of any other settings. When including the &ldquo;Name&rdquo; field on a USP Form, enable this setting to always use it as the Post Author. 
													Example usage: when user-registration is disabled, this setting enables the user to specify their own author name. Tip: if also including the URL field on a USP Form, 
													it automatically will be used for author archive and other links.', 'usp');
				
				elseif ($id == 'redirect_post')   $label = __('Redirect users to their submitted/published post. Requires that the option for &ldquo;Auto Publish Posts&rdquo; set to &ldquo;Always publish immediately&rdquo;.', 'usp');
				elseif ($id == 'enable_stats')    $label = __('Enable basic tracking of user data (e.g., IP, Referrer, Request, et al). When enabled, user stats will attached to submitted posts as custom fields.', 'usp');
				elseif ($id == 'captcha_casing')  $label = __('Check this box if you want the challenge response to be case-sensitive.', 'usp');
				
				elseif ($id == 'cats_nested')     $label = __('Check this box to display nested categories for subcategories', 'usp');
				elseif ($id == 'use_cat')         $label = __('Use a hidden field to assign categories (see next option). Useful for assigning categories to all submitted posts. To assign form-specific categories, use the category shortcode.', 'usp');
				elseif ($id == 'hidden_cats')     $label = __('Hide the Category input field (useful when assigning specific categories for submissions)', 'usp');
				elseif ($id == 'cats_multiple')   $label = __('Allow users to select multiple categories when using the dropdown menu', 'usp');
				
				elseif ($id == 'tags_empty')      $label = __('Hide empty tags (i.e., tags with no associated posts) from the above list of tags (does not affect front-end display). Note: any tags not shown will be deselected.', 'usp');
				elseif ($id == 'hidden_tags')     $label = __('Hide the Tags input field (useful when assigning specific tags for submissions)', 'usp');
				elseif ($id == 'tags_multiple')   $label = __('Allow users to select multiple tags when using the dropdown menu', 'usp');
				
				elseif ($id == 'sessions_on')      $label = __('Enable &ldquo;remembering&rdquo; of form-field values. This option enables use of a &ldquo;remember me&rdquo; checkbox field in USP Forms.', 'usp');
				elseif ($id == 'sessions_scope')   $label = __('When &rdquo;Remember Form Values&rdquo; is enabled, how long should the data be preserved? Check the box to remember indefinitely (until the user clears the browser), 
														or leave unchecked to remember form values only until successful submission (default).', 'usp');
				elseif ($id == 'sessions_default') $label = __('Default state of the &ldquo;remember me&rdquo; checkbox field (checked or unchecked)', 'usp');
				
				elseif ($id == 'titles_unique')    $label = __('Check this box to require Post Titles to be unique', 'usp');
				elseif ($id == 'enable_form_lock') $label = __('Check this box to enable the following three options', 'usp');
				
				$checked = checked($this->general_settings[$id], 1, false);
				
			} elseif ($type == 'uploads') {
				if ($id == 'featured_image')       $label = __('Set submitted images as Featured Images (aka Post Thumbnails) for posts. Note: your theme&rsquo;s single.php file must include', 'usp') . 
													' <code>the_post_thumbnail()</code> '. __('to display Featured Images.', 'usp');
				elseif ($id == 'unique_filename')  $label = __('Check this box to make uploaded file names unique. If enabled, the year-month-day and an unique ID will be prepended to the name of each uploaded file. ', 'usp') .
													__('When disabled, uploaded file names will not be changed, so uploading a file with the same name as an existing file will overwrite the existing file. Default: enabled/unique-filenames.', 'usp');
				elseif ($id == 'user_shortcodes')  $label = __('Check this box to enable User Shortcodes in submitted post content.', 'usp') .' <a href="https://plugin-planet.com/usp-pro-user-shortcodes/" target="_blank">'. __('Learn more &raquo;', 'usp') .'</a>';
				
				$checked = checked($this->uploads_settings[$id], 1, false);
			
			} elseif ($type == 'more') {
				if ($id == 'default_options') {
					$label = __('Restore default options upon plugin deactivation/reactivation', 'usp');
				}
				$checked = checked($this->more_settings[$id], 1, false);
			}
			echo '<input name="usp_'. $type .'['. $id .']" type="checkbox" value="1" '. $checked .' /> <label for="usp_'. $type .'['. $id .']">'. $label .'</label>';
		}
		function callback_number($args) {
			$id = $args['id'];
			$type = $args['type'];
			$value = $this->advanced_settings[$id];
			$label = __('Number of custom form fields to enable', 'usp');
			echo '<input name="usp_'. $type .'['. $id .']" type="number" step="1" min="0" max="999" maxlength="3" value="'. $value .'" /> <label for="usp_'. $type .'['. $id .']">'. $label .'</label>';
		}
		function callback_dropdown($args) {
			$id = $args['id'];
			$type = $args['type'];
			echo '<select name="usp_'. $type .'['. $id .']">';
			if ($id == 'assign_author') {
				global $wpdb;
				$list_authors = $wpdb->get_results("SELECT ID, display_name FROM {$wpdb->users}");
				$label = __('Default author for user-submitted posts', 'usp');
				foreach ($list_authors as $author) {
					echo '<option '. selected($this->general_settings[$id], $author->ID, false) .' value="'. $author->ID .'">'. $author->display_name .'</option>';		
				}
				echo '</select> <label for="usp_'. $type .'['. $id .']">'. $label .'</label>';
			} elseif ($id == 'assign_role') { 
				global $wp_roles;
				$roles = $wp_roles->roles;
				$label = __('Role that should be assigned when registering users (default: subscriber)', 'usp');
				foreach ($roles as $key => $value) {
					echo '<option '. selected($this->general_settings[$id], strtolower($key), false) .' value="'. strtolower($key) .'">'. $value['name'] .'</option>';		
				}
				echo '</select> <label for="usp_'. $type .'['. $id .']">'. $label .'</label>';
			} elseif ($id == 'number_approved') {
				$label = __('Default Post Status for submitted posts (can be overridden per form)', 'usp');
				
				echo '<option '. selected(-6, $this->general_settings[$id], false) .' value="-6">'. __('Future (Scheduled Post)', 'usp') . '</option>';
				echo '<option '. selected(-5, $this->general_settings[$id], false) .' value="-5">'. __('Always publish (via Password)', 'usp') . '</option>';
				echo '<option '. selected(-4, $this->general_settings[$id], false) .' value="-4">'. __('Always publish (via Private)', 'usp') . '</option>';
				echo '<option '. selected(-3, $this->general_settings[$id], false) .' value="-3">'. __('Always moderate (via Custom Status, defined below)', 'usp') . '</option>';
				echo '<option '. selected(-2, $this->general_settings[$id], false) .' value="-2">'. __('Always moderate (via Pending)', 'usp') . '</option>';
				echo '<option '. selected(-1, $this->general_settings[$id], false) .' value="-1">'. __('Always moderate (via Draft)', 'usp') . '</option>';
				echo '<option '. selected( 0, $this->general_settings[$id], false) .' value="0">'. __('Always publish immediately', 'usp') .'</option>';
				
				foreach(range(1, 20) as $value) {
					echo '<option '. selected($value, $this->general_settings[$id], false) .' value="'. $value .'">'. $value .'</option>';
				}
				echo '</select><br /><label for="usp_'. $type .'['. $id .']">'. $label .'</label>';
			}
		}
		function callback_radio($args) {
			global $usp_admin, $usp_advanced, $usp_general, $usp_uploads;
			$id = $args['id'];
			$type = $args['type'];
			//
			if ($id == 'send_mail') {
				$radio_options = $this->send_mail_options();
				$label = __('For submitted posts and approvals: ', 'usp');
				if (isset($usp_admin['send_mail'])) $default = $usp_admin['send_mail'];
				else $default = $this->admin_settings[$id];
				
			} elseif ($id == 'post_type') {
				$radio_options = $this->post_type_options();
				$label = __('Submitted content should be posted as: ', 'usp');
				if (isset($usp_advanced['post_type'])) $default = $usp_advanced['post_type'];
				else $default = $this->advanced_settings[$id];
				
			} elseif ($id == 'cats_menu') {
				$radio_options = $this->cats_menu_options();
				$label = __('On the front-end, categories should be displayed: ', 'usp');
				if (isset($usp_general['cats_menu'])) $default = $usp_general['cats_menu'];
				else $default = $this->general_settings[$id];
				
			} elseif ($id == 'tags_order') {
				$radio_options = $this->tags_order_options();
				$label = __('Select the order in which tags should be displayed: ', 'usp');
				if (isset($usp_general['tags_order'])) $default = $usp_general['tags_order'];
				else $default = $this->general_settings[$id];
				
			} elseif ($id == 'tags_menu') {
				$radio_options = $this->tags_menu_options();
				$label = __('On the front-end, tags should be displayed: ', 'usp');
				if (isset($usp_general['tags_menu'])) $default = $usp_general['tags_menu'];
				else $default = $this->general_settings[$id];
				
			} elseif ($id == 'form_style') {
				$radio_options = $this->style_options();
				$label = __('Include the following CSS with all USP Forms (note: any styles enabled here will be included via inline CSS and may be customized below):', 'usp');
				if (isset($usp_style['form_style'])) $default = $usp_style['form_style'];
				else $default = $this->style_settings[$id];
				
			} elseif ($id == 'post_images') {
				$radio_options = $this->display_images_options();
				$label = __('Automatically display images in submitted posts? Note: applies only to uploaded images. Visit Tools &gt; Shortcodes for alternate ways of displaying images and other file types. (see also next option)', 'usp');
				if (isset($usp_uploads['post_images'])) $default = $usp_uploads['post_images'];
				else $default = $this->uploads_settings[$id];
			}
			echo '<p><label for="usp_' . $type . '['. $id .']">' . $label . '</label></p>';
			if (!isset($checked)) $checked = '';
			echo '<ul>';
			foreach ($radio_options as $radio_option) {
				if ($default) {
					$radio_setting = $default;
				} else {
					if ($type == 'admin') {
						$radio_setting = $this->admin_settings[$id];
					} elseif ($type == 'advanced') {
						$radio_setting = $this->advanced_settings[$id];
					} elseif ($type == 'general') {
						$radio_setting = $this->general_settings[$id];
					} elseif ($type == 'style') {
						$radio_setting = $this->style_settings[$id];
					} elseif ($type == 'uploads') {
						$radio_setting = $this->uploads_settings[$id];
					}
				}
				if ($radio_setting == $radio_option['value']) {
					$checked = ' checked="checked"';
				} else {
					$checked = '';
				}
				echo '<li><input type="radio" name="usp_' . $type .'['. $id .']" value="'. esc_attr($radio_option['value']) .'"'. $checked .' /> '. $radio_option['label'] .'</li>';
			}
			echo '<ul>';
		}
		// DISPLAY
		function add_admin_menus() {
			add_options_page('USP Pro', 'USP Pro', 'manage_options', $this->settings_page, array(&$this, 'plugin_options_page'));
		}
		function plugin_options_page() {
			$tab = isset($_GET['tab']) ? $_GET['tab'] : $this->settings_general; ?>
			<div class="wrap">
				<h2 class="usp-title"><?php _e('USP Pro', 'usp'); ?> <span><?php echo USP_VERSION; ?></span></h2>
				<?php if (isset($_GET['settings_restored']) && $_GET['settings_restored'] == 'true') echo '<div class="updated"><p><strong>'. __('Your settings have been restored.', 'usp') .'</strong></p></div>'; ?>
				<h2 class="nav-tab-wrapper"><?php $this->plugin_options_tabs(); ?></h2>
				<?php if ($tab !== 'usp_tools' && $tab !== 'usp_about' && $tab !== 'usp_license') : ?>
				<form method="post" action="options.php">
					<?php if (usp_check_license() == 'valid') : ?>
					<?php wp_nonce_field('update-options'); ?>
					<?php settings_fields($tab); ?>
					<?php do_settings_sections($tab); ?>
					<?php submit_button(); ?>
					<?php else : ?>
					<h3>Welcome to USP Pro!</h3>
					<p class="intro">Thank you for installing USP Pro. To begin using the plugin, 
					<a href="<?php get_admin_url(); ?>options-general.php?page=usp_options&tab=usp_license"><?php _e('enter your license key &raquo;', 'usp'); ?></a></p>
					<?php endif; ?>
				</form>
				<?php else : ?>
				<?php if ($tab == 'usp_tools')   section_tools_desc(); ?>
				<?php if ($tab == 'usp_about')   section_about_desc(); ?>
				<?php if ($tab == 'usp_license') section_license_desc(); ?>
				<?php endif; ?>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$('.default-hidden').hide();
					$('.usp-toggle-cats').click(function(e){ e.preventDefault(); $('.usp-cats').slideToggle(300); });
					$('.usp-toggle-tags').click(function(e){ e.preventDefault(); $('.usp-tags').slideToggle(300); });
					$('.usp-toggle-s1').click(function(e){ e.preventDefault(); $('.usp-s1').slideToggle(300); });
					$('.usp-toggle-s2').click(function(e){ e.preventDefault(); $('.usp-s2').slideToggle(300); });
					$('.usp-toggle-s3').click(function(e){ e.preventDefault(); $('.usp-s3').slideToggle(300); });
					$('.usp-toggle-s4').click(function(e){ e.preventDefault(); $('.usp-s4').slideToggle(300); });
					$('.usp-toggle-s5').click(function(e){ e.preventDefault(); $('.usp-s5').slideToggle(300); });
					$('.usp-toggle-s6').click(function(e){ e.preventDefault(); $('.usp-s6').slideToggle(300); });
					$('.usp-toggle-regex-1').click(function(e){ e.preventDefault(); $('.usp-regex-1').slideToggle(300); });
					$('.usp-toggle-regex-2').click(function(e){ e.preventDefault(); $('.usp-regex-2').slideToggle(300); });
					$('.usp-toggle-regex-3').click(function(e){ e.preventDefault(); $('.usp-regex-3').slideToggle(300); });
				});
			</script>
		<?php }
		function plugin_options_tabs() {
			$current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->settings_general;
			foreach ($this->settings_tabs as $tab_key => $tab_caption) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->settings_page . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
			}
		}
	}
}

if (class_exists('USP_Pro')) {
	function usp_pro_init() { 
		new USP_Pro; 
	}
	add_action('init', 'usp_pro_init', 1); 
	register_activation_hook  (__FILE__, array('USP_Pro', 'activate'));
	register_deactivation_hook(__FILE__, array('USP_Pro', 'deactivate'));
	//
	$usp_admin    = get_option('usp_admin',    USP_Pro::admin_defaults());
	$usp_advanced = get_option('usp_advanced', USP_Pro::advanced_defaults());
	$usp_general  = get_option('usp_general',  USP_Pro::general_defaults());
	$usp_style    = get_option('usp_style',    USP_Pro::style_defaults());
	$usp_uploads  = get_option('usp_uploads',  USP_Pro::uploads_defaults());
	$usp_more     = get_option('usp_more',     USP_Pro::more_defaults());
	//
	function usp_pro_delete_plugin_options() {
		include_once('uninstall.php');
	}
	if ($usp_more['default_options'] == 1) {
		register_deactivation_hook(__FILE__, 'usp_pro_delete_plugin_options');
	}
	//
	if (!function_exists('exif_imagetype')) {
		function exif_imagetype($filename) {
			if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) { 
				return $type;
			} 
			return false; 
		} 
	} 
}


