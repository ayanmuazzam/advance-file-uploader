<?php 

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://extendons.com
 * @since      1.0.0
 *
 * @package    Extendons_Upload_Files
 * @subpackage Extendons_Upload_Files/admin
 */

 use Automattic\WooCommerce\Utilities\OrderUtil;
 use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
if ( !class_exists( 'Extendons_Upload_Files_Admin' ) ) { 

	/**
	 * Extendons Upload Files Admin class.
	 *
	 * This class handles all admin-related functionality for the File Uploader for WooCommerce plugin.
	 */
	class Extendons_Upload_Files_Admin extends Extendons_Ext_Upload_Files {
		
		public function __construct() {

			/**`
			 * Action hook to register custom post type for file uploader.
			 */
			add_action( 'wp_loaded', array( $this, 'extendons_upload_files_custom_posttype' ));
			 
			/**
			 * Action hook to enqueue admin scripts and styles.
			 */
			add_action( 'admin_enqueue_scripts', array( $this, 'extendons_upload_files_admin_scripts' ) );

			/**
			 * Action hook to save general settings via AJAX.
			 */
			add_action('wp_ajax_extendons_upload_files_save_general_settings', array( $this, 'extendons_upload_files_save_general_settings' ));
			add_action('wp_ajax_nopriv_extendons_upload_files_save_general_settings', array( $this, 'extendons_upload_files_save_general_settings' ));

			/**
			 * Action hook to delete rule file via AJAX.
			 */
			add_action('wp_ajax_extendons_upload_files_delete_rule_file', array( $this, 'extendons_upload_files_delete_rule' ));
			add_action('wp_ajax_nopriv_extendons_upload_files_delete_rule_file', array( $this, 'extendons_upload_files_delete_rule' ));

			/**
			 * Action hook to display file upload popup via AJAX.
			 */
			add_action('wp_ajax_extendons_upload_cfiles_data', array( $this, 'extendons_upload_file_popup' ));
			add_action('wp_ajax_nopriv_extendons_upload_cfiles_data', array( $this, 'extendons_upload_file_popup' ));

			/**
			 * Action hook to handle file upload for checkout page via AJAX.
			 */
			add_action('wp_ajax_extendons_uploadfile_ccta_data', array( $this, 'extendons_uploadfile_ccta_data' ));
			add_action('wp_ajax_nopriv_extendons_uploadfile_ccta_data', array( $this, 'extendons_uploadfile_ccta_data' ));

			/**
			 * Action hook to handle order button click for checkout page via AJAX.
			 */
			add_action('wp_ajax_extendons_uploadfiles_ccta_order_btn', array( $this, 'extendons_uploadfiles_ccta_order_btn' ));
			add_action('wp_ajax_nopriv_extendons_uploadfiles_ccta_order_btn', array( $this, 'extendons_uploadfiles_ccta_order_btn' ));

			/**
			 * Action hook to handle file upload via AJAX.
			 */
			add_action('wp_ajax_extendons_ext_upload_file', array( $this, 'extendons_ext_upload_file' ));
			add_action('wp_ajax_nopriv_extendons_ext_upload_file', array( $this, 'extendons_ext_upload_file' ));

			/**
			 * Action hook to handle file upload for order via AJAX.
			 */
			add_action('wp_ajax_extendons_ext_upload_order_file', array( $this, 'extendons_ext_upload_order_file' ));
			add_action('wp_ajax_nopriv_extendons_ext_upload_order_file', array( $this, 'extendons_ext_upload_order_file' ));

			/**
			 * Action hook to update order file data via AJAX.
			 */
			add_action('wp_ajax_extendons_update_orderfile_data', array( $this, 'extendons_update_orderfile_data' ));
			add_action('wp_ajax_nopriv_extendons_update_orderfile_data', array( $this, 'extendons_update_orderfile_data' ));

			/**
			 * Action hook to delete uploaded file via AJAX.
			 */
			add_action('wp_ajax_extendons_upload_delete_file', array( $this, 'extendons_upload_delete_file' ));
			add_action('wp_ajax_nopriv_extendons_upload_delete_file', array( $this, 'extendons_upload_delete_file' ));

			/**
			 * Action hook to delete uploaded file for checkout page via AJAX.
			 */
			add_action('wp_ajax_extendons_upload_ccta_delete_file', array( $this, 'extendons_upload_ccta_delete_file' ));
			add_action('wp_ajax_nopriv_extendons_upload_ccta_delete_file', array( $this, 'extendons_upload_ccta_delete_file' ));

			/**
			 * Filter hook to add custom tab in WooCommerce settings.
			 */
			add_filter('woocommerce_settings_tabs_array', array( $this, 'fma_upload_files_woocommerce_settings_tabs_array' ), 50 );

			/**
			 * Action hook to render custom settings section in WooCommerce settings.
			 */
			add_action( 'woocommerce_settings_extendons_upload_files', array( $this, 'fma_upload_files_settings' ));

			/**
			 * Filter hook to add custom column in WooCommerce orders list.
			 */
			add_filter( 'manage_edit-shop_order_columns', array( $this, 'custom_shop_order_column' ), 20 );

			/**
			 * Action hook to render custom column content in WooCommerce orders list.
			 */
			add_action( 'manage_shop_order_posts_custom_column' , array( $this, 'custom_orders_list_column_content' ), 20, 2 );

			/**
			 * Action hook to handle post save actions.
			 */
			add_action('save_post', array( $this, 'save_post_callback' ));
			add_action('woocommerce_process_shop_order_meta', array( $this, 'save_post_callback' ) );

			/**
			 * Action hook to save reCAPTCHA settings via AJAX.
			 */
			add_action('wp_ajax_extendons_upload_recapcha_save', array( $this, 'extendons_upload_recapcha_save' ));
			add_action('wp_ajax_nopriv_extendons_upload_recapcha_save', array( $this, 'extendons_upload_recapcha_save' ));

			add_action('wp_ajax_extendons_upload_additional_settings_save', array( $this, 'extendons_upload_additional_settings_save' ));
			add_action('wp_ajax_nopriv_extendons_upload_additional_settings_save', array( $this, 'extendons_upload_additional_settings_save' ));

			/**
			 * Action hook to handle file upload for cart page via AJAX.
			 */
			add_action('wp_ajax_extendons_handle_file_upload_cart', array( $this, 'extendons_handle_file_upload_cart' ));
			add_action('wp_ajax_nopriv_extendons_handle_file_upload_cart', array( $this, 'extendons_handle_file_upload_cart' ));

			/**
			 * Action hook to retrieve session files via AJAX.
			 */
			add_action('wp_ajax_get_session_files', array( $this, 'get_session_files' ));
			add_action('wp_ajax_nopriv_get_session_files', array( $this, 'get_session_files' ));

			/**
			 * Action hook to delete session file via AJAX.
			 */
			add_action('wp_ajax_delete_session_file', array( $this, 'delete_session_file' ));
			add_action('wp_ajax_nopriv_delete_session_file', array( $this, 'delete_session_file' ));

			add_action ( 'add_meta_boxes', array( $this, 'extendons_upload_files_add_meta_boxes' ) );

			if (isset($_GET['page']) &&  'wc-settings' == $_GET['page']  && isset($_GET['tab']) && 'extendons_upload_files' ==  $_GET['tab']  ) {
				add_action('admin_notices', array( $this, 'fme_show_notice_status' ));
			}

			add_filter( 'plugin_action_links_advanced-file-uploader/file-uploader-for-woocommerce.php', array( $this, 'add_plugin_settings_link' ) );
		}

		public function add_plugin_settings_link( $actions ) {
			$settings_url = admin_url( 'admin.php?page=wc-settings&tab=extendons_upload_files' ); // adjust to your settings page
			$settings_link = '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'fsp-flash-sales-pro' ) . '</a>';
			array_unshift( $actions, $settings_link ); // Add to the beginning
			return $actions;
		}

		public function fme_show_notice_status() {
			?>
				<div class="notice notice-warning is-dismissible">
					<p>
					<?php
						echo esc_html__('Have a technical issue or feature request? Submit a', 'shop-as-a-customer-for-woocommerce') . ' <a href="https://woocommerce.com/my-account/contact-support/" target="_blank">' . esc_html__('support ticket', 'shop-as-a-customer-for-woocommerce') . '</a> ' . esc_html__('now, and our developers will get back to you as quickly as possible.', 'shop-as-a-customer-for-woocommerce');               
					?>
					</p>
				</div>
			
				<div class="notice notice-info is-dismissible"><p>
					<?php
				
						printf(
							/* translators: %s: bold tags */
							esc_html__('%1$s Note: %2$s If you are using a page builder (such as Elementor or WPBakery), the upload buttons may not appear on your pages. In that case, use the %3$s[extendons_upload_button]%4$s shortcode to display the file upload button.', 'extendons_Upload_Files'),
							'<b>', '</b>', '<b>', '</b>'
						);
					?>
					</p>
				</div>


				<?php
		}

		public function extendons_handle_file_upload_cart() {
			$retrieved_nonce = isset($_REQUEST['requestID']) ? sanitize_text_field($_REQUEST['requestID']) : '';
			// Verify nonce for security
			if (!wp_verify_nonce($retrieved_nonce, 'ext_fu_ajax_nonce')) {
				http_response_code(401);
				die('Failed security check');
			}

			// Check if WooCommerce session is available
			if (!WC()->session) {
				wp_send_json_error(array( 'message' => 'Session not available.' ));
			}

			$rule_id = isset($_REQUEST['rule_id']) ? sanitize_text_field($_REQUEST['rule_id']) : '';
			$rule_key = isset($_REQUEST['key']) ? sanitize_text_field($_REQUEST['key']) : '';
			$user_id = get_current_user_id(); // 0 for guest users
			$uploaded_files = array();
			$customer_notes = isset($_REQUEST['customer_notes']) ? sanitize_textarea_field($_REQUEST['customer_notes']) : '';

			$extendons_upload_files_additional_settings = get_option('extendons_upload_additional_settings', array());
			$custom_folder_path = !empty($extendons_upload_files_additional_settings['extendons_upload_folder_path']) 
				? rtrim(ABSPATH . $extendons_upload_files_additional_settings['extendons_upload_folder_path'], '/\\') . '/' 
				: '';

			$use_custom_path = false;
			if (!empty($custom_folder_path)) {
				// Ensure the folder exists; create it if it doesn't
				if (!is_dir($custom_folder_path)) {
					wp_mkdir_p($custom_folder_path); // Create directory with proper permissions
				}

				// Check if the folder is writable
				if (is_dir($custom_folder_path) && is_writable($custom_folder_path)) {
					$use_custom_path = true;
					$upload_dir = $custom_folder_path;
					$upload_url = site_url(str_replace(ABSPATH, '', $custom_folder_path));
				}
			}

			if (!$use_custom_path) {
				// Fallback to WordPress media upload directory
				$uploads = wp_upload_dir(null, true);
				$upload_dir = $uploads['path'];
				$upload_url = $uploads['url'];
			}

			// Process files if they exist
			if (isset($_FILES) && !empty($_FILES)) {
				foreach ($_FILES as $input_name => $file_array) {
					if (!empty($file_array['name'][0])) {
						foreach ($file_array['name'] as $key => $name) {
							if (UPLOAD_ERR_OK === $file_array['error'][$key]) {
								// Sanitize file name
								$extendons_filename = stripslashes(str_replace('#', '-', $name));
								// Make file name unique
								$unique_filename = wp_unique_filename($upload_dir, $extendons_filename);

								$file = array(
									'name'     => $unique_filename,
									'type'     => $file_array['type'][$key],
									'tmp_name' => $file_array['tmp_name'][$key],
									'error'    => $file_array['error'][$key],
									'size'     => $file_array['size'][$key],
								);

								// Move file to uploads directory
								$file_path = rtrim($upload_dir, '/\\') . '/' . $file['name']; // Ensure no double slashes
								if (move_uploaded_file($file['tmp_name'], $file_path)) {
									$uploaded_files[] = array(
										'name'    => $file['name'],
										'path'    => $file_path,
										'url'     => rtrim($upload_url, '/') . '/' . $file['name'], // Ensure no double slashes in URL
										'user_id' => $user_id,
									);
								}
							}
						}
					}
				}
			}

			// Store in WooCommerce session
			$session_key = 'uploaded_files_' . $rule_id . '_' . $rule_key;
			$existing_files = WC()->session->get($session_key, array());
			$updated_files = !empty($uploaded_files) ? array_merge($existing_files, $uploaded_files) : $existing_files;
			
			// Store customer notes in session
			$notes_session_key = 'customer_notes_' . $rule_id . '_' . $rule_key;
			WC()->session->set($notes_session_key, $customer_notes);
			
			// Update files in session only if new files were uploaded
			if (!empty($uploaded_files)) {
				WC()->session->set($session_key, $updated_files);
			}

			// Prepare response
			if (empty($uploaded_files) && empty($customer_notes)) {
				wp_send_json_error(array( 'message' => 'No files or notes provided.' ));
			}

			wp_send_json_success(array(
				'message' => 'Files and/or notes uploaded and stored in session.',
				'files'   => $updated_files,
				'notes'   => $customer_notes,
			));
		}

		// Handle AJAX file deletion from session
		public function delete_session_file() {
			$retrieved_nonce = isset($_REQUEST['requestID']) ? sanitize_text_field($_REQUEST['requestID']) : '';
			// Verify nonce for security
			if (! wp_verify_nonce($retrieved_nonce, 'ext_fu_ajax_nonce') ) {
				http_response_code(401);
				die('Failed security check');
			}
			if (!WC()->session) {
				wp_send_json_error(array( 'message' => 'Session not available.' ));
			}

			$rule_id = isset($_REQUEST['rule_id']) ? sanitize_text_field($_REQUEST['rule_id']) : '';
			$key = isset($_REQUEST['key']) ? sanitize_text_field($_REQUEST['key']) : '';
			$index = isset($_REQUEST['index']) ? intval($_REQUEST['index']) : -1;
			$session_key = 'uploaded_files_' . $rule_id . '_' . $key;
			$files = WC()->session->get($session_key, array());

			if ($index >= 0 && isset($files[$index])) {
				// Delete file from server
				if (file_exists($files[$index]['path'])) {
					unlink($files[$index]['path']);
				}
				array_splice($files, $index, 1);
				WC()->session->set($session_key, $files);
				wp_send_json_success(array(
					'message' => 'File removed from session.',
					'files' => $files,
				));
			} else {
				wp_send_json_error(array( 'message' => 'File not found.' ));
			}
		}

		// New AJAX handler to retrieve session files
		public function get_session_files() {
			$retrieved_nonce = isset($_REQUEST['requestID']) ? sanitize_text_field($_REQUEST['requestID']) : '';
			// Verify nonce for security
			if (!wp_verify_nonce($retrieved_nonce, 'ext_fu_ajax_nonce')) {
				http_response_code(401);
				die('Failed security check');
			}
			if (!WC()->session) {
				wp_send_json_error(array( 'message' => 'Session not available.' ));
			}

			$rule_id = isset($_REQUEST['rule_id']) ? sanitize_text_field($_REQUEST['rule_id']) : '';
			$key = isset($_REQUEST['key']) ? sanitize_text_field($_REQUEST['key']) : '';
			$session_key = 'uploaded_files_' . $rule_id . '_' . $key;
			$notes_session_key = 'customer_notes_' . $rule_id . '_' . $key;
			
			$files = WC()->session->get($session_key, array());
			$notes = WC()->session->get($notes_session_key, '');

			wp_send_json_success(array(
				'files' => $files,
				'notes' => $notes,
			));
		}
		
		/**
		 * Add meta boxes for upload files
		 *
		 * @since 1.0.0
		 */
		public function extendons_upload_files_add_meta_boxes() {
			$screen = class_exists(CustomOrdersTableController::class) && wc_get_container()->get(CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled()
			? wc_get_page_screen_id('shop-order')
			: 'shop_order';
			// Add meta box for upload files
			add_meta_box(
				'extendons_upload_files_meta_box',
				__( 'Upload Files', 'extendons_Upload_Files' ),
				array( $this, 'extendons_upload_files_meta_box_callback' ),
				$screen,
				'normal',
				'default'
			);
		}

		/**
		 * Callback function for the upload files meta box.
		 *
		 * @since 1.0.0
		 * @param WP_Post|WC_Order $post The current post or order object.
		 * @return void
		 */
		public function extendons_upload_files_meta_box_callback( $post ) {
			// Ensure $post is a WC_Order object
			$post = ( $post instanceof WP_Post ) ? wc_get_order( $post->ID ) : $post;

			// Get the upload files rules
			$extendons_upload_file_rules = $this->extendons_get_Upload_files_rules();

			if ( ! empty( $extendons_upload_file_rules ) ) {
				// Start table
				echo '<table cellpadding="10" cellspacing="10" class="extendons_upload_files_table" style="width: 100%; border-collapse: collapse;">';
				echo '<tr>';
				echo '<th>' . esc_html__( 'Rule Title', 'extendons_Upload_Files' ) . '</th>';
				echo '<th>' . esc_html__( 'Uploaded Files', 'extendons_Upload_Files' ) . '</th>';
				echo '<th>' . esc_html__( 'Customer Note', 'extendons_Upload_Files' ) . '</th>';
				echo '</tr>';

				foreach ( $extendons_upload_file_rules as $rule ) {
					$rule_id = $rule['extendons_post_id'];
					$files_list = array();
					$notes_list = array();

					// Collect all files and notes for this rule, using label as key
					foreach ( $rule['extendons_upload_files_multiple_files_limit'] as $key => $value ) {
						$uploaded_files = $post->get_meta( 'extendons_upload_files_' . $rule_id . '_' . $key, true );
						$customer_note = $post->get_meta( 'extendons_customer_notes_' . $rule_id . '_' . $key, true );
						$label = !empty( $value['extendons_uploadfiles_label'] ) ? $value['extendons_uploadfiles_label'] : 'No Label';

						// Skip if no files or note
						if ( empty( $uploaded_files ) && empty( $customer_note ) ) {
							continue;
						}

						// Collect files
						$files_html = '<div class="content-group">';
						$files_html .= '<h4 class="label-header">' . esc_html( $label ) . '</h4>';
						if ( ! empty( $uploaded_files ) ) {
							$files_html .= '<ul>';
							foreach ( $uploaded_files as $file ) {
								$files_html .= '<li><a href="' . esc_url( $file['url'] ) . '" target="_blank">' . esc_html( $file['name'] ) . '</a></li>';
							}
								$files_html .= '</ul>';
						} else {
							$files_html .= esc_html__( 'No files uploaded', 'extendons_Upload_Files' );
						}
							$files_html .= '</div>';
							$files_list[$label] = $files_html;

							// Collect notes
							$notes_html = '<div class="content-group">';
							$notes_html .= '<h4 class="label-header">' . esc_html( $label ) . '</h4>';
							$notes_html .= ! empty( $customer_note ) ? esc_html( $customer_note ) : esc_html__( 'No note provided', 'extendons_Upload_Files' );
							$notes_html .= '</div>';
							$notes_list[$label] = $notes_html;
					}

						// Skip if no data to display for this rule
					if ( empty( $files_list ) && empty( $notes_list ) ) {
						continue;
					}

						// Output a single row for the rule
						echo '<tr>';
						// Rule Title
						echo '<td>' . esc_html( $rule['extendons_rule_title'] ) . '</td>';

						// Uploaded Files (all files grouped by label in one cell)
						echo '<td>';
					foreach ( $files_list as $label => $files_html ) {
						echo wp_kses_post($files_html);
					}
						echo '</td>';

						// Customer Notes (all notes grouped by label in one cell)
						echo '<td>';
					foreach ( $notes_list as $label => $notes_html ) {
						echo wp_kses_post($notes_html);
					}
						echo '</td>';

						echo '</tr>';
				}
				echo '</table>';
			} else {
				echo '<p>' . esc_html__( 'No upload file rules found', 'extendons_Upload_Files' ) . '</p>';
			}
		}

		/**
		 * Delete an upload files rule.
		 *
		 * This function deletes an upload files rule based on the provided rule ID.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function extendons_upload_files_delete_rule() {
			global $wpdb;

			// Get the rule ID to be deleted from the request.
			$delete_post_id = isset($_REQUEST['extendons_upload_files_rule_id']) ? filter_var( wp_unslash( $_REQUEST['extendons_upload_files_rule_id'] )) : '';

			// Check if the post type of the post to be deleted is 'extend_upload_files'.
			if ('extend_upload_files' === get_post_type($delete_post_id)) {
				// Delete the post.wp_unslash( 
				wp_delete_post($delete_post_id);
			}

			// Exit the script.
			wp_die();
		}



		public function extendons_uploadfile_ccta_data() {

			$data_allow_upload_file = isset($_REQUEST['data_allow_upload_file']) ? filter_var( wp_unslash( $_REQUEST['data_allow_upload_file'] )) : '1';
			$extendons_upload_files_postid =  isset($_REQUEST['extendons_post_id']) ? filter_var( wp_unslash( $_REQUEST['extendons_post_id'] )) : '';
			$index_key =  isset($_REQUEST['index_key']) ? filter_var( wp_unslash( $_REQUEST['index_key'] )) : '';
			$rule_id_index =  isset($_REQUEST['rule_id_index']) ? filter_var( wp_unslash( $_REQUEST['rule_id_index'] )) : '';
			$productid =  isset($_REQUEST['productid']) ? filter_var( wp_unslash( $_REQUEST['productid'] )) : '';
			$pagetype =  isset($_REQUEST['pagetype']) ? filter_var( wp_unslash( $_REQUEST['pagetype'] )) : '';
			$cart_key =  isset($_REQUEST['cart_key']) ? filter_var( wp_unslash( $_REQUEST['cart_key'] )) : '';
			$extendons_order_id =  isset($_REQUEST['extendons_order_id']) ? filter_var( wp_unslash( $_REQUEST['extendons_order_id'] )) : '';

			$extendons_upload_files_get_status =  get_post_meta($extendons_upload_files_postid, 'extendons_enable_disable_settings', true);
			$extendons_rule_priority =  get_post_meta($extendons_upload_files_postid, 'extendons_rule_priority', true);
			$extendons_upload_files_display_on_position =  get_post_meta($extendons_upload_files_postid, 'extendons_display_on_values', true);
			$extendons_selected_product_category = get_post_meta($extendons_upload_files_postid, 'extendons_selected_product_category', true); 
			$extendons_selected_product_item = get_post_meta($extendons_upload_files_postid, 'extendons_selected_items', true);
			$extendons_selected_user_roles = get_post_meta($extendons_upload_files_postid, 'extendons_selected_user_role', true); 
			$extendons_upload_files_multival_arr = get_post_meta($extendons_upload_files_postid , 'extendons_multiple_files_limit', true);
			
			require_once EXTENDONS_UF_PLUGIN_ADMIN_TEMPLATE_DIR . 'extendons_uploadfile_ccta_data.php' ;
			 
			wp_die();
		}

		/**
		 * Save additional settings for the file uploader.
		 *
		 * This function saves additional settings for the file uploader plugin. It retrieves the settings from the request,
		 * filters them, and updates the 'extendons_upload_additional_settings' option with the new settings.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function extendons_upload_additional_settings_save() {
			$extendons_upload_folder_path = isset($_REQUEST['extendons_upload_folder_path']) ? filter_var( wp_unslash( $_REQUEST['extendons_upload_folder_path'] ), FILTER_SANITIZE_STRING) : '';

			$additional_setting = array(
				'extendons_upload_folder_path' => $extendons_upload_folder_path,
			);
			update_option('extendons_upload_additional_settings', $additional_setting);
			wp_die();
		}

		/**
		 * Save the reCAPTCHA settings for the file uploader.
		 *
		 * This function saves the reCAPTCHA settings for the file uploader plugin. It retrieves the reCAPTCHA
		 * settings from the request, filters them, and updates the 'extendons_upload_recapcha_setting' option
		 * with the new settings.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function extendons_upload_recapcha_save() {

			$extendons_upload_recapcha = isset($_REQUEST['extendons_upload_recapcha']) ? filter_var( wp_unslash( $_REQUEST['extendons_upload_recapcha'] ), FILTER_SANITIZE_STRING) : '';
			$extendons_upload_recapcha_site_key = isset($_REQUEST['extendons_upload_recapcha_site_key']) ? filter_var( wp_unslash( $_REQUEST['extendons_upload_recapcha_site_key'] ), FILTER_SANITIZE_STRING) : '';
			$extendons_upload_recapcha_secrect_key = isset($_REQUEST['extendons_upload_recapcha_secrect_key']) ? filter_var( wp_unslash( $_REQUEST['extendons_upload_recapcha_secrect_key'] ), FILTER_SANITIZE_STRING) : '';

			$recaptch_setting = array(
				'extendons_upload_recapcha' => $extendons_upload_recapcha,
				'extendons_upload_recapcha_site_key' => $extendons_upload_recapcha_site_key,
				'extendons_upload_recapcha_secrect_key' => $extendons_upload_recapcha_secrect_key,    
			);
			update_option('extendons_upload_recapcha_setting', $recaptch_setting);
			wp_die();
		}

		/**
		 * Save post callback function
		 *
		 * This function is called when a post (order) is saved. It sends an email to the customer with the status of their uploaded files.
		 *
		 * @param int $post_id The ID of the post (order) being saved.
		 * @since 1.0.0
		 * @return void
		 */
		public function save_post_callback( $order ) {
	
 
			if (!is_a($order, 'WC_Order')) {
				$order = wc_get_order($order);
				
			}
			if (!$order) {
				return;
			}
			
			global $post, $woocommerce, $wpdb;


			// echo "<pre>";
			// print_r($_REQUEST);
			// echo "</pre>";
			// die();

			// Get the admin email address from WordPress options
			$admin_email = get_option('admin_email');
			// Get the customer's email address from the order meta
			 $user_email = $order->get_billing_email();
			
			// Set the recipient email address to the customer's email
			$to          = $user_email;
			// Start building the email message
			$message = '<h4>File Uploader For Woocommerce</h4><p>Order id:' . $order->get_id() . '</p>';
			// Get the order total from the order meta
			$order_total = $order->get_meta('_order_total', true);

			// Get the meta key from the request
			$key = '';
			if (isset($_REQUEST['extendons_order_meta_key'])) {
				$key = map_deep( wp_unslash($_REQUEST['extendons_order_meta_key'] ), 'sanitize_text_field' );   
			}

			// Get the item ID from the request
			$item_id = '';
			if (isset($_REQUEST['extendons_item_id'])) {
				$item_id = map_deep( wp_unslash($_REQUEST['extendons_item_id'] ), 'sanitize_text_field' );  
			}

			// Get the order status from the request
			$status = '';
			if (isset($_REQUEST['extendons_order_staus_key'])) {
				$status = map_deep( wp_unslash($_REQUEST['extendons_order_staus_key'] ), 'sanitize_text_field' );       
			}
			// Get the current date and time
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');
			$current_date_time = gmdate($date_format . ' ' . $time_format, current_time('timestamp'));
			
			// Get the note from the request
			$note = '';
			if (isset($_REQUEST['extendons_order_file_note'])) {            
				$note = map_deep( wp_unslash( $_REQUEST['extendons_order_file_note'] ), 'sanitize_text_field' );                
			}
			
			// Get the order data from the order meta
			// $extendons_thankyou_pagedata = get_post_meta($post_id, 'extendons_cart_order_data', true);
			
			// $order = wc_get_order($order);
			
			$extendons_thankyou_pagedata = $order ?  $order->get_meta('extendons_cart_order_data', true) : false;
			 
			// If there is a note, loop through each note and update the order item meta
			if (''!=$note || !empty($note)) {
				for ( $i=0; $i < count($note); $i++ ) {
					// If the note is empty, use the status instead
					if (''==$note[$i]) {
						$note[$i] = $status[$i];
					} else {
						$note[$i] = $note[$i];
					}
					// Build the meta keys for the order item meta
					// PIN: status change 2
					// $Uploaded_filestatus_key = 'Status ' . $key[$i] . $item_id[$i];
					$Uploaded_filestatus_key = 'Status ' . $key[$i] . '_' . $item_id[$i];
					$Uploaded_Rejected_key = 'Rejected ' . $key[$i] . $item_id[$i];
					$Uploaded_meta_key = 'Uploaded File ' . $key[$i];
					$Uploaded_ebtn_key = 'Edit Btn ' . $key[$i]; 
					// Build the note message
					$notes = 'The File has Been ' . $status[$i] . ' on ' . $current_date_time . ' You have sent the following notification:' . $note[$i];
				
				
					// PIN: SN1
				 
					// If the status is 'Accepted', update the order item meta with the note and status
					if ('Accepted' == $status[$i]) {
						
						wc_update_order_item_meta($item_id[$i], $Uploaded_ebtn_key, $notes);
						// wc_update_order_item_meta($item_id[$i], 'accepted_rejected', 'accepted');
						wc_update_order_item_meta($item_id[$i], 'accepted_rejected-' . $key[$i], 'accepted');
						wc_update_order_item_meta($item_id[$i], $Uploaded_filestatus_key, $notes);    
						 
						// If the status is 'Rejected', update the order item meta with the note and status
					} else if ( 'Rejected' == $status[$i]) {
						
						// wc_update_order_item_meta($item_id[$i], 'accepted_rejected', 'rejected');
						wc_update_order_item_meta($item_id[$i], 'accepted_rejected-' . $key[$i], 'rejected');
						wc_update_order_item_meta($item_id[$i], $Uploaded_filestatus_key, $notes);  
					}
					
					// Add the uploaded file to the email message
					$message.= wc_get_order_item_meta($item_id[$i], $Uploaded_meta_key);    
					// Set the email subject
					$subject = 'Uploaded file Status';
					// Build the email headers
					$headers = 'From:' . wp_strip_all_tags($admin_email) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					// Start building the email body
					$message .= '<html><body>';
					// If the status is 'Accepted', add a message to the email body
					if ('Accepted' == $status[$i]) {
						$message .= '<p>' . $notes . '</p>';
						// If the status is not 'Accepted', add a different message to the email body
					} else {
						$message .= '<p>' . $notes . '</p>';
					}
					// Close the email body
					$message .= '</body></html>';
				}
			}
			
			// print_r($order);
			// die();
			// If there is a note, send the email
			if (!empty($note)) {
				wp_mail( $to, $subject, $message, $headers);
			}   
		}


		/**
		 * Update order file data
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function extendons_update_orderfile_data() {

			// Get the post ID from the request
			$post_id = isset($_REQUEST['data_id']) ? filter_var( wp_unslash( $_REQUEST['data_id']), FILTER_SANITIZE_NUMBER_INT) : '';

			// Get the button index key from the request
			$data_btnindex_key = isset($_REQUEST['data_btnindex_key']) ? filter_var(wp_unslash( $_REQUEST['data_btnindex_key']), FILTER_SANITIZE_STRING) : '';

			// Get the product ID from the request
			$productid = isset($_REQUEST['data_p_id']) ? filter_var( wp_unslash( $_REQUEST['data_p_id']), FILTER_SANITIZE_NUMBER_INT) : '';

			// Get the rule ID from the request
			$data_rule_id = isset($_REQUEST['data_rule_id']) ? filter_var( wp_unslash( $_REQUEST['data_rule_id']), FILTER_SANITIZE_STRING) : '';

			// Get the order ID from the request
			$extendons_order_id = isset($_REQUEST['extendons_order_id']) ? filter_var( wp_unslash( $_REQUEST['extendons_order_id']), FILTER_SANITIZE_NUMBER_INT) : '';

			// Get the item ID from the request
			$data_item_id = isset($_REQUEST['data_item_id']) ? filter_var( wp_unslash( $_REQUEST['data_item_id']), FILTER_SANITIZE_NUMBER_INT) : '';

			// Get the cart key from the request
			$data_cart_key = isset($_REQUEST['data_cart_key']) ? filter_var( wp_unslash( $_REQUEST['data_cart_key']), FILTER_SANITIZE_STRING) : '';

			// Get the multiple file limit meta value for the post
			$extendons_upload_files_multival_arr = get_post_meta($post_id, 'extendons_multiple_files_limit', true);

			// Get the cart order data meta value for the order
			// $extendons_thankyou_pagedata = get_post_meta($extendons_order_id, 'extendons_cart_order_data', true);
			
			$order = wc_get_order($extendons_order_id);
			$extendons_thankyou_pagedata = $order->get_meta('extendons_cart_order_data');

			require_once EXTENDONS_UF_PLUGIN_ADMIN_TEMPLATE_DIR . 'extendons_update_orderfile_data.php' ;
			 
			wp_die();
		}

		
		/**
		 * Handle file uploads for orders.
		 *
		 * This function is responsible for handling file uploads associated with WooCommerce orders.
		 * It performs necessary security checks, retrieves request parameters, and processes the uploaded files.
		 *
		 * @since 1.0.0
		 * @return array An array containing the processed file data and other relevant information.
		 */
		public function extendons_ext_upload_order_file() {

			$debug_arr = array();
			// PIN: Modify File Handler
			// die('Failed security check Adnan');
			$retrieved_nonce =  isset( $_REQUEST['requestId'] ) ? sanitize_text_field(wp_unslash( $_REQUEST['requestId'] )) : 0;
			if (! wp_verify_nonce($retrieved_nonce, 'ext_fu_ajax_nonce') ) {
				http_response_code(401);
				die('Failed security check');
			}
			
			// // LOG
			// wc_get_logger()->debug( 'Request data: ' . print_r( $_REQUEST, true ), array( 'source' => 'file-uploader-for-woocommerce' ) );
			// wc_get_logger()->debug( '_FILES: ' . print_r( $_FILES, true ), array( 'source' => 'file-uploader-for-woocommerce' ) );
			$i= 0;
			$post_id = isset($_REQUEST['postid']) ? filter_var( wp_unslash( $_REQUEST['postid'] )) : '';
			$productid = isset($_REQUEST['productid']) ? filter_var( wp_unslash( $_REQUEST['productid'] )) : '';
			$btn_key = isset($_REQUEST['btn_key']) ? filter_var( wp_unslash( $_REQUEST['btn_key'] )) : '';
			$rule_index_id = isset($_REQUEST['rule_index_id']) ? filter_var( wp_unslash( $_REQUEST['rule_index_id'] )) : '';
			$pagetype = isset($_REQUEST['pagetype']) ? filter_var( wp_unslash( $_REQUEST['pagetype'] )) : '';
			$cart_key = isset($_REQUEST['cart_key']) ? filter_var( wp_unslash( $_REQUEST['cart_key'] )) : '';
			$index_array =  isset($_REQUEST['indexarray']) ? filter_var( wp_unslash( $_REQUEST['indexarray'] )) : '';
			$item_id =  isset($_REQUEST['data_item_id']) ? filter_var( wp_unslash( $_REQUEST['data_item_id'] )) : '';
			$extendons_order_id = isset($_REQUEST['extendons_order_id']) ? filter_var( wp_unslash( $_REQUEST['extendons_order_id'] )) : '';
			$extendons_upload_files_multival_arr = get_post_meta($post_id , 'extendons_multiple_files_limit', true);

			// // LOG: data
			// $logger = wc_get_logger();
			// $context = array('source' => 'file-uploader-for-woocommerce-modify-file');
			// $log_data = array(
			//  'post_id' => $post_id,
			//  'productid' => $productid,
			//  'btn_key' => $btn_key,
			//  'rule_index_id' => $rule_index_id,
			//  'pagetype' => $pagetype,
			//  'cart_key' => $cart_key,
			//  'index_array' => $index_array,
			//  'item_id' => $item_id,
			//  'extendons_order_id' => $extendons_order_id,
			//  'extendons_upload_files_multival_arr' => $extendons_upload_files_multival_arr,
			//  'time' => $time
			// );
			// // LOG
			// $logger->info('File Uploader for WooCommerce - Request Data: ' . wc_print_r($log_data, true), $context);

			$main_array = array(
				'max_upload_size' => ( '' != $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_size_value'] ) ? $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_size_value'] : '1', 
				'extendons_maximum_upload_files' => ( '' != $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files'] ) ? $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files'] : '1',
				'extendons_file_upload_btn_text' =>  ( '' != $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_btn'] ) ? $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_btn'] : 'Upload File',
				'extendons_file_upload_label' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_label'],
				'extendons_uploadfiles_price' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_price'],
				'extendons_uploadfiles_discount_type' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_discount_type'],
				'extendons_uploadfiles_discount_price' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_discount_price'],
				'extendons_uploadfiles_file_multiply_by_quantity' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_multiply_by_quantity'],
				'extendons_total_upload_files' => count($_FILES),
			);

			$Upload_file_array = array();
			$extendons_upload_files_additional_settings = get_option('extendons_upload_additional_settings', array());
			$custom_folder_path = !empty($extendons_upload_files_additional_settings['extendons_upload_folder_path']) 
				? rtrim(ABSPATH . $extendons_upload_files_additional_settings['extendons_upload_folder_path'], '/\\') . '/' 
				: '';

			$use_custom_path = false;
			if (!empty($custom_folder_path)) {
				// Ensure the folder exists; create it if it doesn't
				if (!is_dir($custom_folder_path)) {
					wp_mkdir_p($custom_folder_path); // Create directory with proper permissions
				}

				// Check if the folder is writable
				if (is_dir($custom_folder_path) && is_writable($custom_folder_path)) {
					$use_custom_path = true;
					$upload_dir = $custom_folder_path;
					$upload_url = site_url(str_replace(ABSPATH, '', $custom_folder_path));
				}
			}

			if (!$use_custom_path) {
				// Fallback to WordPress media upload directory
				$uploads = wp_upload_dir(null, true);
				$upload_dir = $uploads['path'];
				$upload_url = $uploads['url'];
			}

			// LOG: upload_dir and upload_url
			foreach ($_FILES as $key => $_file) {
				if (isset($_file['name']) && '' !== $_file['name']) { 
					// Sanitize file name
					$extendons_filename = stripslashes(str_replace('#', '-', $_file['name']));

					$target_upload_dir = $upload_dir;
					$target_upload_url = $upload_url;

					// Make file name unique
					$unique_filename = wp_unique_filename($target_upload_dir, $extendons_filename);
					$target_file_path = $target_upload_dir . '/' . $unique_filename;

					// Move the uploaded file
					if (move_uploaded_file($_file['tmp_name'], $target_file_path)) { // phpcs:ignore
						$extendons_upload_files_url = $target_upload_url . '/' . $unique_filename;

						$Upload_file_array = array(
							'extendons_post_id'                     => $post_id,
							'extendons_upload_files_cartfilename'   => $unique_filename,
							'extendons_upload_files_cartfilesize'   => $_file['size'],
							'extendons_upload_files_carttmp_name'   => $_file['tmp_name'],
							'extendons_upload_files_carttype'       => $_file['type'],
							'extendons_upload_files_cartfile_url'   => $extendons_upload_files_url,
						);
					}
				}
			}

			$main_array['uploaded_file'] = $Upload_file_array;

			// $extendons_thankyou_pagedata = get_post_meta($extendons_order_id, 'extendons_cart_order_data', true);

			$order = wc_get_order($extendons_order_id);
			$extendons_thankyou_pagedata = $order->get_meta('extendons_cart_order_data', true);
			// wc_get_logger()->debug( ': main_array' . print_r($main_array, true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
			

			// // LOG
			// wc_get_logger()->debug( 'extendons_thankyou_pagedata: ' . print_r( $extendons_thankyou_pagedata, true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
			// wc_get_logger()->debug( 'if btn_id: ' . print_r( $btn_id, true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
			 
			// echo json_encode(array('success' => true, 'data' => $extendons_thankyou_pagedata));
			// die();
			if (!empty($extendons_thankyou_pagedata)) {

				foreach ($extendons_thankyou_pagedata as $cart_item_key => $extendons_upload_file_cart_item) {


					if ($cart_item_key == $cart_key) {

						// wc_get_logger()->debug( ': extendons_upload_file_cart_item befooooooorrrrrrrr' . print_r($extendons_upload_file_cart_item['extendons_upload_file_data'], true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
				
						// [$rule_index_id][$btn_key]['uploaded_file'][$index_array]
						// wc_get_logger()->debug( ': already' . print_r($extendons_upload_file_cart_item['extendons_upload_file_data'], true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
						if (
							!empty($extendons_upload_file_cart_item['extendons_upload_file_data']) && 
							isset($extendons_upload_file_cart_item['extendons_upload_file_data']) && 
							isset($extendons_upload_file_cart_item['extendons_upload_file_data'][$rule_index_id][$btn_key]['uploaded_file'][$index_array]) 
						) {
							$extendons_upload_file_cart_item['extendons_upload_file_data'][$rule_index_id][$btn_key]['uploaded_file'][$index_array] = $main_array['uploaded_file'];
						}                       
						$extendons_thankyou_pagedata[$cart_item_key] = $extendons_upload_file_cart_item;
					}                           
				}

				foreach ($extendons_thankyou_pagedata as $cart_item_key => $values) {
					 
					if ($cart_item_key == $cart_key) {

						if (!empty($values['extendons_upload_file_data'])) {
							$extendons_keys = 0;
							foreach ($values['extendons_upload_file_data'] as $rule_id => $cartitem) {
								
								
								// wc_get_logger()->debug( 'second loop: cartitem  ' . print_r( $cartitem, true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );


								foreach ($cartitem as $btn_id => $data_item) {
									
									$extendons_order_html='';   
									$extendons_order_htmls = '';
									$order_data = array();
							
									if (!empty($data_item['uploaded_file'])) {
										$Uploaded_meta_key = 'Uploaded File ' . $extendons_keys;
										// PIN: status change 3
										// $Uploaded_filestatus_key = 'Status ' . $extendons_keys . $item_id;
										$Uploaded_filestatus_key = 'Status ' . $extendons_keys . '_' . $item_id;
										// $Uploaded_ebtn_key = 'Edit Btn ' . $extendons_keys;
										$Uploaded_ebtn_key = 'Edit Btn ' . $btn_key;
										// wc_get_logger()->debug( 'Uploaded_ebtn_key: ' . print_r( $Uploaded_ebtn_key, true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
										// wc_get_logger()->debug( 'btn_id: ' . print_r( $btn_id, true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
			
										$customer_nmeta_key = 'Customer Note' . $extendons_keys;
										// wc_get_logger()->debug( ': data_item' . print_r($data_item['uploaded_file'], true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
										foreach ($data_item['uploaded_file'] as $key1 => $value) {
											// wc_get_logger()->debug( 'loop3: data_item' . print_r( "------data_item---------", true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );

											$extendons_upload_files_val='';
											$extendons_upload_btn_val = '';
											$extendons_upload_filename_pathx = filter_var($value['extendons_upload_files_cartfile_url']);
											$ext = pathinfo($value['extendons_upload_files_cartfilename'], PATHINFO_EXTENSION); 
											if ('image/jpg'== $value['extendons_upload_files_carttype'] || 'image/png'== $value['extendons_upload_files_carttype'] || 'image/jpeg'== $value['extendons_upload_files_carttype'] || 'image/svg'== $value['extendons_upload_files_carttype'] || 'image/gif'== $value['extendons_upload_files_carttype']) {
												$extendons_upload_files_val.=  '<a href="' . esc_url($extendons_upload_filename_pathx) . '" class="extendons_thankyou_page1" target="_blank" typee="application/' . $ext . '"">' . esc_html__('Preview Image', 'extendons_Upload_Files') . '</a><a href="' . esc_url($extendons_upload_filename_pathx) . '" download><button class="btn extendons_download" style="padding:7px;border:none;cursor:pointer;"><i class="fa fa-download"></i>' . esc_html__('Download', 'extendons_Upload_Files') . '</button></a>';
											} else {

												$extendons_upload_files_val .=  '<a href="' . esc_url($extendons_upload_filename_pathx) . '" class="extendons_thankyou_page1" target="_blank" typee="application/' . $ext . '"">' . esc_html__('Preview', 'extendons_Upload_Files') . '</a><a href="' . esc_url($extendons_upload_filename_pathx) . '" download><button class="btn extendons_download" style="padding:7px;border:none;cursor:pointer;"><i class="fa fa-download"></i> Download</button>
												</a>';
											}

											array_push($order_data, $extendons_upload_files_val);
											
										}

										$extendons_order_html.= '<ul class="data-order-data-extendons">';
										foreach ($order_data as $key123 => $value123) {
											$extendons_order_html.= '<li class="extendons_order_data">' . $value123 . '</li>';
										}
										$extendons_order_html.= '</ul">';

										
										wc_update_order_item_meta($item_id, $Uploaded_meta_key, $extendons_order_html);

										$extendons_upload_btn_val = '<button data-item-id = "' . $item_id . '" data-rule-id = "' . $rule_id . '" data-btn-id ="' . $btn_key . '" type="button" data-p-id="' . $values['product_id'] . '" data-cart-key="' . $values['key'] . '"  data-id="' . $data_item['rule_id'] . '" data-page_type="data-thankyou-page" class="Click-here extendons_upload_file_order_data">' . esc_html__('Modify FIle', 'extendons_Upload_Files') . '</button>';  

										// // LOG
										// wc_get_logger()->debug( 'wc_update_order_item_meta: ' . print_r( [
										//  'item_id' => $item_id, 
										//  'Uploaded_ebtn_key' => $Uploaded_ebtn_key, 
										//  'extendons_upload_btn_val' => $extendons_upload_btn_val
										// ], true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );
			
										 
										
										$extendons_upload_files_vals = '<button type="button" data-product-id = "' . $values['product_id'] . '"  data-attr="' . $item_id . '" class="btn btn-success btn-sm extendons_file_accept" id="" style="padding:7px;opacity:0;border:none;cursor:pointer;background-color:white;color:white;" data-rule-id ="' . $keys . '" value="' . $extendons_keys . '">' . esc_html__('Accept', 'extendons_Upload_Files') . '</button><button type="button" data-product-id="' . $values['product_id'] . '"  data-attr="' . $item_id . '" class="btn btn-danger btn-sm extendons_file_reject" id="extendons_reject_btn" style="padding:7px;opacity:0;border:none;cursor:pointer;background-color:white;color:white;margin-left:10px;" data-rule-id ="' . $keys . '" value="' . $extendons_keys . '">' . esc_html__('Reject', 'extendons_Upload_Files') . ' </button>';

										$extendons_order_htmls.= '<ul class="data-order-dataar-extendons">';
										$extendons_order_htmls.= '<li class="extendons_order_data">' . $extendons_upload_files_vals . '</li>';
										$extendons_order_htmls.= '</ul">';  


										// Status : accept or reject
										// Edit Btn : Modify File or message if accepted or rejected
										// Uploaded File : Downlaod preview

										$a = wc_get_order_item_meta($item_id, $Uploaded_filestatus_key);
										// PIN: SN2
										$accepted_rejected = wc_get_order_item_meta($item_id, 'accepted_rejected-' . $extendons_keys);

										wc_update_order_item_meta($item_id, $Uploaded_ebtn_key, $extendons_upload_btn_val);

										// if (strpos($a, 'Rejected') !== false ) {
										if ('rejected'  ==  $accepted_rejected) {
											wc_update_order_item_meta($item_id, $Uploaded_filestatus_key, $extendons_order_htmls);
											
											// PIN: SN3
											wc_delete_order_item_meta($item_id, 'accepted_rejected-' . $extendons_keys);
											global $post, $woocommerce, $wpdb;
											$admin_email = get_option('admin_email');
											if (is_user_logged_in()) {
												$current_user = wp_get_current_user();
												$user_email=  $current_user->user_email;
											} else {
												$user_email ='guest';
											}

											$to = $admin_email;
											$message = '<h4>Customer Upload Files For Woocommerce</h4><p>Order id:' . $extendons_order_id . '</p>';
											$subject = 'Upload Files Status';
											$headers = 'From:' . wp_strip_all_tags($user_email) . "\r\n";
											$headers .= "MIME-Version: 1.0\r\n";
											$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
											$message .= '<html><body>';
											$message.= wc_get_order_item_meta($item_id[$i], $Uploaded_meta_key);    
											$message.= '<a href="' . admin_url() . 'post.php?post=' . $extendons_order_id . '&action=edit">view order details</a>';
											$message .= '</body></html>';
										}

										if ('' != wc_get_order_item_meta($item_id[$i], $Uploaded_meta_key)) {
											wp_mail( $to, $subject, $message, $headers);
										}   
									
										if ( isset( $data_item['extendons_customer_note'] ) && '' != $data_item['extendons_customer_note']) {
											// // LOG
											// wc_get_logger()->debug( 'before 2: $data_item[extendons_customer_not ' . print_r( $$data_item['extendons_customer_note'], true ), array( 'source' => 'file-uploader-for-woocommerce' ) );

											wc_update_order_item_meta($item_id, $customer_nmeta_key, $data_item['extendons_customer_note']);
										}
										$extendons_keys++;
									}
								}
							}
						}
					}
				}   
				// update_post_meta($extendons_order_id, 'extendons_cart_order_data', $extendons_thankyou_pagedata);
				
				
				// wc_get_logger()->debug( ': bfor saving, extendons_thankyou_pagedata' . print_r($extendons_thankyou_pagedata, true ), array( 'source' => 'file-uploader-for-woocommerce-modify-file' ) );

				$extendons_uploadfiles_order = wc_get_order($extendons_order_id);
				$extendons_uploadfiles_order->update_meta_data('extendons_cart_order_data', $extendons_thankyou_pagedata);
				$extendons_uploadfiles_order->save();


			 
			} 



			wp_die();
		}


		/**
		 * Add custom column to WooCommerce orders list.
		 *
		 * This function adds a custom column to the WooCommerce orders list in the admin area.
		 * The new column displays the number of files uploaded for each order.
		 *
		 * @since 1.0.0
		 * @param array $extendons_columns The existing columns in the orders list.
		 * @return array The modified columns array with the new column added.
		 */
		public function custom_shop_order_column( $extendons_columns ) {
			$extendons_reordered_columns = array();
			foreach ( $extendons_columns as $key => $column) {
				$extendons_reordered_columns[$key] = $column;
				if ('order_status'==$key ) {
					$extendons_reordered_columns['extendons_upload_counter'] = esc_html__( 'Upload Counter', 'extendons_Upload_Files');
				}
			}
			return $extendons_reordered_columns;
		}
		
		/**
		 * Add custom column content for the orders list table.
		 *
		 * This function is responsible for displaying the custom column content
		 * for the "extendons_upload_counter" column in the WooCommerce orders list table.
		 *
		 * @since 1.0.0
		 * @param string $column The name of the column being displayed.
		 * @param int $post_id The ID of the post (order) being displayed.
		 * @return void
		 */
		public function custom_orders_list_column_content( $column, $post_id ) {
			switch ( $column ) {
				case 'extendons_upload_counter':
					global $post;
					$extendons_upload_files_order_id = $post->ID;
					$extendons_uploadfiles_order = new WC_Order( $extendons_upload_files_order_id );
				
					// $extendons_upload_counter = get_post_meta( $post_id, 'extendons_total', true );
				
					$order = wc_get_order($post_id);
					$extendons_upload_counter = $order->get_meta('extendons_total', true);                  
				
					$yesdisplayit=false;
					$items = $extendons_uploadfiles_order->get_items();
					foreach ( $items as $item ) {
						if (!empty($item['_extendons_upload_count']) || 0 < $extendons_upload_counter) {
							$yesdisplayit=true;
							break;
						}
					}
					if ($yesdisplayit) {
						if (!empty($extendons_upload_counter)) {
							echo esc_attr($extendons_upload_counter);
						} else {
							echo esc_html__('0', 'extendons_Upload_Files');
						}
					} break;
			}
		}
		

		/**
		 * Handle file uploads for the plugin
		 *
		 * This function processes the file uploads and associated data for the File Uploader for WooCommerce plugin.
		 *
		 * @since 1.0.0
		 *
		 * @param array $request_data The request data containing file uploads and associated metadata.
		 *
		 * @return array $main_array An array containing the processed file upload data and metadata.
		 */
		public function extendons_ext_upload_file() {

			// $retrieved_nonce =  isset( $_REQUEST['requestId'] ) ? sanitize_text_field($_REQUEST['requestId']) : 0;
			// if (! wp_verify_nonce($retrieved_nonce, 'ext_fu_ajax_nonce') ) {
			//  http_response_code(401);
			//  die('Failed security check');
			// }
 
			$nonce = wp_create_nonce('ext_fu_ajax_nonce');
	  
			 
			if (! wp_verify_nonce($nonce, 'ext_fu_ajax_nonce') ) {
				http_response_code(401);
				die('Failed security check');
			}
 
			$i= 0;
			global $wp_session;
			session_start();
			$post_id = isset($_REQUEST['postid']) ? filter_var(  wp_unslash($_REQUEST['postid'] )) : '';
			$productid = isset($_REQUEST['productid']) ? filter_var( wp_unslash($_REQUEST['productid'])) : '';
			$indexkey = isset($_REQUEST['indexkey']) ? filter_var( wp_unslash($_REQUEST['indexkey'])) : '';
			$data_rule_id = isset($_REQUEST['data_rule_id']) ? filter_var( wp_unslash($_REQUEST['data_rule_id'])) : '';
			$pagetype = isset($_REQUEST['pagetype']) ? filter_var( wp_unslash($_REQUEST['pagetype'])) : '';
			$cart_key = isset($_REQUEST['cart_key']) ? filter_var( wp_unslash($_REQUEST['cart_key'])) : '';
			$extendons_customer_note = isset($_REQUEST['extendons_customer_note']) && 'undefined' != $_REQUEST['extendons_customer_note']? filter_var( wp_unslash($_REQUEST['extendons_customer_note'])) : '';
			$extendons_upload_files_multival_arr = get_post_meta($post_id , 'extendons_multiple_files_limit', true);
			$extendons_upload_files_display_on_position =  get_post_meta($post_id, 'extendons_display_on_values', true);
 

			// Prepare response data
			$response_data = array(
				'success' => true,
				'message' => 'File upload successful',
				'request_data' => $_REQUEST,
			);

			
	
			if ('' == $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files']) {
				$max_upload_file = '1';     
			} else {
				$max_upload_file = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files'];
			}

			$main_array = array(
				'rule_id' => $post_id,
				'max_upload_size' => ( '' != $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_size_value'] ) ? $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_size_value']:'1',   
				'extendons_file_upload_max_upload_file' => ( '' != $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files'] ) ? $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files']:'1',
				'extendons_file_upload_btn_text' =>  ( '' != $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_btn'] )? $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_btn']: 'Upload File',
				'extendons_file_upload_label' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_label'],
				'extendons_uploadfiles_price' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_price'],
				'extendons_uploadfiles_discount_type' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_discount_type'],
				'extendons_uploadfiles_discount_price' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_discount_price'],
				'extendons_uploadfiles_file_multiply_by_quantity' => $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_multiply_by_quantity'],
				'extendons_maximum_upload_files' => $max_upload_file,
				'extendons_total_upload_files' => count($_FILES),
				'extendons_customer_note' => $extendons_customer_note,
				'extendons_btn_background_color'=> $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_background_color'],
				'extendons_btn_text_color'=> $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_color'],

			);

			// print_r($main_array);

			$Upload_file_array = array();
			$extendons_upload_files_additional_settings = get_option('extendons_upload_additional_settings', array());
			$custom_folder_path = !empty($extendons_upload_files_additional_settings['extendons_upload_folder_path']) 
				? rtrim(ABSPATH . $extendons_upload_files_additional_settings['extendons_upload_folder_path'], '/\\') . '/' 
				: '';

			$use_custom_path = false;
			if (!empty($custom_folder_path)) {
				// Ensure the folder exists; create it if it doesn't
				if (!is_dir($custom_folder_path)) {
					wp_mkdir_p($custom_folder_path); // Create directory with proper permissions
				}

				// Check if the folder is writable
				if (is_dir($custom_folder_path) && is_writable($custom_folder_path)) {
					$use_custom_path = true;
					$upload_dir = $custom_folder_path;
					$upload_url = site_url(str_replace(ABSPATH, '', $custom_folder_path));
				}
			}

			if (!$use_custom_path) {
				// Fallback to WordPress media upload directory
				$uploads = wp_upload_dir(null, true);
				$upload_dir = $uploads['path'];
				$upload_url = $uploads['url'];
			}
	
			if (isset($_FILES) && !empty($_FILES)) {
				wc_get_logger()->info('FILES array: ' . print_r($_FILES, true));
				foreach ($_FILES as $key => $_file) {
					if (isset($_file['name']) && '' !== $_file['name']) { 
						$extendons_filename = stripslashes(str_replace('#', '-', $_file['name']));
						$ext = pathinfo($extendons_filename, PATHINFO_EXTENSION);

						$target_dir = $upload_dir;

						// Generate a unique filename
						$unique_filename = wp_unique_filename($target_dir, $extendons_filename);
						$target_path = rtrim($target_dir, '/\\') . '/' . $unique_filename;

						// Move uploaded file
						if (move_uploaded_file($_file['tmp_name'], $target_path)) {
							$extendons_upload_files_url = rtrim($upload_url, '/') . '/' . $unique_filename;

							$Upload_file_array[$i] = array(
								'extendons_post_id' => $post_id,
								'btn_id' => $indexkey,
								'extendons_upload_files_cartfilename' => $unique_filename,
								'extendons_upload_files_cartfilesize' => $_file['size'],
								'extendons_upload_files_carttmp_name' => $_file['tmp_name'],
								'extendons_upload_files_carttype' => $_file['type'],
								'extendons_upload_files_cartfile_url' => $extendons_upload_files_url,
							);
						}
					}
					$i++;
				}
			}
			$main_array['extendons_upload_files_display_on_position'] = $extendons_upload_files_display_on_position;
			$main_array['uploaded_file'] = $Upload_file_array;

			// Start session if needed
			if ( ! WC()->session->has_session() ) {
				WC()->session->set_customer_session_cookie( true );
			}

			if (empty($_FILES) && !is_user_logged_in()) {
				$session_value = WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey);

				$guestId = WC()->session->get( 'guest_id' );
				$response_data['update_option'] = '1';
				// PIN: critical
				$guestId = $guestId . '_' . $post_id . $productid . $indexkey;
				
				update_option($guestId, WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey));
			}

			if ( !empty( $_FILES) ) {

				if ( 'productpage' == $pagetype) {
					$session_value = WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey);
					$response_data['session_key_get'] = 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey;
					$response_data['session_value'] = $session_value;
					$response_data['key'] =  'extendons_upload_files_sessions' . $post_id . $productid . $indexkey;
					if ( isset( WC()->session ) ) {
						
						if (isset($session_value) && '0' == $session_value['extendons_total_upload_files'] || '' == $session_value) {
							$response_data['session_set'] = '1';
							$session_value = WC()->session->set( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey, $main_array);
						} else {
							$session_value['extendons_btn_text_color']= $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_color'];
							$session_value['extendons_btn_background_color'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_background_color'];
							$session_value['rule_id'] = $post_id;
							$session_value['uploaded_file'] = array_merge($session_value['uploaded_file'], $main_array['uploaded_file']);
							$session_value['extendons_customer_note'] = $extendons_customer_note;
							$session_value['extendons_total_upload_files'] = count($session_value['uploaded_file']);
							$session_value['extendons_file_upload_label'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_label'];
							$session_value['extendons_file_upload_btn_text'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_btn'];

							$response_data['session_set'] = '2';
							WC()->session->set( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey, $session_value);
						}
					}


					$response_data['extendons_upload_files_sessions' . $post_id . $productid . $indexkey] = WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey);
					$response_data['is_user_logged_in'] = is_user_logged_in();
					if (!empty(WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey)) && !is_user_logged_in()) {
						$guestId = WC()->session->get( 'guest_id');

						// print_r(WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey));
						$response_data['update_option'] = '1';

						// PIN: critical
						$guestId = $guestId . '_' . $post_id . $productid . $indexkey;
						update_option($guestId, WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey));
					} 
					
				} else {

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						
						if ($cart_item_key == $cart_key ) {

							if (''==$extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files']) {
								$max_upload_file = '1';     
							} else {
								$max_upload_file = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files'];
							}

							if (empty($cart_item['extendons_upload_file_data'])) {

								$main_array['extendons_btn_text_color']= $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_color'];
								$main_array['extendons_btn_background_color'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_background_color'];
								$main_array['rule_id'] = $post_id;
								$main_array['extendons_total_upload_files'] = '0';
								$main_array['extendons_maximum_upload_files'] = $max_upload_file;
								$main_array['extendons_file_upload_label'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_label'];
								$main_array['extendons_file_upload_btn_text'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_btn'];

							} elseif (!empty($cart_item['extendons_upload_file_data']) && '0'== $cart_item['extendons_upload_file_data'][$data_rule_id][$indexkey]['extendons_total_upload_files']) {

									WC()->cart->cart_contents[$cart_item_key]['extendons_upload_file_data'][$data_rule_id][$indexkey] = $main_array;

							} else {
									
								$cartvalue = $cart_item['extendons_upload_file_data'][$data_rule_id][$indexkey];
								$cartvalue['uploaded_file'] = array_merge($cartvalue['uploaded_file'], $main_array['uploaded_file']);
								$cartvalue['extendons_customer_note'] = $extendons_customer_note;
								$cartvalue['extendons_total_upload_files'] = count($cartvalue['uploaded_file']);
								WC()->cart->cart_contents[$cart_item_key]['extendons_upload_file_data'][$data_rule_id][$indexkey] = $cartvalue;

							}
						}
					}
					WC()->cart->set_session();
				}
			} elseif ( 'productpage' == $pagetype) {

				$session_value = WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey);
				if ( isset( WC()->session ) ) {
					
					if (isset($session_value) && '0' != $session_value['extendons_total_upload_files']) {
	
						$session_value['extendons_customer_note'] = $extendons_customer_note;
						$response_data['session_set'] = '3';
						$response_data['total_upload_files'] = $session_value['extendons_total_upload_files'] ?? 0;
						WC()->session->set( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey, $session_value);
					}
				}

			} else {

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						
					if ($cart_item_key == $cart_key ) {

						if (!empty($cart_item['extendons_upload_file_data']) && '0' != $cart_item['extendons_upload_file_data'][$data_rule_id][$indexkey]['extendons_total_upload_files']) {

							$cartvalue = $cart_item['extendons_upload_file_data'][$data_rule_id][$indexkey];
							$cartvalue['extendons_customer_note'] = $extendons_customer_note;

							WC()->cart->cart_contents[$cart_item_key]['extendons_upload_file_data'][$data_rule_id][$indexkey] = $cartvalue;
						}
							
					}
				}
				WC()->cart->set_session();
			}


			// Send JSON response
			wp_send_json($response_data);
			exit;

			wp_die();
		}

 

		/**
		 * Delete an uploaded file from the cart.
		 *
		 * This function handles the deletion of an uploaded file from the cart. It removes the file
		 * from the cart item's data and updates the product price accordingly.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function extendons_upload_ccta_delete_file() {

			$post_id = isset($_REQUEST['datapostid']) ? filter_var( wp_unslash($_REQUEST['datapostid'])) : '';
			$productid = isset($_REQUEST['dataproid']) ? filter_var( wp_unslash($_REQUEST['dataproid'])) : '';
			$keyval = isset($_REQUEST['indexkey']) ? filter_var( wp_unslash($_REQUEST['indexkey'])) : '';    
			$rule_id_index = isset($_REQUEST['rule_id_index']) ? filter_var( wp_unslash($_REQUEST['rule_id_index'])) : '';   
			$delete_cart_key = isset($_REQUEST['delete_cart_key']) ? filter_var( wp_unslash($_REQUEST['delete_cart_key'])) : ''; 
			$arrayindex = isset($_REQUEST['arrindex']) ? filter_var( wp_unslash($_REQUEST['arrindex'])) : '';    
			$extendons_order_id = isset($_REQUEST['extendons_order_id']) ? filter_var( wp_unslash($_REQUEST['extendons_order_id'])) : '';    
			$pagetype = isset($_REQUEST['pagetype']) ? filter_var( wp_unslash($_REQUEST['pagetype'])) : '';  
			$extendons_upload_files_multival_arr = get_post_meta($post_id , 'extendons_multiple_files_limit', true);
			$extendons_upload_files_display_on_position =  get_post_meta($post_id, 'extendons_display_on_values', true);
			$main_array = array();

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

				if ($cart_item_key == $delete_cart_key) {
					unset($cart_item['extendons_upload_file_data'][$rule_id_index][$keyval]['uploaded_file'][$arrayindex]);
					if ( empty( $cart_item['extendons_upload_file_data'][$rule_id_index][$keyval]['uploaded_file']) ) {

						$main_array['extendons_btn_text_color']= $extendons_upload_files_multival_arr[$keyval]['extendons_uploadfiles_file_text_color'];
						$main_array['extendons_btn_background_color'] = $extendons_upload_files_multival_arr[$keyval]['extendons_uploadfiles_file_background_color'];
						$main_array['extendons_upload_files_display_on_position'] = $extendons_upload_files_display_on_position;
						$main_array['rule_id'] = $post_id;
						$main_array['extendons_total_upload_files'] = '0';
						$main_array['extendons_maximum_upload_files'] = $extendons_upload_files_multival_arr[$keyval]['extendons_uploadfiles_file_maximum_upload_files'];
						$main_array['extendons_file_upload_label'] = $extendons_upload_files_multival_arr[$keyval]['extendons_uploadfiles_label'];
						$main_array['extendons_file_upload_btn_text'] = $extendons_upload_files_multival_arr[$keyval]['extendons_uploadfiles_file_text_btn'];


						$product = $cart_item['data'];
						$extendons_uploadfiles_product = wc_get_product( $cart_item['product_id'] );
						$product_type = $extendons_uploadfiles_product->get_type();
						if ('simple'==$product_type) {
							$extendons_product_price_val = get_post_meta($cart_item['product_id'], '_sale_price', true);
							if ('' == $extendons_product_price_val || 0 == $extendons_product_price_val) {
								$extendons_product_price_val= get_post_meta($cart_item['product_id'], '_regular_price', true);
							}
						} else if ( 'variable'==$product_type ) {

							$extendons_product_price_val = get_post_meta($cart_item['variation_id'], '_sale_price', true);
							if ('' == $extendons_product_price_val || 0 == $extendons_product_price_val) {
								$extendons_product_price_val= get_post_meta($cart_item['variation_id'], '_regular_price', true);
							}
						}

						$final_price = floatval($extendons_product_price_val)-floatval($cart_item['extendons_file_price_added']);
						$cart_item['data']->set_price( $final_price );  
						WC()->cart->cart_contents[$cart_item_key]['extendons_upload_file_data'][$rule_id_index][$keyval] = $main_array;

					} else {

						$cartvalue = $cart_item['extendons_upload_file_data'][$rule_id_index][$keyval];
						$cartvalue['extendons_total_upload_files'] = count($cartvalue['uploaded_file']);
						WC()->cart->cart_contents[$cart_item_key]['extendons_upload_file_data'][$rule_id_index][$keyval] = $cartvalue;

					}
				}
			}
			WC()->cart->set_session();
			wp_die();
		}


		/**
		 * Delete an uploaded file from the session
		 *
		 * This function is responsible for deleting an uploaded file from the session data.
		 * It retrieves various parameters from the request, updates the session data accordingly,
		 * and echoes a boolean value indicating whether the uploaded file array is empty or not.
		 *
		 * @since 1.0.0             *
		 * @return void
		 */
		public function extendons_upload_delete_file() {

			$post_id = isset($_REQUEST['datapostid']) ? filter_var( wp_unslash($_REQUEST['datapostid'])) : '';
			$productid = isset($_REQUEST['dataproid']) ? filter_var( wp_unslash($_REQUEST['dataproid'])) : '';
			$indexkey = isset($_REQUEST['indexkey']) ? filter_var( wp_unslash($_REQUEST['indexkey'])) : '';  
			$arrayindex = isset($_REQUEST['arrindex']) ? filter_var( wp_unslash($_REQUEST['arrindex'])) : '';    
			$customer_note = isset($_REQUEST['customer_note']) ? filter_var( wp_unslash($_REQUEST['customer_note'])) : '';   
			$extendons_upload_files_multival_arr = get_post_meta($post_id , 'extendons_multiple_files_limit', true);
			$main_array = WC()->session->get( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey );

			if (!is_user_logged_in() && empty($main_array)) {
				$guestId = WC()->session->get( 'guest_id' );
				$guestId = $guestId . '_' . $post_id . $productid . $indexkey;
				$main_array = get_option($guestId);
			}

			unset($main_array['uploaded_file'][$arrayindex]);
			if (empty($main_array['uploaded_file'])) {
				$main_array['extendons_btn_text_color']= $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_color'];
				$main_array['extendons_btn_background_color'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_background_color'];
				$main_array['extendons_total_upload_files'] = '0';
				$main_array['extendons_maximum_upload_files'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_maximum_upload_files'];
				$main_array['extendons_customer_note'] = $customer_note;
				$main_array['extendons_file_upload_label'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_label'];
				$main_array['extendons_file_upload_btn_text'] = $extendons_upload_files_multival_arr[$indexkey]['extendons_uploadfiles_file_text_btn'];

				WC()->session->set( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey, $main_array);
			} else {
				$main_array = $main_array;
				$main_array['extendons_customer_note'] = $customer_note;
				$main_array['extendons_total_upload_files'] = count($main_array['uploaded_file']);
				WC()->session->set( 'extendons_upload_files_sessions' . $post_id . $productid . $indexkey, $main_array);
			}

			if (empty($main_array['uploaded_file'])) {
				echo true;
			}   
			wp_die();
		}


		/**
		 * Render the upload files button on cart/checkout page
		 *
		 * This function handles rendering the upload files button on the cart and checkout pages.
		 * It retrieves various settings and metadata related to the upload files functionality
		 * and includes the necessary template file to display the button.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function extendons_uploadfiles_ccta_order_btn_() {

			$data_allow_upload_file = isset($_REQUEST['data_allow_upload_file']) ? filter_var( wp_unslash($_REQUEST['data_allow_upload_file'])) : '1';
			$extendons_upload_files_postid =  isset($_REQUEST['extendons_post_id']) ? filter_var( wp_unslash($_REQUEST['extendons_post_id'])) : '';
			$index_key =  isset($_REQUEST['index_key']) ? filter_var(  wp_unslash( $_REQUEST['index_key'])) : '';
			$data_rule_index_id =  isset($_REQUEST['data_rule_index_id']) ? filter_var( wp_unslash($_REQUEST['data_rule_index_id'])) : '';
			$productid =  isset($_REQUEST['productid']) ? filter_var( wp_unslash($_REQUEST['productid'])) : '';
			$pagetype =  isset($_REQUEST['pagetype']) ? filter_var( wp_unslash($_REQUEST['pagetype'])) : '';
			$cart_key =  isset($_REQUEST['cart_key']) ? filter_var( wp_unslash($_REQUEST['cart_key'])) : '';
			$extendons_order_id =  isset($_REQUEST['extendons_order_id']) ? filter_var( wp_unslash($_REQUEST['extendons_order_id'])) : '';

			$extendons_upload_files_get_status =  get_post_meta($extendons_upload_files_postid, 'extendons_enable_disable_settings', true);
			$extendons_rule_priority =  get_post_meta($extendons_upload_files_postid, 'extendons_rule_priority', true);
			$extendons_upload_files_display_on_position =  get_post_meta($extendons_upload_files_postid, 'extendons_display_on_values', true);
			$extendons_selected_product_category = get_post_meta($extendons_upload_files_postid, 'extendons_selected_product_category', true); 
			$extendons_selected_product_item = get_post_meta($extendons_upload_files_postid, 'extendons_selected_items', true);
			$extendons_selected_user_roles = get_post_meta($extendons_upload_files_postid, 'extendons_selected_user_role', true); 
			$extendons_upload_files_multival_arr = get_post_meta($extendons_upload_files_postid , 'extendons_multiple_files_limit', true);
			
			require_once EXTENDONS_UF_PLUGIN_ADMIN_TEMPLATE_DIR . 'extendons_uploadfiles_ccta_order_btn_.php' ;
			 
			wp_die();
		}

 


		/**
		 * Displays the file upload popup
		 *
		 * This function handles the display of the file upload popup on the frontend.
		 * It retrieves various settings and metadata related to the file upload functionality
		 * and includes the necessary template file to render the popup.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		// PIN: popup
		public function extendons_upload_file_popup() {
			$data_allow_upload_file = isset($_REQUEST['data_allow_upload_file']) ? filter_var( wp_unslash($_REQUEST['data_allow_upload_file'])) : '1';
			$extendons_upload_files_postid =  isset($_REQUEST['extendons_post_id']) ? filter_var( wp_unslash($_REQUEST['extendons_post_id'])) : '';
			$index_key =  isset($_REQUEST['index_key']) ? filter_var( wp_unslash($_REQUEST['index_key'])) : '';
			$productid =  isset($_REQUEST['productid']) ? filter_var( wp_unslash($_REQUEST['productid'])) : '';
			$pagetype =  isset($_REQUEST['pagetype']) ? filter_var( wp_unslash($_REQUEST['pagetype'])) : '';

			$extendons_upload_files_get_status =  get_post_meta($extendons_upload_files_postid, 'extendons_enable_disable_settings', true);
			$extendons_rule_priority =  get_post_meta($extendons_upload_files_postid, 'extendons_rule_priority', true);
			$extendons_upload_files_display_on_position =  get_post_meta($extendons_upload_files_postid, 'extendons_display_on_values', true);
			$extendons_selected_product_category = get_post_meta($extendons_upload_files_postid, 'extendons_selected_product_category', true); 
			$extendons_selected_product_item = get_post_meta($extendons_upload_files_postid, 'extendons_selected_items', true);
			$extendons_selected_user_roles = get_post_meta($extendons_upload_files_postid, 'extendons_selected_user_role', true); 
			$extendons_upload_files_multival_arr = get_post_meta($extendons_upload_files_postid , 'extendons_multiple_files_limit', true);

			require_once EXTENDONS_UF_PLUGIN_ADMIN_TEMPLATE_DIR . 'extendons_upload_file_popup.php' ;
			wp_die();
		}



		/**
		 * Get upload file rules from the database.
		 *
		 * This function retrieves the upload file rules from the WordPress database
		 * and returns them as an array.
		 *
		 * @since 1.0.0
		 * @return array $extendons_upload_Files_rules_array An array of upload file rules.
		 */
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
				$extendons_get_status =  get_post_meta($extendons_upload_files_postid, 'extendons_enable_disable_settings', true);
				$extendons_upload_files_display_on_position =  get_post_meta($extendons_upload_files_postid, 'extendons_display_on_values', true);
				$extendons_upload_files_selected_files_type =  get_post_meta($extendons_upload_files_postid, 'extendons_selection_files', true);
				$extendons_upload_files_multival = get_post_meta($extendons_upload_files_postid , 'extendons_multiple_files_limit', true);
				$extendons_upload_files_allowed_file_type = get_post_meta($extendons_upload_files_postid, 'extendons_allowed_file_types', true); 
				$extendons_upload_files_size = get_post_meta($extendons_upload_files_postid, 'extendons_file_size', true); 
				$extendons_upload_files_maximum_file_size = get_post_meta($extendons_upload_files_postid, 'extendons_maximum_uploadsize', true); 
				$extendons_upload_files_selected_pc_type = get_post_meta($extendons_upload_files_postid, 'extendons_selected_product_category', true); 
				$extendons_upload_files_selected_pc = get_post_meta($extendons_upload_files_postid, 'extendons_selected_items', true);
				$extendons_upload_file_user_roles = get_post_meta($extendons_upload_files_postid, 'extendons_selected_user_role', true); 

				$extendons_multiple_by_quantity = get_post_meta($extendons_upload_files_postid, 'extendons_multiple_by_quantity', true);
				$extendons_upload_file_text = get_post_meta($extendons_upload_files_postid, 'extendons_upload_file_text', true);
				$extendons_upload_file_color = get_post_meta($extendons_upload_files_postid, 'extendons_upload_file_color', true);

				$extendons_upload_files_title = get_post_meta($extendons_upload_files_postid, 'extendons_up_rule_name', true);

				$extendons_upload_files_rules = array(
					'extendons_post_id' => $extendons_upload_files_postid,
					'extendons_rule_title' => $extendons_upload_files_title,
					'extendons_upload_files_enable_disable_status' => $extendons_get_status,
					'extendons_upload_files_display_on_position' => $extendons_upload_files_display_on_position,
					'extendons_upload_files_selected_files_type' => $extendons_upload_files_selected_files_type,
					'extendons_upload_files_multiple_files_limit' => $extendons_upload_files_multival,
					'extendons_allowed_file_types' => $extendons_upload_files_allowed_file_type,
					'extendons_upload_files_size_type' => $extendons_upload_files_size,
					'extendons_maximum_uploadfile_size' => $extendons_upload_files_maximum_file_size,
					'extendons_upload_files_selected_pc_type' => $extendons_upload_files_selected_pc_type,
					'extendons_upload_files_selected_pc' => $extendons_upload_files_selected_pc,
					'extendons_upload_files_selected_user_role' => $extendons_upload_file_user_roles,
					'extendons_multiple_by_quantity' => $extendons_multiple_by_quantity,
					'extendons_upload_file_text'=> $extendons_upload_file_text,
					'extendons_upload_file_color'=> $extendons_upload_file_color,
				);

				array_push($extendons_upload_Files_rules_array, $extendons_upload_files_rules);
			}

			return $extendons_upload_Files_rules_array;
		}



		/**
		 * Add File Uploader tab to WooCommerce settings
		 *
		 * @since 1.0.0
		 * @param array $tabs Existing WooCommerce settings tabs
		 * @return array $tabs Updated WooCommerce settings tabs
		 */
		public function fma_upload_files_woocommerce_settings_tabs_array( $tabs ) {
			$tabs['extendons_upload_files'] = __('File Uploader', 'extendons_Upload_Files');
			return $tabs;
		}

		/**
		 * Register the settings page for the plugin
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function fma_upload_files_settings() {
			require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/view/upload-files-admin-settings-page.php' ;
		}
 
 
		/**
		 * Register custom post type for upload files
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function extendons_upload_files_custom_posttype() {

			register_post_type( 'extend_upload_files',
				array(
					'labels' => array(
						'name' => esc_html__( 'extendons_Upload_Files' , 'extendons_Upload_Files'),
						'singular_name' => esc_html__( 'extendons_Upload_Files' , 'extendons_Upload_Files'),
					),
					'public' => true,
					'has_archive' => true,
					'rewrite' => array( 'slug' => 'extendons_Upload_Files' ),
					'show_in_rest' => true,
					'show_ui' => true,
					'show_in_menu'  => false,

				)
			);
		}


		/**
		 * Save general settings for file uploader
		 *
		 * @since 1.0.0
		 * @return int|WP_Error Post ID on success, WP_Error on failure
		 */

		//  PIN: setting ajax handler
		public function extendons_upload_files_save_general_settings() {


			 
			$extendons_enable_disable_setting = isset($_REQUEST['extendons_enable_disable_setting']) ? filter_var(  wp_unslash($_REQUEST['extendons_enable_disable_setting'])) : '';

			$extendons_up_rule_name = isset($_REQUEST['extendons_up_rule_name']) ? filter_var( wp_unslash($_REQUEST['extendons_up_rule_name'])) : '';
			$extendons_rule_priority = isset($_REQUEST['extendons_rule_priority']) ? filter_var( wp_unslash($_REQUEST['extendons_rule_priority'])) : '';

			$extendons_display_on_value = isset($_REQUEST['extendons_display_on_value']) ? stripslashes(filter_var( wp_unslash($_REQUEST['extendons_display_on_value']))) : '';  
			$extendons_display_on_value_array = json_decode($extendons_display_on_value, true);

			$extendons_multiple_files_limit = isset($_REQUEST['extendons_multiple_files_limit']) ? stripslashes(filter_var( wp_unslash($_REQUEST['extendons_multiple_files_limit']))) : '';  

			$extendons_upload_rule_array = json_decode($extendons_multiple_files_limit, true);

			print_r($extendons_upload_rule_array); 

			$extendons_selected_product_category = isset($_REQUEST['extendons_product_category']) ? filter_var( wp_unslash($_REQUEST['extendons_product_category'])) : '';
			$extendons_selected_items = ( isset($_REQUEST['extendons_selected_items']) && is_array($_REQUEST['extendons_selected_items']) ) ? array_map('filter_var', wp_unslash($_REQUEST['extendons_selected_items']) ): '';
			$extendons_selected_user_role =( isset($_REQUEST['extendons_selected_user_role']) && is_array( $_REQUEST['extendons_selected_user_role'] ) ) ? array_map('filter_var', wp_unslash($_REQUEST['extendons_selected_user_role'])) : '';

			$action = isset($_REQUEST['formaction']) ? filter_var( wp_unslash($_REQUEST['formaction'])): '';


			if ( 'add' == $action) {
				$extendons_post_id = wp_insert_post(
					array(
						'comment_status'    =>  'closed',
						'ping_status'       =>  'closed',
						'post_author'       =>  'extendons_Upload_Files',
						'post_name'     =>  'extendons_Upload_Files',
						'post_title'        =>  'extendons_Upload_Files',
						'post_status'       =>  'publish',
						'post_type'     =>  'extend_upload_files',
					)
				);
			} else if ( 'edit' == $action) {

				$extendons_post_id = isset($_REQUEST['extendons_edit_post_id_action']) ? filter_var( wp_unslash($_REQUEST['extendons_edit_post_id_action'])) : '';
			}

			// Save settings to post meta if post ID is set.
			if ($extendons_post_id) {
				update_post_meta($extendons_post_id, 'extendons_enable_disable_settings', $extendons_enable_disable_setting);
				update_post_meta($extendons_post_id, 'extendons_up_rule_name', $extendons_up_rule_name);
				update_post_meta($extendons_post_id, 'extendons_rule_priority', $extendons_rule_priority);
				update_post_meta($extendons_post_id, 'extendons_display_on_values', $extendons_display_on_value_array);
				update_post_meta($extendons_post_id, 'extendons_multiple_files_limit', $extendons_upload_rule_array);
				update_post_meta($extendons_post_id, 'extendons_selected_product_category', $extendons_selected_product_category);
				update_post_meta($extendons_post_id, 'extendons_selected_items', $extendons_selected_items);
				update_post_meta($extendons_post_id, 'extendons_selected_user_role', $extendons_selected_user_role);
			}

			echo 'here we';
			wp_die();
		}

		/**
		 * Enqueue admin scripts and styles
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function extendons_upload_files_admin_scripts() {

			if (isset($_GET['tab'])) {

				if (is_admin() && 'extendons_upload_files'== $_GET['tab']) {
					wp_enqueue_style('jquery');
					wp_enqueue_style( 'bootstrap-min-css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ), false , 1.1 );
					wp_enqueue_style( 'extendons_upload_files_setting_css', plugins_url( 'assets/css/Upload_Files_Admin.css', __FILE__ ), false , 1.0 );
					wp_enqueue_script( 'bootstrap-min-js', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), false, 1.1 );
					wp_enqueue_script( 'extendons_upload_files_setting_js', plugins_url( 'assets/js/extendons-upload-files-admin.js', __FILE__ ), false, '1.0.1' );
					wp_enqueue_script( 'select2-min-js', plugins_url( 'assets/js/select2.min.js', __FILE__ ), false, 1.1 );
					wp_enqueue_style( 'select2-min-css', plugins_url( 'assets/css/select2.min.css', __FILE__ ), false , 1.1 );
					wp_enqueue_script( 'colorpickerjs', plugins_url( 'assets/js/jscolor.js', __FILE__ ), false, 1.1 );      

					$ewcpm_data = array(
						'admin_url' => admin_url('admin-ajax.php'),
					);
					wp_localize_script('extendons_upload_files_setting_js', 'ewcpm_php_vars', $ewcpm_data);
					wp_localize_script('extendons_upload_files_setting_js', 'ajax_url_add_pq', array( 'ajax_url_add_pq_data' => admin_url('admin-ajax.php') ));
				}
			}
			wp_enqueue_script( 'extendons_upload_files_approved_js', plugins_url( 'assets/js/extendons_upload_files_approved.js', __FILE__ ), false, 1.1 );
			
			wp_enqueue_style( 'extendons_upload_files_admin_css', plugins_url( 'assets/css/extendons_style_admin.css', __FILE__ ), false , 1.2 );
			$ewcpm_data_appr = array(
				'admin_url' => admin_url('admin-ajax.php'),
				'rulesHtml' => $this->extendons_upload_files_rule_html(),
			);
			wp_localize_script('extendons_upload_files_approved_js', 'ewcpm_php_vars', $ewcpm_data_appr);
			wp_localize_script('extendons_upload_files_approved_js', 'ajax_url_add_pq', array( 'ajax_url_add_pq_data' => admin_url('admin-ajax.php') ));
		}

		/**
		 * Renders the HTML for the upload files rule.
		 *
		 * @since 1.0.0
		 * @return string The rendered HTML for the upload files rule.
		 */
		public function extendons_upload_files_rule_html() {
			ob_start();
			require_once EXTENDONS_UF_PLUGIN_ADMIN_TEMPLATE_DIR . 'extendons_upload_files_rule_html.php' ;

			return ob_get_clean();
		}
	}

	new Extendons_Upload_Files_Admin();
}
