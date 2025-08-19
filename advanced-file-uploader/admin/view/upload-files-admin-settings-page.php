<?php
// Define a variable to track if we are editing a rule
$extendons_upload_files_edit_post_id = isset($_REQUEST['extendons_edit_rule_id']) 
	? filter_var(wp_unslash($_REQUEST['extendons_edit_rule_id'])) 
	: null;
?>

<div id="message_settings" class="updated inline" style="display: none;">
	<p><strong>
		<?php echo esc_html__('Your settings have been saved.', 'extendons_Upload_Files'); ?>
	</strong></p>
</div>

<ul class="subsubsub">
	<li>
		<a href="#extendons_tab_default_2" class="<?php echo !$extendons_upload_files_edit_post_id ? 'current' : ''; ?>" 
		   data-toggle="tab"><?php echo esc_html__('Manage Rule', 'extendons_Upload_Files'); ?></a> |
	</li>
	<li>
		<a href="#extendons_tab_default_1" class="<?php echo $extendons_upload_files_edit_post_id ? 'current' : ''; ?>" 
		   data-toggle="tab"><?php echo esc_html__('Add Rule', 'extendons_Upload_Files'); ?></a> |
	</li>
	<li>
		<a href="#extendons_tab_default_3" 
		   data-toggle="tab"><?php echo esc_html__('Recaptcha Settings', 'extendons_Upload_Files'); ?></a> |
	</li>
	<li>
		<a href="#extendons_tab_default_4" 
		   data-toggle="tab"><?php echo esc_html__('Additional Settings', 'extendons_Upload_Files'); ?></a>
	</li>
</ul>
<br class="clear">

<div class="extendons-tabbable-line">
	<div class="tab-content extendonsmargin-tops">
		<div class="tab-pane <?php echo !$extendons_upload_files_edit_post_id ? 'active fade in' : 'fade'; ?>" id="extendons_tab_default_2">
			<div class="col-md-12" id="extendons_upload_files_content">
				<?php require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/view/template/extendons-manage-rules.php'; ?>
			</div>
		</div>
		<div class="tab-pane <?php echo $extendons_upload_files_edit_post_id ? 'active fade in' : 'fade'; ?>" id="extendons_tab_default_1">
			<div class="col-md-12" id="extendons_upload_files_content">
				<?php
				if ($extendons_upload_files_edit_post_id) {
					require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/view/template/extendons_edit_settings.php';
				} else {
					require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/view/template/extendons-General-settings.php';
				}
				?>
			</div>
		</div>
		<div class="tab-pane fade" id="extendons_tab_default_3">
			<div class="col-md-12" id="extendons_upload_files_content">
				<?php require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/view/template/Recaptcha.php'; ?>
			</div>
		</div>
		<div class="tab-pane fade" id="extendons_tab_default_4">
			<div class="col-md-12" id="extendons_upload_files_content">
				<?php require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/view/template/extendons-additional-settings.php'; ?>
			</div>
		</div>
	</div>
</div>