<?php

/**
 * Front-end functionality for the File Uploader for WooCommerce plugin.
 *
 * This class handles the front-end functionality of the plugin, including
 * enqueuing scripts, adding cart item data, displaying order data, and
 * validating uploaded files during the add-to-cart process.
 *
 * @package Extendons\FileUploaderForWooCommerce
 * @since 1.0.0
 */


if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
if ( !class_exists( 'Extendons_Upload_Files_Front' ) ) { 

	/**
	 * Extendons Upload Files Front
	 *
	 * Handles the front-end functionality for the Extendons Upload Files plugin.
	 *
	 * @package Extendons/Upload-Files
	 */
	class Extendons_Upload_Files_Front extends Extendons_Ext_Upload_Files {
		// Array to store block names and their custom action hooks
		private $block_hooks = array(
			'woocommerce/cart',
			'woocommerce/filled-cart-block',
			'woocommerce/cart-items-block',
			'woocommerce/cart-line-items-block',
			'woocommerce/cart-cross-sells-block',
			'woocommerce/cart-cross-sells-products-block',
			'woocommerce/cart-totals-block',
			'woocommerce/cart-order-summary-block',
			'woocommerce/cart-order-summary-heading-block',
			'woocommerce/cart-order-summary-coupon-form-block',
			'woocommerce/cart-order-summary-subtotal-block',
			'woocommerce/cart-order-summary-fee-block',
			'woocommerce/cart-order-summary-discount-block',
			'woocommerce/cart-order-summary-shipping-block',
			'woocommerce/cart-order-summary-taxes-block',
			'woocommerce/cart-express-payment-block',
			'woocommerce/proceed-to-checkout-block',
			'woocommerce/cart-accepted-payment-methods-block',
			'woocommerce/checkout',
			'woocommerce/checkout-order-note-block',
		);

		public function __construct() {

			/**
			 * Add extendons upload files templates
			 *
			 * @since 1.0.0
			 */
			// add_action( 'wp', array( $this, 'extendons_Upload_Files_templates' ));

			/**
			 * Add extendons upload files templates
			 *
			 * @since 1.0.0
			 */
			add_action ( 'woocommerce_before_add_to_cart_form', array( $this, 'extendons_Upload_Files_templates' ) );

			/**
			 * Enqueue scripts for extendons upload files
			 *
			 * @since 1.0.0
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'extendons_upload_Files_scripts_front' ) );

			/**
			 * Add text to cart item data
			 *
			 * @since 1.0.0
			 * @param array $cart_item_data Cart item data
			 * @param int $product_id Product ID
			 * @param int $variation_id Variation ID
			 * @return array Cart item data
			 */
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'extendons_uploadfile_add_text_to_cart_item' ), 10, 3);

			/**
			 * Get cart item data in cart
			 *
			 * @since 1.0.0
			 * @param array $other_data Other data
			 * @param array $cart_item Cart item
			 * @return array Other data
			 * PIN: cart hook
			 */
			// PIN: get 
			add_filter( 'woocommerce_get_item_data', array( $this, 'extendons_uploadfile_get_cart_item_in_cart' ), 10, 2 );

			/**
			 * Add custom price to cart
			 *
			 * @since 1.0.0
			 * @param WC_Cart $cart Cart object
			 */
			add_action( 'woocommerce_before_calculate_totals', array( $this, 'extendons_upload_files_extra_price_add_custom_price' ), 20, 1 );

			/**
			 * Save cart item custom meta as order item meta
			 *
			 * @since 1.0.0
			 * @param int $item_id Order item ID
			 * @param array $values Values
			 * @param null $cart_item_key Cart item key
			 */
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_cart_item_custom_meta_as_order_item_meta' ), 11, 4 );

			/**
			 * Display order data on thank you page
			 *
			 * @since 1.0.0
			 * @param int $order_id Order ID
			 */
			add_action( 'woocommerce_thankyou', array( $this, 'extendons_upload_files_display_order_data' ), 20 );

			/**
			 * Display order data on view order page
			 *
			 * @since 1.0.0
			 * @param int $order_id Order ID
			 */
			add_action( 'woocommerce_view_order', array( $this, 'extendons_upload_files_display_order_data' ), 20 );

			/**
			 * Update order meta on checkout
			 *
			 * @since 1.0.0
			 * @param int $order_id Order ID
			 */
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'custom_payment_update_order_meta' ));

			/**
			 * Update order meta on WooCommerce Blocks checkout
			 *
			 * @since 1.0.0
			 * @param int $order_id Order ID
			 */
			add_action('woocommerce_blocks_checkout_order_processed', array( $this, 'custom_payment_update_order_meta' ));

			/**
			 * Add to cart template for loop
			 *
			 * @since 1.0.0
			 */
			add_action( 'woocommerce_loop_add_to_cart_link', array( $this, 'woocommerce_template_loop_add_to_cart' ) , 10, 2 );

			/**
			 * Validate add to cart
			 *
			 * @since 1.0.0
			 * @param bool $passed Whether validation passed
			 * @param int $product_id Product ID
			 * @param int $quantity Quantity
			 * @param int|null $variation_id Variation ID
			 * @return bool Whether validation passed
			 */
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'plugin_republic_add_to_cart_validation' ), 10, 4 );

			/**
			 * Unset specific order item meta data
			 *
			 * @since 1.0.0
			 * @param array $formatted_meta Formatted meta data
			 * @param WC_Order_Item_Product $order_item Order item
			 * @return array Formatted meta data
			 */
			add_filter( 'woocommerce_order_item_get_formatted_meta_data', array( $this, 'unset_specific_order_item_meta_data' ), 10, 2);

			/**
			 * Add upload files cart template
			 *
			 * @since 1.0.0
			 */
			add_action('woocommerce_after_cart_table', array( $this, 'extendons_upload_files_cart_template' ));

			/**
			 * Add upload files cart template on WooCommerce Blocks cart page
			 *
			 * @since 1.0.0
			 */
			add_action( 'bbloomer_after_woocommerce/cart', array( $this, 'extendons_upload_files_cart_template' ) );

			/**
			 * Add upload files cart template on WooCommerce Blocks checkout page
			 *
			 * @since 1.0.0
			 */
			add_action( 'bbloomer_after_woocommerce/checkout-order-note-block', array( $this, 'extendons_upload_files_cart_template' ) );

			/**
			 * Add upload files cart template on checkout page
			 *
			 * @since 1.0.0
			 */
			add_action( 'woocommerce_after_order_notes', array( $this, 'extendons_upload_files_cart_template' ) );

			/**
			 * Calculate fees for upload files in cart
			 *
			 * @since 1.0.0
			 * @param WC_Cart $cart Cart object
			 */
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'extendons_upload_files_cart_calculate_fees' ), 20, 1 );

			/**
			 * Add custom actions before and after the block content.
			 *
			 * @since 1.0.0
			 */
			add_filter( 'render_block', array( $this, 'add_custom_actions' ), 9999, 2 );
			
			/**
			 * Add shortcode for upload button
			 *
			 * @since 1.0.0
			 */
			add_shortcode('extendons_upload_button', array( $this, 'extendons_upload_button_shortcode' ));

			/**
			 * Add custom action to display customer details in email
			 *
			 * @since 1.0.0
			 * @param WC_Order $order Order object
			 * @param bool $sent_to_admin Whether the email is sent to admin
			 * @param bool $plain_text Whether the email is plain text
			 */
			add_action('woocommerce_email_customer_details', array( $this, 'extendons_add_upload_files_to_email' ), 10, 4);

			/**
			 * Add upload files data to thank you page
			 *
			 * @since 1.0.0
			 */
			add_action('woocommerce_thankyou', array( $this, 'extendons_upload_files_order_data' ), 20);

			/**
			 * Add upload files data to view order page
			 *
			 * @since 1.0.0
			 */
			add_action('woocommerce_view_order', array( $this, 'extendons_upload_files_order_data' ), 20);

			/**
			 * Handle guest session
			 *
			 * @since 1.0.0
			 */
			add_action('init', array( $this, 'handle_guest_session' ), 10);
		}

		/**
		 * Add custom actions before and after the block content.
		 *
		 * @since 1.0.0
		 * @param string $block_content The block content.
		 * @param array $block The block data.
		 * @return string Modified block content with custom actions.
		 */
		public function add_custom_actions( $block_content, $block ) {
			if ( in_array( $block['blockName'], $this->block_hooks ) ) {
				// Define allowed tags for wp_kses
				$allowed_tags = wp_kses_allowed_html( 'post' );
				$allowed_tags['input'] = array(
					'type' => true,
					'id' => true,
					'class' => true,
					'name' => true,
					'accept' => true,
					'multiple' => true,
					'data-rule-id' => true,
					'max-file-upload' => true,
					'type-file-format' => true,
					'file-size-allow-to-upload' => true,
				);

				ob_start();
				/**
				 * Execute custom action before the block content.
				 *
				 * @since 1.0.0
				 */
				do_action( 'bbloomer_before_' . $block['blockName'] );
				echo wp_kses( $block_content, $allowed_tags );
				/**
				 * Execute custom action after the block content.
				 *
				 * @since 1.0.0
				 */
				do_action( 'bbloomer_after_' . $block['blockName'] );
				$block_content = ob_get_contents();
				ob_end_clean();
			}
			return $block_content;
		}

		/**
		 * Cart fees calculation
		 * 
		 * @since 1.0.0
		 * @param WC_Cart $cart Cart object
		 */
		public function extendons_upload_files_cart_calculate_fees( $cart ) {
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
			$extendons_upload_file_valid = false;

			// Get products id from the cart
			$extendons_upload_file_product_ids = array();
			$extendons_upload_file_all_terms = array();

			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$product_id = $cart_item['product_id'];
				$extendons_upload_file_product_ids[] = $product_id;

				// Get all terms for this product
				$product_terms = get_the_terms( $product_id, 'product_cat' );
				if ( $product_terms && ! is_wp_error( $product_terms ) ) {
					foreach ( $product_terms as $term ) {
						$extendons_upload_file_all_terms[] = $term->term_id;
					}
				}
			}

			// Remove duplicate term IDs
			$extendons_upload_file_all_terms = array_unique( $extendons_upload_file_all_terms );

			foreach ( $extendons_upload_file_rules as $value ) {

				if ( 'extendons_upload_files_enable' == $value['extendons_upload_files_enable_disable_status'] ) {
					if (
						( isset( $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_cart_page_table'] ) && 'extendons_upload_file_after_cart_table' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_cart_page_table'] ) ||
						( isset( $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_checkout_page_after_notes_field'] ) && 'extendons_upload_file_after_checkout_page_after_notes_field' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_checkout_page_after_notes_field'] )
						) {
						$extendons_valid_array = array();

						if ( 'extendons_upload_files_product' == $value['extendons_upload_files_selected_pc_type'] ) {
							// Add all cart product IDs to valid array
							$extendons_valid_array = $extendons_upload_file_product_ids;
						} else if ( 'extendons_upload_files_category' == $value['extendons_upload_files_selected_pc_type'] ) {
							// Add all cart product category IDs to valid array
							$extendons_valid_array = $extendons_upload_file_all_terms;
						}

						if ( ! empty( $value['extendons_upload_files_selected_pc'] ) ) {
							// Check if any of the valid array IDs are in the selected product categories
							if ( array_intersect( $extendons_valid_array, $value['extendons_upload_files_selected_pc'] ) ) {
								$extendons_upload_file_valid = true;
							} else {
								$extendons_upload_file_valid = false;
							}
						} else {
							$extendons_upload_file_valid = true;
						}
						// If validation failed, continue to next rule
						if ( false == $extendons_upload_file_valid ) {
							continue;
						}

						$rule_id = $value['extendons_post_id'];             
						foreach ($value['extendons_upload_files_multiple_files_limit'] ?? array() as $key => $file_upload_rule_settings) {
							$session_key = 'uploaded_files_' . $rule_id . '_' . $key;
							$extendons_upload_files_session = WC()->session->get( $session_key );
							$uploaded_files = ! empty( $extendons_upload_files_session ) ? $extendons_upload_files_session : array();
							if ( !empty( $uploaded_files )) {
								$extendons_uploadfiles_price = $file_upload_rule_settings['extendons_uploadfiles_price'];
								$extendons_uploadfiles_discount_type = $file_upload_rule_settings['extendons_uploadfiles_discount_type'];
								$extendons_uploadfiles_discount_price = $file_upload_rule_settings['extendons_uploadfiles_discount_price'];
								$calculated_discounted_price = $this->calculateDiscountPrice(
									$extendons_uploadfiles_price,
									$extendons_uploadfiles_discount_price,
									$extendons_uploadfiles_discount_type
								);
								$fee_label = 'File Upload Fee (Total Uploads: ' . count( $uploaded_files ) . ')';
								if ( ! empty( $calculated_discounted_price ) ) {
									$fee_label .= ' (Discounted Price: ' . $calculated_discounted_price . ')';
								}
								$cart->add_fee(
									esc_html__( $fee_label, 'extendons_Upload_Files' ),
									$calculated_discounted_price,
									false
								);
							}
						}
					}
				}
			}
		}

		/**
		 * Add upload files data to email
		 *
		 * @since 1.0.0
		 */
		public function extendons_add_upload_files_to_email( $order, $sent_to_admin, $plain_text, $email ) {
			// Ensure $order is a WC_Order object
			$order = ( $order instanceof WP_Post ) ? wc_get_order($order->ID) : $order;

			// Get the upload files rules
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
			if (empty($extendons_upload_file_rules)) {
				return;
			}
			
			// Prepare data once
			$data = array();
			foreach ($extendons_upload_file_rules as $rule) {
				$rule_id = $rule['extendons_post_id'];
				$rule_data = array(
					'files' => array(),
					'notes' => array(),
				);

				foreach ($rule['extendons_upload_files_multiple_files_limit'] as $key => $value) {
					$label = !empty($value['extendons_uploadfiles_label']) ? $value['extendons_uploadfiles_label'] : 'No Label';
					$uploaded_files = $order->get_meta('extendons_upload_files_' . $rule_id . '_' . $key, true);
					$customer_note = $order->get_meta('extendons_customer_notes_' . $rule_id . '_' . $key, true);

					if (!empty($uploaded_files) || !empty($customer_note)) {
						// Files
						$files_html = '<div style="margin-bottom: 10px;"><strong>' . esc_html($label) . '</strong><br>';
						$files_text = $label . ":\n";
						if (!empty($uploaded_files)) {
							$files_html .= '<ul style="margin: 0; padding-left: 20px;">';
							$files_text .= ' - ';
							$file_list = array();
							foreach ($uploaded_files as $file) {
								$file_name = esc_html($file['name']);
								$file_url = esc_url($file['url']);
								$files_html .= '<li><a href="' . $file_url . '" target="_blank" style="color: #0073aa;">' . $file_name . '</a></li>';
								$file_list[] = $file_name . ' (' . $file_url . ')';
							}
							$files_html .= '</ul></div>';
							$files_text .= implode("\n - ", $file_list) . "\n";
						} else {
							$files_html .= esc_html__('No files uploaded', 'extendons_Upload_Files') . '</div>';
							$files_text .= "No files uploaded\n";
						}

						// Notes
						$notes_html = '<div style="margin-bottom: 10px;"><strong>' . esc_html($label) . '</strong><br>' . 
									( !empty($customer_note) ? esc_html($customer_note) : esc_html__('No note provided', 'extendons_Upload_Files') ) . '</div>';
						$notes_text = $label . ': ' . ( !empty($customer_note) ? $customer_note : 'No note provided' ) . "\n";

						$rule_data['files'][$label] = $files_html;
						$rule_data['notes'][$label] = $notes_html;
						$data['plain'][$label] = $files_text . $notes_text; // For plain text
					}
				}

				if (!empty($rule_data['files']) || !empty($rule_data['notes'])) {
					$data['rules'][] = $rule_data;
				}
			}

			if (empty($data['rules'])) {
				return;
			}

			// Render based on email type
			if (!$plain_text) {
				// HTML email
				echo '<h2>' . esc_html__('Upload Files and Notes', 'extendons_Upload_Files') . '</h2>';
				echo '<table cellpadding="5" cellspacing="0" style="width: 100%; border: 1px solid #eee; border-collapse: collapse;">';
				echo '<tr style="background-color: #f8f8f8;"><th style="border: 1px solid #eee; padding: 10px;">' . esc_html__('Uploaded Files', 'extendons_Upload_Files') . '</th><th style="border: 1px solid #eee; padding: 10px;">' . esc_html__('Customer Note', 'extendons_Upload_Files') . '</th></tr>';
				foreach ($data['rules'] as $rule) {
					echo '<tr>';
					echo '<td style="border: 1px solid #eee; padding: 10px;">' . wp_kses_post(implode('', $rule['files'])) . '</td>';
					echo '<td style="border: 1px solid #eee; padding: 10px;">' . wp_kses_post(implode('', $rule['notes'])) . '</td>';
					echo '</tr>';
				}
				echo '</table>';
			} else {
				// Plain text email
				echo "Upload Files and Notes:\n";
				foreach ($data['rules'] as $rule) {
					foreach ($data['plain'] as $plain_text_content) {
						echo esc_html( $plain_text_content );
					}
					echo "\n";
				}
			}
		}

		/**
		 * Handle guest session for users not logged in
		 *
		 * @since 1.0.0
		 */
		public function handle_guest_session() {
			if ( ! is_user_logged_in() && class_exists( 'WooCommerce' ) ) {
				if ( class_exists( 'WooCommerce' ) && WC()->session && ! WC()->session->has_session() ) {
					WC()->session->set_customer_session_cookie( true );
				}

				// Get or create a guest ID
				if ( WC()->session && WC()->session->has_session() ) {
					$guest_id = WC()->session->get( 'guest_id' );
				}
				if ( empty( $guest_id ) ) {
					$guest_id = wp_generate_uuid4();
					if ( WC()->session && WC()->session->has_session() ) {
						WC()->session->set( 'guest_id', $guest_id );
					}
				}
			}
		}

		/**
		 * Display uploaded files and notes on the order thank you and view order pages.
		 *
		 * @since 1.0.0
		 * @param int|WC_Order $order_id The order ID or WC_Order object.
		 */
		public function extendons_upload_files_order_data( $order_id ) {
			// Ensure $order is a WC_Order object
			$order = ( $order_id instanceof WC_Order ) ? $order_id : wc_get_order( $order_id );
			if ( ! $order ) {
				return;
			}

			// Get the upload files rules
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
			if ( empty( $extendons_upload_file_rules ) ) {
				return;
			}

			$data = array();
			foreach ( $extendons_upload_file_rules as $rule ) {
				$rule_id = $rule['extendons_post_id'];
				$rule_data = array(
					'files' => array(),
					'notes' => array(),
					'status' => array(),
				);

				if ( empty( $rule['extendons_upload_files_multiple_files_limit'] ) ) {
					continue;
				}

				foreach ( $rule['extendons_upload_files_multiple_files_limit'] as $key => $value ) {
					$label = ! empty( $value['extendons_uploadfiles_label'] ) ? $value['extendons_uploadfiles_label'] : 'No Label';
					$allow_file_modification = ! empty( $value['extendons_uploadfiles_file_allow_modification'] ) ? $value['extendons_uploadfiles_file_allow_modification'] : 'false';
					$uploaded_files = $order->get_meta( 'extendons_upload_files_' . $rule_id . '_' . $key, true );
					$customer_note = $order->get_meta( 'extendons_customer_notes_' . $rule_id . '_' . $key, true );
					$files_status = $order->get_meta( 'ext_upload_files_cart_level_status_' . $rule_id . '_' . $key , true );
					$allowed_extensions = ! empty( $value['extendons_uploadfiles_allwoed_extension'] ) ? explode( ',', $value['extendons_uploadfiles_allwoed_extension'] ) : array();
					// wc_get_logger()->debug( 'Uploaded files for rule ID ' . $rule_id . ': ' . print_r( $uploaded_files, true ) );
					if ( ! empty( $uploaded_files ) || ! empty( $customer_note ) ) {
						// Files
						$files_html = '<div style="margin-bottom: 10px;"><strong>' . esc_html( $label ) . '</strong><br>';
						if ( ! empty( $uploaded_files ) ) {
							$files_html .= '<ul style="margin: 0; padding-left: 20px;">';
							foreach ( $uploaded_files as $file_index => $file ) {
								$file_name = !empty( $file['name'] ) ? esc_html( $file['name'] ) : '';
								$file_url = !empty( $file['url'] ) ? esc_url( $file['url'] ) : '';
								if ( $file_name && $file_url ) {
									if (array_key_exists( 'file_id', $file ) && !empty( $file['file_id'] )) {
										$unique_file_id = $file['file_id'];
									}
									$files_html .= '<li style="margin-bottom: 8px;">
										<div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
											<a href="' . $file_url . '" target="_blank" style="color: #0073aa; text-decoration: none;">' . $file_name . '</a>
											<div style="display: flex; gap: 5px;">
												<button type="button" class="extendons-download-file" 
													data-file-url="' . $file_url . '" 
													data-file-name="' . $file_name . '">
													Download
												</button>';
												// Conditionally add the Change button if allow_file_modification is true
												$status = $files_status['status'] ?? '';
									if ( 'false' !== $allow_file_modification && ( empty($files_status) ||  in_array($status, array( 'Rejected', 'Pending' ), true) ) ) {
										$files_html .= '<button type="button" class="extendons-change-file" 
														data-order-id="' . $order->get_id() . '" 
														data-rule-id="' . $rule_id . '" 
														data-key="' . $key . '" 
														data-file-index="' . $file_index . '"
														data-file-id="' . ( $unique_file_id ?? '' ) . '"
														data-allowed-extensions="' . esc_attr( implode( ',', $allowed_extensions ) ) . '">
														Modify File
													</button>';
									}

										$files_html .= '</div>
											</div>
										</li>';
								}
							}
							$files_html .= '</ul></div>';
						} else {
							$files_html .= esc_html__( 'No files uploaded', 'extendons_Upload_Files' ) . '</div>';
						}

						// Notes
						$notes_html = '<div style="margin-bottom: 10px;"><strong>' . esc_html( $label ) . '</strong><br>' .
							( ! empty( $customer_note ) ? esc_html( $customer_note ) : esc_html__( 'No note provided', 'extendons_Upload_Files' ) ) . '</div>';

						$rule_data['files'][ $label ] = $files_html;
						$rule_data['notes'][ $label ] = $notes_html;
						$rule_data['action'][ $label ] = $files_status;
					}
				}

				if ( ! empty( $rule_data['files'] ) || ! empty( $rule_data['notes'] ) ) {
					$data['rules'][] = $rule_data;
				}
			}

			if ( empty( $data['rules'] ) ) {
				return;
			}

			// Output HTML for thank you/view order page
			echo '<h2>' . esc_html__( 'Upload Files and Notes', 'extendons_Upload_Files' ) . '</h2>';
			echo '<table cellpadding="5" cellspacing="0" style="width: 100%; border: 1px solid #eee; border-collapse: collapse;">';
			echo '<tr style="background-color: #f8f8f8;">
			<th style="border: 1px solid #eee; padding: 10px;">' . esc_html__( 'Uploaded Files', 'extendons_Upload_Files' ) . '</th>
			<th style="border: 1px solid #eee; padding: 10px;">' . esc_html__( 'Your Note', 'extendons_Upload_Files' ) . '</th>';
			if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'view-order' ) ) {
				echo '<th style="border: 1px solid #eee; padding: 10px;">' . esc_html__( 'Status', 'extendons_Upload_Files' ) . '</th>';
			}
			echo '</tr>';
			foreach ( $data['rules'] as $rule ) {
				echo '<tr>';
				echo '<td style="border: 1px solid #eee; padding: 10px;">' . wp_kses_post( implode( '', $rule['files'] ) ) . '</td>';
				echo '<td style="border: 1px solid #eee; padding: 10px;">' . wp_kses_post( implode( '', $rule['notes'] ) ) . '</td>';
				if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'view-order' ) ) {
					echo '<td style="border: 1px solid #eee; padding: 10px;">';
					foreach ( $rule['action'] as $label => $status ) {
						echo '<div style="margin-bottom: 10px;"><strong>' . esc_html( $label ) . '</strong><br>';
						$status_text = isset( $status['status'] ) ? esc_html( $status['status'] ) : esc_html__( 'Pending', 'extendons_Upload_Files' );
						echo esc_html( ucfirst($status_text) );
					}
					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</table>';
			require_once EXTENDONS_UF_PLUGIN_FRONT_TEMPLATE_DIR . 'extendons_thankyou_page_modal_change_file.php';
		}

		/**
		 * Validate the uploaded files on the add to cart action
		 *
		 * @since 1.0.0
		 * @param bool $passed Whether the validation passed or not
		 * @param int $product_id The ID of the product being added to the cart
		 * @param int $quantity The quantity of the product being added to the cart
		 * @param int|null $variation_id The ID of the variation being added to the cart, if applicable
		 * @return bool Whether the validation passed or not
		 */
		public function plugin_republic_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = null ) {

				global $post;
				$extendons_upload_file_product_id = $product_id;
				$extendons_upload_file_terms = get_the_terms ( $extendons_upload_file_product_id, 'product_cat' );
				$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
				$extendons_upload_file_user_role = wp_get_current_user()->roles;
				$required_array = array();  

			foreach ($extendons_upload_file_rules as $value) {

				$extendons_upload_file_valid = true;
				if ('extendons_upload_files_enable' == $value['extendons_upload_files_enable_disable_status']) {
					$product_page_flag = ( 'extendons_upload_files_product_page' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_product_page'] ) ? true : false;

					if ($product_page_flag) {
						$extendons_valid_array = array();
						if ('extendons_upload_files_product' == $value['extendons_upload_files_selected_pc_type']) {
							array_push($extendons_valid_array, $extendons_upload_file_product_id);
						} else if ('extendons_upload_files_category' ==$value['extendons_upload_files_selected_pc_type']) {
							foreach ($extendons_upload_file_terms as $key => $term) {
								array_push($extendons_valid_array, $term->term_id);
							}
						}

						if (!empty($value['extendons_upload_files_selected_pc'])) {
							// Check if cart items match selected products/categories
							if (!array_intersect($extendons_valid_array, $value['extendons_upload_files_selected_pc'])) {
								$extendons_upload_file_valid = false;
							}
						}
						// Check user role restrictions only if product/category check passed or was not applicable
						if ($extendons_upload_file_valid && !empty($value['extendons_upload_files_selected_user_role'])) {
							$extendons_upload_file_valid = !empty(array_intersect($extendons_upload_file_user_role, $value['extendons_upload_files_selected_user_role']));
						}
						// If validation failed, skip to next rule
						if (!$extendons_upload_file_valid) {
							continue;
						}
						foreach ($value['extendons_upload_files_multiple_files_limit'] as $keys => $values) {

							if ('' != $values['extendons_uploadfiles_file_text_btn'] && isset($values['extendons_uploadfiles_file_text_btn'])) {
								$extendons_file_upload_btn_text = $values['extendons_uploadfiles_file_text_btn'];
							} else {
								$extendons_file_upload_btn_text = esc_html__('Upload File', 'extendons_Upload_Files');
							}

							$array[$keys] = array(

								'required' =>$values['extendons_uploadfiles_file_required'],
								'product_id'=> $extendons_upload_file_product_id,
								'key' => $keys,
								'post_id' => $value['extendons_post_id'],
								'btn_text' => $extendons_file_upload_btn_text,
							);  
						}
						array_push($required_array, $array);
					}
				}
			}

			foreach ($required_array as $keys => $data) {
			
				foreach ($data as $key => $value) {
					$extendons_valid =false;
					if ('true'==$value['required']) {

						$extendons_post_id = $value['post_id'];
						$product_id = $value['product_id'];
						$keyval = $value['key'];
						$guest_id = WC()->session->get( 'guest_id' );
						$guest_id = $guest_id . '_' . $value['post_id'] . $value['product_id'] . $value['key'];
						if (!is_user_logged_in()) {
							$extendons_session_value = get_option($guest_id);
						} else {
							$extendons_session_value = WC()->session->get( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $keyval);
						}
						if (empty($extendons_session_value['uploaded_file'])) {

							$extendons_valid = true;

						} else if ('0' == $extendons_session_value['extendons_total_upload_files']) {

							$extendons_valid = true;
						}

						if ($extendons_valid) {
							wc_add_notice(  $value['btn_text'] . ' is a required field.', 'error' );
							$passed = false;
						} 
					}
				}
			}
				return $passed; 
		}

		/**
		 * Add "Add to Cart" button in WooCommerce loop
		 *
		 * @since 1.0.0
		 * @param void
		 * @return void
		 */
		public function woocommerce_template_loop_add_to_cart( $link, $product ) {

			$extendons_upload_file_product_id = $product->get_id();
			$extendons_upload_file_terms = get_the_terms($extendons_upload_file_product_id, 'product_cat');
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
			$extendons_upload_file_user_role = wp_get_current_user()->roles;
			$extendons_valid_array = array();

			if ( $product->is_type('variable') || $product->is_type('grouped') ) { 
				return $link; // Return original link if product is variable or grouped
			}
			
			foreach ($extendons_upload_file_rules as $value) {
				$extendons_upload_file_valid = true;

				if ('extendons_upload_files_enable' == $value['extendons_upload_files_enable_disable_status']) {
					$extendons_upload_files_array = array();

					if ('extendons_upload_files_product_page' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_product_page']) {
						if ('extendons_upload_files_product' == $value['extendons_upload_files_selected_pc_type']) {
							array_push($extendons_valid_array, $extendons_upload_file_product_id);
						} else if ('extendons_upload_files_category' == $value['extendons_upload_files_selected_pc_type']) {
							if ($extendons_upload_file_terms) {
								foreach ($extendons_upload_file_terms as $term) {
									array_push($extendons_valid_array, $term->term_id);
								}
							}
						}

						if (!empty($value['extendons_upload_files_selected_pc'])) {
							if (!array_intersect($extendons_valid_array, $value['extendons_upload_files_selected_pc'])) {
								$extendons_upload_file_valid = false;
							} elseif (!is_single()) {
								return $link; // Return original link if not on single product page
							}
						}

						if ($extendons_upload_file_valid && !empty($value['extendons_upload_files_selected_user_role'])) {
							$extendons_upload_file_valid = !empty(array_intersect($extendons_upload_file_user_role, $value['extendons_upload_files_selected_user_role']));
						}

						if (true == $extendons_upload_file_valid) {
							return '<div style="margin-bottom:10px;text-align: center;">
							<a class="button custom-button" href="' . esc_attr( $product->get_permalink() ) . '">' . esc_html__('View product', 'extendons_Upload_Files') . '</a>
							</div>';
						}
					}
				}
			}
			return $link; // Return original link if validation fails
		}

		/**
		 * Update the order meta with the uploaded file data.
		 *
		 * @since 1.0.0
		 *
		 * @param int $order_id The order ID.
		 *
		 * @return void
		 */
		public function custom_payment_update_order_meta( $order_id ) {
			global $woocommerce;
			$extendons_upload_file_user_role = wp_get_current_user()->roles;
			$cart_data = WC()->cart->get_cart();

			if (!empty($cart_data)) {

				// update_post_meta($order_id, 'extendons_cart_order_data', $cart_data);
				
				$order = wc_get_order($order_id);
				$order->update_meta_data('extendons_cart_order_data', $cart_data);
				$order->save();

				$extendons_total_upload_file_array = array();
				$extendons_total = 0;
				foreach ($cart_data as $key => $values) {
					
					if (!empty($values['extendons_upload_file_data'])) {

						foreach ($values['extendons_upload_file_data'] as $keys => $cartitem) {

							foreach ($cartitem as $key => $data_item) {

								if (!empty( $data_item['uploaded_file'] ) ) {
									array_push($extendons_total_upload_file_array, $data_item['uploaded_file']);
								}
							}
						}
					}
				}
				

				if (!empty($extendons_total_upload_file_array)) {
					$extendons_total = 0;
					foreach ($extendons_total_upload_file_array as $key => $value) {
						
						foreach ($value as $key => $values) {
							
							$extendons_total++; 
						}
					}

				}
 
				$order = wc_get_order($order_id);
				// update_post_meta($order_id, 'extendons_total', $extendons_total);

				$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();

				foreach ( $extendons_upload_file_rules as $value ) {
					if ( 'extendons_upload_files_enable' !== $value['extendons_upload_files_enable_disable_status'] ) {
						continue;
					}

					$rule_id = $value['extendons_post_id'];
					$display_position = $value['extendons_upload_files_display_on_position'][0] ?? array();

					$checkout_page_flag = (
						!empty( $display_position['extendons_allow_uf_after_checkout_page_after_notes_field'] ) &&
						'extendons_upload_file_after_checkout_page_after_notes_field' === $display_position['extendons_allow_uf_after_checkout_page_after_notes_field']
					) || (
						!empty( $display_position['extendons_allow_uf_checkout_page'] ) &&
						'extendons_upload_files_checkout_page_after_notes' === $display_position['extendons_allow_uf_checkout_page']
					);

					foreach ( $value['extendons_upload_files_multiple_files_limit'] as $key => $file_upload_rule_settings ) {
						$notes_session_key = 'customer_notes_' . $rule_id . '_' . $key;
						$session_key       = 'uploaded_files_' . $rule_id . '_' . $key;

						$customer_notes = WC()->session->get( $notes_session_key, '' );
						$uploaded_files = WC()->session->get( $session_key, array() );

						// Determine if this rule applies to current user or all
						$selected_roles                = $value['extendons_upload_files_selected_user_role'] ?? array();
						$extendons_upload_files_user_role = empty( $selected_roles ) || !empty( array_intersect( $extendons_upload_file_user_role, $selected_roles ) );

						// If required, visible on checkout, applies to user, and files not uploaded
						$is_required = isset( $file_upload_rule_settings['extendons_uploadfiles_file_required'] ) &&
								'true' === $file_upload_rule_settings['extendons_uploadfiles_file_required'];

						if ( $is_required && $checkout_page_flag && $extendons_upload_files_user_role && empty( $uploaded_files ) ) {
							wc_add_notice(
								esc_html__( 'Please upload the required files for ' . $file_upload_rule_settings['extendons_uploadfiles_file_text_btn'], 'extendons_Upload_Files' ),
								'error'
							);

							$uploaded_files = array();
							$customer_notes = '';
							continue;
						}

						// Save uploaded files
						if ( !empty( $uploaded_files ) ) {
							$order->update_meta_data( 'extendons_upload_files_' . $rule_id . '_' . $key, $uploaded_files );
						}

						// Save customer notes
						if ( !empty( $customer_notes ) ) {
							$order->update_meta_data( 'extendons_customer_notes_' . $rule_id . '_' . $key, $customer_notes );
						}

						// Clear sessions
						WC()->session->__unset( $notes_session_key );
						if ( WC()->session && WC()->session->has_session() && !empty( $uploaded_files ) ) {
							WC()->session->__unset( $session_key );
						}
					}
				}
				
				$order->update_meta_data('extendons_total', $extendons_total);
				$order->save(); 
			}
		}

		/**
		 * Enqueue scripts and styles for the front-end.
		 *
		 * @since 1.0.0
		 */
		public function extendons_upload_Files_scripts_front() {
			wp_enqueue_script('jquery');
			wp_enqueue_style( 'extendons_front_css', plugins_url( 'assets/css/Upload_Files_template.css', __FILE__ ), false , 1.1 );
			wp_enqueue_script(  'extendons_upload_file_front_js', plugins_url( 'assets/js/extendons_front_upload_file.js', __FILE__ ), false, 1.2);
			wp_enqueue_script( 'extendons_upload_file_cart_js', plugins_url( 'assets/js/extendons_front_upload_file_cart.js', __FILE__ ), false, 1.2 );
			$extendons_ewcpm_data = array(
				'admin_url' => admin_url('admin-ajax.php'),
				'ext_fu_requestID' => wp_create_nonce('ext_fu_ajax_nonce'),
				'nonce' => wp_create_nonce('extendons_file_operations'),
			);
			wp_localize_script('extendons_upload_file_front_js', 'ewcpm_php_vars', $extendons_ewcpm_data);
			wp_localize_script('extendons_upload_file_front_js', 'ajax_url_add_pq', array( 'ajax_url_add_pq_data' => admin_url('admin-ajax.php') ));
		}

		/**
		 * Display order data for uploaded files.
		 *
		 * @since 1.0.0
		 *
		 * @param int $extendons_upload_files_order_id The order ID.
		 *
		 * @return void
		 */
		public function extendons_upload_files_display_order_data( $extendons_upload_files_order_id ) {
			require_once EXTENDONS_UF_PLUGIN_FRONT_TEMPLATE_DIR . 'extendons_upload_files_display_order_data.php';
		}
	 
		// PIN: 2 
		/**
		 * Save the uploaded file data as order item meta.
		 *
		 * @since 1.0.0
		 *
		 * @param int $item_id The order item ID.
		 * @param array $values The order item values.
		 * @param string $cart_item_key The cart item key.
		 *
		 * @return void
		 */
		public function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {
			$extendons_upload_file_product_page_rule = $this->extendons_get_Upload_files_rules();
			
			if ( isset( $values['extendons_upload_file_data'] ) && ! empty( $values['extendons_upload_file_data'] ) ) {
				$extendons_keys = 0;

				foreach ( $extendons_upload_file_product_page_rule as $key => $extendons_rule ) {
					if ( 'extendons_upload_files_enable' === $extendons_rule['extendons_upload_files_enable_disable_status'] ) {
						if ( ! empty( $values['extendons_upload_file_data'] ) ) {
							$extendons_keys = 0;

							foreach ( $values['extendons_upload_file_data'] as $rule_id => $cartitem ) {
								foreach ( $cartitem as $btn_id => $data_item ) {
									$extendons_order_html = '';
									$extendons_order_htmls = '';
									$order_data = array();

									if ( ! empty( $data_item['uploaded_file'] ) ) {
										$Uploaded_meta_key = 'Uploaded File ' . $extendons_keys;
										$Uploaded_filestatus_key = 'Status ' . $extendons_keys . '_' . $item->get_id();
										$Uploaded_ebtn_key = 'Edit Btn ' . $extendons_keys;
										$customer_nmeta_key = 'Customer Note' . $extendons_keys;

										foreach ( $data_item['uploaded_file'] as $key1 => $value ) {
											$extendons_upload_files_val = '';
											$extendons_upload_btn_val = '';
											$extendons_upload_filename_pathx = filter_var( $value['extendons_upload_files_cartfile_url'] );

											$ext = pathinfo( $value['extendons_upload_files_cartfilename'], PATHINFO_EXTENSION );
											if ( in_array( $value['extendons_upload_files_carttype'], array( 'image/jpg', 'image/png', 'image/jpeg', 'image/svg', 'image/gif' ), true ) ) {
												$extendons_upload_files_val .= '<a href="' . esc_url( $extendons_upload_filename_pathx ) . '" class="extendons_thankyou_page1" target="_blank" typee="application/' . $ext . '">' . esc_html__( 'Preview Image', 'extendons_Upload_Files' ) . '</a><a href="' . esc_url( $extendons_upload_filename_pathx ) . '" download><button style="padding:7px;border:none;cursor:pointer;margin-left:5px;" class="btn extendons_download data-extendons-download" id="data-extendons-download"><i class="fa fa-download"></i> Download</button></a>';
											} else {
												$extendons_upload_files_val .= '<a href="' . esc_url( $extendons_upload_filename_pathx ) . '" class="extendons_thankyou_page1" target="_blank" typee="application/' . $ext . '">' . esc_html__( 'Preview', 'extendons_Upload_Files' ) . '</a><a href="' . esc_url( $extendons_upload_filename_pathx ) . '" download><button style="padding:7px;border:none;cursor:pointer;margin-left:5px;" class="btn extendons_download data-extendons-download" id="data-extendons-download"><i class="fa fa-download"></i> Download</button></a>';
											}

											array_push( $order_data, $extendons_upload_files_val );
										}

										$extendons_order_html .= '<table class="woocommerce-table data-order-data-extendons"><tbody>';
										foreach ( $order_data as $key123 => $value123 ) {
											$extendons_order_html .= '<tr class="extendons-order-item"><td class="extendons_order_data">' . $value123 . '</td></tr>';
										}
										$extendons_order_html .= '</tbody></table>';

										// Add meta using WC_Order_Item
										$item->add_meta_data( $Uploaded_meta_key, $extendons_order_html, true );

										if ( 'true' === ( $data_item['extendons_uploadfiles_file_allow_modification'] ?? 'false' ) || !isset( $data_item['extendons_uploadfiles_file_allow_modification'] ) ) {
											$extendons_upload_btn_val = '<button data-meta-key = "' . $Uploaded_filestatus_key . '" data-item-id="' . $item->get_id() . '" data-rule-id="' . $rule_id . '" data-btn-id="' . $btn_id . '" type="button" data-p-id="' . $values['product_id'] . '" data-cart-key="' . $values['key'] . '" style="padding:7px;border:none;cursor:pointer;background-color:white;color:white;" data-id="' . $data_item['rule_id'] . '" data-page_type="data-thankyou-page" class="Click-here extendons_upload_file_order_data">' . esc_html__( 'Modify File', 'extendons_Upload_Files' ) . '</button>';
											$item->add_meta_data( $Uploaded_ebtn_key, $extendons_upload_btn_val, true );
										}

										$extendons_upload_files_vals = '<button type="button" data-modify-key = "' . $Uploaded_ebtn_key . '" data-btn-id="' . $btn_id . '" data-key="' . $Uploaded_filestatus_key . '" data-product-id="' . $values['product_id'] . '" data-attr="' . $item->get_id() . '" class="button button-primary" id="extendons_accept_btn" style="margin-right:10px;" data-rule-id="' . $data_item[ 'rule_id' ] . '" value="' . $extendons_keys . '">' . esc_html__( 'Accept', 'extendons_Upload_Files' ) . '</button><button type="button" data-btn-id="' . $btn_id . '" data-key="' . $Uploaded_filestatus_key . '" data-product-id="' . $values['product_id'] . '" data-attr="' . $item->get_id() . '" class="button button-secondary" id="extendons_reject_btn" data-rule-id="' . $data_item[ 'rule_id' ] . '" value="' . $extendons_keys . '">' . esc_html__( 'Reject', 'extendons_Upload_Files' ) . '</button>';

										$extendons_order_htmls .= '<ul class="data-order-dataar-extendons">';
										$extendons_order_htmls .= '<li class="extendons_order_data" style="display:flex;">' . ( $extendons_upload_files_vals ?? '' ) . '</li>';
										$extendons_order_htmls .= '</ul>';

										$item->add_meta_data( $Uploaded_filestatus_key, $extendons_order_htmls, true );

										if ( ! empty( $data_item['extendons_customer_note'] ) ) {
											$item->add_meta_data( $customer_nmeta_key, $data_item['extendons_customer_note'], true );
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
		 * Add custom price to cart
		 *
		 * @since 1.0.0
		 * @param WC_Cart $cart Cart object
		 */
		public function extendons_upload_files_extra_price_add_custom_price( $cart ) {
			if (is_admin() && !defined('DOING_AJAX')) {
				return;
			}
			if (did_action('woocommerce_before_calculate_totals') >= 2) {
				return;
			}

			foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
				$product        = $cart_item['data'];
				$quantity       = $cart_item['quantity'];
				$base_price     = $product->get_price();
				$total_discount = 0.0;

				// Handle uploaded file prices
				if (!empty($cart_item['extendons_upload_file_data'])) {
					foreach ($cart_item['extendons_upload_file_data'] as $rule) {
						foreach ($rule as $upload_data) {
							$file_count  = isset($upload_data['uploaded_file']) ? count($upload_data['uploaded_file']) : 0;
							$extra_price = isset($upload_data['extendons_uploadfiles_price']) ? floatval($upload_data['extendons_uploadfiles_price']) : 0;
							$discount    = isset($upload_data['extendons_uploadfiles_discount_price']) ? floatval($upload_data['extendons_uploadfiles_discount_price']) : 0;
							$discount_type = isset($upload_data['extendons_uploadfiles_discount_type']) ? $upload_data['extendons_uploadfiles_discount_type'] : '';

							if ($file_count && $extra_price) {
								if ('extendons_upload_files_percentage' === $discount_type) {
									$extra_price -= ( $extra_price * $discount ) / 100;
								} elseif ('extendons_upload_files_fixed' === $discount_type) {
									$extra_price -= $discount;
								}
								$total_discount += max(0, $extra_price); // don't allow negative
							}
						}
					}
				}

				// Calculate subtotal and apply extra charge per quantity
				$adjusted_price = $base_price + $total_discount;
				$product->set_price($adjusted_price);
			}
		}

		/**
		 * Display the upload file button and form on the product page.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */

		// PIN:before
		public function extendons_Upload_Files_templates() {
			if (is_single()) {
				echo do_shortcode( '[extendons_upload_button]' );
			}
		}

		public function extendons_upload_files_cart_template() {
			if ( ( is_cart() || is_checkout() ) || ( is_page() && ( has_block( 'woocommerce/cart' ) || has_block( 'woocommerce/checkout' ) ) ) ) {
				/**
				* This action is used to display the upload files section after the cart table.
				* 
				* @hooked bbloomer_after_woocommerce_cart - 10
				* @since 1.0.0
				*/
				do_action( 'bbloomer_after_woocommerce_cart' );
			}
				
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
			$extendons_upload_file_user_role = wp_get_current_user()->roles;
				
			// Get products id from the cart
			$extendons_upload_file_product_ids = array();
			$extendons_upload_file_all_terms = array();
			if ( ! WC()->cart || ! WC()->cart->get_cart() ) {
				return;
			}
			foreach (WC()->cart->get_cart() as $cart_item) {
				$product_id = $cart_item['product_id'];
				$extendons_upload_file_product_ids[] = $product_id;
				
				// Get all terms for this product
				$product_terms = get_the_terms($product_id, 'product_cat');
				if ($product_terms && !is_wp_error($product_terms)) {
					foreach ($product_terms as $term) {
						$extendons_upload_file_all_terms[] = $term->term_id;
					}
				}
			}
				
				// Remove duplicate term IDs
			$extendons_upload_file_all_terms = array_unique($extendons_upload_file_all_terms);
				
			foreach ($extendons_upload_file_rules as $value) {
				$extendons_upload_file_valid = true;
				if ('extendons_upload_files_enable' == $value['extendons_upload_files_enable_disable_status']) {
					// Check if this rule applies to cart/checkout pages
					if (
						( isset( $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_cart_page_table'] ) && ( 'extendons_upload_file_after_cart_table' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_cart_page_table'] && is_cart() ) ) ||
						( isset( $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_checkout_page_after_notes_field'] ) && ( 'extendons_upload_file_after_checkout_page_after_notes_field' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_after_checkout_page_after_notes_field'] && is_checkout() ) )
					) {
						$extendons_valid_array = array();
							
						if ('extendons_upload_files_product' == $value['extendons_upload_files_selected_pc_type']) {
							// Add all cart product IDs to valid array
							$extendons_valid_array = $extendons_upload_file_product_ids;
						} else if ('extendons_upload_files_category' == $value['extendons_upload_files_selected_pc_type']) {
							// Add all cart product category IDs to valid array
							$extendons_valid_array = $extendons_upload_file_all_terms;
						}
						if (!empty($value['extendons_upload_files_selected_pc'])) {
							// Check if cart items match selected products/categories
							if (!array_intersect($extendons_valid_array, $value['extendons_upload_files_selected_pc'])) {
								$extendons_upload_file_valid = false;
							}
						}
						// Check user role restrictions only if product/category check passed or was not applicable
						if ($extendons_upload_file_valid && !empty($value['extendons_upload_files_selected_user_role'])) {
							$extendons_upload_file_valid = !empty(array_intersect($extendons_upload_file_user_role, $value['extendons_upload_files_selected_user_role']));
						}
						// If validation failed, skip to next rule
						if (!$extendons_upload_file_valid) {
							continue;
						}

						foreach ($value['extendons_upload_files_multiple_files_limit'] as $key => $values ) {
							$extendons_uploadfiles_price = isset($values['extendons_uploadfiles_price']) ? $values['extendons_uploadfiles_price'] : '';
							$extendons_uploadfiles_discount_type = isset($values['extendons_uploadfiles_discount_type']) ? $values['extendons_uploadfiles_discount_type'] : '';
							$extendons_uploadfiles_discount_price = isset($values['extendons_uploadfiles_discount_price']) ? $values['extendons_uploadfiles_discount_price'] : '';
							$calculated_discounted_price = $this->calculateDiscountPrice(
								$extendons_uploadfiles_price,
								$extendons_uploadfiles_discount_price,
								$extendons_uploadfiles_discount_type
							);
							require EXTENDONS_UF_PLUGIN_FRONT_TEMPLATE_DIR . 'extendons_template_html_cart_page_upload.php';
						}
					}
				}
			}
		}


		/**
		 * Get the uploaded file data in the cart.
		 *
		 * @since 1.0.0
		 * 
		 * @param array $extendons_upload_file_item_data The cart item data.
		 * @param array $extendons_upload_file_cart_item The cart item.
		 *
		 * @return array The updated cart item data.
		 */
		public function extendons_uploadfile_get_cart_item_in_cart( $extendons_upload_file_item_data, $extendons_upload_file_cart_item ) {

			// echo "<pre>";
			// var_dump(  $extendons_upload_file_cart_item );
			// echo '</pre>';
			$extendons_upload_file_product_page_rule  = $this->extendons_get_Upload_files_rules();
			// die();
			// //WORKING
			// print_r( $extendons_upload_file_cart_item['extendons_upload_file_data'], true );

			if (!empty($extendons_upload_file_cart_item['extendons_upload_file_data']) && isset($extendons_upload_file_cart_item['extendons_upload_file_data'])) {
				
		 
				foreach ($extendons_upload_file_cart_item['extendons_upload_file_data'] as $keys => $values) {
	
					foreach ($values as $key=> $value) {
						 
						$extendons_true_page = false;
					
						if ('extendons_upload_files_cart_page' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_cart_page'] && ( is_cart() || is_page() && has_block( 'woocommerce/cart' ) ) ) {
							$extendons_true_page = true;
						}

						if ('extendons_upload_files_checkout_page_after_notes' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_checkout_page'] && ( is_checkout() || is_page() && has_block( 'woocommerce/checkout' ) ) ) {
							$extendons_true_page = true;
						}

						if ($extendons_true_page) {

							if ('' != $value['extendons_file_upload_label'] && isset($value['extendons_file_upload_label'])) {
								$extendons_file_upload_label = $value['extendons_file_upload_label'];
							}

							if ('' != $value['extendons_file_upload_btn_text'] && isset($value['extendons_file_upload_btn_text'])) {
								$extendons_file_upload_btn_text = $value['extendons_file_upload_btn_text'];
							} else {
								$extendons_file_upload_btn_text = 'Upload File';
							}

							if (isset($value['max_upload_size']) && '' != $value['max_upload_size']) {
								$max_upload_size = filter_var($value['max_upload_size']);
							} else {
								$max_upload_size = '1';
							}

							if ('' != $value['extendons_maximum_upload_files'] && isset($value['extendons_maximum_upload_files'])) {
								$extendons_file_upload_max_upload_file = $value['extendons_maximum_upload_files'];
							} else {
								$extendons_file_upload_max_upload_file = '1';
							}

							 
							// $upload_count_file = $value['extendons_total_upload_files'];
							$upload_count_file = isset($value['uploaded_file']) ? count( $value['uploaded_file'] ) : 0;
							$total_allow_upload = $value['extendons_maximum_upload_files'];

							$btnbackgroundcolor = 'background-color:' . $value['extendons_btn_background_color'] . ';';
							$btntextcolor = 'color:' . $value['extendons_btn_text_color'] . ';';

							if ( ( is_cart() || is_checkout() ) || ( is_page() && ( has_block( 'woocommerce/cart' ) || has_block( 'woocommerce/checkout' ) ) ) ) {
								require EXTENDONS_UF_PLUGIN_FRONT_TEMPLATE_DIR . 'extendons_uploadfile_get_cart_item_in_cart.php';
							}
							 
						} else if ('extendons_upload_files_product_page' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_product_page']) {
							// If the position is not cart or checkout, we can skip this part
							if ( isset( $value['uploaded_file'] ) && is_array( $value['uploaded_file'] ) ) {
								foreach ( $value['uploaded_file'] as $data_value ) {
									$extendons_upload_file_item_data [] = array(
										'name' => 'Attachments',
										'value' => '<a href="' . esc_url( $data_value['extendons_upload_files_cartfile_url'] ) . '" target="_blank" typee="application/' . $data_value['extendons_upload_files_carttype'] . '">' . esc_html( $data_value['extendons_upload_files_cartfilename'] ) . '</a>',
									);
								}
							}
						}
					}
				}
				if ( ( is_cart() || is_checkout() ) || ( is_page() && ( has_block( 'woocommerce/cart' ) || has_block( 'woocommerce/checkout' ) ) ) ) {
					echo '<span class="extendons_modal_upload_cart_file"></span>';
				}
				
			}

				  
			
			// $logger = wc_get_logger();
			// $logger->info( 'Upload File Rules: ' . wc_print_r( $extendons_upload_file_item_data, true ), array( 'source' => 'extendons_uploadfile_get_cart_item_in_cart' ) );
 

		

			//      echo " value in cart";
			// echo "<pre>";
			// print_r($extendons_upload_file_item_data);
			// echo "</pre>";
			return $extendons_upload_file_item_data;
		}

		/**
		 * Add the uploaded file data to the cart item.
		 *
		 * @since 1.0.0
		 *
		 * @param array $extendons_upload_file_cart_item_data The cart item data.
		 * @param int $product_id The product ID.
		 * @param int $variation_id The variation ID.
		 *
		 * @return array The updated cart item data.
		 */
		public function extendons_uploadfile_add_text_to_cart_item( $extendons_upload_file_cart_item_data, $product_id, $variation_id ) {

			$time = strtotime('now');
			$extendons_upload_file_product_page_rule  = $this->extendons_get_Upload_files_rules();
			$extendons_upload_file_product_id = $product_id;
			$extendons_upload_file_terms = get_the_terms ( $extendons_upload_file_product_id, 'product_cat' );
			$extendons_upload_file_category_id = $extendons_upload_file_terms[0]->term_id;
			$extendons_upload_file_category_parent_id = $extendons_upload_file_terms[0]->parent;
			$extendons_upload_file_user_role = wp_get_current_user()->roles;
			$extendons_upload_file_valid = false;
			$extendons_uploadfiles_product = wc_get_product( $product_id );
			$product_type = $extendons_uploadfiles_product->get_type();
		  

			 
			if ('simple'==$product_type) {
				$check_product_id = $product_id;
			} else if ( 'variable'==$product_type ) {
				$check_product_id = $variation_id;
				$check_product_id = wp_get_post_parent_id( $check_product_id );
			}
			$extendons_upload_files_product = array();
			$session_main_array = array();
			foreach ($extendons_upload_file_product_page_rule as $key => $extendons_rule) {         

				if ('extendons_upload_files_enable' == $extendons_rule['extendons_upload_files_enable_disable_status']) {

					if ('extendons_upload_files_product' == $extendons_rule['extendons_upload_files_selected_pc_type']) {
						
						if ( isset( $check_product_id ) ) {
							array_push($extendons_upload_files_product, $check_product_id);
						}
					} else if ('extendons_upload_files_category' ==$extendons_rule['extendons_upload_files_selected_pc_type']) {
						foreach ($extendons_upload_file_terms as $key => $term) {
							array_push( $extendons_upload_files_product, $term->term_id);
						}
					} 
					if (!empty($extendons_rule['extendons_upload_files_selected_pc'])) {

						if (array_intersect($extendons_upload_files_product, $extendons_rule['extendons_upload_files_selected_pc'])) {
							
							if (!empty($extendons_rule['extendons_upload_files_selected_user_role'])) {
								if (!empty(array_intersect($extendons_upload_file_user_role, $extendons_rule['extendons_upload_files_selected_user_role']))) {
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
					} elseif (!empty($extendons_rule['extendons_upload_files_selected_user_role'])) {
						if (!empty(array_intersect($extendons_upload_file_user_role, $extendons_rule['extendons_upload_files_selected_user_role']))) {
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
					

					if ('extendons_upload_files_cart_page' == $extendons_rule['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_cart_page'] || 'extendons_upload_files_product_page'== $extendons_rule['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_product_page'] || 'extendons_upload_files_checkout_page_after_notes'== $extendons_rule['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_checkout_page'] || 'extendons-upload-file-thankyou-page'== $extendons_rule['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_thankyou_page'] || 'extendons-upload-file-Account-page'== $extendons_rule['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_account_page']) {

						
						$extendons_new_session = array();
						$extendons_post_id = $extendons_rule['extendons_post_id'];
	 
						// $guest_id = $_COOKIE['PHPSESSID'];
						foreach ($extendons_rule['extendons_upload_files_multiple_files_limit'] as $keyval => $values) {
							 
							// $extendons_session_value = [];

							$guest_id = WC()->session->get( 'guest_id' );
						
							// PIN: critical
							$guest_id = $guest_id . '_' . $extendons_post_id . $product_id . $keyval;
						 
							if (!is_user_logged_in()) {
							 
								// $extendons_session_value = get_option($guest_id."_". $extendons_post_id . $product_id . $keyval);
								$extendons_session_value = get_option($guest_id);
 
							} else {
								 
	
								$extendons_session_value = WC()->session->get( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $keyval);
							}  

							if (isset($values['extendons_uploadfiles_file_maximum_upload_files']) && '' != $values['extendons_uploadfiles_file_maximum_upload_files']) {
								$max_upload_size = filter_var($values['extendons_uploadfiles_file_maximum_upload_files']);
							} else {
								$max_upload_size = '1';
							}

 
							 
							 
							if ( isset( WC()->session ) ) {
							 
								// if ( isset( $extendons_session_value ) && '0' != $extendons_session_value['extendons_total_upload_files']) {
								if ( isset( $extendons_session_value['extendons_total_upload_files
								'] ) && '0' != $extendons_session_value['extendons_total_upload_files']) {
									WC()->session->set( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $keyval, $extendons_session_value);
									
								} else {

									if ( ! is_array( $extendons_session_value ) ) {
										$extendons_session_value = array();
									}
									$extendons_session_value['extendons_btn_text_color'] = $values['extendons_uploadfiles_file_text_color'];
									$extendons_session_value['extendons_btn_background_color'] = $values['extendons_uploadfiles_file_background_color'];
									$extendons_session_value['extendons_upload_files_display_on_position'] = $extendons_rule['extendons_upload_files_display_on_position'];
									$extendons_session_value['rule_id'] = $extendons_post_id;
									$extendons_session_value['extendons_total_upload_files'] = '0';
									$extendons_session_value['extendons_maximum_upload_files'] = $max_upload_size;
									$extendons_session_value['extendons_file_upload_label'] = $values['extendons_uploadfiles_label'];
									$extendons_session_value['extendons_file_upload_btn_text'] = $values['extendons_uploadfiles_file_text_btn'];

									WC()->session->set( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $keyval, $extendons_session_value);
								}
							}

							
							array_push($extendons_new_session, $extendons_session_value);
							WC()->session->__unset( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $keyval);
							delete_option($guest_id);
						 
						}
						

						if (!empty($extendons_new_session) && '0' != $extendons_session_value['extendons_total_upload_files']) {
							if (!empty($extendons_new_session[$keyval])) {
								array_push($session_main_array, $extendons_new_session);
							}   
						} elseif (!empty($extendons_new_session[$keyval])) {
								array_push($session_main_array, $extendons_new_session);   
						}
					}
					

				}           
			}       

			// if( $value['extendons_customer_note'] == "undefined"){
			//  unset($value['extendons_customer_note']);           
			// }

			// PIN: set data 
			$extendons_upload_file_cart_item_data['extendons_upload_file_data'] = $session_main_array;
		  
			// echo "in the end<pre>";
			// var_dump([
			//  $extendons_upload_file_cart_item_data, $product_id, $variation_id 
			// ]);
			// echo '</pre>';
			// // die();
			// $extendons_upload_file_cart_item_data['custom_data'] = array(
			//  'custom_key' => 'Custom Value'
			// );
			return $extendons_upload_file_cart_item_data;   
		}


		/**
		 * Render the HTML for the file upload section on the product page.
		 *
		 * @since 1.0.0
		 *
		 * @param array $value An array containing the settings for the file upload section.
		 *
		 * @return void
		 */
		public function extendons_template_html_product_page_upload( $value ) {
		 
			$extendons_post_id = $value['extendons_post_id'];
			$product_id = get_the_ID();
			$session_array = array();

			foreach ($value['extendons_upload_files_multiple_files_limit'] as $key => $values) {
				
				$btnbackgroundcolor = 'background-color:' . $values['extendons_uploadfiles_file_background_color'] . ';';
				$btntextcolor = 'color:' . $values['extendons_uploadfiles_file_text_color'] . ';';

				if ('' != $values['extendons_uploadfiles_label'] && isset($values['extendons_uploadfiles_label'])) {
					$extendons_file_upload_label = $values['extendons_uploadfiles_label'];
				} else {
					$extendons_file_upload_label = '';
				}
				if ('' != $values['extendons_uploadfiles_file_text_btn'] && isset($values['extendons_uploadfiles_file_text_btn'])) {
					$extendons_file_upload_btn_text = $values['extendons_uploadfiles_file_text_btn'];
				} else {
					$extendons_file_upload_btn_text = 'Upload File';
				}

				if (isset($values['extendons_uploadfiles_file_maximum_upload_files']) && ''!= $values['extendons_uploadfiles_file_maximum_upload_files']) {
					$max_upload_size = filter_var($values['extendons_uploadfiles_file_maximum_upload_files']);
				} else {
					$max_upload_size = '1';
				}

				if ('' != $values['extendons_uploadfiles_file_maximum_upload_files'] && isset($values['extendons_uploadfiles_file_maximum_upload_files'])) {
					$extendons_file_max_upload_file = $values['extendons_uploadfiles_file_maximum_upload_files'];
				} else {
					$extendons_file_max_upload_file = 1;
				}
				$extendons_uploadfiles_file_required = isset($values['extendons_uploadfiles_file_required']) ? $values['extendons_uploadfiles_file_required'] : 'false';

				if ( isset( WC()->session ) ) {
					if (WC()->session->get( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $key )=='') {
						$session_array['extendons_btn_text_color']= $values['extendons_uploadfiles_file_text_color'];
						$session_array['extendons_btn_background_color'] = $values['extendons_uploadfiles_file_background_color'];
						$session_array['extendons_upload_files_display_on_position'] = $value['extendons_upload_files_display_on_position'];
						$session_array['rule_id'] = $extendons_post_id;
						$session_array['extendons_total_upload_files'] = '0';
						$session_array['extendons_maximum_upload_files'] = $extendons_file_max_upload_file;
						$extendons_session_value['extendons_file_upload_label'] = $extendons_file_upload_label;
						$extendons_session_value['extendons_file_upload_btn_text'] = $extendons_file_upload_btn_text;
						WC()->session->set( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $key, $session_array);
					}
	
					$guest_id = WC()->session->get( 'guest_id' );

					if (!is_user_logged_in() && !empty($guest_id)) {
						 
						// PIN: critical
						$guest_id = $guest_id . '_' . $extendons_post_id . $product_id . $key;
						$extendons_session_value = get_option($guest_id);
						if (!empty($extendons_session_value)) {
							WC()->session->set( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $key, $extendons_session_value);
						} else {
							$extendons_session_value = WC()->session->get( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $key);
						}
					}                 
				}

				$extendons_session_value = WC()->session->get( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $key);

				// WC()->session->__unset( 'extendons_upload_files_sessions' . $extendons_post_id . $product_id . $key);
				$upload_count_file = $extendons_session_value['extendons_total_upload_files'] ?? 0;
				$total_allow_upload = $extendons_file_max_upload_file ?? $extendons_session_value['extendons_maximum_upload_files'] ?? 0;
				

				// $upload_count_file = isset($extendons_session_value['extendons_total_upload_files']) ? $extendons_session_value['extendons_total_upload_files'] : 0;
				// $total_allow_upload = isset($extendons_session_value['extendons_maximum_upload_files']) ? $extendons_session_value['extendons_total_upload_files'] : 0;
 
				
				require EXTENDONS_UF_PLUGIN_FRONT_TEMPLATE_DIR . 'extendons_template_html_product_page_upload.php' ;
				
			}
		}

		/**
		 * Unset specific order item meta data.
		 *
		 * @since 1.0.0
		 *
		 * @param array $formatted_meta The formatted order item meta data.
		 * @param array $item The order item data.
		 *
		 * @return array The updated formatted order item meta data.
		 */
		public function unset_specific_order_item_meta_data( $formatted_meta, $item ) {
			
			// wc_get_logger()->debug( 'abcccc: ' . $formatted_meta, array( 'source' => 'extendons-upload-files', 'data' => $formatted_meta ) );
			// wc_get_logger()->debug( 'abcccc: ' . $formatted_meta, array( 'source' => 'extendons-upload-files', 'data' => $formatted_meta ) );

			 
			$is_resend = isset( $_REQUEST['wc_order_action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wc_order_action'] ) ) === 'send_order_details' : false;

			if ( !$is_resend && ( is_admin() || is_wc_endpoint_url() ) ) {
				return $formatted_meta;
			}

			foreach ( $formatted_meta as $key => $meta ) {
				if (strpos($meta->key, 'Edit Btn') !== false ) {
					unset($formatted_meta[$key]);
				}       
			}
			return $formatted_meta;
		}

		/**
		 * Shortcode to display the upload button on the product page.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function extendons_upload_button_shortcode() {
			global $post;
			$extendons_upload_file_product_id = $post->ID;
			$extendons_upload_file_terms = get_the_terms ( $extendons_upload_file_product_id, 'product_cat' );
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();
			$extendons_upload_file_user_role = wp_get_current_user()->roles;
			$extendons_valid_array = array();
			foreach ($extendons_upload_file_rules as $value) {
				$extendons_upload_file_valid = true;
				if ('extendons_upload_files_enable' == $value['extendons_upload_files_enable_disable_status']) {
					$extendons_upload_files_array = array();    
					if ('extendons_upload_files_product_page' == $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_product_page']) {

						if ('extendons_upload_files_product' == $value['extendons_upload_files_selected_pc_type']) {
							array_push($extendons_valid_array, $extendons_upload_file_product_id);
						} else if ('extendons_upload_files_category' ==$value['extendons_upload_files_selected_pc_type']) {
							foreach ($extendons_upload_file_terms as $key => $term) {
								array_push($extendons_valid_array, $term->term_id);
							}
						}

						if (!empty($value['extendons_upload_files_selected_pc'])) {
							// Check if cart items match selected products/categories
							if (!array_intersect($extendons_valid_array, $value['extendons_upload_files_selected_pc'])) {
								$extendons_upload_file_valid = false;
							} elseif (!is_single()) {
								return false;
							}
						}
						// Check user role restrictions only if product/category check passed or was not applicable
						if ($extendons_upload_file_valid && !empty($value['extendons_upload_files_selected_user_role'])) {
							$extendons_upload_file_valid = !empty(array_intersect($extendons_upload_file_user_role, $value['extendons_upload_files_selected_user_role']));
						}

						if (false == $extendons_upload_file_valid) {
							continue;
						}
					}

					if ('extendons_upload_files_product_page'== $value['extendons_upload_files_display_on_position'][0]['extendons_allow_uf_product_page']) {
						$this->extendons_template_html_product_page_upload($value);
					} 
				}
			}
		}

		/**
		 * Calculate the discounted price based on discount type.
		 *
		 * @param float $price Original price.
		 * @param float $discountValue Discount value (amount or percentage).
		 * @param string $discountType Discount type ('extendons_upload_files_fixed' or 'extendons_upload_files_percentage').
		 * @return float Discounted price, never less than zero.
		 */
		public function calculateDiscountPrice( $price, $discountValue, $discountType ) {
			// Validate inputs
			if ( ! is_numeric( $price ) || $price < 0 ) {
				return 0;
			}
			if ( ! is_numeric( $discountValue ) || $discountValue < 0 ) {
				return $price;
			}
			if ( ! in_array( $discountType, array( 'extendons_upload_files_fixed', 'extendons_upload_files_percentage' ), true ) ) {
				return $price;
			}

			// Calculate discounted price
			if ( 'extendons_upload_files_fixed' === $discountType ) {
				$discountedPrice = $price - $discountValue;
			} else {
				$discountedPrice = $price - ( $price * $discountValue / 100 );
			}

			// Ensure the discounted price is not negative
			return max( 0, round( $discountedPrice, 2 ) );
		}
	}




	new Extendons_Upload_Files_Front();

}
