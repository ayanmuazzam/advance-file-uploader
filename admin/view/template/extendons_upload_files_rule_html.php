<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="heading{count_variable}">
			<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion"
					href="#collapse{count_variable}" aria-expanded="true" aria-controls="collapse{count_variable}"
					class="extendons_uf_accordian">Item #{count_variable}</a></h4>
		</div>
		<div id="collapse{count_variable}'" class="panel-collapse collapse in" role="tabpanel"
			aria-labelledby="headingOne">
			<div class="panel-body">
				<div class="row" id="extendons_upload_files_FormSettingsupload">
					<div class="col-xs-4 col-md-3">
						<label id="extendons_upload_files_label">Label:<span class="woocommerce-help-tip"
								data-toggle="tooltip" data-placement="bottom"
								title="Enter Label for Upload File Button"></span></label>
						<input type="Text" name="extendonspriceuploadfilelabel[]"
							value="{extendons_upload_file_value_variable}" id="extendonspriceuploadfilelabel"
							style="width: 100%">
					</div>
					<div class="col-xs-4 col-md-3">
						<label id="extendons_upload_files_label">Allowed Extension:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									data-placement="bottom"
									title="Add the allowed file formats, writing the extensions divided by comma (e.g., jpg, png, gif)."></span></span></label>
						<input type="text" name="extendonspriceuploadfileallowedextension[]"
							value="{extendons_upload_file_value_variable}" id="extendonspriceuploadfileallowedextension"
							style="width: 100%">
					</div>
					<div class="col-xs-4 col-md-2">
						<label id="extendons_upload_files_label">Price:<span class="tip" style="float: right;"><span
									class="woocommerce-help-tip" data-toggle="tooltip" data-placement="bottom"
									title="Enter Price for file uploaded"></span></span></label>
						<input type="number" min="1"
							oninput="validity.valid||(value={extendons_upload_file_value_variable})"
							name="extendonspriceuploadfileallowedprice[]" value="{extendons_upload_file_value_variable}"
							id="extendonspriceuploadfileallowedprice" style="width: 100%">
					</div>
					<div class="col-xs-4 col-md-2">
						<label id="extendons_upload_files_label">Discount Type:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-placement="bottom"
									data-toggle="tooltip"
									title="Select Discount Type Fixed or Percentage"></span></span></label>
						<select id="extendonsDiscounttype" name="extendonsDiscounttype[]" style="width: 100%">
							<option value="">Select Type</option>
							<option value="extendons_upload_files_fixed">Fixed</option>
							<option value="extendons_upload_files_percentage">Percentage</option>
						</select>
					</div>
					<div class="col-xs-4 col-md-2">
						<label id="extendons_upload_files_label">Discount Price:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-placement="bottom"
									data-toggle="tooltip"
									title="Enter Discount Price for file uploaded"></span></span></label>
						<input type="number" min="1" name="extendonsDiscountvalue[]"
							value="{extendons_upload_file_value_variable}" id="extendonsDiscountvalue"
							oninput="validity.valid||(value={extendons_upload_file_value_variable})"
							style="width: 100%;">
					</div>
				</div>
				<div class="row" id="extendons_upload_files_Discount">
					<div class="col-xs-4 col-md-3">
						<label id="extendons_upload_files_label">Description:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="Enter Description for Upload File Button"></span></span></label>
						<textarea name="extendons_upload_files_description[]" style="width: 100%" rows="3"
							cols="50"></textarea>
					</div>
					<div class="col-md-5">
						<label id="extendons_upload_files_label">Upload File Button Text:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="Enter Upload File Button Text"></span></span></label>
						<input type="text" name="extendonspriceuploadfilebtntext[]"
							value="{extendons_upload_file_value_variable}" style="width: 100%">
					</div>
					<div class="col-xs-4 col-md-3">
						<label id="extendons_upload_files_label">Maximum Upload Size:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="Enter Maximum Upload Size for File Upload"></span></span></label><br>
						<select id="extendons_file_size" name=extendons_file_size[] style="width: 25%;">
							<option value="extendons_upload_files_KB">KB</option>
							<option value="extendons_upload_files_MB">MB</option>
						</select>
						<input type="number" min="0" placeholder="Enter Size" class="form-control extendons-upload-size"
							name="extendons-maximum-uploadsize[]" value="{extendons_upload_file_value_variable}"
							id="extendons-maximum-uploadsize"
							oninput="validity.valid||(value={extendons_upload_file_value_variable})"
							style="display: initial;width: 70%;">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-md-3" style="margin-top: 20px;">
						<label id="extendons_upload_files_label">Customer Notes:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="<?php echo esc_html__('Enable to Allow Customer notes', 'extendons_Upload_Files'); ?>"></span></span></label>
						<input type="checkbox" name="extendons_uf_allow_notes_checkbox[]"
							class="extendons_vt_checkboxes"><br>
						<label id="extendons_upload_files_label">Required:<span class="tip" style="float: right;"><span
									class="woocommerce-help-tip" data-toggle="tooltip"
									title="Enable to Upload File Required"></span></span></label>
						<input type="checkbox" name="extendons_uf_required[]" class="extendons_vt_checkboxes">
					</div>
					<div class="col-xs-4 col-md-3" style="margin-top: 20px;">
						<label id="extendons_upload_files_label">Customer Notes Label:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="Label For Customer Notes"></span></span></label>
						<input type="text" name="extendonspriceuploadfilecustomernotelabel[]" value=""
							id="extendonspriceuploadfilecustomernotelabel" style="width: 100%">
					</div>
					<div class="col-md-3" style="margin-top: 20px;">
						<label id="extendons_upload_files_label">Maximum File Upload:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="Enter Maximum Quantity for upload file button"></span></span></label>
						<input type="number" min="0" name="extendons_uploadfile_allowed_max_qunatity[]" value=""
							id="extendons_uploadfile_allowed_max_qunatity" style="width: 100%"><span
							class="description">If Empty then customer can upload only 1 file.</span>
					</div>
					<div class="col-xs-4 col-md-3" style="margin-top: 20px;">
						<label id="extendons_upload_files_label">Background Color:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="Choose Background Color for Upload File Button"></span></span></label>
						<input type="color" class="jscolor" name="extendons_upload_files_color[]"
							id="extendons_upload_files_color" value=""><br>
						<label id="extendons_upload_files_label">Text Color:<span class="tip"
								style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip"
									title="Choose Text Color for Upload File Button"></span></span></label>
						<input type="color" class="jscolor" name="extendons_upload_files_text_color[]"
							id="extendons_upload_files_text_color" value="#653232">
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<button type="button" class="btn btn-danger remove">Delete</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>