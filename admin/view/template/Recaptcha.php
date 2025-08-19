<?php
$extendons_upload_recapcha_setting = get_option('extendons_upload_recapcha_setting');
$extendons_upload_status = isset($extendons_upload_recapcha_setting['extendons_upload_recapcha']) ? filter_var($extendons_upload_recapcha_setting['extendons_upload_recapcha']) : '';

$extendons_upload_site_key = isset($extendons_upload_recapcha_setting['extendons_upload_recapcha_site_key']) ? filter_var($extendons_upload_recapcha_setting['extendons_upload_recapcha_site_key']) : '';

$extendons_upload_secret_key = isset($extendons_upload_recapcha_setting['extendons_upload_recapcha_secrect_key']) ? filter_var($extendons_upload_recapcha_setting['extendons_upload_recapcha_secrect_key']) : '';

?>
<table class="form-table">
	<div id="message_recaptcha" class="updated inline" style="display: none;"><p><strong>
		<?php echo esc_html__('Your settings have been saved.', 'extendons_Upload_Files'); ?></strong></p></div>
	<tbody>
		<tr valign="top" class="single_select_page_enable">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Enable/Disable Recaptcha', 'extendons_Upload_Files'); ?>
					<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enable / Disable Recaptcha' )); ?></span>
				</label>
				
			</th>
			<td class="forminp">
				<select id="extendons_upload_recapcha">
					<option value="Recaptcha_enable" <?php selected('Recaptcha_enable', $extendons_upload_status); ?>><?php echo esc_html__('Enable', 'extendons_Upload_Files'); ?></option>
					<option value="Recaptcha_disable" <?php selected('Recaptcha_disable', $extendons_upload_status); ?>><?php echo esc_html__('Disable', 'extendons_Upload_Files'); ?></option>
				</select>
			</td>
		</tr>


		<tr valign="top" class="single_select_page_enable extendons_fileuploader_recaptcha_settings">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Recaptcha site Key', 'extendons_Upload_Files'); ?>
					<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Recaptcha Site Key' )); ?></span>
				</label>
				
			</th>
			<td class="forminp">
				<input type="text" name="extendons_upload_recapcha_site_key" id="extendons_upload_recapcha_site_key" value="<?php echo filter_var($extendons_upload_site_key); ?>">
			</td>
		</tr>

		<tr valign="top" class="single_select_page_enable extendons_fileuploader_recaptcha_settings">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Recaptcha Secret Key', 'extendons_Upload_Files'); ?>
					<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Recaptcha Secret Key' )); ?></span>
				</label>
			</th>
			<td class="forminp">
				<input type="text" name="extendons_upload_recapcha_secrect_key" id="extendons_upload_recapcha_secrect_key" value="<?php echo filter_var($extendons_upload_secret_key); ?>">
			</td>
		</tr>

		<tr>
			<td>
				<input type="button" name="extendons_save_settings" onclick="extendons_file_upload_recptcha_settings()" value="<?php echo esc_html__('Save Settings', 'extendons_Upload_Files'); ?>" class="btn btn-primary">
			</td>
		</tr>

</tbody>
</table>
