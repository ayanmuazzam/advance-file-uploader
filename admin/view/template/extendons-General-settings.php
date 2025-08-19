<table class="form-table">
	<tbody>
		<tr valign="top" class="single_select_page_enable">
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Enable/Disable', 'extendons_Upload_Files'); ?>
					<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enable / Disable Rule' )); ?></span>
				</label>
				
			</th>
			<td class="forminp">
				<select id="extendons_upload_files_enable_disable_setting">
					<option value=""><?php echo esc_html__('Choose visibility', 'extendons_Upload_Files'); ?></option>
					<option value="extendons_upload_files_enable"><?php echo esc_html__('Enable', 'extendons_Upload_Files'); ?></option>
					<option value="extendons_upload_files_disable"><?php echo esc_html__('Disable', 'extendons_Upload_Files'); ?></option>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row" class="titledesc">
				<label><?php echo esc_html__('Rule Name', 'extendons_Upload_Files'); ?>
					<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Rule Name' )); ?></span>
				</label>
			</th>
			<td>
				<input type="text" name="extendons_rule_name" id="extendons_up_rule_name" value="">
			</td>
		</tr>
		
		<!-- PIN: from new rule -->
		<tr valign="top" class="single_select_page"> 
			<th scope="row" class="titledesc">
				<label  id="extendons_upload_files_label"><?php echo esc_html__('Display On', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Position To Display Upload File Button' )); ?></span></label>
			</th>
			<td class="forminp">
				<input type="checkbox" id="extendons-product-page" class="extendons_upload_files_radio extendons_vt_checkboxes" name="extendons-radio-select-display-on" value="extendons_upload_files_product_page">
				<label for="extendons-product-page" id="extendons_upload_files_radio"><?php echo esc_html__('Product Page', 'extendons_Upload_Files'); ?></label>
				<br/>
				<input type="checkbox" id="extendons-after-cart-page-table" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons_upload_file_after_cart_table">
				<label for="extendons-after-cart-page-table" id="extendons_upload_files_radio">
					<?php echo esc_html__('Cart Page', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" id="extendons-after-checkout-page-after-notes-field" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons_upload_file_after_checkout_page_after_notes_field">
				<label for="extendons-after-checkout-page-after-notes-field" id="extendons_upload_files_radio">
					<?php echo esc_html__('Checkout Page > After Notes', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" id="extendons-thankyou-page" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons-upload-file-thankyou-page">
				<label for="extendons-checkout-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('ThankYou Page', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" id="extendons-Account-page" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons-upload-file-Account-page">
				<label for="extendons-checkout-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('Account Page', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" id="extendons-cart-page" class="extendons_upload_files_radio extendons_vt_checkboxes" name="extendons-radio-select-display-on" value="extendons_upload_files_cart_page">
				<label for="extendons-cart-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('Cart Page (Alongside Cart Items)', 'extendons_Upload_Files'); ?>
				</label>
				<br/>
				<input type="checkbox" id="extendons-checkout-page-after-notes" class="extendons_upload_files_radio extendons_vt_checkboxes " name="extendons-radio-select-display-on" value="extendons_upload_files_checkout_page_after_notes">
				<label for="extendons-checkout-page" id="extendons_upload_files_radio">
					<?php echo esc_html__('Checkout Page (Alongside Cart Items)', 'extendons_Upload_Files'); ?>
				</label>
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
								<div class="panel-group" id="accordion" style="margin-top: 10px;" role="tablist" aria-multiselectable="true">
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="headingOne">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1" class="extendons_uf_accordian">
													Item #1
												</a>
											</h4>
										</div>
										<div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
											<div class="panel-body">
												
													<div class="row" id="extendons_upload_files_FormSettingsupload">
														<div class="col-xs-4 col-md-3">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Label:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Label for Upload File Button' )); ?></span>
															</label>
															<input type="Text" name="extendonspriceuploadfilelabel[]" value="" id="extendonspriceuploadfilelabel" style="width: 100%">
														</div>
														<div class="col-xs-4 col-md-3">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Allowed Extension:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Add the allowed file formats, writing the extensions divided by comma (e.g., jpg,png,gif).' )); ?></span>
															</label>
															<input type="text" name="extendonspriceuploadfileallowedextension[]" value="" id="extendonspriceuploadfileallowedextension" style="width: 100%">
														</div>
														<div class="col-xs-4 col-md-2">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Price:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Price for file uploaded' )); ?></span>
															</label>
															<input type="number" min="1" oninput="validity.valid||(value='')" name="extendonspriceuploadfileallowedprice[]" value="" id="extendonspriceuploadfileallowedprice" style="width: 100%">
														</div>
														<div class="col-xs-4 col-md-2">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Discount Type:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Select Discount Type Fixed or Percentage' )); ?></span>
															</label>
															<select id="extendonsDiscounttype" name="extendonsDiscounttype[]" style="width: 100%">
																<option value=""><?php echo esc_html__('Select Type', 'extendons_Upload_Files'); ?></option>
																<option value="extendons_upload_files_fixed"><?php echo esc_html__('Fixed', 'extendons_Upload_Files'); ?></option>
																<option value="extendons_upload_files_percentage"><?php echo esc_html__('Percentage', 'extendons_Upload_Files'); ?></option>
															</select>
														</div>
														<div class="col-xs-4 col-md-2">
															<label id="extendons_upload_files_label"><?php echo esc_html__('Discount Price:', 'extendons_Upload_Files'); ?>
															<span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Discount Price for file uploaded' )); ?></span>
														</label>
														<input type="number" min="1" name="extendonsDiscountvalue[]" value="" id="extendonsDiscountvalue" oninput="validity.valid||(value='');" style="width: 100%;">
													</div>
												</div>
												<div class="row" id="extendons_upload_files_Discount">
													<div class="col-xs-4 col-md-3">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Description:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Description for Upload File Button' )); ?></span>
														</label>
														<textarea name="extendons_upload_files_description[]" style="width: 100%" rows="3" cols="50"></textarea>
													</div>
													<div class="col-md-3">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Upload File Button Text:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Upload File Button Text' )); ?></span>
														</label>
														<input type="text" name="extendonspriceuploadfilebtntext[]" value="" id="extendonspriceuploadfilebtntext" style="width: 100%">
													</div>
													
													<div class="col-xs-4 col-md-5">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Maximum Upload Size:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Maximum Upload Size for File Upload' )); ?></span></label><br>
														<select id="extendons_file_size" name="extendons_file_size[]" style="width: 25%;">
															<option value="extendons_upload_files_KB"><?php echo esc_html__('KB', 'extendons_Upload_Files'); ?></option>
															<option value="extendons_upload_files_MB"><?php echo esc_html__('MB', 'extendons_Upload_Files'); ?></option>
								
														</select>
														<input type="number" min="0" placeholder="Enter Size" class="form-control extendons-upload-size" name="extendons-maximum-uploadsize[]" value="" id="extendons-maximum-uploadsize" oninput="validity.valid||(value='');" style="display: initial;width: 70%;">
													</div>
												</div>
												<div class="row">
													<div class="col-xs-4 col-md-3" style="margin-top: 20px;">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Customer Notes:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enable to Allow Customer notes' )); ?></span></label>
														<input type="checkbox" name="extendons_uf_allow_notes_checkbox[]" class="extendons_vt_checkboxes">
														<br>
														<label id="extendons_upload_files_label"><?php echo esc_html__('Required:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enable to Upload File Required' )); ?></span></label>
														<input type="checkbox" name="extendons_uf_required[]" class="extendons_vt_checkboxes">
													</div>

													<div class="col-xs-4 col-md-3" style="margin-top: 20px;">

														<label id="extendons_upload_files_label"><?php echo esc_html__('Customer Notes Label:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Label For Customer Notes' )); ?></span></label>
														<input type="text" name="extendonspriceuploadfilecustomernotelabel[]" value="" id="extendonspriceuploadfilecustomernotelabel" style="width: 100%">
													</div>
													<div class="col-md-3" style="margin-top: 20px;">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Maximum File Upload:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Enter Maximum Quantity for upload file button' )); ?></span></label>
														<input type="number" min="0" name="extendons_uploadfile_allowed_max_qunatity[]" value="" id="extendons_uploadfile_allowed_max_qunatity" style="width: 100%"><span class="description">
														<?php echo esc_html__('If Maximum File Upload Field Empty then customer can upload only 1 file.', 'extendons_Upload_Files'); ?>													</span>
													</div>
													<div class="col-xs-4 col-md-3" style="margin-top: 20px;">
														<label id="extendons_upload_files_label"><?php echo esc_html__('Background Color:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Background Color for Upload File Button' )); ?></span>
														</label>
														<input type="color" class="jscolor" name="extendons_upload_files_color[]" id="extendons_upload_files_color" value="#337ab7">
														<br>
														<label id="extendons_upload_files_label"><?php echo esc_html__('Text Color:', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Text Color for Upload File Button' )); ?></span></label>
														<input type="color" class="jscolor" name="extendons_upload_files_text_color[]" id="extendons_upload_files_text_color" value="#FFFFFF">
													</div>
												
											</div>
										</div>
									</div>
								</div>
							</span>
						</div>
					</span>
				</div>
			</div>
		</td>

	</tr>

	
	<tr valign="top" class="single_select_page">
		<th scope="row" class="titledesc">
			<label id="extendons_upload_files_label"><?php echo esc_html__('Product/Category Restriction', 'extendons_Upload_Files'); ?><span class="tip" style="float: right;"><?php echo filter_var( wc_help_tip('Choose Product/Category where you want to display Upload File Button' )); ?></span></label>
		</th>
		<td class="forminp">
			<select class="form-control extendonsproductcategory" id="extendonsproductcategory" name="selectpc[]" onchange="extendons_upload_file_choosen_product_cateory('extendons_create');">
				<option value=""><?php echo esc_html__('Visible for every product:', 'extendons_Upload_Files'); ?></option>
				<option value="extendons_upload_files_product"><?php echo esc_html__('Product', 'extendons_Upload_Files'); ?></option>
				<option value="extendons_upload_files_category"><?php echo esc_html__('Category', 'extendons_Upload_Files'); ?></option>
			</select>
			<span class="extendons_upload_files_description">
				<?php 
				echo esc_html__('Upload field can optionally visible/hidden only if the selected products are in cart/order.', 'extendons_Upload_Files'); 
				?>
			</span>
		</td>
	</tr>

	<tr valign="top" class="single_select_page" id="extendons_upload_files_Products">
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
						?>
						<option value="<?php echo esc_attr($products->ID); ?>"><?php echo filter_var($products->post_title); ?></option>
						<?php
					}

					?>
				</select>
			<?php }; ?>
		</td>
	</tr>

	<tr valign="top" class="single_select_page" id="extendons_upload_files_category">
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
						?>
						<option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_attr($category->name); ?></option>
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
			if (!empty($extendons_upload_files_default_roles)) {
				?>
				<select class="extendons_choosen" id="extendons_choosen-user-role" multiple="multiple" name="">
					<?php
					foreach ($extendons_upload_files_default_roles as $key => $value) {
						?>
						<option value="<?php echo filter_var(strtolower(str_replace(' ', '_', $value))); ?>">
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
		<input type="button" name="extendons_save_settings" onclick="extendons_upload_file_save_general_settings('add');" value="<?php echo esc_html__('Save Settings', 'extendons_Upload_Files'); ?>" class="btn btn-primary">
	</div>
</div>
<div class="row">
	<div class="col-md-5 col-xs-3">
		<span id="extendons_settings_msg">
			<p><?php echo esc_html__('Save General Settings Successfully!', ' extendons_menu_cart_plugin'); ?></p>
		</span>
	</div>
</div>




