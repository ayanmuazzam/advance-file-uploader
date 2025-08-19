<?php
// Include Google Drive Config class
if (!class_exists('WC_Google_Drive_Config')) {
	require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/google-drive-handler/class-wc-google-drive-config.php';
}

$extendons_upload_additional_settings = get_option('extendons_upload_additional_settings', array());
$extendons_upload_folder_path = isset($extendons_upload_additional_settings['extendons_upload_folder_path']) 
	? filter_var($extendons_upload_additional_settings['extendons_upload_folder_path']) 
	: '';
$extendons_enable_google_drive_uploads = isset($extendons_upload_additional_settings['extendons_enable_google_drive_uploads']) 
	? filter_var($extendons_upload_additional_settings['extendons_enable_google_drive_uploads']) 
	: 0;
$extendons_google_drive_json_file = isset($extendons_upload_additional_settings['extendons_google_drive_json_file']) 
	? filter_var($extendons_upload_additional_settings['extendons_google_drive_json_file']) 
	: '';
$extendons_google_drive_folder_name = isset($extendons_upload_additional_settings['extendons_google_drive_folder_name']) 
	? filter_var($extendons_upload_additional_settings['extendons_google_drive_folder_name']) 
	: '';

// Check OAuth success/error messages
if (isset($_GET['oauth_success']) && '1' === $_GET['oauth_success']) {
	echo '<div class="notice notice-success is-dismissible"><p>';
	echo '<strong>' . esc_html__('🎉 Google Drive OAuth authorization completed successfully!', 'extendons_Upload_Files') . '</strong><br>';
	echo esc_html__('Your plugin can now upload files to Google Drive. The configuration is ready to use.', 'extendons_Upload_Files');
	echo '</p></div>';
}

if (isset($_GET['oauth_error']) && !empty($_GET['oauth_error'])) {
	echo '<div class="notice notice-error is-dismissible"><p>';
	echo '<strong>' . esc_html__('❌ OAuth authorization failed:', 'extendons_Upload_Files') . '</strong><br>';
	if (isset($_GET['oauth_error']['message'])) {
		echo esc_html(urldecode(sanitize_text_field(wp_unslash($_GET['oauth_error']['message']))));
	}
	echo '<small>' . esc_html__('Please try the authorization process again or check your OAuth credentials.', 'extendons_Upload_Files') . '</small>';
	echo '</p></div>';
}
?>
<?php
// Display a native WordPress admin notice
echo '<div class="notice notice-info inline"><p>';
echo esc_html__('Note: Use the [extendons_upload_button] shortcode to display the file upload form on any page or post.', 'extendons_Upload_Files');
echo '</p></div>';
?>
<table class="form-table">
		<div id="message_additional_settings" class="updated inline" style="display: none;"><p><strong>
		<?php echo esc_html__('Your settings have been saved.', 'extendons_Upload_Files'); ?></strong></p></div>
	<tbody>
		<tr valign="top" class="single_select_page_enable">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Upload Folder Path', 'extendons_Upload_Files'); ?>
					<span class="tip"
						style="float: right;"><?php echo filter_var(wc_help_tip('Enter the path starting from the wp-content/uploads directory where the uploaded files will be stored. For example, if you want to store files in a folder named "custom_uploads", you would enter "wp-content/uploads/custom_uploads". Make sure the folder exists and has the correct permissions for file uploads.')); ?></span>
				</label>
			</th>
			<td class="forminp">
				<input type="text" name="extendons_upload_folder_path" id="extendons_upload_folder_path"
					value="<?php echo esc_attr($extendons_upload_folder_path); ?>"
					placeholder="<?php echo esc_attr('wp-content/uploads'); ?>">
			</td>
		</tr>
		<tr valign="top" class="single_select_page_enable">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Enable Google Drive Uploads ', 'extendons_Upload_Files'); ?>
					<span class="tip"
						style="float: right;"><?php echo filter_var(wc_help_tip('Enable this option to allow users to upload files directly to Google Drive')); ?></span>
				</label>
			</th>
			<td class="forminp">
				<input type="checkbox" name="extendons_enable_google_drive_uploads" id="extendons_enable_google_drive_uploads" class ="extendons_vt_checkboxes"
					value="1" <?php checked($extendons_enable_google_drive_uploads, 'true'); ?>>
				<span for="extendons_enable_google_drive_uploads"><?php echo esc_html__('Enable Google Drive Uploads', 'extendons_Upload_Files'); ?></span>
			</td>
		</tr>
		<tr valign="top" class="single_select_page_enable">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Google Drive OAuth Credentials JSON', 'extendons_Upload_Files'); ?>
					<span class="tip"
						style="float: right;"><?php echo filter_var(wc_help_tip('Upload the OAuth credentials JSON file downloaded from Google Cloud Console. This file contains your client ID, client secret, and other OAuth configuration.')); ?></span>
				</label>
			</th>
			<td class="forminp">
				<input type="file" name="extendons_google_drive_json_file" id="extendons_google_drive_json_file"
					accept=".json" class="extendons_vt_file_input">
				<?php if (!empty($extendons_google_drive_json_file)) : ?>
					<p><?php echo esc_html__('Current OAuth JSON file:', 'extendons_Upload_Files'); ?>
						<strong><?php echo esc_html(basename($extendons_google_drive_json_file)); ?></strong>
					</p>
				<?php else : ?>
					<p><?php echo esc_html__('No OAuth credentials uploaded yet.', 'extendons_Upload_Files'); ?></p>
				<?php endif; ?>
				
				<?php
				// Check OAuth configuration status
				$gdrive_config = null;
				$gdrive_status = array( 'is_credentials_uploaded' => false, 'is_configured' => false, 'needs_authorization' => false );
				
				try {
					$gdrive_config = WC_Google_Drive_Config::get_instance();
					$gdrive_status = $gdrive_config->get_config_status();
				} catch (Exception $e) {
					// Google Drive not available, show message
					echo '<div class="notice notice-warning inline"><p>';
					echo esc_html__('Google Drive API not available. Please check server configuration.', 'extendons_Upload_Files');
					echo '</p></div>';
				}
				?>
				
				<?php if ($gdrive_config && $gdrive_status['is_credentials_uploaded'] && !$gdrive_status['is_configured']) : ?>
					<div class="notice notice-warning inline">
						<p>
							<strong><?php echo esc_html__('OAuth credentials uploaded successfully!', 'extendons_Upload_Files'); ?></strong>
						</p>
						<p>
							<?php echo esc_html__('Next step: Authorize the application to access your Google Drive.', 'extendons_Upload_Files'); ?>
						</p>
						<p>
							<?php 
							try {
								$auth_url = $gdrive_config->get_auth_url();
								echo '<a href="' . esc_url($auth_url) . '" class="button button-primary" target="_blank">';
								echo esc_html__('🔑 Authorize Google Drive Access', 'extendons_Upload_Files');
								echo '</a>';
							} catch (Exception $e) {
								echo '<span class="button button-primary button-disabled">';
								echo esc_html__('❌ Authorization unavailable (API error)', 'extendons_Upload_Files');
								echo '</span>';
								echo '<p><small style="color: #d63638;">' . esc_html($e->getMessage()) . '</small></p>';
							}
							?>
						</p>
						<p>
							<small>
								<?php echo esc_html__('📝 You will be redirected to Google to grant permissions, then automatically returned here.', 'extendons_Upload_Files'); ?>
							</small>
						</p>
					</div>
				<?php elseif ($gdrive_config && $gdrive_status['is_configured']) : ?>
					<div class="notice notice-success inline">
						<p>
							<strong><?php echo esc_html__('✓ Google Drive is configured and ready to use!', 'extendons_Upload_Files'); ?></strong>
						</p>
						<p>
							<?php echo esc_html__('Connected to project:', 'extendons_Upload_Files'); ?> 
							<strong><?php echo esc_html($gdrive_status['project_id']); ?></strong>
						</p>
						<p>
							<?php echo esc_html__('Upload folder:', 'extendons_Upload_Files'); ?> 
							<strong><?php echo esc_html($gdrive_status['folder_name']); ?></strong>
						</p>
						<p>
							<small>
								<?php echo esc_html__('Configured on:', 'extendons_Upload_Files'); ?> 
								<?php echo esc_html($gdrive_status['configured_at'] ?? 'Unknown'); ?>
							</small>
						</p>
						<p>
							<a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=extendons_upload_files&action=test_gdrive')); ?>" 
							   class="button button-secondary">
								<?php echo esc_html__('Test Connection', 'extendons_Upload_Files'); ?>
							</a>
							<a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=extendons_upload_files&action=reset_gdrive')); ?>" 
							   class="button button-secondary" 
							   onclick="return confirm('<?php echo esc_js(__('Are you sure you want to reset Google Drive configuration?', 'extendons_Upload_Files')); ?>')">
								<?php echo esc_html__('Reset Configuration', 'extendons_Upload_Files'); ?>
							</a>
						</p>
					</div>
				<?php endif; ?>
			</td>
		</tr>
		<tr valign="top" class="single_select_page_enable">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Google Drive Folder Name', 'extendons_Upload_Files'); ?>
					<span class="tip"
						style="float: right;"><?php echo filter_var(wc_help_tip('Enter the name of the Google Drive folder where files will be uploaded. If the folder does not exist, it will be created automatically during authorization.')); ?></span>
				</label>
			</th>
			<td class="forminp">
				<input type="text" name="extendons_google_drive_folder_name" id="extendons_google_drive_folder_name"
					value="<?php echo esc_attr($extendons_google_drive_folder_name); ?>"
					placeholder="<?php echo esc_attr('WooCommerce File Uploads'); ?>">
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" name="extendons_save_settings" onclick="extendons_file_upload_additional_settings()"
					value="<?php echo esc_html__('Save Settings', 'extendons_Upload_Files'); ?>"
					class="btn btn-primary">
			</td>
		</tr>
	</tbody>
</table>