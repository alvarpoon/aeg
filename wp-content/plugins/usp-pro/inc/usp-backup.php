<?php // USP Pro - Backup Settings

if (!function_exists('usp_display_options_page')) {
	function usp_display_options_page() { ?>
	
	<div class="usp-s6 usp-toggle default-hidden usp-backup-settings">
		<p><?php _e('Here you can download a backup of your settings, and/or import previous settings as needed. Note that your USP Pro License Key is not saved in the backup file.', 'usp'); ?></p>
		<h4><?php _e('Backup/Export', 'usp'); ?></h4>
		<form method="post">
			<p><?php _e('Download your current settings:', 'usp'); ?></p>
			<p><input type="submit" name="submit" id="submit" class="button" value="<?php _e('Export Settings', 'usp'); ?>"></p>
			<?php wp_nonce_field('usp_options_export', 'usp_options_export'); ?>
			<input type="hidden" name="usp_options" value="export" />
		</form>
		<h4><?php _e('Restore/Import', 'usp'); ?></h4>
		<form method="post" enctype="multipart/form-data">
			<p><?php _e('Step 1: Select the backup file that you would like to restore:', 'usp'); ?></p>
			<p><input type="file" name="file_select" /></p>
			<p><?php _e('Step 2: Click to restore your settings:', 'usp'); ?></p>
			<p><input type="submit" name="submit" id="submit" class="button" value="<?php _e('Import Settings', 'usp'); ?>"></p>
			<?php wp_nonce_field('usp_options_import', 'usp_options_import'); ?>
			<input type="hidden" name="usp_options" value="import" />
		</form>
	</div>
<?php }
}

if (!function_exists('usp_export_options')) {
	function usp_export_options() {
		global $usp_admin, $usp_advanced, $usp_general, $usp_style, $usp_uploads, $usp_more, $usp_widget;
		
		if (empty($_POST['usp_options']) || 'export' != $_POST['usp_options']) return;
		if (!wp_verify_nonce($_POST['usp_options_export'], 'usp_options_export')) return;
		if (!current_user_can('manage_options')) return;
		
		ignore_user_abort(true);
		nocache_headers();
		header('Content-Type: application/json; charset=utf-8');
		header('Content-Disposition: attachment; filename=usp-options-'. date('Ymd-His') .'.json');
		header("Expires: 0");
		
		$usp_options = array(
			'usp_admin'    => $usp_admin, 
			'usp_advanced' => $usp_advanced, 
			'usp_general'  => $usp_general, 
			'usp_style'    => $usp_style, 
			'usp_uploads'  => $usp_uploads, 
			'usp_more'     => $usp_more, 
			'usp_widget'   => $usp_widget,
		);
		echo json_encode($usp_options);
		exit;
	}
	add_action('admin_init', 'usp_export_options');
}

if (!function_exists('usp_import_options')) {
	function usp_import_options() {
		
		if (empty($_POST['usp_options']) || 'import' != $_POST['usp_options']) return;
		if (!wp_verify_nonce($_POST['usp_options_import'], 'usp_options_import')) return;
		if (!current_user_can('manage_options')) return;
		
		$file_name = explode('.', $_FILES['file_select']['name']);
		$extension = end($file_name);
	
		if ($extension != 'json') wp_die(__('Invalid file type (must be JSON format)', 'usp'));
		$file = $_FILES['file_select']['tmp_name'];
		
		if (empty($file)) wp_die(__('File is empty', 'usp'));
		
		$usp_options = (array) json_decode(file_get_contents($file), true);
		
		update_option('usp_admin',    $usp_options['usp_admin']);
		update_option('usp_advanced', $usp_options['usp_advanced']);
		update_option('usp_general',  $usp_options['usp_general']);
		update_option('usp_style',    $usp_options['usp_style']);
		update_option('usp_uploads',  $usp_options['usp_uploads']);
		update_option('usp_more',     $usp_options['usp_more']);
		update_option('usp_widget',   $usp_options['usp_widget']);
		
		wp_safe_redirect(admin_url('options-general.php?page=usp_options&tab=usp_tools&settings_restored=true'));
		exit;
	}
	add_action('admin_init', 'usp_import_options');
}


