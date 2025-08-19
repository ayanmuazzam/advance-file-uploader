<?php
$allowed_extensions = $values['extendons_uploadfiles_allwoed_extension'] ?? '';
$file_maximum_upload_size = 'extendons_upload_files_MB' == $values['extendons_uploadfiles_file_maximum_upload_size'] ? 'MB' : 'KB';
$max_upload_file_size_value = $values['extendons_uploadfiles_file_maximum_upload_size_value'] ?? '';
$uploadfiles_discount_price = $values['extendons_uploadfiles_discount_price'] ?? '';
$maximum_upload_files = $values['extendons_uploadfiles_file_maximum_upload_files'] ?? '';
$button_background_color = $values['extendons_uploadfiles_file_background_color'] ?? '';
$button_text_color = $values['extendons_uploadfiles_file_text_color'] ?? '';
$button_uploadfiles_file_text_btn = !empty($values['extendons_uploadfiles_file_text_btn']) ? $values['extendons_uploadfiles_file_text_btn'] : esc_html__('Upload Files', 'extendons_Upload_Files');
$file_allow_notes_label = $values['extendons_uploadfiles_file_allow_notes_label'] ?? '';
$file_allow_notes_checkbox = $values['extendons_uploadfiles_file_allow_notes_checkbox'] ?? '';
$rule_description = $values['extendons_uploadfiles_description'] ?? '';
$rule_id = $value['extendons_post_id'] ?? '';
$required_files = $values['extendons_uploadfiles_file_required'] ?? '';
$check_cart = is_cart();
?>
<div class="extendons_upload_files_container <?php echo esc_attr($check_cart ? 'upload_files_container_cart' : ''); ?>" data-rule-id="<?php echo esc_attr($rule_id); ?>">
	<div class="extendons_upload_files_main_wrapper"
		id="extendons_upload_files_drop_zone_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
		<ul class="extendons_upload_files_info_list"
			id="extendons_upload_files_specifications_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
			<?php if (!empty($rule_description)) : ?>
			<li class="extendons_upload_files_info_item"
				id="extendons_upload_files_rule_description_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
				<span class="extendons_upload_files_info_label"><?php echo esc_html__('Description:', 'extendons_Upload_Files'); ?></span>
				<span class="extendons_upload_files_info_value"><?php echo esc_html($rule_description); ?></span>
			</li>
			<?php endif; ?>
			<?php if (!empty($allowed_extensions)) : ?>
			<li class="extendons_upload_files_info_item"
				id="extendons_upload_files_allowed_extensions_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
				<span class="extendons_upload_files_info_label"><?php echo esc_html__('Allowed Extensions:', 'extendons_Upload_Files'); ?></span>
				<span class="extendons_upload_files_info_value"><?php echo esc_html($allowed_extensions); ?></span>
			</li>
			<?php endif; ?>
			<?php if (!empty($file_maximum_upload_size)) : ?>
			<li class="extendons_upload_files_info_item"
				id="extendons_upload_files_max_filesize_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
				<span class="extendons_upload_files_info_label"><?php echo esc_html__('Maximum Upload Filesize:', 'extendons_Upload_Files'); ?></span>
				<span
					class="extendons_upload_files_info_value"><?php echo esc_html($max_upload_file_size_value . ' ' . $file_maximum_upload_size); ?></span>
			</li>
			<?php endif; ?>
			<?php if (!empty($maximum_upload_files)) : ?>
			<li class="extendons_upload_files_info_item"
				id="extendons_upload_files_maximum_upload_files_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
				<span class="extendons_upload_files_info_label"><?php echo esc_html__('Maximum Upload Files:', 'extendons_Upload_Files'); ?></span>
				<span class="extendons_upload_files_info_value"><?php echo esc_html($maximum_upload_files); ?></span>
			</li>
			<?php endif; ?>
			<?php if (!empty($uploadfiles_discount_price)) : ?>
			<li class="extendons_upload_files_info_item"
				id="extendons_upload_files_price_after_discount_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
				<span class="extendons_upload_files_info_label"><?php echo esc_html__('File upload price after discount:', 'extendons_Upload_Files'); ?></span>
				<span
					class="extendons_upload_files_info_value"><?php echo esc_html($calculated_discounted_price); ?></span>
			</li>
			<?php endif; ?>
		</ul>

		<div class="extendons_upload_files_upload_section"
			id="extendons_upload_files_upload_area_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
			<label
				for="extendons_upload_files_file_input_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
				class="extendons_upload_files_upload_button_wrapper"
				id="extendons_upload_files_upload_label_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
				style="background-color: <?php echo esc_attr($button_background_color); ?>;">
				<div class="extendons_upload_files_upload_button"
					id="extendons_upload_files_upload_btn_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
					style="color: <?php echo esc_attr($button_text_color); ?>;">
					<svg class="extendons_upload_files_upload_icon"
						id="extendons_upload_files_icon_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
						viewBox="0 0 24 24">
						<path
							d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
					</svg>
					<span class="extendons_upload_files_button_text"
						id="extendons_upload_files_button_label_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
						<?php 
						echo esc_html($button_uploadfiles_file_text_btn);
						if ('false' !== $required_files) {
							echo ' ';
							echo '<span style="color:red;font-size:15px;font-weight:bold">*</span>';
						}
						?>
					</span>
				</div>
			</label>			
			<input type="file"
				id="extendons_upload_files_file_input_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
				class="extendons_upload_files_hidden_input" data-rule-id="<?php echo esc_attr($rule_id); ?>"
				name="extendons_upload_files_file_input_<?php echo esc_attr($rule_id); ?>[]"
				accept="<?php echo esc_attr($allowed_extensions); ?>"
				max-file-upload="<?php echo esc_attr($maximum_upload_files); ?>"
				type-file-format="<?php echo esc_attr($file_maximum_upload_size); ?>"
				file-size-allow-to-upload="<?php echo esc_attr($max_upload_file_size_value); ?>" multiple>
		</div>

		<div class="extendons_upload_files_selected_images"
			id="extendons_upload_files_image_list_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
			style="display: none;">
			
		</div>

		
		<div class="extendons_upload_files_error_message"
			id="extendons_upload_files_error_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
		</div>
		<div class="extendons_upload_files_success_message"
			id="extendons_upload_files_success_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
		</div>

		
		<input type="hidden" name="extendons_hidden_array456_<?php echo esc_attr($rule_id); ?>"
			id="extendons_upload_files_counter_<?php echo esc_attr($rule_id); ?>">

		<?php if ('true' === $file_allow_notes_checkbox) : ?>
		<div class="extendons_upload_files_note_section"
			id="extendons_upload_files_note_area_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>">
			<label
				for="extendons_upload_files_note_textarea_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
				class="extendons_upload_files_note_label"
				id="extendons_upload_files_note_title_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"><?php echo esc_html($file_allow_notes_label); ?></label>
			<textarea
				id="extendons_upload_files_note_textarea_<?php echo esc_attr($rule_id); ?>_<?php echo esc_attr($key); ?>"
				class="extendons_upload_files_note_textarea"
				name="extendons_upload_files_note_<?php echo esc_attr($rule_id); ?>"
				placeholder="Enter your note here..." rows="3"></textarea>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php
if (function_exists('wp_is_block_theme') && wp_is_block_theme()) {
	echo '<span class="extendons-block-theme-active" style="display: none;"></span>';
}
?>