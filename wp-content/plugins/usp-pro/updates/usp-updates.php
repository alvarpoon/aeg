<?php // USP Pro - License & Activation

define('USP_ITEM_NAME', 'USP Pro Personal');

if (!class_exists('EDD_SL_Plugin_Updater')) {
	include(dirname(__FILE__) . '/usp-updater.php');
}

if (!function_exists('usp_pro_plugin_updater')) : 
	function usp_pro_plugin_updater() {
		$license_key = trim(get_option('usp_license_key'));
		$edd_updater = new EDD_SL_Plugin_Updater(
			USP_URL, USP_FILE, 
			array(
				'license'   => $license_key,
				'item_name' => USP_ITEM_NAME,
				'author'    => USP_AUTHOR,
				'version'   => USP_VERSION,
				'url'       => USP_URL,
				// 'item_id'   => ,
			)
		);
	}
	add_action('admin_init', 'usp_pro_plugin_updater', 0);
endif;

// settings menu
if (!function_exists('usp_license_menu')) :
function usp_license_menu() {
	add_plugins_page('USP Pro License', 'USP Pro License', 'manage_options', 'usp-pro-license', 'usp_license_page');
}
add_action('admin_menu', 'usp_license_menu');
endif;

// settings page
if (!function_exists('usp_license_page')) :
function usp_license_page() {
	$license 	= get_option('usp_license_key');
	$status 	= get_option('usp_license_status'); ?>

	<?php if (isset($_GET['settings-updated'])) { ?>
		<div id="message" class="updated"><p><strong><?php _e('Settings saved.', 'usp') ?></strong></p></div>
	<?php } ?>

	<div class="wrap">
		<h2 class="usp-title"><?php _e('USP Pro License', 'usp'); ?></h2>
		<p>
			<?php echo __('Your purchase of USP Pro entitles you to free automatic updates according to the license terms. To enable this feature, enter your License Key below. 
			Note: to view your License Key at any time,', 'usp') .' <a href="'. USP_URL .'/wp/wp-admin/" target="_blank">'. __('log in to your account at Plugin Planet', 'usp') .'</a>.'; ?>
		</p>
		<form method="post" action="options.php">
		
			<?php settings_fields('usp_license_settings'); ?>
			
			<table class="form-table">
				<tbody>
					<?php if ($status !== false && $status == 'valid') : ?>
						<tr valign="top">	
							<th scope="row" valign="top"><?php _e('License Key', 'usp'); ?></th>
							<td>
								<p><code style="padding:5px 10px;text-shadow:1px 1px 1px #fff;font-size:16px;"><?php esc_attr_e($license); ?></code></p>
								<input id="usp_license_key" name="usp_license_key" type="hidden" value="<?php esc_attr_e($license); ?>" />
							</td>
						</tr>
						<tr valign="top">	
							<th scope="row" valign="top"><?php _e('License Status', 'usp'); ?></th>
							<td>
								<p><small style="color:green;"><?php _e('Your USP Pro License is currently active.', 'usp'); ?></small></p>
								<p><input type="submit" class="button-secondary" name="usp_license_deactivate" value="<?php _e('Deactivate License', 'usp'); ?>" /></p>
								<?php wp_nonce_field('usp_license_nonce', 'usp_license_nonce'); ?>
							</td>
						</tr>
					<?php else : ?>
						<tr valign="top">	
							<th scope="row" valign="top"><?php _e('License Key', 'usp'); ?></th>
							<td>
								<p><input id="usp_license_key" name="usp_license_key" type="text" class="regular-text" value="<?php esc_attr_e($license); ?>" /><br />
								<small><label class="description" for="usp_license_key"><?php _e('Enter your license key', 'usp'); ?></label></small></p>
							</td>
						</tr>
						<?php if (!empty($license)) : ?>
						<tr valign="top">	
							<th scope="row" valign="top"><?php _e('License Status', 'usp'); ?></th>
							<td>
								<p><small><?php _e('Your license is currently not active. To activate, click &ldquo;Activate License&rdquo;:', 'usp'); ?></small></p>
								<p><input type="submit" class="button-secondary" name="usp_license_activate" value="<?php _e('Activate License', 'usp'); ?>" /></p>
								<?php wp_nonce_field('usp_license_nonce', 'usp_license_nonce'); ?>
							</td>
						</tr>
						<?php endif; ?>
					<?php endif; ?>
				</tbody>
			</table>	
			<?php submit_button(); ?>

			<p><a href="<?php get_admin_url(); ?>options-general.php?page=usp_options&tab=usp_license"><?php _e('Return to USP Pro Settings &raquo;', 'usp'); ?></a></p>
		</form>
	<?php 
}
endif;

// register option
if (!function_exists('usp_license_register_option')) :
function usp_license_register_option() {
	register_setting('usp_license_settings', 'usp_license_key', 'usp_sanitize_option');
}
add_action('admin_init', 'usp_license_register_option');
endif;

// sanitize option
if (!function_exists('usp_sanitize_option')) :
function usp_sanitize_option($new) {
	$old = get_option('usp_license_key');
	if ($old && $old != $new) delete_option('usp_license_status');
	return $new;
}
endif;

// activate license
if (!function_exists('usp_activate_license')) :
function usp_activate_license() {
	if (isset($_POST['usp_license_activate'])) {
	 	if (!check_admin_referer('usp_license_nonce', 'usp_license_nonce')) return;

		$license = trim(get_option('usp_license_key'));
		$api_params = array('edd_action' => 'activate_license', 'license' => $license, 'item_name' => urlencode(USP_ITEM_NAME));
		
		$add_args = add_query_arg($api_params, USP_URL);
		$response = wp_remote_get(esc_url_raw($add_args), array('timeout' => 15, 'sslverify' => false));

		if (is_wp_error($response)) return false;
		
		$license_data = json_decode(wp_remote_retrieve_body($response));
		update_option('usp_license_status', $license_data->license);
	}
}
add_action('admin_init', 'usp_activate_license');
endif;

// deactivate license
if (!function_exists('usp_deactivate_license')) :
function usp_deactivate_license() {
	if (isset($_POST['usp_license_deactivate'])) {
	 	if (!check_admin_referer('usp_license_nonce', 'usp_license_nonce')) return;

		$license = trim(get_option('usp_license_key'));
		$api_params = array('edd_action' => 'deactivate_license', 'license' => $license, 'item_name' => urlencode(USP_ITEM_NAME));
		
		$add_args = add_query_arg($api_params, USP_URL);
		$response = wp_remote_get(esc_url_raw($add_args), array('timeout' => 15, 'sslverify' => false));

		if (is_wp_error($response)) return false;
		
		$license_data = json_decode(wp_remote_retrieve_body($response));
		if ($license_data->license == 'deactivated') delete_option('usp_license_status');
	}
}
add_action('admin_init', 'usp_deactivate_license');
endif;

// check license
if (!function_exists('usp_check_license')) :
function usp_check_license() {
	$license = get_option('usp_license_key');
	$api_params = array( 
		'edd_action' => 'check_license', 
		'license'    => $license, 
		'item_name'  => urlencode(USP_ITEM_NAME) 
	);
	
	$add_args = add_query_arg($api_params, USP_URL);
	$response = wp_remote_get(esc_url_raw($add_args), array('timeout' => 15, 'sslverify' => false));
	
	if (is_wp_error($response)) return false;
	
	$license_data = json_decode(wp_remote_retrieve_body($response));
	if ($license_data->license == 'valid') {
		set_transient('license_status', 'valid', 60 * 60 * 24);
	} else {
		set_transient('license_status', 'invalid', 60 * 60 * 24);
	}
	$license_status = get_transient('license_status');
	return $license_status;
	exit;
}
endif;
