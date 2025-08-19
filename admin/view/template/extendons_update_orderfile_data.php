<div class="custom-model-main">
	<div class="custom-model-inner">
		<div class="close-btn">×</div>
		<div class="custom-model-wrap">
			<div class="pop-up-content-wrap">

				<table class="table table-responsive extendons_show_files">
					<thead>
						<tr>
							<th><?php echo esc_html__('Thumbnail', 'extendons_Upload_Files'); ?></th>
							<th><?php echo esc_html__('FileName', 'extendons_Upload_Files'); ?></th>
							<th><?php echo esc_html__('View', 'extendons_Upload_Files'); ?></th>
							<th><?php echo esc_html__('Action', 'extendons_Upload_Files'); ?></th>
						</tr>
					</thead>
					
					<tbody class="showimage1">

						<?php 

						if (!empty($extendons_thankyou_pagedata)) {

							foreach ($extendons_thankyou_pagedata as $cart_key => $value) {

								if ($cart_key==$data_cart_key) {

									if ('' == $extendons_upload_files_multival_arr[$data_btnindex_key]['extendons_uploadfiles_file_maximum_upload_files']) {
										$max_upload_file = '1';
									} else {

										$max_upload_file = $extendons_upload_files_multival_arr[$data_btnindex_key]['extendons_uploadfiles_file_maximum_upload_files'];
									}

									if (isset($value['extendons_upload_file_data']) && !empty($value['extendons_upload_file_data'])) {

										if (!empty($value['extendons_upload_file_data'][$data_rule_id][$data_btnindex_key]['uploaded_file'])) {
											?>
							<td>
							<input class="extendons_file_input" data-item-id="<?php echo filter_var($data_item_id); ?>"
								indexarray="" order_id="<?php echo filter_var($extendons_order_id); ?>"
								data-rule-id="<?php echo filter_var($data_rule_id); ?>"
								cart-key="<?php echo filter_var($cart_key); ?>" btn_key=""
								pro-id="<?php echo filter_var($productid); ?>" page="thankyou_account_page"
								data-post-id="<?php echo filter_var($post_id); ?>" type="file"
								type-file-format="<?php echo filter_var($extendons_upload_files_multival_arr[$data_rule_id]['extendons_uploadfiles_file_maximum_upload_size']); ?>"
								file-size-allow-to-upload="<?php echo filter_var($extendons_upload_files_multival_arr[$data_rule_id]['extendons_uploadfiles_file_maximum_upload_size_value']); ?>"
								accept="<?php echo filter_var($extendons_upload_files_multival_arr[$data_btnindex_key]['extendons_uploadfiles_allwoed_extension']); ?>"
								max-file-upload="<?php echo filter_var($max_upload_file); ?>"
								name="extendons_file_input[]" id="extendons_file_input">
							<input type="hidden" name="extendons_hidden_array<?php echo filter_var($productid); ?>"
								value="">
							<input type="hidden" name="extendons_hidden_file_array<?php echo filter_var($productid); ?>"
								value="">
						</td>
											<?php
											foreach ($value['extendons_upload_file_data'][$data_rule_id][$data_btnindex_key]['uploaded_file'] as $index_array => $value1) {

												$extension = pathinfo(filter_var($value1['extendons_upload_files_cartfilename']), PATHINFO_EXTENSION);
												$videoextendions = array( 'mp4', 'mov', 'wmv', 'webm', 'avi', 'avchd', 'flv', 'f4v', 'swf', 'mpg' );
												$file_type = explode('/', $value1['extendons_upload_files_carttype']);
												if (in_array($extension, $videoextendions)) {
													$file_src = '<img src="https://iconarchive.com/download/i61405/hadezign/hobbies/Movies.ico" width="50" style="max-width: unset !important;">';
												} else if ( 'image' == $file_type[0]) {
													$file_src = '<img src="' . esc_url($value1['extendons_upload_files_cartfile_url']) . '" width="100" style="max-width: unset !important;">';
												} else if ( 'application' == $file_type[0]) {

													// $file_src = '<img src="https://i0.wp.com/www.raltron.com/wp-content/uploads/2016/09/adobe-pdf-icon.png?fit=250%2C250&ssl=1" width="50" style="max-width: unset !important;">';
													$file_src = 'https://www.svgrepo.com/show/424860/pdf-file-type.svg" width="50">';

												} else if ( 'audio' == $file_type[0]) {

													$file_src = '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyYf66jcjiKLRac02OnFXPkx-gULXJNxtsgQ&usqp=CAU" width="50" style="max-width: unset !important;">';
												} else {
													$file_src = '<img src="https://cdn3.iconfinder.com/data/icons/brands-applications/512/File-512.png" width="100" style="max-width: unset !important;">';
												}   

												?>
						<tr>
							<td id="thumb<?php echo filter_var($index_array . $data_item_id . $data_btnindex_key); ?>">
												<?php echo filter_var($file_src); ?>

							</td>
							<td>
								<small
									id="title<?php echo filter_var($index_array . $data_item_id . $data_btnindex_key); ?>"><?php echo filter_var($value1['extendons_upload_files_cartfilename']); ?></small>
							</td>
							<td>
								<span
									id="view<?php echo filter_var($index_array . $data_item_id . $data_btnindex_key); ?>"
									style="display:inline-flex;cursor:pointer;">
									<a class="extendonsvieworderfile"
										href="<?php echo filter_var($value1['extendons_upload_files_cartfile_url']); ?>"
										target="_blank;"><img
											src="https://cdn.iconscout.com/icon/premium/png-256-thumb/view-file-461477.png"
											width="30" style="max-width: unset !important;">
									</a>
								</span>
							</td>
							<td>
												<?php 
																			
												$btnbackgroundcolor = 'background-color:' . $value['extendons_upload_file_data'][$data_rule_id][$data_btnindex_key]['extendons_btn_background_color'] . ';';
												$btntextcolor = 'color:' . $value['extendons_upload_file_data'][$data_rule_id][$data_btnindex_key]['extendons_btn_text_color'] . ';';
												?>
								<label for="extendons_file_input">
									<div class="extendons_btn_upload extendons_order_data_val_change"
										index-array="<?php echo filter_var($index_array); ?>"
										btn_key="<?php echo filter_var($data_btnindex_key); ?>"
										style="<?php echo filter_var($btnbackgroundcolor); ?>margin-top: 1em;cursor: pointer;padding: 6px;">
										<i class="fa fa-upload fa-1x" aria-hidden="true"
											style="<?php echo filter_var($btntextcolor); ?>line-height: unset;"></i>
										<span class="extendons_file_text"
											style="<?php echo filter_var($btntextcolor); ?>">
												<?php echo esc_html__('Choose File', 'extendons_Upload_Files'); ?>
										</span>
									</div>
								</label>
								<span class="extendonsrrormsg description"></span>
							</td>
						</tr>
												<?php	                                                              
											}

										}
									}
								}
							}
						}
						?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery(".close-btn, .bg-overlay").click(function () {
			jQuery(".custom-model-main").removeClass('model-open');
		});
	});
</script>