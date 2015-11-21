<?php // USP Pro - About/Infos (Settings Tab)

if (!defined('ABSPATH')) die();



/*
	About USP Pro
*/
if (!function_exists('usp_about_plugin')) : 
function usp_about_plugin() {
	$plugin_data = get_plugin_data(USP_PATH .'/usp-pro.php', false, false);
	
	if (isset($plugin_data['PluginURI']))  $plugin_data['PluginURI']  = '<a target="_blank" href="https://plugin-planet.com/usp-pro/">https://plugin-planet.com/usp-pro/</a>';
	if (isset($plugin_data['Author']))     $plugin_data['Author']     = '<a target="_blank" href="https://twitter.com/perishable" title="Jeff Starr @perishable on Twitter">Jeff Starr</a>';
	if (isset($plugin_data['AuthorURI']))  $plugin_data['AuthorURI']  = '<a target="_blank" href="http://monzilla.biz/">Monzilla Media</a> and <a target="_blank" href="https://perishablepress.com/">Perishable Press</a>';
	if (isset($plugin_data['DomainPath'])) $plugin_data['DomainPath'] = '<code>'. $plugin_data['DomainPath'] .'</code>';
	if (isset($plugin_data['Name']))       $plugin_data['Name']       = 'USP Pro (User Submitted Posts Pro)';
	
	if (isset($plugin_data['Network']))   unset($plugin_data['Network']); // MultiSite not yet supported
	if (isset($plugin_data['Title']))     unset($plugin_data['Title']); // redundant info, same as Name
	
	$about_plugin = '<ul>';
	foreach ($plugin_data as $key => $value) {
		$about_plugin .= '<li><strong>' . $key .':</strong> '. $value .'</li>';
	}
	$about_plugin .= '<li><strong>'. __('Donate Link:', 'usp') .' </strong> <a target="_blank" href="http://m0n.co/donate">http://m0n.co/donate</a></li>';
	$about_plugin .= '<li><strong>'. __('Minimum WP Version:', 'usp') .' </strong> '. USP_REQUIRES .'</li>';
	$about_plugin .= '<li><strong>'. __('Tested up to:', 'usp') .' </strong>'. USP_TESTED .'</li>';
	$about_plugin .= '</ul>';
	
	return $about_plugin;
}
endif;

/*
	About WordPress
*/
if (!function_exists('usp_about_wp')) : 
function usp_about_wp() {
	global $wp_version, $wpdb, $current_user;
	if (current_user_can('manage_options')) {
		$default = __('Undefined', 'usp');
		$current_theme = wp_get_theme();
		$current_time = current_time('mysql');
		$active_plugins = count(get_option('active_plugins'));
		$language_locale = get_locale();
		
		$multisite = usp_is_multisite() ? __('MultiSite Installation', 'usp') : __('Standard Installation', 'usp');
		$home_url = get_home_url();
		$site_url = get_site_url();
		$content_url = content_url();
		$language = get_bloginfo('language');
		$language_dir = defined('WP_LANG_DIR') ? WP_LANG_DIR : $default;
		$temp_dir = !defined('WP_TEMP_DIR') ? $default : WP_TEMP_DIR;
		$default_theme = defined('WP_DEFAULT_THEME') ? WP_DEFAULT_THEME : $default;
		$memory_limit = defined('WP_MEMORY_LIMIT') ? WP_MEMORY_LIMIT : $default;
		$autosave_int = AUTOSAVE_INTERVAL == false ? $default : __('Enabled: ', 'usp') . AUTOSAVE_INTERVAL . __(' seconds', 'usp');
		$empty_trash = !defined('EMPTY_TRASH_DAYS') || EMPTY_TRASH_DAYS == 0  ? $default : __('Enabled: ', 'usp') . EMPTY_TRASH_DAYS . __(' days', 'usp');
		
		if (defined('UPLOADS')) $uploads_directory = UPLOADS;
		else $wp_uploads_directory = wp_upload_dir(); $uploads_directory = $wp_uploads_directory['baseurl'];
		
		$post_revisions = __('-- n/a --', 'usp');
		if ((WP_POST_REVISIONS === false) || (WP_POST_REVISIONS === 0)) $post_revisions = $default;
		elseif ((WP_POST_REVISIONS === true) || (WP_POST_REVISIONS === -1)) $post_revisions = __('Enabled: no limit', 'usp');
		else $post_revisions = WP_POST_REVISIONS;
		
		$filesystem_method = get_filesystem_method(array());
		$filesystem_message = $filesystem_method !== 'direct' ? __(' (FTP/SSH access only)', 'usp') : __(' (Direct access allowed)', 'usp');
	
		$about_wp = '<ul>';
		$about_wp .= '<li><strong>' . __('WordPress Version:', 'usp') . ' </strong> ' . $wp_version . '</li>';
		$about_wp .= '<li><strong>' . __('Installation Type:', 'usp') . ' </strong> ' . $multisite . '</li>';
		$about_wp .= '<li><strong>' . __('Update Method:', 'usp') . ' </strong> ' . $filesystem_method . $filesystem_message . '</li>';
		$about_wp .= '<li><strong>' . __('WP Memory Limit:', 'usp') . ' </strong> ' . $memory_limit . '</li>';
		$about_wp .= '<li><strong>' . __('Site Address (URL):', 'usp') . ' </strong> ' . $home_url . '</li>';
		$about_wp .= '<li><strong>' . __('WordPress Address (URL):', 'usp') . ' </strong> ' . $site_url . '</li>';
		$about_wp .= '<li><strong>' . __('Language:', 'usp') . ' </strong> ' . $language . '</li>';
		$about_wp .= '<li><strong>' . __('Locale:', 'usp') . ' </strong> ' . $language_locale . '</li>';
		$about_wp .= '<li><strong>' . __('WP Post Revisions:', 'usp') . ' </strong> ' . $post_revisions . '</li>';
		$about_wp .= '<li><strong>' . __('WP Autosave Interval:', 'usp') . ' </strong> ' . $autosave_int . '</li>';
		$about_wp .= '<li><strong>' . __('WP Empty Trash Interval:', 'usp') . ' </strong> ' . $empty_trash . '</li>';
		$about_wp .= '<li><strong>' . __('Current Theme:', 'usp') . ' </strong> ' . $current_theme . '</li>';
		$about_wp .= '<li><strong>' . __('WordPress Time:', 'usp') . ' </strong> ' . $current_time . '</li>';
		$about_wp .= '<li><strong>' . __('Active Plugins:', 'usp') . ' </strong> ' . $active_plugins . '</li>';
		$about_wp .= '<li><strong>' . __('WP Content Directory:', 'usp') . ' </strong> ' . $content_url . '</li>';
		$about_wp .= '<li><strong>' . __('WP Uploads Directory:', 'usp') . ' </strong> ' . $uploads_directory . '</li>';
		$about_wp .= '<li><strong>' . __('WP Language Directory:', 'usp') . ' </strong> ' . $language_dir . '</li>';
		$about_wp .= '<li><strong>' . __('WP Temp Directory:', 'usp') . ' </strong> ' . $temp_dir . '</li>';
		$about_wp .= '<li><strong>' . __('Default Theme:', 'usp') . ' </strong> ' . $default_theme . '</li>';	
		$about_wp .= '<ul>';
	} else {
		$about_wp = __('Adminstrator-level access required to view WordPress information.', 'usp');
	}
	return $about_wp;
}
endif;

/*
	WordPress Constants
	Thanks to Heiko Rabe's awesome plugin "WP System Health" @ http://www.code-styling.de/english/
*/
if (!function_exists('usp_about_constants')) : 
function usp_about_constants() {
	if (current_user_can('manage_options')) {
		$default = __('Undefined', 'usp');
		$proxy_username = defined('WP_PROXY_USERNAME') ? __('Defined', 'usp') . __(' (not displayed for security reasons)', 'usp') : $default;
		$proxy_password = defined('WP_PROXY_PASSWORD') ? __('Defined', 'usp') . __(' (not displayed for security reasons)', 'usp') : $default;

		$wp_constants = '<ul>';
		$wp_constants .= '<li><strong>' . __('ABSPATH:', 'usp') . ' </strong> ' . usp_return_defined('ABSPATH') . '</li>';
		$wp_constants .= '<li><strong>' . __('SUBDOMAIN_INSTALL:', 'usp') . ' </strong> ' . usp_return_defined('SUBDOMAIN_INSTALL') . '</li>';
		$wp_constants .= '<li><strong>' . __('WPMU_PLUGIN_DIR:', 'usp') . ' </strong> ' . usp_return_defined('WPMU_PLUGIN_DIR') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_ALLOW_REPAIR:', 'usp') . ' </strong> ' . usp_return_defined('WP_ALLOW_REPAIR') . '</li>';
		$wp_constants .= '<li><strong>' . __('COOKIE_DOMAIN:', 'usp') . ' </strong> ' . usp_return_defined('COOKIE_DOMAIN') . '</li>';
		$wp_constants .= '<li><strong>' . __('VHOST:', 'usp') . ' </strong> ' . usp_return_defined('VHOST') . '</li>';
	
		$wp_constants .= '<li><strong>' . __('WP_CACHE:', 'usp') . ' </strong> ' . usp_return_defined('WP_CACHE') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_CRON_LOCK_TIMEOUT:', 'usp') . ' </strong> ' . usp_return_defined('WP_CRON_LOCK_TIMEOUT') . '</li>';
		$wp_constants .= '<li><strong>' . __('DISABLE_WP_CRON:', 'usp') . ' </strong> ' . usp_return_defined('DISABLE_WP_CRON') . '</li>';
		$wp_constants .= '<li><strong>' . __('ALTERNATE_WP_CRON:', 'usp') . ' </strong> ' . usp_return_defined('ALTERNATE_WP_CRON') . '</li>';
		$wp_constants .= '<li><strong>' . __('SAVEQUERIES:', 'usp') . ' </strong> ' . usp_return_defined('SAVEQUERIES') . '</li>';
		$wp_constants .= '<li><strong>' . __('MEDIA_TRASH:', 'usp') . ' </strong> ' . usp_return_defined('MEDIA_TRASH') . '</li>';
		
		$wp_constants .= '<li><strong>' . __('CUSTOM_USER_META_TABLE:', 'usp') . ' </strong> ' . usp_return_defined('CUSTOM_USER_META_TABLE') . '</li>';
		$wp_constants .= '<li><strong>' . __('CUSTOM_USER_TABLE:', 'usp') . ' </strong> ' . usp_return_defined('CUSTOM_USER_TABLE') . '</li>';
		$wp_constants .= '<li><strong>' . __('NOBLOGREDIRECT:', 'usp') . ' </strong> ' . usp_return_defined('NOBLOGREDIRECT') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_ACCESSIBLE_HOSTS:', 'usp') . ' </strong> ' . usp_return_defined('WP_ACCESSIBLE_HOSTS') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_HTTP_BLOCK_EXTERNAL:', 'usp') . ' </strong> ' . usp_return_defined('WP_HTTP_BLOCK_EXTERNAL') . '</li>';
		$wp_constants .= '<li><strong>' . __('FS_METHOD:', 'usp') . ' </strong> ' . usp_return_defined('FS_METHOD') . '</li>';
		$wp_constants .= '<li><strong>' . __('DO_NOT_UPGRADE_GLOBAL_TABLES:', 'usp') . ' </strong> ' . usp_return_defined('DO_NOT_UPGRADE_GLOBAL_TABLES') . '</li>';
		
		$wp_constants .= '<li><strong>' . __('WP_PROXY_HOST:', 'usp') . ' </strong> ' . usp_return_defined('WP_PROXY_HOST') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_PROXY_PORT:', 'usp') . ' </strong> ' . usp_return_defined('WP_PROXY_PORT') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_PROXY_USERNAME:', 'usp') . ' </strong> ' . $proxy_username . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_PROXY_PASSWORD:', 'usp') . ' </strong> ' . $proxy_password . '</li>';
		
		$wp_constants .= '<li><strong>' . __('FTP_BASE:', 'usp') . ' </strong> ' . usp_return_defined('FTP_BASE') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_CONTENT_DIR:', 'usp') . ' </strong> ' . usp_return_defined('FTP_CONTENT_DIR') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_PLUGIN_DIR:', 'usp') . ' </strong> ' . usp_return_defined('FTP_PLUGIN_DIR') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_PUBKEY:', 'usp') . ' </strong> ' . usp_return_defined('FTP_PUBKEY') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_PRIKEY:', 'usp') . ' </strong> ' . usp_return_defined('FTP_PRIKEY') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_USER:', 'usp') . ' </strong> ' . usp_return_defined('FTP_USER') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_PASS:', 'usp') . ' </strong> ' . usp_return_defined('FTP_PASS') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_HOST:', 'usp') . ' </strong> ' . usp_return_defined('FTP_HOST') . '</li>';
		$wp_constants .= '<li><strong>' . __('FTP_SSL:', 'usp') . ' </strong> ' . usp_return_defined('FTP_SSL') . '</li>';
		
		$wp_constants .= '<li><strong>' . __('WP_DEBUG:', 'usp') . ' </strong> ' . usp_return_defined('WP_DEBUG') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_DEBUG_LOG:', 'usp') . ' </strong> ' . usp_return_defined('WP_DEBUG_LOG') . '</li>';
		$wp_constants .= '<li><strong>' . __('WP_DEBUG_DISPLAY:', 'usp') . ' </strong> ' . usp_return_defined('WP_DEBUG_DISPLAY') . '</li>';
		$wp_constants .= '<li><strong>' . __('SCRIPT_DEBUG:', 'usp') . ' </strong> ' . usp_return_defined('SCRIPT_DEBUG') . '</li>';
		
		$wp_constants .= '<li><strong>' . __('ENFORCE_GZIP:', 'usp') . ' </strong> ' . usp_return_defined('ENFORCE_GZIP') . '</li>';
		$wp_constants .= '<li><strong>' . __('COMPRESS_CSS:', 'usp') . ' </strong> ' . usp_return_defined('COMPRESS_CSS') . '</li>';
		$wp_constants .= '<li><strong>' . __('COMPRESS_SCRIPTS:', 'usp') . ' </strong> ' . usp_return_defined('COMPRESS_SCRIPTS') . '</li>';
		$wp_constants .= '<li><strong>' . __('CONCATENATE_SCRIPTS:', 'usp') . ' </strong> ' . usp_return_defined('CONCATENATE_SCRIPTS') . '</li>';
		
		$wp_constants .= '<li><strong>' . __('DISALLOW_FILE_MODS:', 'usp') . ' </strong> ' . usp_return_defined('DISALLOW_FILE_MODS') . '</li>';
		$wp_constants .= '<li><strong>' . __('DISALLOW_FILE_EDIT:', 'usp') . ' </strong> ' . usp_return_defined('DISALLOW_FILE_EDIT') . '</li>';
		$wp_constants .= '<li><strong>' . __('DISALLOW_UNFILTERED_HTML:', 'usp') . ' </strong> ' . usp_return_defined('DISALLOW_UNFILTERED_HTML') . '</li>';
		$wp_constants .= '<li><strong>' . __('ALLOW_UNFILTERED_UPLOADS:', 'usp') . ' </strong> ' . usp_return_defined('ALLOW_UNFILTERED_UPLOADS') . '</li>';
		$wp_constants .= '<li><strong>' . __('FORCE_SSL_LOGIN:', 'usp') . ' </strong> ' . usp_return_defined('FORCE_SSL_LOGIN') . '</li>';
		$wp_constants .= '<li><strong>' . __('FORCE_SSL_ADMIN:', 'usp') . ' </strong> ' . usp_return_defined('FORCE_SSL_ADMIN') . '</li>';
		$wp_constants .= '</ul>';
	} else {
		$wp_constants = __('Adminstrator-level access required to view WordPress information.', 'usp');
	}
	return $wp_constants;
}
endif;
	
/*
	About Server
	Thanks to Heiko Rabe's awesome plugin "WP System Health" @ http://www.code-styling.de/english/
*/
if (!function_exists('usp_about_server')) : 
function usp_about_server() {
	if (current_user_can('manage_options')) {
		global $wpdb;
		$default = __('Undefined', 'usp');
		$max_execution_time = ini_get('max_execution_time'); 
		if ($max_execution_time > 1000) $max_execution_time /= 1000;
		
		$wp_server = '<ul>';
		$wp_server .= '<li><strong>' . __('Server Operating System:', 'usp') . ' </strong> ' . php_uname() . '</li>';
		$wp_server .= '<li><strong>' . __('Server Software:', 'usp') . ' </strong>' . isset($_SERVER['SERVER_SOFTWARE']) && !empty($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : $default . '</li>';
		$wp_server .= '<li><strong>' . __('Server Signature:', 'usp') . ' </strong>' . isset($_SERVER['SERVER_SIGNATURE']) && !empty($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : $default . '</li>';
		$wp_server .= '<li><strong>' . __('Server Name:', 'usp') . ' </strong>' . isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $default . '</li>';
		$wp_server .= '<li><strong>' . __('Server Address:', 'usp') . ' </strong>' . isset($_SERVER['SERVER_ADDR']) && !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $default . '</li>';
		$wp_server .= '<li><strong>' . __('Server Port:', 'usp') . ' </strong>' . isset($_SERVER['SERVER_PORT']) && !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : $default . '</li>';
		$wp_server .= '<li><strong>' . __('Server Gateway:', 'usp') . ' </strong>' . isset($_SERVER['GATEWAY_INTERFACE']) && !empty($_SERVER['GATEWAY_INTERFACE']) ? $_SERVER['GATEWAY_INTERFACE'] : $default . '</li>';
		$wp_server .= '<li><strong>' . __('PHP Version:', 'usp') . ' </strong>' . PHP_VERSION . '</li>';
		$wp_server .= '<li><strong>' . __('Zend Version:', 'usp') . ' </strong> ' . zend_version() . '</li>';
		$wp_server .= '<li><strong>' . __('Platform:', 'usp') . ' </strong> ' . (PHP_INT_SIZE * 8) . '-bit' . '</li>';
		$wp_server .= '<li><strong>' . __('Loaded Extensions:', 'usp') . ' </strong> ' . implode(', ', get_loaded_extensions()) . '</li>';
		$wp_server .= '<li><strong>' . __('MySQL Server:', 'usp') . ' </strong> ' . $wpdb->get_var("SELECT VERSION() AS version") . '</li>';
		$wp_server .= '<li><strong>' . __('Memory Limit:', 'usp') . ' </strong> ' . size_format(usp_return_bytes(@ini_get('memory_limit'))) . '</li>';
		$wp_server .= '<li><strong>' . __('Server Time:', 'usp') . ' </strong> ' . date('Y-m-d H:i:s') . '</li>';
		$wp_server .= '<li><strong>' . __('Include Path:', 'usp') . ' </strong> ' . ini_get('include_path') . '</li>';
		$wp_server .= '<li><strong>' . __('Display Errors:', 'usp') . ' </strong> ' . ini_get('display_errors') . '</li>';
		$wp_server .= '<li><strong>' . __('Register Globals:', 'usp') . ' </strong> ' . ini_get('register_globals') . '</li>';
		$wp_server .= '<li><strong>' . __('Max Post Size:', 'usp') . ' </strong> ' . ini_get('post_max_size') . ' (' . usp_return_bytes(ini_get('post_max_size')) .' bytes)</li>';
		$wp_server .= '<li><strong>' . __('Max Input Time:', 'usp') . ' </strong> ' . ini_get('max_input_time') . __(' seconds', 'usp') . '</li>';
		$wp_server .= '<li><strong>' . __('Max Execution Time:', 'usp') . ' </strong> ' . $max_execution_time . __(' seconds', 'usp') . '</li>';
		$wp_server .= '<li><strong>' . __('File Uploads:', 'usp') . ' </strong> ' . usp_return_bool(ini_get('file_uploads')) . '</li>';
		$wp_server .= '<li><strong>' . __('Temp Uploads Directory:', 'usp') . ' </strong> ' . ini_get('upload_tmp_dir') . '</li>';
		$wp_server .= '<li><strong>' . __('Max Upload File Size:', 'usp') . ' </strong> ' . size_format(usp_return_bytes(ini_get('upload_max_filesize'))) . '</li>';
		$wp_server .= '<li><strong>' . __('Multibyte Function Overload:', 'usp') . ' </strong> ' . ini_get('mbstring.func_overload') . '</li>';
		$wp_server .= '<li><strong>' . __('Short Open Tag:', 'usp') . ' </strong> ' . usp_return_bool(ini_get('short_open_tag')) . '</li>';
		$wp_server .= '<li><strong>' . __('ASP Tags:', 'usp') . ' </strong> ' . usp_return_bool(ini_get('asp_tags')) . '</li>';
		$wp_server .= '<li><strong>' . __('Zend Compatibility:', 'usp') . ' </strong> ' . usp_return_bool(ini_get('zend.ze1_compatibility_mode')) . '</li>';
		$wp_server .= '<li><strong>' . __('Remote Open Files:', 'usp') . ' </strong> ' . usp_return_bool(ini_get('allow_url_fopen')) . '</li>';
		$wp_server .= '<li><strong>' . __('Remote Include Files:', 'usp') . ' </strong> ' . usp_return_bool(ini_get('allow_url_include')) . '</li>';
		$wp_server .= '<li><strong>' . __('PHP Safe Mode:', 'usp') . ' </strong> ' . usp_return_bool(ini_get('safe_mode')) . '</li>';
		$wp_server .= '<li><strong>' . __('Open Basedir:', 'usp') . ' </strong> ' . ini_get('open_basedir') . '</li>';
		$wp_server .= '<li><strong>' . __('Disabled Functions:', 'usp') . ' </strong> ' . ini_get('disable_functions') . '</li>';
		$wp_server .= '<li><strong>' . __('Disabled Classes:', 'usp') . ' </strong> ' . ini_get('disable_classes') . '</li>';
		$wp_server .= '</ul>';
	} else {
		$wp_server = __('Adminstrator-level access required to view Server information.', 'usp');
	}
	return $wp_server;
}
endif;

/*
	About User
*/
if (!function_exists('usp_about_user')) : 
function usp_about_user() {
	if (current_user_can('manage_options')) {
		global $current_user;
		get_currentuserinfo();
		$default = __('Undefined', 'usp');
		$user_name = $current_user->user_login;
		$user_display = $current_user->display_name;
		$user_email = $current_user->user_email;
		$user_ip = usp_get_ip();
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $default;
		$user_ident = isset($_SERVER['REMOTE_IDENT']) ? $_SERVER['REMOTE_IDENT'] : $default;
		$user_port = isset($_SERVER["REMOTE_PORT"]) ? $_SERVER["REMOTE_PORT"] : $default;
		$user_prot = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : $default;
		$user_http = isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : $default;
		
		$wp_user = '<ul>';
		$wp_user .= '<li><strong>' . __('Login/Username:', 'usp') . ' </strong> ' . $user_name . '</li>';
		$wp_user .= '<li><strong>' . __('Display Name:', 'usp') . ' </strong> ' . $user_display . '</li>';
		$wp_user .= '<li><strong>' . __('Email Address:', 'usp') . ' </strong> ' . $user_email . '</li>';
		$wp_user .= '<li><strong>' . __('IP Address:', 'usp') . ' </strong> ' . $user_ip . '</li>';
		$wp_user .= '<li><strong>' . __('User Agent:', 'usp') . ' </strong> ' . $user_agent . '</li>';
		$wp_user .= '<li><strong>' . __('Remote Identity:', 'usp') . ' </strong> ' . $user_ident . '</li>';
		$wp_user .= '<li><strong>' . __('Remote Port:', 'usp') . ' </strong> ' . $user_port . '</li>';
		$wp_user .= '<li><strong>' . __('Server Protocol:', 'usp') . ' </strong> ' . $user_prot . '</li>';
		$wp_user .= '<li><strong>' . __('HTTP Connection:', 'usp') . ' </strong> ' . $user_http . '</li>';
		$wp_user .= '</ul>';
	} else {
		$wp_user = __('Adminstrator-level access required to view Server information.', 'usp');
	}
	return $wp_user;
}
endif;


