<?php
/*
* Plugin Name: File Uploader for WooCommerce
* Description: Simple interface to upload files from a page.
* Author: Extendons
* Requires Plugins: woocommerce
* TextDomain: extendons_Upload_Files
* Version: 1.0.3
*
*/
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}


if ( !class_exists( 'Extendons_Ext_Upload_Files' ) ) {

	include_once ABSPATH . 'wp-admin/includes/plugin.php' ;

	/**
	* Class Extendons_Ext_Upload_Files
	*
	* This class handles the main functionality of the File Uploader for WooCommerce plugin.
	*/

	class Extendons_Ext_Upload_Files {

		/**
		* Constructor
		*
		* Initializes the plugin by checking if WooCommerce is active, setting up constants,
		* loading necessary files, and adding filters and actions.
		*/

		public function __construct() {

			if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

				add_action( 'admin_notices', array( $this, 'extendons_upload_files_admin_notice' ) );

			}

			$this->extendons_upload_files_module_constants();
			if ( is_admin() ) {
				require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/extendons-upload-files-admin.php' ;
			} else {
				require_once EXTENDONS_UF_PLUGIN_DIR . 'front/extendons-upload-files-front.php' ;
			}
			

			add_filter( 'woocommerce_order_item_get_formatted_meta_data', array( $this, 'unset_specific_order_item_meta_data' ), 10, 2 );
			add_filter( 'woocommerce_order_item_display_meta_key', array( $this, 'change_order_item_meta_title' ), 20, 3 );
			// add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_cart_item_custom_meta_as_order_item_meta' ), 11, 4 );
			add_filter( 'woocommerce_order_item_display_meta_value', array( $this, 'extendons_conditionally_meta_display' ), 10, 3 );

			/**
			* Action hook to load text domain for internationalization.
			*/
			add_action( 'init', array( $this, 'extendons_upload_files_load_text_domain' ) );
		}

		/**
		* Load the plugin text domain for translation.
		*
		* @since 1.0.0
		*/

		public function extendons_upload_files_load_text_domain() {
			load_plugin_textdomain( 'extendons_Upload_Files', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		* Display admin notice for extensions upload files
		*
		* @since 1.0.0
		* @return void
		*/

		public function extendons_upload_files_admin_notice() {

			// Deactivate the plugin
			deactivate_plugins( __FILE__ );

			$allowed_tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
				),
				'abbr' => array(
					'title' => array(),
				),
				'b' => array(),
				'blockquote' => array(
					'cite'  => array(),
				),
				'cite' => array(
					'title' => array(),
				),
				'code' => array(),
				'del' => array(
					'datetime' => array(),
					'title' => array(),
				),
				'dd' => array(),
				'div' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'h1' => array(),
				'h2' => array(),
				'h3' => array(),
				'h4' => array(),
				'h5' => array(),
				'h6' => array(),
				'i' => array(),
				'img' => array(
					'alt'    => array(),
					'class'  => array(),
					'height' => array(),
					'src'    => array(),
					'width'  => array(),
				),
				'li' => array(
					'class' => array(),
				),
				'ol' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
				),
				'q' => array(
					'cite' => array(),
					'title' => array(),
				),
				'span' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'strike' => array(),
				'strong' => array(),
				'ul' => array(
					'class' => array(),
				),
			);

			$wooextmm_message = '<div id="message" class="error">
			<p><strong>File Uploader for WooCommerce Plugin is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for this plugin to work. Please install &amp; activate WooCommerce »</p></div>';

			echo wp_kses( $wooextmm_message, $allowed_tags );
		}

		public function extendons_conditionally_meta_display( $display_value, $meta, $item ) {
			// For Accepted/Rejected files
			if ( strpos( $meta->key, 'Status' ) === 0 ) {
				$ext_product_level_status = $item->get_meta( 'ext_upload_files_product_status_' . $meta->key );
				$display_value = str_replace(
				'<button',
				'<button data-item-id="' . esc_attr( $item->get_id() ) . '"',
				$display_value
				);
				if (!empty($ext_product_level_status) && ( 'Rejected' === $ext_product_level_status['status'] || 'Approved' === $ext_product_level_status['status'] )) {
					$display_value = $ext_product_level_status['status'];
				}
			}

			if ( strpos( $meta->key, 'Edit Btn' ) === 0 ) {
				$ext_product_level_editbtn = $item->get_meta( 'ext_upload_files_product_editbtn_' . $meta->key );
				if (!empty($ext_product_level_editbtn) && isset($ext_product_level_editbtn) && 'true' == $ext_product_level_editbtn['status']) {
					$display_value = 'Accepted';
				}
				$display_value = str_replace(
					'<button',
					'<button data-item-id="' . esc_attr( $item->get_id() ) . '"',
					$display_value
				);
			}
			
			return $display_value;
		}

		// PIN
		/**
		* Save cart item custom meta as order item meta
		*
		* This method saves the custom meta data associated with the uploaded files
		* as order item meta data when an order is placed.
		*
		* @since 1.0.0
		* @param int    $item_id      The order item ID.
		* @param array  $values       The order item values.
		* @param string $cart_item_key The cart item key.
		*/
		public function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {
		 $extendons_upload_file_product_page_rule = $this->extendons_get_Upload_files_rules();

			if ( isset( $values[ 'extendons_upload_file_data' ] ) && ! empty( $values[ 'extendons_upload_file_data' ] ) ) {
				$extendons_keys = 0;
				foreach ( $extendons_upload_file_product_page_rule as $key => $extendons_rule ) {
					if ( 'extendons_upload_files_enable' === $extendons_rule[ 'extendons_upload_files_enable_disable_status' ] ) {
						if ( ! empty( $values[ 'extendons_upload_file_data' ] ) ) {
							$extendons_keys = 0;

							foreach ( $values[ 'extendons_upload_file_data' ] as $rule_id => $cartitem ) {
								foreach ( $cartitem as $btn_id => $data_item ) {
									$extendons_order_html = '';
									$extendons_order_htmls = '';
									$order_data = array();

									if ( ! empty( $data_item[ 'uploaded_file' ] ) ) {
										$Uploaded_meta_key = 'Uploaded File ' . $extendons_keys;
										$Uploaded_filestatus_key = 'Status ' . $extendons_keys . '_' . $item->get_id();
										$Uploaded_ebtn_key = 'Edit Btn ' . $extendons_keys;
										$customer_nmeta_key = 'Customer Note' . $extendons_keys;

										foreach ( $data_item[ 'uploaded_file' ] as $key1 => $value ) {
											$extendons_upload_files_val = '';
											$extendons_upload_btn_val = '';
											$extendons_upload_filename_pathx = filter_var( $value[ 'extendons_upload_files_cartfile_url' ] );

											$ext = pathinfo( $value[ 'extendons_upload_files_cartfilename' ], PATHINFO_EXTENSION );
											if ( in_array( $value[ 'extendons_upload_files_carttype' ], array( 'image/jpg', 'image/png', 'image/jpeg', 'image/svg', 'image/gif' ), true ) ) {
												$extendons_upload_files_val .= '<a href="' . esc_url( $extendons_upload_filename_pathx ) . '" class="extendons_thankyou_page1" target="_blank" typee="application/' . $ext . '">' . esc_html__( 'Preview Image', 'extendons_Upload_Files' ) . '</a><a href="' . esc_url( $extendons_upload_filename_pathx ) . '" download><button style="padding:7px;border:none;cursor:pointer;margin-left:5px;" class="btn extendons_download data-extendons-download" id="data-extendons-download"><i class="fa fa-download"></i> Download</button></a>';
											} else {
												$extendons_upload_files_val .= '<a href="' . esc_url( $extendons_upload_filename_pathx ) . '" class="extendons_thankyou_page1" target="_blank" typee="application/' . $ext . '">' . esc_html__( 'Preview', 'extendons_Upload_Files' ) . '</a><a href="' . esc_url( $extendons_upload_filename_pathx ) . '" download><button style="padding:7px;border:none;cursor:pointer;margin-left:5px;" class="btn extendons_download data-extendons-download" id="data-extendons-download"><i class="fa fa-download"></i> Download</button></a>';
											}

											array_push( $order_data, $extendons_upload_files_val );
										}

										$extendons_order_html .= '<table class="woocommerce-table data-order-data-extendons"><tbody>';
										foreach ( $order_data as $key123 => $value123 ) {
											$extendons_order_html .= '<tr><td class="extendons_order_data">' . $value123 . '</td></tr>';
										}
										$extendons_order_html .= '</tbody></table>';

										// Add meta using WC_Order_Item
										$item->add_meta_data( $Uploaded_meta_key, $extendons_order_html, true );

										$extendons_upload_btn_val = '<button data-meta-key="' . $Uploaded_filestatus_key . '" data-item-id="' . $item->get_id() . '" data-rule-id="' . $rule_id . '" data-btn-id="' . $btn_id . '" type="button" data-p-id="' . $values[ 'product_id' ] . '" data-cart-key="' . $values[ 'key' ] . '" style="padding:7px;border:none;cursor:pointer;background-color:white;color:white;" data-id="' . $data_item[ 'rule_id' ] . '" data-page_type="data-thankyou-page" class="Click-here extendons_upload_file_order_data">' . esc_html__( 'Modify File', 'extendons_Upload_Files' ) . '</button>';

										$item->add_meta_data( $Uploaded_ebtn_key, $extendons_upload_btn_val, true );

										$extendons_upload_files_vals = '<button type="button" data-product-id="' . $values[ 'product_id' ] . '" data-attr="' . $item->get_id() . '" class="btn btn-success btn-sm extendons_file_accept" id="extendons_accept_btn" style="padding:7px;border:none;cursor:pointer;color:white;" data-rule-id="' . $key . '" value="' . $extendons_keys . '">' . esc_html__( 'Accept', 'extendons_Upload_Files' ) . '</button>
		                             <button type="button" data-product-id="' . $values[ 'product_id' ] . '" data-attr="' . $item->get_id() . '" class="btn btn-danger btn-sm extendons_file_reject" id="extendons_reject_btn" style="padding:7px;border:none;cursor:pointer;color:white;margin-left:10px;" data-rule-id="' . $key . '" value="' . $extendons_keys . '">' . esc_html__( 'Reject', 'extendons_Upload_Files' ) . '</button>';

										$extendons_order_htmls .= '<ul class="data-order-dataar-extendons">';
										$extendons_order_htmls .= '<li class="extendons_order_data">' . ( $extendons_upload_files_vals ?? '' ) . '</li>';
										$extendons_order_htmls .= '</ul>';

										// $item->add_meta_data( $Uploaded_filestatus_key, $extendons_order_htmls, true );

										if ( ! empty( $data_item[ 'extendons_customer_note' ] ) ) {
											$item->add_meta_data( $customer_nmeta_key, $data_item[ 'extendons_customer_note' ], true );
										}

										$extendons_keys++;
									}
								}
							}
						}
					}
				}

				$cart_data = WC()->cart->get_cart();
				$order->update_meta_data( 'extendons_cart_order_data', $cart_data );
				$order->save();

				// Save the order item meta
				$item->save();
			}
		}

		/**
		* Change order item meta title
		*
		* This method changes the title of specific order item meta keys
		* to more user-friendly titles.
		*
		* @param string $key   The meta key.
		* @param object $meta  The meta object.
		* @param object $item  The order item object.
		*
		* @return string The modified meta key title.
		*/

		public function change_order_item_meta_title( $key, $meta, $item ) {

			if ( strpos( $key, 'Uploaded File' ) !== false ) {
				$key = 'Uploaded File';
			}

			if ( strpos( $key, 'Status' ) !== false ) {
			 $key = 'Accept/Reject';
			}

			if ( strpos( $key, 'Edit Btn' ) !== false ) {
				// Check if the file has been accepted by looking for the corresponding status meta
				$ext_product_level_editbtn = $item->get_meta( 'ext_upload_files_product_editbtn_' . $key );
				if ( !empty( $ext_product_level_editbtn ) && isset( $ext_product_level_editbtn ) && 'true' == $ext_product_level_editbtn['status'] ) {
					$key = 'Status';
				} else {
					$key = 'Here You Can Update Files';
				}
			}           if ( strpos( $key, 'Customer Note' ) !== false ) {
				$key = 'Customer Note';
			}

			// PIN: SN6
			// if ( $key == 'accepted_rejected' ) {
			if ( strpos( $key, 'accepted_rejected' ) !== false ) {
				// Replace underscore with space and capitalize first letter

				$key = ucwords( str_replace( '_', ' ', $key ) );

				$key = ucwords( str_replace( '-', ' ', $key ) );

			}
			return $key;
		}

		/**
		* Unset specific order item meta data
		*
		* This method unsets specific order item meta data based on certain conditions,
		* such as the order status or the user's role.
		 *
		 * @param array  $formatted_meta The formatted meta data array.
		 * @param object $item           The order item object.
		 *
		 * @return array The modified formatted meta data array.
		 */
		public function unset_specific_order_item_meta_data( $formatted_meta, $item ) {

			
			global $post;
			$order_id = $item->get_data()['order_id'];
			$order = wc_get_order( $order_id );
			$order_status  = ( $order && is_a( $order, 'WC_Order' ) ) ? $order->get_status() : '';
			$extendons_upload_file_product_id = ( is_array( $item->get_data() ) && isset( $item->get_data()['product_id'] ) ) ? $item->get_data()['product_id'] : '';
			$extendons_upload_file_terms = get_the_terms ( $extendons_upload_file_product_id, 'product_cat' );
			$extendons_upload_file_category_id = ( isset( $extendons_upload_file_terms[0]->term_id ) ) ? $extendons_upload_file_terms[0]->term_id : '';
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
			$extendons_upload_file_user_role = wp_get_current_user()->roles;
			$extendons_upload_file_valid = false;

			// $logger = wc_get_logger();
			// $context = array( 'source' => 'unset_specific_order_item_meta_data' );
			
			// $logger->info( "Order ID: " . $order_id, $context );
			// $logger->info( "Order Status: " . $order_status, $context );
			// $logger->info( "Product ID: " . $extendons_upload_file_product_id, $context );
			// $logger->info( "Category ID: " . $extendons_upload_file_category_id, $context );
			// $logger->info( "User Roles: " . implode(', ', $extendons_upload_file_user_role), $context );
			// $logger->info( "Upload File Valid: " . ($extendons_upload_file_valid ? 'true' : 'false'), $context );
			// $logger->info( "Upload File Rules: " . print_r($extendons_upload_file_rules, true), $context );
			// $logger->info( "formatted_meta: " . print_r($formatted_meta, true), $context );
			

			foreach ($extendons_upload_file_rules as $value) {

				if ('extendons_upload_files_enable' == $value['extendons_upload_files_enable_disable_status']) {
				
					$extendons_upload_files_needle_id = 0;
					if ('extendons_upload_files_product' == $value['extendons_upload_files_selected_pc_type']) {
						$extendons_upload_files_needle_id = $extendons_upload_file_product_id;
					} else if ('extendons_upload_files_category' ==$value['extendons_upload_files_selected_pc_type']) {
						$extendons_upload_files_needle_id = $extendons_upload_file_category_id;
					}

				
					if (!empty($value['extendons_upload_files_selected_pc'])) {

						if (in_array($extendons_upload_files_needle_id, $value['extendons_upload_files_selected_pc'])) {
							
							if (!empty($value['extendons_upload_files_selected_user_role'])) {
								if ( !empty( array_intersect($extendons_upload_file_user_role, $value['extendons_upload_files_selected_user_role'] ))) {
									$extendons_upload_file_valid = true;
								} else {
									$extendons_upload_file_valid = false;
								}
							} else {
								$extendons_upload_file_valid = true;
							}
							
						} else {
							$extendons_upload_file_valid = false;
						}
					} elseif (!empty($value['extendons_upload_files_selected_user_role'])) {
						if (!empty(array_intersect($extendons_upload_file_user_role, $value['extendons_upload_files_selected_user_role']))) {
							$extendons_upload_file_valid = true;
						} else {
							$extendons_upload_file_valid = false;
						}
					} else {
						$extendons_upload_file_valid = true;
					}
				

					if (false == $extendons_upload_file_valid) {
						continue;
					} 

		
					
					if ('processing' != $order_status  && 'pending' != $order_status && 'on-hold' != $order_status) {

						/** This will run for every other order status than 
						 * processing, pending and on-hold */


						//  // PIN: SN5
						// $accepted_rejected = false;
						// foreach ( $formatted_meta as $key => $meta ) {
						//  if ( $meta->key == 'accepted_rejected') {
						//      $accepted_rejected = true;
						//      break;
						//  }
						// }


						// PIN:from here

						// example key: accepted_rejected-{{0}}
						$accepted_rejected_ = array();
						foreach ( $formatted_meta as $key => $meta ) {
							if ( strpos($meta->key, 'accepted_rejected') === 0 ) {
								// $index = substr($meta->key, strrpos($meta->key, '-'));
									
								$parts = explode('-', $meta->key);
								$index = $parts[1];

								$accepted_rejected_[$index] = true;
							} 
						}

						
						
						// Fill missing indices with false
						$max_index = empty($accepted_rejected_) ? 0 : max(array_keys($accepted_rejected_));
						for ($i = 0; $i <= $max_index; $i++) {
							if (!isset($accepted_rejected_[$i])) {
								$accepted_rejected_[$i] = false;
							}
						}
						
						// PIN: to here

						
						foreach ( $formatted_meta as $key => $meta ) {

							 /**
							  * If status is other than processing, pending and on-hold, 
							  * then only show the Edit Btn key if the order is 
							  * already accepted or rejected. becuase otherwise this will contain 
							  * Modify File action, if accepted or rejected then It will contain the message.

							  */
	
							if (strpos($meta->key, 'Edit Btn') !== false) {

								// meta->key : Edit Btn 0

								$edit_accepted_rejected = false;

								//status_key : 0
								$edit_key = explode(' ', $meta->key)[2];

								// echo "edit key :".$edit_key;                             
								$edit_accepted_rejected = isset($accepted_rejected_[$edit_key]) ? $accepted_rejected_[$edit_key] : false;
								
								if ( !$edit_accepted_rejected ) {
									unset($formatted_meta[$key]);
								}
							}
							
							 
							/**Just exclude the reject and accept action buttons when status is not 
							 * processing, pending or on-hold
							 */
							 
							if (strpos($meta->key, 'Status') !== false ) {
								unset($formatted_meta[$key]);
							}

						}

					} else {

						
						if ('false' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_account_page']) {

							if (is_account_page()) {
								foreach ( $formatted_meta as $key => $meta ) {
									if (strpos($meta->key, 'Edit Btn') !== false ) {
										unset($formatted_meta[$key]);
									}
								}
							}

						}  

						if ('false' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_thankyou_page']) {

							if (is_wc_endpoint_url( 'order-received' )) {
								foreach ( $formatted_meta as $key => $meta ) {

									if (strpos($meta->key, 'Edit Btn') !== false ) {
										unset($formatted_meta[$key]);
									}

								}
							}
						}

						if (is_admin()) {




							// // PIN: SN4
							// $accepted_rejected = false;
							// foreach ( $formatted_meta as $key => $meta ) {
							//  if ( $meta->key == 'accepted_rejected') {
									
							//      $accepted_rejected = true;
							//      break;
							//  } 
							// }



							// PIN:from here

							// example key: accepted_rejected-{{0}}
							$accepted_rejected = array();
							foreach ( $formatted_meta as $key => $meta ) {
								if ( strpos($meta->key, 'accepted_rejected') === 0 ) {
									// $index = substr($meta->key, strrpos($meta->key, '-'));
									 
									$parts = explode('-', $meta->key);
									$index = $parts[1];
 
									$accepted_rejected[$index] = true;
								} 
							}

							
							
							// Fill missing indices with false
							$max_index = empty($accepted_rejected) ? 0 : max(array_keys($accepted_rejected));
							for ($i = 0; $i <= $max_index; $i++) {
								if (!isset($accepted_rejected[$i])) {
									$accepted_rejected[$i] = false;
								}
							}
							
							// PIN: to here




							// foreach ( $formatted_meta as $key => $meta ) {

							//  // wc_get_logger()->info( 'Meta Key: ' . $meta->key, array( 'source' => 'file-uploader-for-woocommerce-in-loop' ) );
							//  // wc_get_logger()->info( 'Meta strpos( $meta->key, accepted_rejected ): ' . strpos($meta->key, 'accepted_rejected'), array( 'source' => 'file-uploader-for-woocommerce-in-loop' ) );
							//  // if (strpos($meta->key, 'Edit Btn') !== false ) {
							//  //  unset($formatted_meta[$key]);
							//  // }
							//  if ( !$accepted_rejected ) {
							//      if (strpos($meta->key, 'Edit Btn') !== false) {
							//          unset($formatted_meta[$key]);
							//      }
							//  }
							//  if (strpos($meta->key, 'Status') !== false ) {
							//      unset($formatted_meta[$key]);
							//  }
									
									
							// }
							// wc_get_logger()->info( 'Meta Key: ' . $meta->key, array( 'source' => 'file-uploader-for-woocommerce-in-loop' ) );    
						
							// Status : accept or reject
							// Edit Btn : Modify File or message if accepted or rejected
							// Uploaded File : Downlaod preview


 
							foreach ( $formatted_meta as $key => $meta ) {


 
 
								 /** Here it will work if the order status is 
								  * processing, 
								  * pending and on-hold
								  */
								

								/**If the file is accepted or rejected then 
								 * this key will contains the message.
								 * we are excluding it if accept or reject action is not performed yet
								 */
								if (strpos($meta->key, 'Edit Btn') !== false ) {

									// meta->key : Edit Btn 0

									$edit_accepted_rejected = false;

									//status_key : 0'
									$edit_key = explode(' ', $meta->key)[2];


									// echo "edit key :".$edit_key;
									
									$edit_accepted_rejected = isset($accepted_rejected[$edit_key]) ? $accepted_rejected[$edit_key] : false;
									
									
									
									if ( !$edit_accepted_rejected ) {
										 
										 
										unset($formatted_meta[$key]);
									}    
								}


								/** 
								 * The status field contains the accept and reject buttons, 
								 * so we are excluding it if the file is already accepted or rejected
								 */
								if (strpos($meta->key, 'Status') !== false ) {
									// PIN: SN Pausing 
									// echo "status key :".$meta->key;


									// meta->key : Status 0_684

									$status_accepted_rejected = false;

									//status_key : 0_684
									$status_key = explode(' ', $meta->key)[1];

									// status_key : 0
									$status_key = explode('_', $status_key)[0];

									// echo "status key :".$status_key;
									 
									$status_accepted_rejected = isset($accepted_rejected[$status_key]) ? $accepted_rejected[$status_key] : false;

									if ( $status_accepted_rejected ) {
										 
										unset($formatted_meta[$key]);
									}
								}
							}
 
						} 
					}
				}
			}

			if ( !is_admin() ) {
				foreach ( $formatted_meta as $key => $meta ) {

					if (strpos($meta->key, 'Status') !== false ) {
						unset($formatted_meta[$key]);
					}
				}
			}
			return $formatted_meta;
		}



		public function separate_numbers_and_check_key( $array, $string ) {
			// Separate numbers from the string
			$numbers = preg_replace('/[ ^0-9 ]/', '', $string);
			
			// Loop through the array keys
			foreach ($array as $key => $value) {
				// Check if the string starts with the key
				if (strpos($string, (string) $key) === 0) {
					return $key;
				}
			}
			
			// If no key is found at the beginning of the string, return null
			return null;
		}


		/**
		 * Get upload files rules
		 *
		 * This method retrieves the upload files rules from the database.
		 *
		 * @return array The array of upload files rules.
		 */
		// PIN: get rules 
		public function extendons_get_Upload_files_rules() {
			global $post;
			global $woocommerce;
			
			$extendons_upload_files_args = array(
				'post_type'=> 'extend_upload_files',
				'orderby'    => 'ID',
				'post_status' => 'publish',
				'order'    => 'ASC',
				'fields'    => 'ids',
				'posts_per_page' => -1, // this will retrive all the post that is published 
			);
			$extendons_upload_files_get_rules = new WP_Query( $extendons_upload_files_args );   
			$extendons_upload_Files_rules_array = array();
			foreach ($extendons_upload_files_get_rules->get_posts() as $key => $extendons_upload_files_postid) {
				$extendons_upload_files_get_status = get_post_meta($extendons_upload_files_postid, 'extendons_enable_disable_settings', true);
				$extendons_rule_priority =  get_post_meta($extendons_upload_files_postid, 'extendons_rule_priority', true);
				$extendons_upload_files_display_on_position =  get_post_meta($extendons_upload_files_postid, 'extendons_display_on_values', true);
				$extendons_selected_product_category = get_post_meta($extendons_upload_files_postid, 'extendons_selected_product_category', true); 
				$extendons_selected_product_item = get_post_meta($extendons_upload_files_postid, 'extendons_selected_items', true);
				$extendons_selected_user_roles = get_post_meta($extendons_upload_files_postid, 'extendons_selected_user_role', true); 
				$extendons_upload_files_multival_arr = get_post_meta($extendons_upload_files_postid , 'extendons_multiple_files_limit', true);
				$extendons_upload_files_rules = array(
					'extendons_post_id'=> $extendons_upload_files_postid,
					'extendons_upload_files_enable_disable_status' => $extendons_upload_files_get_status,
					'extendons_upload_files_rule_priority' => $extendons_rule_priority,
					'extendons_upload_files_display_on_position' => $extendons_upload_files_display_on_position,
					'extendons_upload_files_multiple_files_limit' => $extendons_upload_files_multival_arr,
					'extendons_upload_files_selected_pc_type' => $extendons_selected_product_category,
					'extendons_upload_files_selected_pc' => $extendons_selected_product_item,
					'extendons_upload_files_selected_user_role' => $extendons_selected_user_roles,
					
				);

				array_push($extendons_upload_Files_rules_array, $extendons_upload_files_rules);
			}

			// // PIN 
			// echo "<pre>";
			// print_r( $extendons_upload_Files_rules_array );
			// echo "</pre>";
			return $extendons_upload_Files_rules_array;
		}

		/**
		 * Set up plugin constants
		 *
		 * This method defines the necessary constants for the plugin.
		 */
		public function extendons_upload_files_module_constants() {
		
			if ( !defined( 'EXTENDONS_UF_URL' ) ) {
				// Define the URL of the plugin
				define( 'EXTENDONS_UF_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( !defined( 'EXTENDONS_UF_BASENAME' ) ) {
				// Define the basename of the plugin
				define( 'EXTENDONS_UF_BASENAME', plugin_basename( __FILE__ ) );
			}

			if ( ! defined( 'EXTENDONS_UF_PLUGIN_DIR' ) ) {
				// Define the directory path of the plugin
				define( 'EXTENDONS_UF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			
			}
			
			if ( ! defined( 'EXTENDONS_UF_PLUGIN_ADMIN_TEMPLATE_DIR' ) ) {
				 
				// Define the directory path for admin templates
				define( 'EXTENDONS_UF_PLUGIN_ADMIN_TEMPLATE_DIR', plugin_dir_path( __FILE__ ) . 'admin/view/template/' );
			}
			
			if ( ! defined( 'EXTENDONS_UF_PLUGIN_FRONT_TEMPLATE_DIR' ) ) {
				 
				// Define the directory path for front-end templates
				define( 'EXTENDONS_UF_PLUGIN_FRONT_TEMPLATE_DIR', plugin_dir_path( __FILE__ ) . 'front/templates/' );
			}
		}
	} 

	// Instantiate the plugin class
	new Extendons_Ext_Upload_Files();
	 
}


// add_action( 'woocommerce_thankyou', 'display_order_meta_on_thankyou_page', 20 );

// function display_order_meta_on_thankyou_page( $order_id ) {
//  if ( ! $order_id ) {
//      return;
//  }

//  $order = wc_get_order( $order_id );
//  if ( $order ) {
//      // echo '<h2>Order Meta Data</h2>';
//      // echo '<ul>';

//      // // Loop through all order meta data and display them
//      // foreach ( $order->get_meta_data() as $meta ) {
//      //     $meta_key = $meta->key;
//      //     $meta_value = $meta->value;
//      //     echo '<li><strong>' . esc_html( $meta_key ) . ':</strong> ' . esc_html( $meta_value ) . '</li>';
//      // }

//      // echo '</ul>';

//      // Print order item meta data
//      echo '<h2>Order Item Meta Data</h2>';
//      echo '<pre>';
//      foreach ( $order->get_items() as $item_id => $item ) {
//          $item_data = $item->get_meta_data();
//          if ( ! empty( $item_data ) ) {
//              echo 'Item ID: ' . esc_html( $item_id ) . "\n";
//              var_dump( $item_data );
//              echo "\n";
//          }
//      }
//      echo '</pre>';
		//  }
		// }
