<?php 
if (!defined('ABSPATH')) {
	exit;
}
global $wp_roles;
global $post;
global $woocommerce;
$extendons_args = array(
'post_type'=> 'extend_upload_files',
'orderby'    => 'ID',
'post_status' => 'publish',
'order'    => 'ASC',
'posts_per_page' => -1, // this will retrive all the post that is published 
);
$extendons_Upload_Files_rules = new WP_Query( $extendons_args );

?>
<table class="wc-shipping-zones widefat extendons_rule_data" style="margin-top: 10px;">
	<input type="hidden" name="extendons_edit_rule_id" value="">
	<thead>
		<tr>
			<th class="extendons_upload_file"><?php echo esc_html__('Rule', 'extendons_Upload_Files'); ?></th>
			<th class="extendons_upload_file"><?php echo esc_html__('Rule Status', 'extendons_Upload_Files'); ?></th>
			<!-- PIN: from coulmn -->
			<!-- <th class="extendons_upload_file"><?php echo esc_html__('Display Position', 'extendons_Upload_Files'); ?></th> -->
			<th  class="extendons_upload_file"><?php echo esc_html__('User Roles:', 'extendons_Upload_Files'); ?></th>
			<th class="extendons_upload_file"><?php echo esc_html__('Product/Category', 'extendons_Upload_Files'); ?></th>
		</tr>
	</thead>

	<tbody>
			<?php 
			$extendons_upload_file_rule_key = 1;
			if ( $extendons_Upload_Files_rules->have_posts() ) {        
				while ( $extendons_Upload_Files_rules->have_posts() ) {
					$extendons_Upload_Files_rules->the_post(); 
					$extendons_postid = get_the_ID(); 
					$rule_name = get_post_meta($extendons_postid, 'extendons_up_rule_name', true);
					$extendons_rule_name = !empty($rule_name) ? $rule_name : $extendons_upload_file_rule_key;
					?>
					<tr id="dr<?php echo filter_var($extendons_postid); ?>">
						<td class="wc-shipping-zone-name" style="width: unset;">
							<?php echo esc_html__('Rule: ', 'extendons_Upload_Files') . esc_attr($extendons_rule_name); ?>
							<div class="row-actions">
								<button type="submit" data-id="<?php echo filter_var($extendons_postid); ?>" name="extendons_edit_rule" id="extendons_upload_files_editbtn" style="background-color: transparent;border: none;color: #236a85;">Edit</button> | <a href="#" class="wc-shipping-zone-delete" onclick="extendons_upload_file_delete_rule(<?php echo esc_attr($extendons_postid); ?>)"  id="extendons_upload_files_deletebtn">Delete</a>
							</div>
						</td>
						<td>
							<?php 
							$extendons_upload_files_get_status =  get_post_meta($extendons_postid, 'extendons_enable_disable_settings', true);
							$extendons_upload_files_get_status =  str_replace('extendons_upload_files_', '', $extendons_upload_files_get_status);
							echo esc_attr(ucfirst($extendons_upload_files_get_status));
							?>
						</td>
						
						<!-- <td> -->
							<?php 
							// $extendons_upload_files_display_on_position =  get_post_meta($extendons_postid, 'extendons_display_on_values', true);

							// if( !empty($extendons_upload_files_display_on_position) ){
							//  foreach ($extendons_upload_files_display_on_position as $key => $value) {
									
							//      if ('false' != $value['extendons_allow_uf_product_page']) {
							//          $extendons_allow_uf_product_page =  str_replace('extendons_upload_files_', '', $value['extendons_allow_uf_product_page']); 
							//          echo esc_attr(str_replace('_', ' ', ucfirst($extendons_allow_uf_product_page))) . '<br>';
							//      } 
							//      if ('false' != $value['extendons_allow_uf_cart_page']) {
							//          $extendons_allow_uf_cart_page =  str_replace('extendons_upload_files_', '', $value['extendons_allow_uf_cart_page']); 
							//          echo esc_attr(str_replace('_', ' ', ucfirst($extendons_allow_uf_cart_page))) . '<br>';
							//      }   
							//      if ('false' != $value['extendons_allow_uf_checkout_page']) {
							//          $extendons_allow_uf_checkout_page =  str_replace('extendons_upload_files_', '', $value['extendons_allow_uf_checkout_page']); 
							//          echo esc_attr(str_replace('_', ' ', ucfirst($extendons_allow_uf_checkout_page))) . '<br>';
							//      }   
							//      if ('false' != $value['extendons_allow_uf_thankyou_page']) {
							//          $extendons_allow_uf_thankyou_page =  str_replace('extendons-upload-file-', '', $value['extendons_allow_uf_thankyou_page']); 
							//          echo esc_attr(str_replace('-', ' ', ucfirst($extendons_allow_uf_thankyou_page))) . '<br>';
							//      }   
							//      if ('false' != $value['extendons_allow_uf_account_page']) {
							//          $extendons_allow_uf_account_page =  str_replace('extendons-upload-file-', '', $value['extendons_allow_uf_account_page']); 
							//          echo esc_attr(str_replace('-', ' ', ucfirst($extendons_allow_uf_account_page))) . '<br>';
							//      }       
							//  }
							// }

							?>
						<!-- </td> -->
						<td>
							<?php 
							$extendons_upload_file_user_roles = get_post_meta($extendons_postid, 'extendons_selected_user_role', true); 
							if (!empty($extendons_upload_file_user_roles)) {
								$extendons_roles = implode(', ', $extendons_upload_file_user_roles);
								echo esc_attr(ucfirst($extendons_roles));
							}
							?>
						</td>
						<td>
							<?php 
							$extendons_upload_files_items_type_pc = get_post_meta($extendons_postid, 'extendons_selected_product_category', true);              
							$extendons_upload_files_selected_product = get_post_meta($extendons_postid, 'extendons_selected_items', true);
							if ('extendons_upload_files_product' == $extendons_upload_files_items_type_pc ) {
								if (!empty($extendons_upload_files_selected_product)) {
									foreach ($extendons_upload_files_selected_product as $key => $value) {
										echo esc_attr(get_the_title($value)) . ', ';
									} 
								}
							} elseif (!empty($extendons_upload_files_selected_product)) {
								foreach ($extendons_upload_files_selected_product as $key => $value) {
									$extendons_upload_file_category = get_term($value);
									echo esc_attr($extendons_upload_file_category->name) . ', ';
								}
							}
							?>
						</td>
					</tr>
					<?php
					$extendons_upload_file_rule_key++;
				}
			} else {

				?>
				<tr>
					<td>
						<center><?php echo esc_html__('Rule is empty', 'extendons_Upload_Files'); ?></center>
					</td>
				</tr>
				<?php } ?>		
	</tbody>
</table>

