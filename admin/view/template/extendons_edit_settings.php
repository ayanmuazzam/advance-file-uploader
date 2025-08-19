<?php 
$extendons_upload_files_edit_post_id;
$extendons_upload_files_get_status =  get_post_meta($extendons_upload_files_edit_post_id, 'extendons_enable_disable_settings', true);
$extendons_up_rule_name =  get_post_meta($extendons_upload_files_edit_post_id, 'extendons_up_rule_name', true);
$extendons_rule_priority =  get_post_meta($extendons_upload_files_edit_post_id, 'extendons_rule_priority', true);
$extendons_display_on_values =  get_post_meta($extendons_upload_files_edit_post_id, 'extendons_display_on_values', true);
$extendons_selected_product_category = get_post_meta($extendons_upload_files_edit_post_id, 'extendons_selected_product_category', true); 
$extendons_selected_product_item = get_post_meta($extendons_upload_files_edit_post_id, 'extendons_selected_items', true);
$extendons_selected_user_roles = get_post_meta($extendons_upload_files_edit_post_id, 'extendons_selected_user_role', true); 
$extendons_upload_files_multival_arr = get_post_meta($extendons_upload_files_edit_post_id , 'extendons_multiple_files_limit', true);
$extendons_selected_user_roles = is_array( $extendons_selected_user_roles ) ? $extendons_selected_user_roles : array();

?>
<table class="form-table">
	<tbody>
		<tr valign="top" class="single_select_page_enable">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Enable/Disable', 'extendons_Upload_Files'); ?>
					 
					<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip( 'Enable / Disable Rule' ) ); ?></span>
			
				</label>
				
			</th>
			<td class="forminp">
				<select id="extendons_upload_files_enable_disable_setting">
					<option value="" <?php selected('', $extendons_upload_files_get_status, true); ?>><?php echo esc_html__('Choose visibility', 'extendons_Upload_Files'); ?></option>
					<option value="extendons_upload_files_enable"  <?php selected('extendons_upload_files_enable', $extendons_upload_files_get_status, true); ?>><?php echo esc_html__('	Enable', 'extendons_Upload_Files'); ?></option>
					<option value="extendons_upload_files_disable" <?php selected('extendons_upload_files_disable', $extendons_upload_files_get_status, true); ?>><?php echo esc_html__('Disable', 'extendons_Upload_Files'); ?></option>
				</select>
				<a href="" style="float: right;">Back</a>
			</td>
		</tr>
		
		<tr>
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Rule Name', 'extendons_Upload_Files'); ?>
					<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Rule Name')); ?></span>
				</label>
			</th>
			<td>
				<input type="text" name="extendons_rule_name" id="extendons_up_rule_name" value="<?php echo esc_attr($extendons_up_rule_name); ?>">
			</td>
		</tr>

		<!-- PIN: from existing rule -->
		<tr valign="top" class="single_select_page">
			<th scope="row" class="titledesc">
				<label  id="extendons_upload_files_label"><?php echo esc_html__('Display On', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Position To Display Upload File Button')); ?></span></label>
				
			</th>
			<td class="forminp">
				
			 
				<input type="checkbox" <?php isset($extendons_display_on_values[0]['extendons_allow_uf_product_page']) ? checked($extendons_display_on_values[0]['extendons_allow_uf_product_page'], 'extendons_upload_files_product_page') : false; ?>  id="extendons-product-page" class="extendons_upload_files_radio extendons_vt_checkboxes" name="extendons-radio-select-display-on" value="extendons_upload_files_product_page">
				<label for="extendons-product-page" id="extendons_upload_files_radio"><?php echo esc_html__('Product Page', 'extendons_Upload_Files'); ?></label>
				<br/>
				<input type="checkbox" <?php checked($extendons_display_on_values[0]['extendons_allow_uf_after_cart_page_table'] ?? false, 'extendons_upload_file_after_cart_table'); ?>  id="extendons-after-cart-page-table" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons_upload_file_after_cart_table">
				<label for="extendons-after-cart-page-table" id="extendons_upload_files_radio">
					<?php echo esc_html__('Cart Page', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" <?php checked($extendons_display_on_values[0]['extendons_allow_uf_after_checkout_page_after_notes_field'] ?? false , 'extendons_upload_file_after_checkout_page_after_notes_field'); ?>
				id="extendons-after-checkout-page-after-notes-field" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons_upload_file_after_checkout_page_after_notes_field">
				<label for="extendons-after-checkout-page-after-notes-field" id="extendons_upload_files_radio">
					<?php echo esc_html__('Checkout Page > After Notes', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" <?php isset($extendons_display_on_values[0]['extendons_allow_uf_thankyou_page']) ? checked($extendons_display_on_values[0]['extendons_allow_uf_thankyou_page'], 'extendons-upload-file-thankyou-page') : false; ?> id="extendons-thankyou-page" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons-upload-file-thankyou-page">
				<label for="extendons-checkout-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('ThankYou Page', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox"  <?php isset($extendons_display_on_values[0]['extendons_allow_uf_account_page']) ?  checked($extendons_display_on_values[0]['extendons_allow_uf_account_page'], 'extendons-upload-file-Account-page') : false; ?> id="extendons-Account-page" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons-upload-file-Account-page">
				<label for="extendons-checkout-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('Account Page', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" <?php isset($extendons_display_on_values[0]['extendons_allow_uf_cart_page']) ? checked($extendons_display_on_values[0]['extendons_allow_uf_cart_page'], 'extendons_upload_files_cart_page') : false; ?> id="extendons-cart-page" class="extendons_upload_files_radio extendons_vt_checkboxes" name="extendons-radio-select-display-on" value="extendons_upload_files_cart_page">
				<label for="extendons-cart-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('Cart Page (Alongside Cart Items)', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" <?php isset($extendons_display_on_values[0]['extendons_allow_uf_checkout_page']) ?  checked($extendons_display_on_values[0]['extendons_allow_uf_checkout_page'], 'extendons_upload_files_checkout_page_after_notes') : false; ?> id="extendons-checkout-page-after-notes" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons_upload_files_checkout_page_after_notes">
				<label for="extendons-checkout-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('Checkout Page (Alongside Cart Items)', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
			</td>
		</tr>

		<tr>
			<th>
				<label id="extendons_upload_files_label"><?php echo esc_html__('Main upload rules:', 'extendons_Upload_Files'); ?></label>
			</th>
			<td>
				<div class="row" id="extendons_upload_files_FormSetting">

					<div class="col-xs-4 col-md-12">
						<span id="extendonsmultiplefiles">
							<button type="button" onclick="extendons_uploadfile_multiplefile('extendons_create');" class="add button">+ Add More</button>
							<input type="hidden" name="extendons_counter_btn" value="1">
							<span class="extendons_upload_files_price_single">
								<?php 
								if ( !empty($extendons_upload_files_multival_arr) ) {
									foreach ($extendons_upload_files_multival_arr as $key => $value) {
										$keyitem = intval($key) + intval(1);
										?>
								<div class="panel-group" id="accordion" style="margin-top: 10px;" role="tablist" aria-multiselectable="true">
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="headingOne">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo filter_var($keyitem); ?>" aria-expanded="true" aria-controls="collapse<?php echo filter_var($keyitem); ?>" class="extendons_uf_accordian">
													<?php echo esc_html('Item #' . filter_var($keyitem)); ?>
												</a>
											</h4>
										</div>
										<div id="collapse<?php echo filter_var($keyitem); ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
											<div class="panel-body">
												
													<div class="row" id="extendons_upload_files_FormSettingsupload">
														<div class="col-xs-4 col-md-3">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Label:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Label for Upload File Button')); ?></span>
															</label>
															<input type="Text" name="extendonspriceuploadfilelabel[]" value="<?php echo filter_var($value['extendons_uploadfiles_label']); ?>" id="extendonspriceuploadfilelabel" style="width: 100%">
														</div>
														<div class="col-xs-4 col-md-3">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Allowed Extension:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Add the allowed file formats, writing the extensions divided by comma (e.g., jpg,png,gif).')); ?></span>
															</label>
															<input type="text" name="extendonspriceuploadfileallowedextension[]" value="<?php echo filter_var($value['extendons_uploadfiles_allwoed_extension']); ?>" id="extendonspriceuploadfileallowedextension" style="width: 100%">
														</div>
														<div class="col-xs-4 col-md-2"></span>
															</label>
															<label id="extendons_upload_files_label"><?php echo esc_html__('Price:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Price for file uploaded')); ?></span>
															</label>
															<input type="number"  min="1" oninput="validity.valid||(value='')" name="extendonspriceuploadfileallowedprice[]" value="<?php echo filter_var($value['extendons_uploadfiles_price']); ?>" id="extendonspriceuploadfileallowedprice" style="width: 100%">
														</div>
														<div class="col-xs-4 col-md-2">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Discount Type:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Select Discount Type Fixed or Percentage')); ?></span>
															</label>
															<select id="extendonsDiscounttype" name="extendonsDiscounttype[]" style="width: 100%">
																<option value="" <?php selected('', $value['extendons_uploadfiles_discount_type']); ?>> <?php echo esc_html__('Discount Type', 'extendons_Upload_Files'); ?> </option>
																<option value="extendons_upload_files_fixed" <?php selected('extendons_upload_files_fixed', $value['extendons_uploadfiles_discount_type']); ?>> <?php echo esc_html__('Fixed', 'extendons_Upload_Files'); ?></option>
																<option value="extendons_upload_files_percentage" <?php selected('extendons_upload_files_percentage', $value['extendons_uploadfiles_discount_type']); ?>> <?php echo esc_html__('Percentage', 'extendons_Upload_Files'); ?></option>
															</select>
														</div>
														<div class="col-xs-4 col-md-2">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Discount Price:', 'extendons_Upload_Files'); ?>
															<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Discount Price for file uploaded')); ?></span>
														</label>
														<input type="number" min="1" name="extendonsDiscountvalue[]" value="<?php echo esc_attr($value['extendons_uploadfiles_discount_price']); ?>" id="extendonsDiscountvalue" oninput="validity.valid||(value='');" style="width: 100%;">
													</div>
												</div>
												<div class="row" id="extendons_upload_files_Discount">
													<div class="col-xs-4 col-md-3">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Description:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Description for Upload File Button')); ?></span>
														</label>
														<textarea name="extendons_upload_files_description[]" style="width: 100%" rows="3" cols="50"><?php echo esc_attr($value['extendons_uploadfiles_description']); ?>
														</textarea>
													</div>
													<div class="col-md-3">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Upload File Button Text:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Upload File Button Text')); ?></span>
														</label>
														<input type="text" name="extendonspriceuploadfilebtntext[]" value="<?php echo esc_attr($value['extendons_uploadfiles_file_text_btn']); ?>" id="extendonspriceuploadfilebtntext" style="width: 100%">
													</div>
													
													<div class="col-xs-4 col-md-5">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Maximum Upload Size:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Maximum Upload Size for File Upload' )); ?></span></label><br>
														<select id="extendons_file_size" name="extendons_file_size[]" style="width: 25%;">
														
															<option value="extendons_upload_files_KB" <?php selected('extendons_upload_files_KB', $value['extendons_uploadfiles_file_maximum_upload_size']); ?>><?php echo esc_html__('KB', 'extendons_Upload_Files'); ?></option>
															<option value="extendons_upload_files_MB"  <?php selected('extendons_upload_files_MB', $value['extendons_uploadfiles_file_maximum_upload_size']); ?>><?php echo esc_html__('MB', 'extendons_Upload_Files'); ?></option>
														</select>
														<input type="number" min="0" placeholder="Enter Size" class="form-control extendons-upload-size" name="extendons-maximum-uploadsize[]" value="<?php echo filter_var($value['extendons_uploadfiles_file_maximum_upload_size_value']); ?>" id="extendons-maximum-uploadsize" oninput="validity.valid||(value='');" style="display: initial;width: 70%;">
													</div>
												</div>
												<div class="row">
													<div class="col-xs-4 col-md-3" style="margin-top: 20px;">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Customer Notes:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enable to Allow Customer notes' )); ?></span></label>
														<input type="checkbox" <?php checked('true', $value['extendons_uploadfiles_file_allow_notes_checkbox']); ?>  name="extendons_uf_allow_notes_checkbox[]" class="extendons_vt_checkboxes">
														<br>
														<label id="extendons_upload_files_label"><?php echo esc_html__('Required:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enable to Upload File Required' )); ?></span></label>
														<input type="checkbox"  <?php checked('true', $value['extendons_uploadfiles_file_required']); ?>  name="extendons_uf_required[]" class="extendons_vt_checkboxes">
													</div>

													<div class="col-xs-4 col-md-3" style="margin-top: 20px;">

														<label id="extendons_upload_files_label"><?php echo esc_html__('Customer Notes Label:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Label For Customer Notes' )); ?></span></label>
														<input type="text" name="extendonspriceuploadfilecustomernotelabel[]" value="<?php echo filter_var($value['extendons_uploadfiles_file_allow_notes_label']); ?>" id="extendonspriceuploadfilecustomernotelabel" style="width: 100%">
													</div>
													<div class="col-md-3" style="margin-top: 20px;">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Maximum File Upload:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Maximum Quantity for upload file button' )); ?></span></label>
														<input type="number" min="0" name="extendons_uploadfile_allowed_max_qunatity[]" value="<?php echo filter_var($value['extendons_uploadfiles_file_maximum_upload_files']); ?>" id="extendons_uploadfile_allowed_max_qunatity" style="width: 100%"><span class="description">
														
														
														<?php echo esc_html__('If Maximum File Upload Field Empty then customer can upload only 1 file.', 'extendons_Upload_Files'); ?>												</span>
													</div>
													<div class="col-xs-4 col-md-3" style="margin-top: 20px;">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Background Color:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Background Color for Upload File Button' )); ?></span>
														</label>
														<input type="color" value="<?php echo filter_var($value['extendons_uploadfiles_file_background_color']); ?>" class="jscolor" name="extendons_upload_files_color[]" id="extendons_upload_files_color" value="">
														<br>
														<label id="extendons_upload_files_label"><?php echo esc_html__('Text Color:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Text Color for Upload File Button' )); ?></span></label>
														<input type="color" class="jscolor" name="extendons_upload_files_text_color[]" id="extendons_upload_files_text_color" value="<?php echo filter_var($value['extendons_uploadfiles_file_text_color']); ?>">
													</div>
												
											</div>
										</div>
									</div>
								</div>
										<?php 
									} 
								}
								?>
							</span>
						</div>
					</span>
				</div>
			</div>
		</td>

	</tr>

	
	<tr valign="top" class="single_select_page">
		<th scope="row" class="titledesc">
			<label id="extendons_upload_files_label"><?php echo esc_html__('Product/Category Restriction', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Product/Category where you want to display Upload File Button')); ?></span></label>
		</th>
		<td class="forminp">
			<select class="form-control extendonsproductcategory" id="extendonsproductcategory" name="selectpc[]" onchange="extendons_upload_file_choosen_product_cateory('extendons_edit');">
				<option value="" <?php selected('', $extendons_selected_product_category, true); ?>><?php echo esc_html__('Visible for every product:', 'extendons_Upload_Files'); ?></option>
				<option value="extendons_upload_files_product" <?php selected('extendons_upload_files_product', $extendons_selected_product_category, true); ?>><?php echo esc_html__('Product', 'extendons_Upload_Files'); ?></option>
				<option value="extendons_upload_files_category" <?php selected('extendons_upload_files_category', $extendons_selected_product_category, true); ?>><?php echo esc_html__('Category', 'extendons_Upload_Files'); ?></option>
			</select>
			<span class="extendons_upload_files_description">
				<?php 
				echo esc_html__('Upload field can optionally visible/hidden only if the selected products are in cart/order.', 'extendons_Upload_Files'); 
				?>
			</span>
		</td>
	</tr>

	<tr valign="top" class="single_select_page" id="extendons_edit_Products"
	<?php 
	if ('extendons_upload_files_category' == $extendons_selected_product_category || '' == $extendons_selected_product_category) {
		echo "style='display:none'";
	} 
	?>
	>
		<th scope="row" class="titledesc">
			<label id="extendons_upload_files_label"><?php echo esc_html__('Select Product', 'extendons_Upload_Files'); ?></label>
		</th>
		<td class="forminp">
			<?php 
			global $post;
			$extendons_upload_files_uploadfiles_product = array(
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'post_type' => array( 'product' ),
			);
			$extendons_upload_files_woo_Products = get_posts($extendons_upload_files_uploadfiles_product);
			if (!empty($extendons_upload_files_woo_Products)) { 
				?>
				<select class="extendons_choosen" id="extendons_files-product" multiple="multiple" name="">
					<?php
					foreach ($extendons_upload_files_woo_Products as $products) {
						if ('' != $extendons_selected_product_item) {
							$extendons_selected_product_items = $extendons_selected_product_item;
						} else {
							$extendons_selected_product_items = array();
						}
						?>
						<option value="<?php echo esc_attr($products->ID); ?>"<?php selected(in_array($products->ID, $extendons_selected_product_items), true); ?>><?php echo filter_var($products->post_title); ?></option>
						<?php
					}

					?>
				</select>
			<?php }; ?>
		</td>
	</tr>

	<tr valign="top" class="single_select_page" id="extendons_edit_category"
	<?php 
	if ('extendons_upload_files_product' == $extendons_selected_product_category || '' == $extendons_selected_product_category) { 
		echo "style='display:none'";
	} 
	?>
	>
		<th scope="row" class="titledesc">
			<label id="extendons_upload_files_label"><?php echo esc_html__('Select category', 'extendons_Upload_Files'); ?></label>
		</th>
		<td class="forminp">
			<?php 
			$extendons_upload_files_woo_category = array(
				'taxonomy' => 'product_cat',
			);
			$extendons_upload_files_products_categories = get_terms($extendons_upload_files_woo_category);
			if (!empty($extendons_upload_files_products_categories)) { 
				?>
				<select class="extendons_choosen" id="extendons_files-category" multiple="multiple" name="">
					<?php
					foreach ($extendons_upload_files_products_categories as $category) {
						if ('' != $extendons_selected_product_item) {
							$extendons_selected_product_items = $extendons_selected_product_item;
						} else {
							$extendons_selected_product_items = array();
						}
						?>
						<option value="<?php echo esc_attr($category->term_id); ?>"<?php selected(in_array($category->term_id, $extendons_selected_product_items), true); ?>><?php echo esc_attr($category->name); ?></option>
						<?php
					}

					?>
				</select>

			<?php } ?>
		</td>
	</tr>


	<tr valign="top" class="single_select_page">
		<th scope="row" class="titledesc">
			<label id="extendons_upload_files_label"><?php echo esc_html__('User role', 'extendons_Upload_Files'); ?></label>
		</th>
		<td class="forminp">
			<?php
			global $wp_roles;
			$extendons_upload_files_default_roles = $wp_roles->get_names();
			if (!empty($extendons_upload_files_default_roles) ) {
				?>
				<select class="extendons_choosen" id="extendons_choosen-user-role" multiple="multiple" name="">
					<?php
					foreach ($extendons_upload_files_default_roles as $key => $value) {
						?>
						<option value="<?php echo filter_var(strtolower($value)); ?>" <?php selected(in_array(strtolower($value), $extendons_selected_user_roles), true); ?>>
							<?php echo filter_var($value); ?>
						</option>
						<?php
					}
					?>
				</select>
				<?php
			}
			?>
			<br/>
			<span class="extendons_upload_files_description">
				<?php 
				echo esc_html__('Selecting at least one role will make the upload field to be visible/unvisible to that role..', 'extendons_Upload_Files'); 
				?>
			</span>
		</td>
	</tr>

</tbody>
</table>

<div class="row" id="extendons_upload_files_FormSettings">
	<div class="col-xs-4 col-md-3 0">
		<span id="extendons_settings_loader"><img src="<?php echo esc_url(EXTENDONS_UF_URL) . 'admin/images/spinner.gif'; ?>" class=""></span>
		<input type="hidden" name="extendons_edit_post_id_action" value="<?php echo filter_var($extendons_upload_files_edit_post_id); ?>">
		<input type="button" name="extendons_save_settings" onclick="extendons_upload_file_save_general_settings('edit');" value="<?php echo esc_html__('Save Settings', 'extendons_Upload_Files'); ?>" class="btn btn-primary">
	</div>
</div>
<div class="row">
	<div class="col-md-5 col-xs-3">
		<span id="extendons_settings_msg">
			<p><?php echo esc_html__('Save General Settings Successfully!', ' extendons_Upload_Files'); ?></p>
		</span>
	</div>
</div>




