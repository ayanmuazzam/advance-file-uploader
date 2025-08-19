<div class="custom-model-main">
	<div class="custom-model-inner">
		<div class="close-btn">×</div>
		<div class="custom-model-wrap">
			<div class="pop-up-content-wrap">
				<span class="rule-label">
					<h3><?php echo esc_html__('Upload your file', 'extendons_Upload_Files'); ?></h3>
				</span>
				<hr>
				<form method="post" class="extendons_upload_form" enctype="multipart/form-data">
					<div class="upload-rule rule-id-<?php echo filter_var($extendons_upload_files_postid); ?>">
						<?php 
						if (isset($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_description']) && '' != $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_description']) {
							?>
						<span class="extendons_label"><b>
							<?php 
												echo esc_html__('Description: ', 'extendons_Upload_Files');
							?>
							</b>
						</span>
						<p class="description">
							<?php echo filter_var($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_description']); ?>
						</p>
							<?php
						}
						?>
						<span class="extendons_label"><b>
								<?php echo esc_html__('Allowed extension: ', 'extendons_Upload_Files'); ?></b>
							<?php echo filter_var($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_allwoed_extension']); ?>
						</span>
						<br>
						<?php
						if (isset($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_maximum_upload_size_value']) && $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_maximum_upload_size']) {

							$extendonsfilesize = $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_maximum_upload_size_value'];
							$extendonsfileformattype = str_replace('extendons_upload_files_', '', $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_maximum_upload_size']);
						} 
						if ('' != $extendonsfilesize && '' != $extendonsfileformattype ) {
							?>
						<span class="extendons_label"><b>
								<?php echo esc_html__('Max allowed size: ', 'extendons_Upload_Files'); ?></b>
							<?php echo filter_var($extendonsfilesize . $extendonsfileformattype); ?>
						</span>
							<?php 
						} 
						if ('' != $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_type']) {

							if ('extendons_upload_files_percentage' == $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_type']) {

								$extendons_upload_fileprice = $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_price'];

								if ('' != $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_price']) {

										$extendonsfile_price = ( $extendons_upload_fileprice * $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_price'] )/100;

										$extendonsfile_price = floatval($extendons_upload_fileprice)-floatval($extendonsfile_price);

										$extendons_upload_files_dicounted_product_val = floatval($extendons_upload_fileprice-$extendonsfile_price);

									?>
						<br>
						<span class="extendons_label"><b>
									<?php echo esc_html__('Actual Price: ', 'extendons_Upload_Files'); ?></b>
									<?php echo filter_var(wc_price($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_price'])); ?>
						</span>
						<br>
						<span class="extendons_label"><b>
									<?php echo esc_html__('Discount: ', 'extendons_Upload_Files'); ?></b>
									<?php echo filter_var($extendons_upload_files_dicounted_product_val ); ?>
						</span>
									<?php
								}
							} else if ('extendons_upload_files_fixed' == $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_type']) {

								if ('' != $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_price']) {

										$extendons_upload_fileprice =$extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_price'];

										$extendonsfile_price = ( $extendons_upload_fileprice - $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_price'] );

										$extendons_upload_files_dicounted_product_val = esc_attr($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_discount_price']);
									?>
						<br>
						<span class="extendons_label"><b>
									<?php echo esc_html__('Actual Price: ', 'extendons_Upload_Files'); ?></b>
									<?php echo filter_var(wc_price($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_price'])); ?>
						</span>
						<br>
						<span class="extendons_label"><b>
									<?php echo esc_html__('Discount: ', 'extendons_Upload_Files'); ?></b>
									<?php echo filter_var(wc_price($extendons_upload_files_dicounted_product_val)); ?>
						</span>
									<?php

								}

							}
						} else {
							$extendonsfile_price = $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_price'];
						}
						if ( isset( $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_price']) && '' != $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_price']) {
							?>
						<br>
						<span class="extendons_label"><b>
							<?php echo esc_html__('Total Price: ', 'extendons_Upload_Files'); ?></b>
							<?php echo filter_var(wc_price($extendonsfile_price)); ?>
						</span>
							<?php
						}
						?>
						<input class="extendons_file_input_order_page" cart-key="<?php echo filter_var($cart_key); ?>"
							indexkey="<?php echo filter_var($index_key); ?>"
							rule-index-id="<?php echo filter_var($data_rule_index_id); ?>"
							pro-id="<?php echo filter_var($productid); ?>" page="<?php echo filter_var($pagetype); ?>"
							data-post-id="<?php echo filter_var($extendons_upload_files_postid); ?>" type="file"
							type-file-format="<?php echo filter_var($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_maximum_upload_size']); ?>"
							file-size-allow-to-upload="<?php echo filter_var($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_maximum_upload_size_value']); ?>"
							accept="<?php echo filter_var($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_allwoed_extension']); ?>"
							max-file-upload="<?php echo filter_var($data_allow_upload_file); ?>"
							name="extendons_file_input_order__page" id="extendons_file_input">
						<?php

															// $extendons_thankyou_pagedata = get_post_meta($extendons_order_id, 'extendons_cart_order_data', true);
																
							$extOrder = wc_get_order($extendons_order_id);
							$extendons_thankyou_pagedata = $extOrder->get_meta('extendons_cart_order_data', true);                         
						
						if (!empty($extendons_thankyou_pagedata)) {
							foreach ($extendons_thankyou_pagedata as $cart_item_key => $extendons_upload_file_cart_item) {

								if ($cart_item_key == $cart_key) {

									if (isset($extendons_upload_file_cart_item['extendons_upload_file_data']) && !empty($extendons_upload_file_cart_item['extendons_upload_file_data']) && '0' != $extendons_upload_file_cart_item['extendons_upload_file_data'][$data_rule_index_id][$index_key]['extendons_total_upload_files']) {

										?>
						<input type="show" name="extendons_hidden_array<?php echo filter_var($productid); ?>"
							value="<?php echo filter_var($extendons_upload_file_cart_item['extendons_upload_file_data'][$data_rule_index_id][$index_key]['extendons_total_upload_files']); ?>">
										<?php

									} else {
										?>
						<input type="show" name="extendons_hidden_array<?php echo filter_var($productid); ?>" value="">
										<?php
									}

								}

							}
						}   

						?>
						<span class="extendonsrrormsg description"></span>
						<table class="table table-responsive extendons_show_files">
							<thead></thead>
							<tbody class="showimage">
								<?php

																// $extendons_thankyou_pagedata = get_post_meta($extendons_order_id, 'extendons_cart_order_data', true);
								
								
								$extOrder = wc_get_order($extendons_order_id);
								$extendons_thankyou_pagedata = $extOrder->get_meta('extendons_cart_order_data', true);

								if (!empty($extendons_thankyou_pagedata)) {
									foreach ($extendons_thankyou_pagedata as $cart_item_key => $extendons_upload_file_cart_item) {

										if ($cart_item_key == $cart_key) {

											if (!empty($extendons_upload_file_cart_item['extendons_upload_file_data'])) {

												if (isset($extendons_upload_file_cart_item['extendons_upload_file_data'])) {

													if (!empty($extendons_upload_file_cart_item['extendons_upload_file_data']) && '0' != $extendons_upload_file_cart_item['extendons_upload_file_data'][$data_rule_index_id][$index_key]['extendons_total_upload_files']) {

														foreach ($extendons_upload_file_cart_item['extendons_upload_file_data'][$data_rule_index_id][$index_key]['uploaded_file'] as $keyval => $session_value) {

															$extension = pathinfo(filter_var($session_value['extendons_upload_files_cartfilename']), PATHINFO_EXTENSION);
															$videoextendions = array( 'mp4', 'mov', 'wmv', 'webm', 'avi', 'avchd', 'flv', 'f4v', 'swf', 'mpg' );
															$file_type = explode('/', $session_value['extendons_upload_files_carttype']);
															if (in_array($extension, $videoextendions)) {
																$file_src = '<img src="https://iconarchive.com/download/i61405/hadezign/hobbies/Movies.ico" width="50">';
															} else if ( 'image' == $file_type[0]) {
																$file_src = '<img src="' . esc_url($session_value['extendons_upload_files_cartfile_url']) . '" width="100">';
															} else if ( 'application' == $file_type[0]) {

																// $file_src = '<img src="https://i0.wp.com/www.raltron.com/wp-content/uploads/2016/09/adobe-pdf-icon.png?fit=250%2C250&ssl=1" width="50">';
																$file_src = 'https://www.svgrepo.com/show/424860/pdf-file-type.svg" width="50">';

															} else if ( 'audio' == $file_type[0]) {

																$file_src = '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyYf66jcjiKLRac02OnFXPkx-gULXJNxtsgQ&usqp=CAU" width="50">';
															} else {
																$file_src = '<img src="https://cdn3.iconfinder.com/data/icons/brands-applications/512/File-512.png" width="100">';
															}

															?>
								<tr class="extendons-image-list">
									<td>
															<?php echo filter_var($file_src); ?>
									</td>
									<td>
										<small><?php echo filter_var($session_value['extendons_upload_files_cartfilename']); ?></small>
									</td>
									<td>
										<span style="display:inline-flex;cursor:pointer;">
											<a href="<?php echo filter_var($session_value['extendons_upload_files_cartfile_url']); ?>"
												target="_blank;"><img
													src="https://cdn.iconscout.com/icon/premium/png-256-thumb/view-file-461477.png"
													width="30">
											</a>
										</span>
									</td>
									<td>
										<input type="hidden" name="extendons_index_file"
											value="<?php echo filter_var($keyval); ?>">
										<label for="extendons_file_input">
											<div class="extendons_btn_upload" style="margin-top: 1em;cursor: pointer;">

												<i class="fa fa-upload fa-1x" aria-hidden="true"
													style="line-height: unset;"></i>
												<span class="extendons_file_text">
															<?php 
															if (isset($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_text_btn']) &&  '' != $extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_text_btn']) {
																echo filter_var($extendons_upload_files_multival_arr[$index_key]['extendons_uploadfiles_file_text_btn']);
															} else {
																echo filter_var('Upload File', 'extendons_Upload_Files');
															}       
															?>
												</span>
											</div>
										</label>
									</td>
								</tr>
															<?php
														}
													}   
												}
											}
										}
									}
								}

								?>
							</tbody>
						</table>
						<input type="submit" name="extendons_save_data" value="Submit" style="float: right;">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="bg-overlay"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery(".close-btn, .bg-overlay").click(function () {
			jQuery(".custom-model-main").removeClass('model-open');
		});
	});
</script>