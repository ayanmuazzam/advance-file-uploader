<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

if ( ! class_exists( 'Extendons_Upload_Files_Email_Template' ) ) {

	/**
	 * Email template class for generating professional email notifications
	 */
	class Extendons_Upload_Files_Email_Template {

		/**
		 * Generate WordPress native-style email template
		 *
		 * @since 1.0.0
		 * @param int $order_id Order ID
		 * @param string $customer_name Customer name
		 * @param array $files_array Array of file names
		 * @param string $status Status (Approved/requires_review)
		 * @return string Generated HTML email template
		 */
		public static function generate_email_template( $order_id, $customer_name, $files_array, $status ) {
			$site_name = get_option('blogname');
			$order_url = wc_get_endpoint_url('view-order', $order_id, wc_get_page_permalink('myaccount'));

			// Use status configuration for consistency
			$status_config = self::get_status_config( $status );
			$status_color = $status_config['color'];
			$status_text = $status_config['text'];
			$status_message = $status_config['message'];
			$status_icon = $status_config['icon'];

			ob_start();
			?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php echo esc_html( 'Approved' === $status ? __('Files Approved', 'extendons_Upload_Files') : __('Files Review Required', 'extendons_Upload_Files') ); ?>
	</title>
	<style>
	body {
		margin: 0;
		padding: 0;
		background-color: #f7f7f7;
		font-family: Arial, sans-serif;
		line-height: 1.6;
		color: #333;
	}

	.email-container {
		max-width: 600px;
		margin: 0 auto;
		background-color: #ffffff;
		border: 1px solid #ddd;
	}

	.email-header {
		background-color: #0073aa;
		color: white;
		padding: 20px;
		text-align: center;
	}

	.email-header h1 {
		margin: 0;
		font-size: 24px;
	}

	.email-body {
		padding: 30px;
	}

	.status-badge {
		display: inline-block;
		padding: 8px 16px;
		border-radius: 4px;
		color: white;
		font-weight: bold;
		font-size: 14px;
		margin-bottom: 20px;
		background-color: 
		<?php 
		echo esc_attr($status_color);
			?>
		;
	}

	.order-info {
		background-color: #f9f9f9;
		padding: 15px;
		border-left: 4px solid #0073aa;
		margin: 20px 0;
	}

	.files-list {
		margin: 20px 0;
	}

	.file-item {
		display: block;
		padding: 10px;
		margin: 5px 0;
		background-color: #f8f9fa;
		border-left: 4px solid 
		<?php 
		echo esc_attr($status_color);
			?>
		;
		border-radius: 3px;
	}

	.file-item::before {
		content: "<?php echo esc_js( $status_icon ); ?>";
		color: 
		<?php 
		echo esc_attr($status_color);
			?>
		;
		font-weight: bold;
		margin-right: 10px;
	}

	.email-footer {
		background-color: #f7f7f7;
		padding: 20px;
		text-align: center;
		font-size: 12px;
		color: #666;
		border-top: 1px solid #ddd;
	}

	.ext-upload-files-view-order {
		display: inline-block;
		padding: 12px 24px;
		background-color: #005a87;
		color: white !important;
		text-decoration: none;
		border-radius: 4px;
		margin: 15px 0;
	}

	.ext-upload-files-view-order:hover {
		background-color: #005a87;
	}
	</style>
</head>

<body>
	<div class="email-container">
		<!-- Header -->
		<div class="email-header">
			<h1><?php echo esc_html( 'Approved' === $status ? __('Files Approved', 'extendons_Upload_Files') : __('Files Rejected', 'extendons_Upload_Files') ); ?>
			</h1>
		</div>

		<!-- Body -->
		<div class="email-body">
			<h2>
				<?php
			/* translators: %s: customer name */
			printf( esc_html( __( 'Hello %s,', 'extendons_Upload_Files' ) ), esc_html( $customer_name ) ); 
				?>
			</h2>

			<div class="status-badge">
				<?php echo esc_html($status_text); ?>
			</div>

			<p><?php echo esc_html($status_message); ?></p>

			<div class="order-info">
				<strong>
					<?php
				/* translators: %s: order ID number */
				printf( esc_html( __( 'Order #%s', 'extendons_Upload_Files' ) ), esc_html( $order_id ) ); 
					?>
				</strong><br>
				<?php echo esc_html( __('Status Update:', 'extendons_Upload_Files') ); ?>
				<?php echo esc_html( ucfirst( $status ) ); ?><br>
				<?php echo esc_html( __('Date:', 'extendons_Upload_Files') ); ?>
				<?php echo esc_html( current_time('F j, Y g:i A') ); ?>
			</div>

			<div class="files-list">
				<h3><?php echo esc_html( __('Uploaded Files:', 'extendons_Upload_Files') ); ?></h3>
				<?php foreach ($files_array as $file_name) : ?>
				<div class="file-item">
					<?php echo esc_html($file_name); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<?php if ( 'Approved' === $status ) : ?>
			<p><strong><?php echo esc_html( __("What's next?", 'extendons_Upload_Files') ); ?></strong><br>
				<?php echo esc_html( __("Your order is now being processed. We'll send you another update when your order ships.", 'extendons_Upload_Files') ); ?>
			</p>
			<?php else : ?>
			<p><strong><?php echo esc_html( __('What you need to do:', 'extendons_Upload_Files') ); ?></strong><br>
				<?php echo esc_html( __('Please review your uploaded files and resubmit if necessary. If you have questions, please contact our support team.', 'extendons_Upload_Files') ); ?>
			</p>
			<?php endif; ?>

			<p style="text-align: center;">
				<a href="<?php echo esc_url( $order_url ); ?>"
					class="ext-upload-files-view-order">
					<?php echo esc_html( __('View Your Order', 'extendons_Upload_Files') ); ?>
				</a>
			</p>

			<p><?php echo esc_html( __('If you have any questions, please don\'t hesitate to contact us.', 'extendons_Upload_Files') ); ?>
			</p>

			<p><?php echo esc_html( __('Thank you for your business!', 'extendons_Upload_Files') ); ?></p>
		</div>

		<!-- Footer -->
		<div class="email-footer">
			<p>
				<?php
				printf( 
					/* translators: %s: site name */
					esc_html( __('This email was sent from %s', 'extendons_Upload_Files') ), 
					'<a href="' . esc_url($site_url) . '">' . esc_html($site_name) . '</a>' 
				); 
				?>
				<br>
				<?php echo esc_html( __('You received this email because you placed an order with us.', 'extendons_Upload_Files') ); ?>
			</p>
		</div>
	</div>
</body>

</html>
<?php
			return ob_get_clean();
		}

		/**
		 * Get default email template styles
		 *
		 * @since 1.0.0
		 * @return array Array of CSS styles
		 */
		public static function get_default_styles() {
			return array(
				'body' => 'margin: 0; padding: 0; background-color: #f7f7f7; font-family: Arial, sans-serif; line-height: 1.6; color: #333;',
				'container' => 'max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #ddd;',
				'header' => 'background-color: #0073aa; color: white; padding: 20px; text-align: center;',
				'ext-upload-files-view-order' => 'display: inline-block; padding: 12px 24px; background-color: #0073aa; color: white; text-decoration: none; border-radius: 4px; margin: 15px 0;',
			);
		}

		/**
		 * Get status configuration
		 *
		 * @since 1.0.0
		 * @param string $status Status type
		 * @return array Status configuration
		 */
		public static function get_status_config( $status ) {
			$configs = array(
				'Approved' => array(
					'color' => '#28a745',
					'text' => __('APPROVED', 'extendons_Upload_Files'),
					'icon' => '✓',
					'message' => __('Great news! Your uploaded files have been Approved and your order is being processed.', 'extendons_Upload_Files'),
				),
				'requires_review' => array(
					'color' => '#dc3545',
					'text' => __('REJECTED', 'extendons_Upload_Files'),
					'icon' => '⚠',
					'message' => __('Your uploaded files have been rejected. Please check the details below and resubmit if necessary.', 'extendons_Upload_Files'),
				),
			);

			return isset($configs[$status]) ? $configs[$status] : $configs['requires_review'];
		}
	}

} // End class_exists check