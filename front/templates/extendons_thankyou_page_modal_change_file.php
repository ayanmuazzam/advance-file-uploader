<div id="extendons-file-change-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
	<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 5px; min-width: 300px;">
		<h3><?php echo esc_html__( 'Change File', 'extendons_Upload_Files' ); ?></h3>
		<form id="extendons-change-file-form" enctype="multipart/form-data">
			<input type="file" id="extendons-new-file" name="new_file" style="margin-bottom: 15px;" />
			<input type="hidden" id="extendons-order-id" name="order_id" />
			<input type="hidden" id="extendons-rule-id" name="rule_id" />
			<input type="hidden" id="extendons-key" name="key" />
			<input type="hidden" id="extendons-file-index" name="file_index" />
			<input type="hidden" id="extendons-file-extensions" name="file_extensions" />
			<input type="hidden" id="extendons-file-id" name="file_id" />
			<div style="text-align: right;">
				<button type="button" id="extendons-cancel-change"><?php echo esc_html__( 'Cancel', 'extendons_Upload_Files' ); ?></button>
				<button type="submit"><?php echo esc_html__( 'Upload', 'extendons_Upload_Files' ); ?></button>
			</div>
		</form>
	</div>
</div>