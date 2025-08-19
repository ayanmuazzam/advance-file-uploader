<?php
$extendons_upload_additional_settings = get_option('extendons_upload_additional_settings', array());
$extendons_upload_folder_path = isset($extendons_upload_additional_settings['extendons_upload_folder_path']) 
	? filter_var($extendons_upload_additional_settings['extendons_upload_folder_path']) 
	: '';
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
		<tr>
			<td>
				<input type="button" name="extendons_save_settings" onclick="extendons_file_upload_additional_settings()"
					value="<?php echo esc_html__('Save Settings', 'extendons_Upload_Files'); ?>"
					class="btn btn-primary">
			</td>
		</tr>
	</tbody>
</table>