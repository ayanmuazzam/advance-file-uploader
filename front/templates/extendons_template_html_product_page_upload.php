<label>
	<b>
		<?php
		echo esc_html($extendons_file_upload_label);
		if ('false' !== $extendons_uploadfiles_file_required) {
			echo ' ';
			echo '<span style="color:red">*</span>';
		}
		?>
	</b>
</label>
<img src="<?php echo filter_var(EXTENDONS_UF_URL); ?>front/images/loader.gif" style="display:none;">
<button style="<?php echo filter_var( $btnbackgroundcolor . $btntextcolor); ?>" type="button"
	p-id="<?php echo filter_var($product_id); ?>" index-key="<?php echo filter_var($key); ?>"
	data-id="<?php echo filter_var($value['extendons_post_id']); ?>"
	data-allow-upload_file="<?php echo filter_var($extendons_file_max_upload_file); ?>" page_type="productpage"
	class="Click-here extendons_uploadfiles_btn"><?php echo esc_html($extendons_file_upload_btn_text . ' ' . $upload_count_file . '/' . $total_allow_upload ); ?>
</button>