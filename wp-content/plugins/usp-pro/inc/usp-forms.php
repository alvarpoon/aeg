<?php // USP Pro - Custom Post Type for USP Forms

if (!defined('ABSPATH')) die();

/*
	Class: Custom Post Type for USP Forms
	Provides various functions for the usp_form post type
*/
if (!class_exists('USP_Pro_Forms')) {
	class USP_Pro_Forms {
		const POST_TYPE = 'USP_Form';
		public function __construct() {
			add_action('init', array(&$this, 'init'));
			add_action('admin_init', array(&$this, 'admin_init'));
		}
		public function init() {
			$this->create_post_type();
			$this->create_post_examples();
			add_action('save_post', array(&$this, 'save_post'));
			add_action('manage_usp_form_posts_custom_column', array(&$this, 'shortcode_column'), 10, 2);
			add_action('load-edit.php', array(&$this, 'columns_execute'));
			add_action('loop_start', array(&$this, 'disable_autop'));
			add_action('admin_init', array(&$this, 'settings_updated'));
			
			add_filter('manage_usp_form_posts_columns' , array(&$this, 'add_columns'));
			add_filter('manage_edit-usp_form_sortable_columns', array(&$this, 'columns_sortable'));
			add_filter('the_content', array(&$this, 'form_filter'), 10, 1);
		}
		public static function create_post_type() {
			$capabilities = array(
				'edit_post'              => 'edit_usp_form',
				'read_post'              => 'read_usp_form',
				'delete_post'            => 'delete_usp_form',
				'edit_posts'             => 'edit_usp_forms',
				'publish_posts'          => 'publish_usp_forms',
				'edit_others_posts'      => 'edit_others_usp_forms',
				'read_private_posts'     => 'read_private_usp_forms',
				'delete_posts'           => 'delete_usp_forms',
				'delete_private_posts'   => 'delete_private_usp_forms',
				'delete_published_posts' => 'delete_published_usp_forms',
				'delete_others_posts'    => 'delete_others_usp_forms',
				'edit_private_posts'     => 'edit_private_usp_forms',
				'edit_published_posts'   => 'edit_published_usp_forms',
			);
			$labels = array(
				'name'               => __(sprintf('%ss', ucwords(str_replace("_", " ", self::POST_TYPE))), 'usp'),
				'singular_name'      => __(ucwords(str_replace("_", " ", self::POST_TYPE)), 'usp'),
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Form',
				'edit_item'          => 'Edit Form',
				'new_item'           => 'New Form',
				'all_items'          => 'All Forms',
				'view_item'          => 'View Form',
				'search_items'       => 'Search Forms',
				'not_found'          => 'No forms found',
				'not_found_in_trash' => 'No forms found in Trash',
				'menu_name'          => 'USP Forms',
			);
			$args = array(
				'labels'              => $labels,
				'description'         => __('USP Submission Forms', 'usp'),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'publicly_queryable'  => true, 
				'exclude_from_search' => true,
				'show_in_nav_menus'   => true,
				'menu_position'       => null,
				'menu_icon'           => 'dashicons-admin-page',
				'hierarchical'        => false,
				'supports'            => array('title', 'editor', 'author', 'thumbnail', 'custom-fields', 'post-formats'),
				'has_archive'         => true,
				'can_export'          => true,
				'rewrite'             => array('slug' => 'usp_form', 'with_front' => true),
				'query_var'           => 'usp_form',
				//
				'capability_type'     => 'usp_form',
				'map_meta_cap'        => true, 
				'capabilities'        => $capabilities,
			);
			register_post_type(strtolower(self::POST_TYPE), $args);
		}
		public function settings_updated() {
			global $pagenow, $usp_advanced;
			if (is_admin() && $pagenow == 'options-general.php') {
				if (isset($_GET['page']) && $_GET['page'] == 'usp_options') {
					if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
						if (isset($usp_advanced['form_type_role'])) {
							$roles = $usp_advanced['form_type_role'];
							$matches = array();
							$misses = array();
							foreach (get_editable_roles() as $role_name => $role_info) {
								if (in_array($role_name, $roles)) $matches[] = $role_name;
								else $misses[] = $role_name;
							}
							foreach ($matches as $match) $this->add_capability($match);
							foreach ($misses as $miss) $this->remove_capability($miss);
						}
					}
				}
			}
		}
		public function add_capability($role) {
			$role_obj = get_role($role);
			$caps = $this->default_caps();
			foreach ($caps as $cap) $role_obj->add_cap($cap);
		}
		public function remove_capability($role) {
			$role_obj = get_role($role);
			$caps = $this->default_caps();
			foreach ($caps as $cap) $role_obj->remove_cap($cap);
		}
		public static function default_caps() {
			$caps = array(
				'edit_usp_form',
				'read_usp_form',
				'delete_usp_form',
				'edit_usp_forms',
				'publish_usp_forms',
				'edit_others_usp_forms',
				'read_private_usp_forms',
				'delete_usp_forms',
				'delete_private_usp_forms',
				'delete_published_usp_forms',
				'delete_others_usp_forms',
				'edit_private_usp_forms',
				'edit_published_usp_forms'
			);
			return $caps;	
		}
		public function create_post_examples() {
			global $usp_advanced;
			if ($usp_advanced['form_demos']) {
				include_once('usp-demos.php');
				$existing_demo_1 = get_page_by_title('USP Form Demo', ARRAY_A, 'usp_form');
				if (!$existing_demo_1) {
					$form_demo = array(
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_content'   => $usp_form,
						'post_name'      => 'submit',
						'post_status'    => 'draft',
						'post_title'     => 'USP Form Demo',
						'post_type'      => 'usp_form',
					);
					$postID = wp_insert_post($form_demo);
					$shortcode = '[usp_form id="submit"]';
					add_post_meta($postID, 'usp_shortcode', $shortcode, true);
					$alt = 'default';
					$this->add_custom_fields($postID, $alt);
				}
				$existing_demo_2 = get_page_by_title('Contact Form Demo', ARRAY_A, 'usp_form');
				if (!$existing_demo_2) {
					$form_demo = array(
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_content'   => $contact_form,
						'post_name'      => 'contact',
						'post_status'    => 'draft',
						'post_title'     => 'Contact Form Demo',
						'post_type'      => 'usp_form',
					);
					$postID = wp_insert_post($form_demo);
					$shortcode = '[usp_form id="contact"]';
					add_post_meta($postID, 'usp_shortcode', $shortcode, true);
					$alt = 'contact';
					$this->add_custom_fields($postID, $alt);
				}
				$existing_demo_3 = get_page_by_title('User Registration Demo', ARRAY_A, 'usp_form');
				if (!$existing_demo_3) {
					$form_demo = array(
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_content'   => $register_form,
						'post_name'      => 'register',
						'post_status'    => 'draft',
						'post_title'     => 'User Registration Demo',
						'post_type'      => 'usp_form',
					);
					$postID = wp_insert_post($form_demo);
					$shortcode = '[usp_form id="register"]';
					add_post_meta($postID, 'usp_shortcode', $shortcode, true);
					$alt = 'register';
					$this->add_custom_fields($postID, $alt);
				}
				$existing_demo_4 = get_page_by_title('Image Preview Demo', ARRAY_A, 'usp_form');
				if (!$existing_demo_4) {
					$image_demo = array(
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_content'   => $image_form,
						'post_name'      => 'preview',
						'post_status'    => 'draft',
						'post_title'     => 'Image Preview Demo',
						'post_type'      => 'usp_form',
					);
					$postID = wp_insert_post($image_demo);
					$shortcode = '[usp_form id="preview"]';
					add_post_meta($postID, 'usp_shortcode', $shortcode, true);
					$alt = 'preview';
					$this->add_custom_fields($postID, $alt);
				}
				$existing_demo_5 = get_page_by_title('Classic Form Demo', ARRAY_A, 'usp_form');
				if (!$existing_demo_5) {
					$classic_demo = array(
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_content'   => $classic_form,
						'post_name'      => 'classic',
						'post_status'    => 'draft',
						'post_title'     => 'Classic Form Demo',
						'post_type'      => 'usp_form',
					);
					$postID = wp_insert_post($classic_demo);
					$shortcode = '[usp_form id="classic"]';
					add_post_meta($postID, 'usp_shortcode', $shortcode, true);
					$alt = 'classic';
					$this->add_custom_fields($postID, $alt);
				}
			}
		}
		public function add_columns($columns) {
			unset($columns['author'], $columns['date'], $columns['title']);
			return array_merge($columns, array('title'=>__('Title', 'usp'), 'author'=>__('Author', 'usp'), 'shortcode'=>__('Shortcode', 'usp'), 'date'=>__('Date', 'usp')));
		}
		public function shortcode_column($column_name, $id) {
			global $post;
			switch ($column_name) {
				case 'shortcode':
					echo get_post_meta($post->ID, 'usp_shortcode', true);
					break;
				default: 
					echo '[undefined]';
					break;
			}
		}
		public function columns_sortable($columns) {
			$custom = array('author' => 'author', 'date' => 'date', 'title' => 'title', 'shortcode' => 'shortcode');
			return wp_parse_args($custom, $columns);
		}
		public function columns_execute() {
			add_filter('request', array(&$this, 'columns_orderby'));
		}
		public function columns_orderby($vars) {
			if (isset($vars['post_type']) && 'USP_Form' == $vars['post_type']) {
				if (isset( $vars['orderby']) && 'shortcode' == $vars['orderby']) {
					$vars = array_merge($vars, array('meta_key' => 'usp_shortcode', 'orderby' => 'meta_value'));
				}
			}
			return $vars;
		}
		public function save_post($post_id) {
			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}
		}
		public function admin_init() {
			add_action('save_post', array(&$this, 'add_form_meta'));
			add_action('admin_print_footer_scripts', array(&$this, 'add_quicktags'));
			add_filter('quicktags_settings', array(&$this, 'default_quicktags'), 10, 1);
			add_filter('admin_footer', array(&$this, 'disable_visual_editor'), 99);
			//add_filter('user_can_richedit', array(&$this, 'enable_quicktags'));
		}
		public function add_form_meta($post) {
			global $post;
			if (get_post_type() == strtolower(self::POST_TYPE)) {
				$matches = array();
				$custom = get_post_custom();
				foreach ($custom as $key => $value) {
					$matches = preg_match("/usp_/i", $key);
					if ($matches) break;
				}
				if (!$matches) {
					$id = $post->ID;
					if (get_option('permalink_structure') != '' ) {
						$post_data = get_post($id, ARRAY_A);
						$slug = $post_data['post_name'];
						$id = $slug;
					}
					if ($id) $id = $id;
					else $id = $post->ID;
					$shortcode = '[usp_form id="' . $id . '"]';
					add_post_meta($post->ID, 'usp_shortcode', $shortcode, true);
					$this->add_custom_fields($post->ID);
				}
			}
		}
		public function add_quicktags() {
			if (get_post_type() == strtolower(self::POST_TYPE)) {
				if (wp_script_is('quicktags') && get_post_type() == strtolower(self::POST_TYPE)) include_once('usp-quicktags.php');
			}
		}
		public function default_quicktags($qtInit) {
			if (get_post_type() == strtolower(self::POST_TYPE)) {
				// $defaults = array('strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,fullscreen');
				$qtInit['buttons'] = 'em,link,img,ul,ol,li,code'; // remove strong,block,del,ins,more,close,fullscreen
			}
			return $qtInit;
		}
		public function autop_sponge($pee) { 
			return $pee; 
		}
		public function disable_autop() {
			global $usp_advanced;
			if (get_post_type() == strtolower(self::POST_TYPE)) {
				if (!$usp_advanced['enable_autop']) {
					remove_filter('the_content', 'wpautop');
					add_filter('the_content', array(&$this, 'autop_sponge'));
				}
			}
		}
		public function form_filter($content) {
			global $post, $usp_advanced;
			$classes = 'usp-pro usp-form-' . $post->ID;
			$args = array('classes' => $classes, 'id' => $post->ID);
			
			if (isset($_GET['usp_success'])) $success = true;
			else $success = false;
			$form_wrap = usp_form_wrap($args, $success);
			
			if (get_post_type() == strtolower(self::POST_TYPE)) {
				if ($success && $usp_advanced['success_form'] == '0') $content = $form_wrap['form_before'] . $form_wrap['form_after'];
				else $content = $form_wrap['form_before'] . $content . $form_wrap['form_after'];
			} else {
				$content = $content;
			}
			return $content;
		}
		public function disable_visual_editor() {
			if (get_post_type() == strtolower(self::POST_TYPE)) {
				$screen = get_current_screen();
				if ($screen->base == 'post') {
					echo '<style type="text/css">a#content-tmce, a#content-tmce:hover, #qt_content_fullscreen { display: none; }</style>';
					echo '<script type="text/javascript">jQuery(document).ready(function() { document.getElementById("content-tmce").onclick = \'none\';});</script>';
				}
			}
		}
		public function enable_quicktags() {
			if (get_post_type() == strtolower(self::POST_TYPE)) {
				$screen = get_current_screen();
				if ($screen->base == 'post') {
					return true;
				}
			}
		}
		public function add_custom_fields($postID, $alt = null) {
			global $usp_advanced;
			if ($postID) {
				if ($alt == 'register') {
					add_post_meta($postID, '[usp_custom_field form="register" id="1"]', 'name#nicename|for#nicename|label#Nicename|placeholder#Nicename', true);
					add_post_meta($postID, '[usp_custom_field form="register" id="2"]', 'name#displayname|for#displayname|label#Display Name|placeholder#Display Name', true);
					add_post_meta($postID, '[usp_custom_field form="register" id="3"]', 'name#nickname|for#nickname|label#Nickname|placeholder#Nickname', true);
					add_post_meta($postID, '[usp_custom_field form="register" id="4"]', 'name#firstname|for#firstname|label#First Name|placeholder#First Name', true);
					add_post_meta($postID, '[usp_custom_field form="register" id="5"]', 'name#lastname|for#lastname|label#Last Name|placeholder#Last Name', true);
					add_post_meta($postID, '[usp_custom_field form="register" id="6"]', 'name#description|for#description|label#Description|placeholder#Description', true);
				} else {
					if (isset($usp_advanced['custom_fields']) && is_numeric($usp_advanced['custom_fields'])) {
						$max = 1 + intval($usp_advanced['custom_fields']);
						if ($max > 0) {
							for ( $i = 1; $i < $max; $i++ ) {
								add_post_meta($postID, '[usp_custom_field form="'. $postID .'" id="'. strval($i) .'"]', 'data-required#true', true);
							}
						}
					}
				}
			}
		}
	}
}


/*
	Class: USP Form
	Displays USP Form as widget
*/
if (!class_exists('USP_Form_Widget')) {
	class USP_Form_Widget extends WP_Widget {
		function __construct() {
			parent::WP_Widget('usp_form_widget', __('USP Form Widget', 'usp'), array('classname' => 'widget_text', 'description' => __('Display any USP Form as a widget', 'usp')));
		}
		function widget($args, $instance) {
			extract($args);
			$id = $instance['id'];
			$class = $instance['class'];
			$postID = trim($instance['postID']);
			$postIDs = explode(",", $postID);
			$usp_args = array('id' => $id, 'class' => $class);
			if (!empty($postID)) {
				foreach ($postIDs as $ID) {
					if (is_single($ID) || is_page($ID)) {
						echo usp_form($usp_args);
					}
				}
			} else {
				echo usp_form($usp_args);
			}
		}
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['id']     = $new_instance['id'];
			$instance['class']  = $new_instance['class'];
			$instance['postID'] = $new_instance['postID'];
			$instance['title']  = strip_tags($new_instance['title']);
			return $instance;
		}
		function form($instance) {
			if ($instance) {
				$instance = wp_parse_args((array) $instance, array('title' => ''));
				$id     = esc_attr($instance['id']);
				$class  = esc_attr($instance['class']);
				$postID = esc_attr($instance['postID']);
				$title  = strip_tags($instance['title']);
			} else {
				$id     = '';
				$class  = '';
				$postID = '';
				$title  = '';
			} ?>
			<p>
				<strong><?php _e('Widget Title', 'usp'); ?></strong><br />
				<input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /><br />
				<small><label for="<?php echo $this->get_field_id('id'); ?>">
					<?php _e('Enter an optional title for this widget (useful when using multiple form widgets). Displayed only on the widget panel here in the Admin Area.', 'usp'); ?>
				</label></small>
			</p>
			<p>
				<strong><?php _e('USP Form ID', 'usp'); ?></strong><br />
				<input name="<?php echo $this->get_field_name('id'); ?>" id="<?php echo $this->get_field_id('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" /><br />
				<small><label for="<?php echo $this->get_field_id('id'); ?>">
					<?php _e('Enter the ID of a published USP Form. Make sure the form is published and not saved as a draft or pending. Check the form&rsquo;s shortcode for its ID. (Required)', 'usp'); ?>
				</label></small>
			</p>
			<p>
				<strong><?php _e('Custom CSS Classes', 'usp'); ?></strong><br />
				<input name="<?php echo $this->get_field_name('class'); ?>" id="<?php echo $this->get_field_id('class'); ?>" type="text" value="<?php echo esc_attr($class); ?>" /><br />
				<small><label for="<?php echo $this->get_field_id('class'); ?>">
					<?php _e('Optional custom classes for the form. Use commas to separate each class, like so: class1,class2,class3', 'usp'); ?>
				</label></small>
			</p>
			<p>
				<strong><?php _e('Limit Form to Post/Page', 'usp'); ?></strong><br />
				<input name="<?php echo $this->get_field_name('postID'); ?>" id="<?php echo $this->get_field_id('postID'); ?>" type="text" value="<?php echo esc_attr($postID); ?>" /><br />
				<small><label for="<?php echo $this->get_field_id('postID'); ?>">
					<?php _e('Enter the ID of a specific post or page; leave blank to display on all posts/pages. Separate multiple IDs with a comma.', 'usp'); ?>
				</label></small>
			</p>
	<?php }
	}
	add_action('widgets_init', create_function('', 'register_widget("usp_form_widget");'));
}


/*
	Function: Enqueue Script & Style
	Includes the specified JavaScript and CSS file(s)
*/
if (!function_exists('usp_enqueue_resources')) : 
function usp_enqueue_resources() {
	global $usp_style;
	$usp_version = USP_VERSION;

	$include_urls = '';
	if (isset($usp_style['include_url']) && !empty($usp_style['include_url'])) {
		$target_urls = trim($usp_style['include_url']);
		$explode_urls = explode(",", $target_urls);
		$include_urls = array();
		foreach ($explode_urls as $url) $include_urls [] = rtrim(trim($url), ',');
	}
	$errors_cleared = USP_Pro_Process::get_query_vars();
	$current_url = trailingslashit('http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	$current_url = remove_query_arg($errors_cleared, $current_url);
	$current_url = remove_query_arg('usp_success', $current_url);

	if (!is_admin() && $usp_style['include_css']) {
		if (!empty($include_urls)) {
			foreach ($include_urls as $url) {
				if ($current_url == $url) wp_enqueue_style('usp', WP_PLUGIN_URL .'/'. basename(dirname(dirname(__FILE__))) .'/css/usp-pro.css', false, $usp_version, 'all');
			}
		} else {
			wp_enqueue_style('usp', WP_PLUGIN_URL .'/'. basename(dirname(dirname(__FILE__))) .'/css/usp-pro.css', false, $usp_version, 'all');
		}
	}
	if (!is_admin() && $usp_style['include_js']) {
		if (!empty($include_urls)) {
			foreach ($include_urls as $url) {
				if ($current_url == $url) wp_enqueue_script('usp', WP_PLUGIN_URL .'/'. basename(dirname(dirname(__FILE__))) .'/js/usp-pro.js', array('jquery'), $usp_version);
			}
		} else {
			wp_enqueue_script('usp', WP_PLUGIN_URL .'/'. basename(dirname(dirname(__FILE__))) .'/js/usp-pro.js', array('jquery'), $usp_version);
		}
	}
}
add_action ('init', 'usp_enqueue_resources');
endif;


/*
	Function: Add styles to WP rich text editor
*/
if (!function_exists('usp_wp_editor_style')) : 
function usp_wp_editor_style($mce_css) {
	if (!empty($mce_css)) $mce_css .= ',';
	$mce_css .= WP_PLUGIN_URL .'/'. basename(dirname(dirname(__FILE__))) .'/css/editor-style.css';
	return $mce_css;
}
add_filter('mce_css', 'usp_wp_editor_style');
endif;


/*
	Function: USP Form Wrapper
		Returns the required HTML elements to display the USP form
		Syntax: usp_form_wrap($args, $success);
		Parameters:
			$args = array('classes' => $classes, 'id' => $id);
			$success = whether successful form submission
*/
if (!function_exists('usp_form_wrap')) : 
function usp_form_wrap($args, $success) {
	global $usp_general, $usp_advanced, $usp_style, $current_user;
	
	if ($args) {
		$form_id = $args['id'];
		$classes = $args['classes'];
		
		if (!empty($usp_advanced['custom_before'])) $custom_before = $usp_advanced['custom_before'];
		else $custom_before = '';
		
		if (!empty($usp_advanced['custom_after'])) $custom_after = $usp_advanced['custom_after'];
		else $custom_after = '';
		
		if (isset($usp_advanced['form_atts']) && !empty($usp_advanced['form_atts'])) $form_atts = ' '. $usp_advanced['form_atts'];
		else $form_atts = '';
		
		if (isset($usp_style['form_style']) && $usp_style['form_style'] !== 'disable') {
			$form_style = "\n" . '<style type="text/css">' . "\n";
			if     ($usp_style['form_style'] == 'minimal' && isset($usp_style['style_min']))    $form_style .= $usp_style['style_min'];
			elseif ($usp_style['form_style'] == 'small'   && isset($usp_style['style_small']))  $form_style .= $usp_style['style_small'];
			elseif ($usp_style['form_style'] == 'large'   && isset($usp_style['style_large']))  $form_style .= $usp_style['style_large'];
			elseif ($usp_style['form_style'] == 'custom'  && isset($usp_style['style_custom'])) $form_style .= $usp_style['style_custom'];
			$form_style .= "\n" . '</style>' . "\n";
		} else {
			$form_style = '';
		}
		$form_css = get_post_meta($form_id, 'usp-css', true);
		if (!empty($form_css)) $form_css = "\n" . '<style type="text/css">' . $form_css . '</style>' . "\n";
		
		if (isset($usp_style['script_custom']) && !empty($usp_style['script_custom'])) {
			$form_script = "\n" . '<script type="text/javascript">' . "\n";
			$form_script .= htmlspecialchars_decode($usp_style['script_custom'], ENT_QUOTES);
			$form_script .= "\n" . '</script>' . "\n";
		} else {
			$form_script = '';
		}
		$form_js = get_post_meta($form_id, 'usp-js', true);
		if (!empty($form_js)) $form_js = "\n" . '<script type="text/javascript">' . $form_js . '</script>' . "\n";
		
		if ($current_user->ID) $user_login = '<input type="hidden" name="usp-logged-id" value="' . $current_user->ID . '" />'. "\n";
		else $user_login = '';

		if (!empty($usp_general['use_cat']) && !empty($usp_general['use_cat_id'])) {
			$cat_ids = trim($usp_general['use_cat_id']);
			$cat_ids = explode(",", $cat_ids);
			$cat_id_list = '';
			foreach ($cat_ids as $cat_id) $cat_id_list .= trim($cat_id) . '|';
			$value = rtrim(trim($cat_id_list), '|');
			$user_cat = '<input type="hidden" name="usp-logged-cat" value="'. $value .'" />'. "\n";
		} else { 
			$user_cat = '';
		}
		if ($usp_advanced['submit_button']) {
			if ($usp_advanced['fieldsets']) {
				$fieldset_before = '<fieldset class="usp-fieldset usp-fieldset-default">'. "\n";
				$fieldset_after  = '</fieldset>'. "\n";
			} else {
				$fieldset_before = '';
				$fieldset_after  = '';
			}
			$submit_button = $fieldset_before .'<input type="submit" class="usp-submit usp-submit-default" value="'. $usp_advanced['submit_text'] .'" />'. "\n" . $fieldset_after ."\n";
		} else {
			$submit_button = '';
		}

		$error_message = usp_display_errors();
		$success_message = usp_display_success();

		if (!isset($_SESSION)) session_start();
		$session_verify = '<input type="hidden" name="PHPSESSID" value="'. session_id() .'" />'. "\n";
		$user_verify = '<input type="text" name="usp-verify" id="verify" value="" style="display:none;" class="exclude" />'. "\n";
		$wp_nonce = wp_nonce_field('usp_form_submit', 'usp_form_submit', false, false). "\n";
		$usp_form = '<input type="hidden" name="usp-form-id" value="'. $form_id .'" />'. "\n";
		
		$hidden_inputs = $user_login . $user_cat . $session_verify. $user_verify . $wp_nonce . $usp_form;
		
		$form_info          = "\n\n" .'<!-- USP Pro @ https://plugin-planet.com/usp-pro/ -->'. "\n";
		$form_wrap_before   = '<div id="usp-pro" class="'. $classes .'">'. "\n";
		$form_before        = '<form id="usp-form-'. $form_id .'" class="usp-form" method="post" enctype="multipart/form-data" action=""'. $form_atts .'>'. "\n\n";
		$form_hidden_before = "\n" .'<div class="usp-hidden">'. "\n";
		$form_hidden_after  = '</div>'. "\n\n";
		$form_after         = '</form>'. "\n";
		$form_wrap_after    = '</div>'. "\n\n";
		
		$form_wrap = array(
				'form_before' => $form_style . $form_css . $custom_before . $form_info . $form_wrap_before . $error_message . $success_message . $form_before,
				'form_after'  => $submit_button . $form_hidden_before . $hidden_inputs . $form_hidden_after . $form_after . $form_wrap_after . $custom_after . $form_script . $form_js,
			);
		if ($success && $usp_advanced['success_form'] == '0') {
			$form_wrap = array(
				'form_before' => $custom_before . $success_message, 
				'form_after'  => $custom_after,
			);
		}
	} else {
		$form_wrap = __('error:usp_form_wrap:1:', 'usp') . $form_id;
	}
	return $form_wrap;
}
endif;


/*
	Function: Display errors
		Returns any errors for display
		Syntax: usp_display_errors();
*/
if (!function_exists('usp_display_errors')) : 
function usp_display_errors() {
	global $usp_advanced, $usp_general, $usp_more;
	
	$errors = array();
	$error_message = '';
	$custom_error  = '';
	$custom_prefix = $usp_advanced['custom_prefix'];
	$error_before  = $usp_advanced['error_before'];
	$error_after   = $usp_advanced['error_after'];
	$error_sep     = $usp_more['error_sep'];
	
	if (empty($custom_prefix)) $custom_prefix = 'null___';
	
	$captcha = $usp_general['recaptcha_public'];
	if (isset($captcha) && !empty($captcha)) $captcha_type = __('reCAPTCHA field', 'usp');
	else $captcha_type = __('Challenge Question', 'usp');
	
	wp_parse_str(wp_strip_all_tags($_SERVER['QUERY_STRING']), $params);
	if ($params) {
		foreach ($params as $key => $value) {
			
			if (preg_match("/^usp_error_([0-9]+)$/i", $key)) {
				$errors[] = $usp_more[$key .'_desc'];
				
			} elseif (preg_match("/^usp_error_14_([0-9a-z_-]+)$/i", $key, $match)) {
				$errors[] = $usp_more['tax_before'] . $match[1] . $usp_more['tax_after'];
				
			} elseif (preg_match("/^usp_error_([a-g])$/i", $key)) {
				$errors[] = $usp_more[$key .'_desc'];
				
			} else {
				if (preg_match("/^usp_error_custom_([0-9a-z_-]+)$/i", $key, $match)) {
					if (isset($usp_advanced['usp_label_c'. $match[1]]) && !empty($usp_advanced['usp_label_c'. $match[1]])) {
						$custom_error = $usp_advanced['usp_label_c'. $match[1]];
					} else {
						$custom_error = $match[1];
					}
				} elseif (preg_match("/^usp_ccf_error_([0-9a-z_-]+)$/i", $key, $match)) {
					if (isset($usp_advanced['usp_custom_label_' .$match[1]]) && !empty($usp_advanced['usp_custom_label_'. $match[1]])) {
						$custom_error = $usp_advanced['usp_custom_label_'. $match[1]];
					} else {
						$custom_error = $match[1];
					}
				} elseif (preg_match("/^usp_error_$custom_prefix([0-9a-z_-]+)?$/i", $key, $match)) {
					$custom_error = $custom_prefix . $match[1];
				}
				if (!empty($custom_error)) $errors[] = $usp_more['custom_field_before'] . $custom_error . $usp_more['custom_field_after'];
			}
			if ($key == 'usp_error_form') {
				$errors[] = $usp_more['form_allowed'];
			}
			if ($key == 'usp_error_post') {
				if     ($value == 'post_duplicate') $errors[] = $usp_more['post_duplicate'];
				elseif ($value == 'post_required')  $errors[] = $usp_more['post_required'];
			}
			if ($key == 'usp_error_register') {
				if     ($value == 'error_username') $errors[] = $usp_more['error_username'];
				elseif ($value == 'error_email')    $errors[] = $usp_more['error_email'];
				elseif ($value == 'error_register') $errors[] = $usp_more['error_register'];
				elseif ($value == 'user_exists')    $errors[] = $usp_more['user_exists'];
			}
			if ($key == 'usp_error_1a')  $errors[] = $usp_more['name_restrict'];
			if ($key == 'usp_error_5a')  $errors[] = $usp_more['spam_response'];
			if ($key == 'usp_error_7a')  $errors[] = $usp_more['content_min'];
			if ($key == 'usp_error_7b')  $errors[] = $usp_more['content_max'];
			if ($key == 'usp_error_9a')  $errors[] = $usp_more['email_restrict'];
			if ($key == 'usp_error_10a') $errors[] = $usp_more['subject_restrict'];
			if ($key == 'usp_error_8g')  $errors[] = $usp_more['min_req_files'];
			if ($key == 'usp_error_8h')  $errors[] = $usp_more['max_req_files'];
			
			if (preg_match("/^usp_error_8-([0-9a-z_-]+)$/i", $key))  $errors[] = $usp_more['files_required'];
			if (preg_match("/^usp_error_8a-([0-9a-z_-]+)$/i", $key)) $errors[] = $usp_more['file_type_not'];
			if (preg_match("/^usp_error_8b-([0-9a-z_-]+)$/i", $key)) $errors[] = $usp_more['file_dimensions'];
			if (preg_match("/^usp_error_8c-([0-9a-z_-]+)$/i", $key)) $errors[] = $usp_more['file_max_size'];
			if (preg_match("/^usp_error_8d-([0-9a-z_-]+)$/i", $key)) $errors[] = $usp_more['file_min_size'];
			if (preg_match("/^usp_error_8e-([0-9a-z_-]+)$/i", $key)) $errors[] = $usp_more['file_required'];
			if (preg_match("/^usp_error_8f-([0-9a-z_-]+)$/i", $key)) $errors[] = $usp_more['file_name'];
		} 
		
		if (!empty($errors)) {
			$error_count = count($errors);
			$error_message  = '<div id="usp-form-errors" class="usp-form-errors">';
			$error_message .= $error_before;
			foreach ($errors as $key => $error) {
				if (!empty($error_sep)) {
					$index = (int) $key + 1;
					if ($index === $error_count) $error_message .= $error;
					else $error_message .= $error . $error_sep;
				} else {
					$error_message .= $error;
				}
			}
			$error_message .= $error_after;
			$error_message .= '</div>';
		}
	}
	return $error_message;
}
endif;


/*
	Function: Display success message
		Returns appropriate success message
		Syntax: usp_display_success();
*/
if (!function_exists('usp_display_success')) : 
function usp_display_success() {
	global $usp_advanced;
	wp_parse_str(wp_strip_all_tags($_SERVER['QUERY_STRING']), $params);
	if ($params) {
		$message = '';
		$success_message = '';
		foreach ($params as $key => $value) {
			if ($key == 'usp_success') {
				if     ($value == '1') $message = $usp_advanced['success_reg'];
				elseif ($value == '2') $message = $usp_advanced['success_post'];
				elseif ($value == '3') $message = $usp_advanced['success_both'];
				elseif ($value == '4') $message = $usp_advanced['success_contact'];
				elseif ($value == '5') $message = $usp_advanced['success_email_reg'];
				elseif ($value == '6') $message = $usp_advanced['success_email_post'];
				elseif ($value == '7') $message = $usp_advanced['success_email_both'];
			}
		}
		if ($message !== '') {
			$message = trim($message);
			$success_before = $usp_advanced['success_before'];
			$success_after = $usp_advanced['success_after'];
			$success_message = $success_before . $message . $success_after;
		}
		return $success_message;
	}
}
endif;


/*
	Function: Get Form ID
		Returns the post ID based on id or slug
		Syntax: usp_get_form_id($form_id);
		Parameters:
			$form_id = $post->ID;
*/
if (!function_exists('usp_get_form_id')) : 
function usp_get_form_id($form_id) {
	global $post, $wpdb;
	if ($form_id) {
		if (is_numeric($form_id)) { // id from id
			$form_id = $form_id;
		} else { // id from slug
			$prefix = $wpdb->prefix;
			$current_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM ". $prefix ."posts WHERE post_name = '%s' AND post_type = 'usp_form'", $form_id));
			if ($current_id) $form_id = $current_id;
			else $form_id = __('error:usp_get_form_id:1:', 'usp') . $current_id;
		}
	} else {
		$form_id = __('error:usp_get_form_id:2:', 'usp') . $form_id;	
	}
	return $form_id;
}
endif;


/*
	Function: Checks if form is allowed based on ID and type
		Accepts $form_id and $form_type
		Returns true or false
		Syntax: usp_check_form_type($form_id, $form_type);
*/
if (!function_exists('usp_check_form_type')) : 
function usp_check_form_type($form_id, $form_type) {
	global $usp_general;
	
	$form_id = (string) usp_get_form_id($form_id);
	
	if (!empty($form_id) && !empty($form_type)) {
		
		if ($form_type == 'submit') {
			if (isset($usp_general['submit_form_ids']) && !empty($usp_general['submit_form_ids'])) {
				$submit_ids = usp_get_array($usp_general['submit_form_ids']);
				if (in_array($form_id, $submit_ids)) return true;
			}
		} elseif ($form_type == 'register') {
			if (isset($usp_general['register_form_ids']) && !empty($usp_general['register_form_ids'])) {
				$register_ids = usp_get_array($usp_general['register_form_ids']);
				if (in_array($form_id, $register_ids)) return true;
			}
		} elseif ($form_type == 'contact') {
			if (isset($usp_general['contact_form']) && !empty($usp_general['contact_form'])) {
				$contact_ids = usp_get_array($usp_general['contact_form']);
				if (in_array($form_id, $contact_ids)) return true;
			}
		}
	}
	return false;
}
endif; 


/*
	Function: Coverts comma-separated string to array
		Accepts a string returns an array
		Syntax: usp_get_array($string);
*/
if (!function_exists('usp_get_array')) : 
function usp_get_array($string) {
	$string = rtrim(trim($string), ',');
	$array = array_map('usp_get_form_id', array_map('trim', explode(',', $string)));
	return $array;
}
endif;


/*
	Function: Auto-include Fieldset
		Returns required markup for fieldset
		Syntax: usp_fieldset($arg);
		Argument: true (default classes), false or null (to disable), or custom class name(s) (comma-separated)
		Default: value of Advanced > Form Configuration > Auto-include fieldsets
*/
if (!function_exists('usp_fieldset')) : 
function usp_fieldset($arg) {
	global $usp_advanced;
	$display = (bool) $usp_advanced['fieldsets'];
	$fieldset_before = '';
	$fieldset_after  = '';
	$classes = '';
	if (!empty($arg)) {
		if ($arg == 'true') {
			$display = true;
		} elseif ($arg == 'false' || $arg == 'null') {
			$display = false;
		} else {
			$display = true;
			$custom_classes = trim($arg);
			$class_pieces = explode(',', $custom_classes);
			foreach ($class_pieces as $class) $classes .= ' '. trim($class);
			$classes = ' '. trim($classes);
		}
	}
	if ($display) {
		$fieldset_before = '<fieldset class="usp-fieldset usp-fieldset-default'. $classes .'">'. "\n";
		$fieldset_after  = '</fieldset>'. "\n";
	}
	return array('fieldset_before' => $fieldset_before, 'fieldset_after' => $fieldset_after);
}
endif;


/*
	Function: Auto-include Fieldset for Custom Fields
		Returns required markup for fieldset for Custom Fields
		Syntax: usp_fieldset_custom($disable, $classes);
		Arguments: 
			$enable = optional: true to enable the fieldset (default), or false/null to disable
			$class  = optional: custom class name(s) for the fieldset (comma-separated)
		Default: value of Advanced > Form Configuration > Auto-include fieldsets
*/
if (!function_exists('usp_fieldset_custom')) : 
function usp_fieldset_custom($enable = '', $class = '') {
	global $usp_advanced;
	$display = (bool) $usp_advanced['fieldsets'];
	$fieldset_before = '';
	$fieldset_after  = '';
	$classes = '';
	if (!empty($enable)) {
		if ($enable == 'true') {
			$display = true;
		} elseif ($enable == 'false' || $enable == 'null') {
			$display = false;
		}
	}
	if (!empty($class)) {
		$custom_classes = trim($class);
		$class_pieces = explode(',', $custom_classes);
		foreach ($class_pieces as $class) $classes .= ' '. trim($class);
		$classes = ' '. trim($classes);
	}
	if ($display) {
		$fieldset_before = '<fieldset class="usp-fieldset usp-fieldset-default'. $classes .'">'. "\n";
		$fieldset_after  = '</fieldset>'. "\n";
	}
	return array('fieldset_before' => $fieldset_before, 'fieldset_after' => $fieldset_after);
}
endif;


/*
	Function: Label input
		Returns appropriate label value
		Syntax: usp_label();
		Parameters:
			$args = passed from shortcode
			$field = identity of input
*/
if (!function_exists('usp_label')) : 
function usp_label($args, $field) {
	global $usp_advanced, $usp_general;
	if (isset($args['label']) && !empty($args['label'])) {
		if ($args['label'] == 'null') $label = '';
		else $label = trim($args['label']);
	} else {
		if ($field == 'captcha_question') {
			$label = $usp_general[$field];
		} else {
			if (isset($usp_advanced[$field]) && !empty($usp_advanced[$field])) {
				$label = $usp_advanced[$field];
			} else {
				$label = $field;
			}
		}
	}
	return $label;
}
endif;


/*
	Function: Required attribute
		Returns appropriate required attribute value
		Syntax: usp_required();
		Parameters:
			$args = passed from shortcode
*/
if (!function_exists('usp_required')) : 
function usp_required($args) {
	$required = 'true';
	if (isset($args['required']) && !empty($args['required'])) {
		$att_val = $args['required'];
		if ($att_val == 'false' || $att_val == 'no' || $att_val == 'off') $required = 'false';
	}
	return $required;
}
endif;


/*
	Function: Max input characters
		Returns value for maxlength attribute
		Syntax: usp_max_att();
		Parameters:
			$args = passed from shortcode
			$default = default value
*/
if (!function_exists('usp_max_att')) : 
function usp_max_att($args, $default) {
	if (isset($args['max']) && !empty($args['max'])) {
		$max = trim($args['max']);
	} else {
		$max = $default;
	}
	return $max;
}
endif;


/*
	Function: Placeholder input
		Returns appropriate placeholder value
		Syntax: usp_placeholder();
		Parameters:
			$args = passed from shortcode
			$field = identity of input
*/
if (!function_exists('usp_placeholder')) : 
function usp_placeholder($args, $field) {
	global $usp_advanced, $usp_general;
	if (isset($args['placeholder']) && !empty($args['placeholder'])) {
		if ($args['placeholder'] == 'null') $placeholder = '';
		else $placeholder = trim($args['placeholder']);
	} else {
		if ($field == 'captcha_question') {
			$placeholder = $usp_general[$field];
		} else {
			$placeholder = $usp_advanced[$field];
		}
	}
	return $placeholder;
}
endif;


/*
	Function: Custom CSS classes
		Returns formatted set of classes per field
		Syntax: usp_classes();
*/
if (!function_exists('usp_classes')) : 
function usp_classes($args, $field = '') {
	$error = '';
	wp_parse_str(wp_strip_all_tags($_SERVER['QUERY_STRING']), $vars);
	if ($vars) {
		if ($field !== 'submit') {
			foreach ($vars as $var) {
				if (preg_match("/^usp_error_$field([a-z_-]+)?$/i", $var)) {
					$error = 'usp-error-field usp-error-primary ';
					
				} elseif (preg_match("/^usp_error_8([0-9a-z-]+)$/i", $var)) {
					$error = 'usp-error-field usp-error-file ';
				}		
			}
		}
	}
	if (isset($args) && !empty($args)) {
		$class_string = trim($args);
		$class_pieces = explode(",", $class_string);
		$classes = '';
		foreach ($class_pieces as $class) {
			$classes .= trim($class) . ' ';
		}
		$classes = trim($classes);
	} else {
		$classes = '';	
	}
	$classes = $error . $classes;
	return $classes;
}
endif;


/*
	Function: Default tags
		Returns formatted set of default tags
		Syntax: usp_tags();
*/
if (!function_exists('usp_tags')) : 
function usp_tags($args) {
	if (isset($args) && !empty($args)) {
		$tags_string = trim($args);
		$tags_pieces = explode(",", $tags_string);
		$tags = '';
		foreach ($tags_pieces as $tag) {
			$tags .= trim($tag) . '|';
		}
		$tags = rtrim(trim($tags), '|');
	} else {
		$tags = '';	
	}
	return $tags;
}
endif;


/*
	Function: Default cats
		Returns formatted set of default categories
		Syntax: usp_cats();
*/
if (!function_exists('usp_cats')) : 
function usp_cats($args) {
	if (isset($args) && !empty($args)) {
		$cats_string = trim($args);
		$cats_pieces = explode(",", $cats_string);
		$cats = '';
		foreach ($cats_pieces as $cat) {
			$cats .= trim($cat) . '|';
		}
		$cats = rtrim(trim($cats), '|');
	} else {
		$cats = '';	
	}
	return $cats;
}
endif;


/*
	Function: Get the current IP address
		Returns the current IP address
		Syntax: usp_get_ip();
*/
if (!function_exists('usp_get_ip')) : 
function usp_get_ip() {
	if(isset($_SERVER)) {
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif(isset($_SERVER["HTTP_CLIENT_IP"])) {
			$ip_address = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$ip_address = $_SERVER["REMOTE_ADDR"];
		}
	} else {
		if(getenv('HTTP_X_FORWARDED_FOR')) {
			$ip_address = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('HTTP_CLIENT_IP')) {
			$ip_address = getenv('HTTP_CLIENT_IP');
		} else {
			$ip_address = getenv('REMOTE_ADDR');
		}
	}
	return $ip_address;
}
endif;


/*
	Function: converts custom field strings into arrays
		Returns an array containing two arrays, optional and required
		Syntax: usp_custom_field_string_to_array();
*/
if (!function_exists('usp_custom_field_string_to_array')) : 
function usp_custom_field_string_to_array() {
	global $usp_advanced;
	$custom_optional = array();
	$custom_required = array();
	
	if (isset($usp_advanced['custom_optional']) && !empty($usp_advanced['custom_optional'])) {
		$custom_optional = '';
		$custom_optional = $usp_advanced['custom_optional'];
		$custom_optional = trim($custom_optional);
		$custom_optional_array = explode(",", $custom_optional);
		$custom_optional = array();
		foreach ($custom_optional_array as $value) $custom_optional[] = trim($value);
	}
	if (isset($usp_advanced['custom_required']) && !empty($usp_advanced['custom_required'])) {
		$custom_required = '';
		$custom_required = $usp_advanced['custom_required'];
		$custom_required = trim($custom_required);
		$custom_required_array = explode(",", $custom_required);
		$custom_required = array();
		foreach ($custom_required_array as $value) $custom_required[] = trim($value);
	}
	if (is_array($custom_optional) && is_array($custom_required)) {
		$custom_field_arrays = array('optional' => $custom_optional, 'required' => $custom_required);
		return $custom_field_arrays;
	}
}
endif;


/*
	Function: merges optional and required custom custom fields into array
		Returns an array of custom custom field names
		Syntax: usp_merge_custom_fields();
*/
if (!function_exists('usp_merge_custom_fields')) : 
function usp_merge_custom_fields() {
	
	$custom_field_arrays = usp_custom_field_string_to_array();
	
	if (is_array($custom_field_arrays['optional']) && is_array($custom_field_arrays['required'])) {
		$custom_custom = array_merge($custom_field_arrays['optional'], $custom_field_arrays['required']);
	}
	if (isset($custom_custom) && !empty($custom_custom)) return $custom_custom;
	else return array();
}
endif;


/*
	Function: appends -required to set of required custom custom field names
		Returns an array of names for hidden required fields
		Syntax: usp_required_custom_fields();
*/
if (!function_exists('usp_required_custom_fields')) : 
function usp_required_custom_fields() {
	global $usp_advanced;
	$custom_required = array();
	if (isset($usp_advanced['custom_required']) && !empty($usp_advanced['custom_required'])) $custom_required = $usp_advanced['custom_required'];
	if ($custom_required) {
		$custom_required = trim($custom_required);
		$custom_required_array = explode(",", $custom_required);
		$custom_required = array();
		foreach ($custom_required_array as $value) $custom_required[] = trim($value) .'-required';
	}
	return $custom_required;
}
endif;


/*
	Function: Checks for malicious input
		Returns true or false
		Syntax: usp_check_malicious($input);
		Parameters:
			$input = string to check
*/
if (!function_exists('usp_check_malicious')) : 
function usp_check_malicious($input) {
	$mal = false;
	$bad_string = array("\r", "\n", "mime-version", "content-type", "cc:", "to:");
	foreach ($bad_string as $string) {
		if (strpos(strtolower($input), strtolower($string)) !== false) {
			$mal = true;
			break;
		}
	}
	return $mal;
}
endif;


/*
	Function: Cleans up input strings
		Returns true or false
		Syntax: usp_clean($string);
		Parameters:
			$string = string to clean
*/
if (!function_exists('usp_clean')) : 
function usp_clean($string) {
	$string = trim($string); 
	$string = strip_tags($string);
	$string = htmlspecialchars($string, ENT_QUOTES, get_option('blog_charset', 'UTF-8'));
	$string = str_replace("\n", "", $string);
	$string = trim($string); 
	return $string;
}
endif;


/*
	Function: Convert to bytes
		Returns number in bytes
		Syntax: usp_return_bytes($value)
		Parameters:
			$value = string to convert
*/
if (!function_exists('usp_return_bytes')) : 
function usp_return_bytes($value) {
	if (is_numeric($value)) {
		return $value;
	} else {
		$value_length = strlen($value);
		$qty = substr($value, 0, $value_length - 1);
		$unit = strtolower(substr($value, $value_length - 1 ));
		switch ($unit) {
			case 'k':
				$qty *= 1024;
				break;
			case 'm':
				$qty *= 1048576;
				break;
			case 'g':
				$qty *= 1073741824;
				break;
		}
		return $qty;
	}		
}
endif;


/*
	Function: Returns boolean response
		Returns 'Enabled', 'Disabled', or n/a
		Syntax: usp_return_bool($value);
		Parameters:
			$value = string to convert
*/
if (!function_exists('usp_return_bool')) : 
function usp_return_bool($value) {
	$value = strtolower($value);
	if (empty($value) || $value == 'off') return __('Disabled', 'usp');
	if ($value == '1' || $value == 'on') return __('Enabled', 'usp');
	return __('-- n/a --', 'usp');
}
endif;


/*
	Function: Cleans up input strings
		Returns true or false
		Syntax: usp_return_defined($value);
		Parameters:
			$string = string to check
*/
if (!function_exists('usp_return_defined')) : 
function usp_return_defined($value) {
	$value = strtoupper($value);
	if (defined($value)) {
		if (constant($value) === true) return 'TRUE';
		elseif (constant($value) === false) return 'FALSE';
		else return constant($value);
	} else {
		return __('Undefined', 'usp');
	}
}
endif;


/*
	Function: Checks if multisite install
		Returns true or false
		Syntax: usp_is_multisite();
*/
if (!function_exists('usp_is_multisite')) : 
function usp_is_multisite() {
	if (!function_exists('is_multisite')) return false;
	return is_multisite();
}
endif;


/*
	Function: Get popular tags
		Returns an array of popular tags
		Syntax: get_popular_tags($number);
		Parameters:
			$number = number of tags to return
*/
if (!function_exists('get_popular_tags')) : 
function get_popular_tags($number) {
	$args = array(
		'orderby'    => 'count',
		'order'      => 'ASC',
		'hide_empty' => false,
		'number'     => $number,
	);
	$tags = get_tags($args); 
	$tag_ids = array();
	foreach ($tags as $tag) $tag_ids[] = $tag->term_id;
	return $tag_ids;
}
endif;


/*
	Function: Display uploaded images
		Displays uploaded images directly in post content for user-submitted posts
		Syntax: usp_display_images();
		Parameters:
			Filters the $content variable
*/
if (!function_exists('usp_display_images')) : 
function usp_display_images($content) {
	global $post, $usp_uploads;
	$size = $usp_uploads['display_size'];
	$content = $content;
	$submitted_images = usp_get_image(false, $size, false, false, 'file', false, false, false, false, false, false, false, false, false);
	if (!isset($submitted_images) || empty($submitted_images)) $submitted_images = '';
	$display_images = $usp_uploads['post_images'];
	if (isset($display_images) && !empty($display_images)) {
		if (usp_is_submitted()) {
			if ($display_images == 'before') $content = $submitted_images . $content;
			elseif ($display_images == 'after') $content = $content . $submitted_images;
		}
	}
	return $content;
}
add_filter('the_content', 'usp_display_images');
endif;


/*
	Function: Get array of *all* categories
		Returns array containing id and level
		Syntax: usp_get_categories();
*/
if (!function_exists('usp_get_categories')) : 
function usp_get_categories() {
	$cats = get_categories(array('parent' => 0, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 0));
	$usp_cats = array();
	if (!empty($cats)) {
		foreach ($cats as $c) {
			// parents
			$usp_cats['c'][] = array('id' => $c->term_id, 'c1' => array());
			$children = get_terms('category', array('parent' => $c->term_id, 'hide_empty' => 0));
			if (!empty($children)) {
				foreach ($children as $c1) {
					// children
					$usp_cats['c'][]['c1'][] = array('id' => $c1->term_id, 'c2' => array());
					$grandchildren = get_terms('category', array('parent' => $c1->term_id, 'hide_empty' => 0));
					if (!empty($grandchildren)) {
						foreach ($grandchildren as $c2) {
							// grandchildren
							$usp_cats['c'][]['c1'][]['c2'][] = array('id' => $c2->term_id, 'c3' => array());
							$great_grandchildren = get_terms('category', array('parent' => $c2->term_id, 'hide_empty' => 0));
							if (!empty($great_grandchildren)) {
								foreach ($great_grandchildren as $c3) {
									// great enkelkinder
									$usp_cats['c'][]['c1'][]['c2'][]['c3'][] = array('id' => $c3->term_id, 'c4' => array());
									$great_great_grandchildren = get_terms('category', array('parent' => $c3->term_id, 'hide_empty' => 0));
									if (!empty($great_great_grandchildren)) {
										foreach ($great_great_grandchildren as $c4) {
											// great great grandchildren
											$usp_cats['c'][]['c1'][]['c2'][]['c3'][]['c4'][] = array('id' => $c4->term_id);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	return $usp_cats;
}
endif;


/*
	Function: Get array of *selected* categories
		Returns array containing id and level
		Syntax: usp_get_cats();
*/
if (!function_exists('usp_get_cats')) : 
function usp_get_cats() {
	global $usp_general;
	$cats = array();
	if (isset($usp_general['categories'])) $cats = $usp_general['categories'];
	$cats_on = array(); 
	$usp_cats = usp_get_categories();
	foreach ($usp_cats as $v0) {
		if (is_array($v0)) {
			foreach ($v0 as $v1) {
				if (is_array($v1)) {
					if (!empty($v1['id'])) {
						if (in_array($v1['id'], $cats)) $cats_on[] = array('id' => $v1['id'], 'level' => 'parent');
					} else {
						foreach ($v1['c1'] as $v2) {
							if (is_array($v2)) {
								if (!empty($v2['id'])) {
									if (in_array($v2['id'], $cats)) $cats_on[] = array('id' => $v2['id'], 'level' => 'child');
								} else {
									foreach ($v2['c2'] as $v3) {
										if (is_array($v3)) {
											if (!empty($v3['id'])) {
												if (in_array($v3['id'], $cats)) $cats_on[] = array('id' => $v3['id'], 'level' => 'grandchild');
											} else {
												foreach ($v3['c3'] as $v4) {
													if (is_array($v4)) {
														if (!empty($v4['id'])) {
															if (in_array($v4['id'], $cats)) $cats_on[] = array('id' => $v4['id'], 'level' => 'great_grandchild');
														} else {
															foreach ($v4['c4'] as $v5) {
																if (is_array($v5)) {
																	if (!empty($v5['id'])) {
																		if (in_array($v5['id'], $cats)) $cats_on[] = array('id' => $v5['id'], 'level' => 'great_great_grandchild');
																	}
																}
															}
														}	
													}
												}
											}		
										}
									}
								}	
							}
						}
					}
				}
			}
		}
	}
	return $cats_on;
}
endif;


