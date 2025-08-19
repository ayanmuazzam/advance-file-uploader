<label
	style="display: block;"><b><?php echo esc_html($extendons_file_upload_label ?? '' ); ?></b></label>
<img src="<?php echo filter_var(EXTENDONS_UF_URL); ?>front/images/loader.gif" style="display:none;">
<button style="<?php echo filter_var($btnbackgroundcolor . $btntextcolor); ?>" type="button"
	data-cart-key="<?php echo filter_var($extendons_upload_file_cart_item['key']); ?>"
	data-p-id="<?php echo filter_var($extendons_upload_file_cart_item['product_id']); ?>"
	data-index-key="<?php echo filter_var($key); ?>" data-rule-id="<?php echo filter_var($keys); ?>"
	data-id="<?php echo filter_var($value['rule_id']); ?>"
	data-allow-upload_file="<?php echo filter_var($extendons_file_upload_max_upload_file); ?>" data-page-type=''
	class="Click-here extendons_uploadfiles_ccta_btn"><?php echo esc_html($extendons_file_upload_btn_text . ' ' . $upload_count_file . '/' . $total_allow_upload ); ?>
</button>