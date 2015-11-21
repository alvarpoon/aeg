<?php // USP Pro - Custom Post Type for USP Posts

if (!defined('ABSPATH')) die();

/*
	Class: Custom Post Type for USP Posts
	Provides various functions for the usp_post post type
*/
if (!class_exists('USP_Pro_Posts')) {
	class USP_Pro_Posts {
		const POST_TYPE = 'USP_Post';
		public function __construct() {
			add_action('init', array(&$this, 'init'));
		}
		public function init() {
			$this->create_post_type();
			$this->create_post_examples();
			add_action('admin_init', array(&$this, 'settings_updated'));
		}
		public static function create_post_type() {
			global $usp_advanced;
			if (isset($usp_advanced['post_type_slug']) && !empty($usp_advanced['post_type_slug'])) $post_slug = $usp_advanced['post_type_slug'];
			if ($usp_advanced['post_type'] == 'usp_post') {
				$capabilities = array(
					'edit_post'              => 'edit_usp_post',
					'read_post'              => 'read_usp_post',
					'delete_post'            => 'delete_usp_post',
					'edit_posts'             => 'edit_usp_posts',
					'publish_posts'          => 'publish_usp_posts',
					'edit_others_posts'      => 'edit_others_usp_posts',
					'read_private_posts'     => 'read_private_usp_posts',
					'delete_posts'           => 'delete_usp_posts',
					'delete_private_posts'   => 'delete_private_usp_posts',
					'delete_published_posts' => 'delete_published_usp_posts',
					'delete_others_posts'    => 'delete_others_usp_posts',
					'edit_private_posts'     => 'edit_private_usp_posts',
					'edit_published_posts'   => 'edit_published_usp_posts',
				);
				$labels = array(
					'name'               => __(sprintf('%ss', ucwords(str_replace("_", " ", self::POST_TYPE))), 'usp'),
					'singular_name'      => __(ucwords(str_replace("_", " ", self::POST_TYPE)), 'usp'),
					'add_new'            => __('Add New', 'usp'),
					'add_new_item'       => __('Add New USP Post', 'usp'),
					'edit_item'          => __('Edit USP Post', 'usp'),
					'new_item'           => __('New USP Post', 'usp'),
					'view_item'          => __('View USP Post', 'usp'),
					'search_items'       => __('Search USP Posts', 'usp'),
					'not_found'          => __('No USP Posts found', 'usp'),
					'not_found_in_trash' => __('No USP Posts found in Trash', 'usp'),
					'parent_item_colon'  => __('Parent USP Post:', 'usp'),
					'menu_name'          => __('USP Posts', 'usp'),
				);
				$args = array(
					'labels'              => $labels,
					'description'         => __('USP Post Types', 'usp'),
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'publicly_queryable'  => true, 
					'exclude_from_search' => false,
					'show_in_nav_menus'   => true,
					'menu_position'       => null,
					'menu_icon'           => 'dashicons-admin-post',
					'hierarchical'        => false,
					'taxonomies'          => array('category', 'post_tag', 'page-category', 'optional'),
					'supports'            => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes'),
					'has_archive'         => true,
					'can_export'          => true,
					'rewrite'             => array('slug' => $post_slug, 'with_front' => true),
					'query_var'           => $post_slug,
					//
					'capability_type'     => 'usp_post',
					'map_meta_cap'        => true, 
					'capabilities'        => $capabilities,
				);
				register_post_type(strtolower(self::POST_TYPE), $args);
			}
		}
		public function settings_updated() {
			global $pagenow, $usp_advanced;
			if (is_admin() && $pagenow == 'options-general.php') {
				if (isset($_GET['page']) && $_GET['page'] == 'usp_options') {
					if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
						if (isset($usp_advanced['post_type_role'])) {
							$roles = $usp_advanced['post_type_role'];
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
				'edit_usp_post',
				'read_usp_post',
				'delete_usp_post',
				'edit_usp_posts',
				'publish_usp_posts',
				'edit_others_usp_posts',
				'read_private_usp_posts',
				'delete_usp_posts',
				'delete_private_usp_posts',
				'delete_published_usp_posts',
				'delete_others_usp_posts',
				'edit_private_usp_posts',
				'edit_published_usp_posts'
			);
			return $caps;	
		}
		public function create_post_examples() {
			global $usp_advanced;
			if ($usp_advanced['post_type'] == 'usp_post') {
				if ($usp_advanced['post_demos']) {
					$usp_post = __('This is an example of a custom USP Post. To delete this post, disable the option "Auto-generate post demo" (Advanced settings tab) and then delete as mormal. To restore the demo post, re-enable the auto-generate option.', 'usp');
					$usp_shortcode = '<p>This demo includes a few of the plugin&rsquo;s shortcodes. Visit this post on the front-end to see it in action!</p>'. "\n" .'<h3>Display user info</h3>'. "\n" .'<p>Hello, [usp_status display="name"]! Here is your information:</p>'. "\n" .'<ul>'. "\n" .'<li>ID: [usp_status display="id"]</li>'. "\n" .'<li>Role: [usp_status display="role"]</li>'. "\n" .'<li>Email: [usp_status display="email"]</li>'. "\n" .'</ul>'. "\n" .'<h3>Display content based on user role</h3>'. "\n" .'<p>[usp_access cap="switch_themes" deny=""]Content for Admins only.[/usp_access]</p>'. "\n" .'<h3>Display content for any logged-in user</h3>'. "\n" .'<p>[usp_member deny="Login required!"]Content for logged-in users only.[/usp_member]</p>'. "\n" .'<h3>Display content for visitors</h3>'. "\n" .'<p>[usp_visitor deny="Sorry, you are logged in!"]Content for visitors (not logged in) only.[/usp_visitor]</p>';
					$existing_post = get_page_by_title('USP Post Demo', ARRAY_A, 'usp_post');
					$existing_shortcode = get_page_by_title('USP Shortcode Demo', ARRAY_A, 'usp_post');
					if (!$existing_post) {
						$post_demo = array(
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
							'post_content'   => $usp_post,
							'post_name'      => 'example-post',
							'post_status'    => 'draft',
							'post_title'     => 'USP Post Demo',
							'post_type'      => 'usp_post',
						);
						$post_demo_ID = wp_insert_post($post_demo);
						add_post_meta($post_demo_ID, 'is_submission', '1', true);
					}
					if (!$existing_shortcode) {
						$shortcode_demo = array(
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
							'post_content'   => $usp_shortcode,
							'post_name'      => 'shortcodes',
							'post_status'    => 'draft',
							'post_title'     => 'USP Shortcode Demo',
							'post_type'      => 'usp_post',
						);
						$shortcode_demo_ID = wp_insert_post($shortcode_demo);
						add_post_meta($shortcode_demo_ID, 'is_submission', '1', true);
					}
				}
			}
		}
	}
}



// meta boxes
function usp_custom_meta() {
	global $post;
	$post_types = get_post_types(array('public' => true, '_builtin' => false));
	array_push($post_types, 'post', 'page');
	
	$display_box = false;
	$post_meta = get_post_meta($post->ID);
	foreach($post_meta as $key => $value) {
		if (strpos($key, '_usp_') !== false) {
			$display_box = true; 
			break;
		}
	}
	if ($display_box) {
		foreach ($post_types as $post_type) add_meta_box('usp_meta', __('USP Custom Fields', 'usp'), 'usp_meta_callback', $post_type);
	}
}
add_action('add_meta_boxes', 'usp_custom_meta');

function usp_meta_callback($post) {
	wp_nonce_field(basename(__FILE__), 'usp_meta_nonce');
	$post_meta = get_post_meta($post->ID); ?>
		
	<table class="usp-meta-boxes">
	<thead><tr><th><?php _e('Name', 'usp'); ?></th><th><?php _e('Value', 'usp'); ?></th></tr></thead><tbody>
	
	<?php foreach ($post_meta as $key => $value) {
		if (preg_match("/^_usp_([0-9a-z_-]+)?$/i", $key, $match)) {
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					$label = str_replace('_usp_', '', $match[0]);
					$label = ucwords(trim(str_replace('_', ' ', $label))); ?>
					
					<tr class="usp-meta-box">
						<td class="usp-meta-name"><label for="<?php echo $key; ?>" class="usp-meta-label"><?php echo $label; ?></label></td>
						<td class="usp-meta-value"><input name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="usp-meta-input" type="text" value="<?php echo $v; ?>" /></td>
					</tr>
				<?php }
			}
		}
	} ?>
	
	</tbody></table>
<?php }

function usp_meta_save($post_id) {
	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = (isset($_POST['usp_meta_nonce']) && wp_verify_nonce($_POST['usp_meta_nonce'], basename(__FILE__))) ? 'true' : 'false';
	if ($is_autosave || $is_revision || !$is_valid_nonce) return;
	
	$post_meta = get_post_meta($post_id);
	foreach ($post_meta as $key => $value) {
		if (preg_match("/^_usp_([0-9a-z_-]+)?$/i", $key, $match)) {
			if (isset($_POST[$key])) {
				$update = update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
			}
		}	
	}
}
add_action('save_post', 'usp_meta_save');


