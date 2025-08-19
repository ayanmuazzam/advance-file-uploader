jQuery(document).ready(function () {
    "use strict";
    window.onbeforeunload = null;
    // jQuery('#extendonsmultiplefiles').hide();
    jQuery("#extendons_upload_files_Products").hide();
    jQuery("#extendons_upload_files_category").hide();
    // jQuery('#extendons_upload_files_price_single').hide();
    jQuery(".extendons_loading_animation").hide();
    jQuery(".extendons_loading_frame").hide();
    jQuery("#extendons_success_msg").hide();
    jQuery("#extendons_settings_msg").hide();
    jQuery("#extendons_loader").hide();
    jQuery("#extendons_settings_loader").hide();
    jQuery(".extendons_upload_files_loader_d").hide();
    jQuery(".extendons_loader_edit").hide();
    jQuery(".extendons_delete_msg").hide();
    jQuery(".extendons_choosen").select2();
    jQuery(".Multiply_by_Quantity").hide();
    jQuery(".Edit_Multiply_by_Quantity").hide();

    jQuery('input[name="extendons-radio-select-display-on"]').on(
        "click",
        function () {
            "use strict";
            var extendons_display_on_value = jQuery(
                "input[name='extendons-radio-select-display-on']:checked"
            ).val();
            if (
                extendons_display_on_value ==
                "extendons_upload_files_product_page"
            ) {
                jQuery(".Multiply_by_Quantity").show();
            } else {
                jQuery(".Multiply_by_Quantity").hide();
            }
        }
    );

    jQuery("body").on(
        "click",
        'input[name="extendons-edit-radio-select-display-on"]',
        function () {
            "use strict";
            var extendons_display_on_value = jQuery(
                "input[name='extendons-edit-radio-select-display-on']:checked"
            ).val();
            if (
                extendons_display_on_value ==
                "extendons_upload_files_product_page"
            ) {
                jQuery(".Edit_Multiply_by_Quantity").show();
            } else {
                jQuery(".Edit_Multiply_by_Quantity").hide();
            }
        }
    );
    jQuery(".subsubsub li a").click(function () {
        jQuery(".subsubsub li a").removeClass("current");
        jQuery(this).addClass("current");
    });

    jQuery('button[name="extendons_edit_rule"]').on("click", function () {
        var e_id = jQuery(this).attr("data-id");
        jQuery('input[name="extendons_edit_rule_id"]').val(e_id);
    });
});

function extendons_file_upload_recptcha_settings() {
    var extendons_upload_recapcha = jQuery("#extendons_upload_recapcha").val();
    var extendons_upload_recapcha_site_key = jQuery(
        "#extendons_upload_recapcha_site_key"
    ).val();
    var extendons_upload_recapcha_secrect_key = jQuery(
        "#extendons_upload_recapcha_secrect_key"
    ).val();

    jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: {
            action: "extendons_upload_recapcha_save",
            extendons_upload_recapcha: extendons_upload_recapcha,
            extendons_upload_recapcha_site_key:
                extendons_upload_recapcha_site_key,
            extendons_upload_recapcha_secrect_key:
                extendons_upload_recapcha_secrect_key,
        },
        success: function (data) {
            jQuery("#message_recaptcha").show();
            jQuery("#message_recaptcha").delay(1000).fadeOut("slow");

            // window.location.reload();
        },
    });
}

function extendons_file_upload_additional_settings() {
    let extendons_upload_folder_path = jQuery("#extendons_upload_folder_path").val();
    
    jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: {
            action: "extendons_upload_additional_settings_save",
            extendons_upload_folder_path: extendons_upload_folder_path,
        },
        success: function (data) {
            jQuery("#message_additional_settings").show();
            jQuery("#message_additional_settings").delay(1000).fadeOut("slow");
            window.onbeforeunload = null;
        }
    });
}

function extendons_upload_file_choosen_product_cateory(
    extendons_upload_file_type
) {
    "use strict";
    if ("extendons_create" == extendons_upload_file_type) {
        var extendons_product_category = jQuery(
            "#extendonsproductcategory"
        ).val();
        if ("extendons_upload_files_category" == extendons_product_category) {
            jQuery("#extendons_upload_files_Products").hide();
            jQuery("#extendons_upload_files_category").show();
        } else if (
            "extendons_upload_files_product" == extendons_product_category
        ) {
            jQuery("#extendons_upload_files_Products").show();
            jQuery("#extendons_upload_files_category").hide();
        } else {
            jQuery("#extendons_upload_files_Products").hide();
            jQuery("#extendons_upload_files_category").hide();
        }
    } else {
        var extendons_product_category = jQuery(
            "#extendonsproductcategory"
        ).val();
        if ("extendons_upload_files_category" == extendons_product_category) {
            jQuery("#extendons_edit_Products").hide();
            jQuery("#extendons_edit_category").show();
        } else if (
            "extendons_upload_files_product" == extendons_product_category
        ) {
            jQuery("#extendons_edit_Products").show();
            jQuery("#extendons_edit_category").hide();
        } else {
            jQuery("#extendons_edit_Products").hide();
            jQuery("#extendons_edit_category").hide();
        }
    }
}

function extendons_upload_file_save_general_settings(formaction) {
    "use strict";
    var extendons_enable_disable_setting = jQuery(
        "#extendons_upload_files_enable_disable_setting"
    ).val();
    var extendons_up_rule_name = jQuery("#extendons_up_rule_name").val();
    var extendons_rule_priority = jQuery("#extendons_rule_priority").val();
    var extendonsproductpage,
        extendonscartpage,
        extendonscheckoutpage,
        extendonsthankyoupage,
        extendonsaccountpage,
        extendonsaftercartpage,
        extendonscheckoutpageafternotes;
    var allowed_position = [];
    if (jQuery("#extendons-product-page").prop("checked") == true) {
        extendonsproductpage = jQuery("#extendons-product-page").val();
    } else {
        extendonsproductpage = "false";
    }
    if (jQuery("#extendons-cart-page").prop("checked") == true) {
        extendonscartpage = jQuery("#extendons-cart-page").val();
    } else {
        extendonscartpage = "false";
    }
    if (
        jQuery("#extendons-checkout-page-after-notes").prop("checked") == true
    ) {
        extendonscheckoutpage = jQuery(
            "#extendons-checkout-page-after-notes"
        ).val();
    } else {
        extendonscheckoutpage = "false";
    }
    if (jQuery("#extendons-thankyou-page").prop("checked") == true) {
        extendonsthankyoupage = jQuery("#extendons-thankyou-page").val();
    } else {
        extendonsthankyoupage = "false";
    }
    if (jQuery("#extendons-Account-page").prop("checked") == true) {
        extendonsaccountpage = jQuery("#extendons-Account-page").val();
    } else {
        extendonsaccountpage = "false";
    }
    if (jQuery("#extendons-after-cart-page-table").prop("checked") == true) {
        extendonsaftercartpage = jQuery("#extendons-after-cart-page-table").val();
    } else {
        extendonsaftercartpage = "false";
    }
    if (jQuery("#extendons-after-checkout-page-after-notes-field").prop("checked") == true) {
        extendonscheckoutpageafternotes = jQuery("#extendons-after-checkout-page-after-notes-field").val();
    } else {
        extendonscheckoutpageafternotes = "false";
    }

    var allowed_position_array = {
        extendons_allow_uf_product_page: extendonsproductpage,
        extendons_allow_uf_cart_page: extendonscartpage,
        extendons_allow_uf_checkout_page: extendonscheckoutpage,
        extendons_allow_uf_thankyou_page: extendonsthankyoupage,
        extendons_allow_uf_account_page: extendonsaccountpage,
        extendons_allow_uf_after_cart_page_table: extendonsaftercartpage,
        extendons_allow_uf_after_checkout_page_after_notes_field: extendonscheckoutpageafternotes,
    };

    allowed_position.push(allowed_position_array);

    var extendons_multiple_files_limit = [];
    var extendons_allow_note_checked;
    var extendons_uf_required;
    var valid = true;
    jQuery('input[name="extendonspriceuploadfilelabel[]"]').each(function () {
        if (
            jQuery(this)
                .parent()
                .parent()
                .parent()
                .find('input[name="extendons_uf_allow_notes_checkbox[]"]')
                .prop("checked") == true
        ) {
            extendons_allow_note_checked = "true";
        } else {
            extendons_allow_note_checked = "false";
        }

        if (
            jQuery(this)
                .parent()
                .parent()
                .parent()
                .find('input[name="extendons_uf_required[]"]')
                .prop("checked") == true
        ) {
            extendons_uf_required = "true";
        } else {
            extendons_uf_required = "false";
        }

        var allowedextension = jQuery(this)
            .parent()
            .next()
            .find('input[name="extendonspriceuploadfileallowedextension[]"]')
            .val();
        if (allowedextension == "") {
            jQuery(this)
                .parent()
                .next()
                .find(
                    'input[name="extendonspriceuploadfileallowedextension[]"]'
                )
                .next()
                .remove();
            jQuery(this)
                .parent()
                .next()
                .find(
                    'input[name="extendonspriceuploadfileallowedextension[]"]'
                )
                .after(
                    '<p class="extendonsextensionsmessage" style="color:red;"> Allowed Extension Required Field</p>'
                );
            jQuery(".extendonsextensionsmessage").delay(3000).fadeOut("slow");
            valid = false;
        }
        var temp_array = {
            extendons_uploadfiles_label: jQuery(this).val(),
            extendons_uploadfiles_allwoed_extension: jQuery(this)
                .parent()
                .next()
                .find(
                    'input[name="extendonspriceuploadfileallowedextension[]"]'
                )
                .val(),
            extendons_uploadfiles_price: jQuery(this)
                .parent()
                .next()
                .next()
                .find('input[name="extendonspriceuploadfileallowedprice[]"]')
                .val(),
            extendons_uploadfiles_discount_type: jQuery(this)
                .parent()
                .next()
                .next()
                .next()
                .find('select[name="extendonsDiscounttype[]"]')
                .val(),
            extendons_uploadfiles_discount_price: jQuery(this)
                .parent()
                .next()
                .next()
                .next()
                .next()
                .find('input[name="extendonsDiscountvalue[]"]')
                .val(),
            extendons_uploadfiles_description: jQuery(this)
                .parent()
                .parent()
                .next()
                .find('textarea[name="extendons_upload_files_description[]"]')
                .val().trim(),
            extendons_uploadfiles_file_text_btn: jQuery(this)
                .parent()
                .parent()
                .next()
                .find('input[name="extendonspriceuploadfilebtntext[]"]')
                .val(),
            extendons_uploadfiles_file_multiply_by_quantity: jQuery(this)
                .parent()
                .parent()
                .next()
                .find(
                    'select[name="extendons_upload_files_multiple_by_qunatity[]"]'
                )
                .val(),
            extendons_uploadfiles_file_maximum_upload_size: jQuery(this)
                .parent()
                .parent()
                .next()
                .find('select[name="extendons_file_size[]"]')
                .val(),
            extendons_uploadfiles_file_maximum_upload_size_value: jQuery(this)
                .parent()
                .parent()
                .next()
                .find('input[name="extendons-maximum-uploadsize[]"]')
                .val(),
            extendons_uploadfiles_file_allow_notes_checkbox:
                extendons_allow_note_checked,
            extendons_uploadfiles_file_allow_notes_label: jQuery(this)
                .parent()
                .parent()
                .parent()
                .find(
                    'input[name="extendonspriceuploadfilecustomernotelabel[]"]'
                )
                .val(),
            extendons_uploadfiles_file_maximum_upload_files: jQuery(this)
                .parent()
                .parent()
                .parent()
                .find(
                    'input[name="extendons_uploadfile_allowed_max_qunatity[]"]'
                )
                .val(),
            extendons_uploadfiles_file_background_color: jQuery(this)
                .parent()
                .parent()
                .parent()
                .find('input[name="extendons_upload_files_color[]"]')
                .val(),
            extendons_uploadfiles_file_text_color: jQuery(this)
                .parent()
                .parent()
                .parent()
                .find('input[name="extendons_upload_files_text_color[]"]')
                .val(),
            extendons_uploadfiles_file_required: extendons_uf_required,
        };
        extendons_multiple_files_limit.push(temp_array);
    });

    var extendons_product_category = jQuery("#extendonsproductcategory").val();
    if (extendons_product_category == "extendons_upload_files_product") {
        var extendons_selected_items = jQuery("#extendons_files-product").val();
    } else if (
        extendons_product_category == "extendons_upload_files_category"
    ) {
        var extendons_selected_items = jQuery(
            "#extendons_files-category"
        ).val();
    } else {
        var extendons_selected_items = "";
    }
    var extendons_selected_user_role = jQuery(
        "#extendons_choosen-user-role"
    ).val();
    var extendons_edit_post_id_action = jQuery(
        'input[name="extendons_edit_post_id_action"]'
    ).val();

    var ajaxurl = ewcpm_php_vars.admin_url;
    if (valid) {
        jQuery("#extendons_settings_loader").show();
        // PIN: setting ajax
        jQuery.ajax({
            url: ajaxurl,
            type: "post",
            data: {
                action: "extendons_upload_files_save_general_settings",
                extendons_enable_disable_setting:
                    extendons_enable_disable_setting,
                extendons_up_rule_name: extendons_up_rule_name,
                extendons_rule_priority: extendons_rule_priority,
                extendons_display_on_value: JSON.stringify(allowed_position),
                extendons_multiple_files_limit: JSON.stringify(
                    extendons_multiple_files_limit
                ),
                extendons_product_category: extendons_product_category,
                extendons_selected_items: extendons_selected_items,
                extendons_selected_user_role: extendons_selected_user_role,
                formaction: formaction,
                extendons_edit_post_id_action: extendons_edit_post_id_action,
            },
            success: function (data) {
                console.log("response");
                console.log(data);
                jQuery("#extendons_settings_msg").show();
                jQuery("#extendons_settings_msg").delay(1000).fadeOut("slow");
                jQuery("#extendons_settings_loader").show();
                jQuery("#extendons_settings_loader")
                    .delay(1000)
                    .fadeOut("slow");
                window.onbeforeunload = null;
                // console.log("preventing reload after saving settings");
                location.reload();
            },
        });
    }
}

function extendons_upload_file_delete_rule(extendons_upload_files_rule_id) {
    "use strict";
    var ajaxurl = ewcpm_php_vars.admin_url;
    var checkstr = confirm("Are you sure you want to delete this?");
    if (checkstr == true) {
        jQuery(
            "#extendons_loader_deltete" + extendons_upload_files_rule_id
        ).show();
        jQuery.ajax({
            url: ajaxurl,
            type: "post",
            data: {
                action: "extendons_upload_files_delete_rule_file",
                extendons_upload_files_rule_id: extendons_upload_files_rule_id,
            },
            success: function (data) {
                jQuery("#message_settings").show();
                jQuery("#message_settings").delay(1000).fadeOut("slow");
                window.location.reload();
            },
        });
    }
}

jQuery(function () {
    jQuery("body").on("click", ".remove", function () {
        "use strict";
        var count = jQuery('input[name="extendons_counter_btn"]').val();
        jQuery(this).closest("#accordion").remove();
        count = count - parseFloat(1);
        jQuery('input[name="extendons_counter_btn"]').val(count);

        var curr = jQuery(this)
            .closest("#accordion")
            .find(".panel-default")
            .find(".collapse")
            .attr("id");

        jQuery(".extendons_uf_accordian").each(function () {
            // console.log(curr);
            var asdf = jQuery(this).attr("href");
            // console.log(asdf);
            asdf = asdf.replace("#collapse", "");
            curr = curr.replace("collapse", "");

            if (parseInt(asdf) > parseInt(curr)) {
                // console.log(asdf);

                jQuery(this).attr("href", "#collapse" + (parseInt(asdf) - 1));
                jQuery(this).html("Item #" + (parseInt(asdf) - 1));
                jQuery(this).attr(
                    "aria-controls",
                    "collapse" + (parseInt(asdf) - 1)
                );
                jQuery(this)
                    .parent()
                    .parent()
                    .attr("id", "heading" + (parseInt(asdf) - 1));
                jQuery(this)
                    .parent()
                    .parent()
                    .parent()
                    .find(".collapse")
                    .attr("id", "collapse" + (parseInt(asdf) - 1));
            }
        });
    });
});

function extendons_uploadfile_multiplefile(extendons_upload_filetype) {
    "use strict";
    var count = jQuery('input[name="extendons_counter_btn"]').val();
    if (extendons_upload_filetype == "extendons_create") {
        count++;
        var div = jQuery("<div/>");
        div.html(extendons_upload_file_GetDynamicTextBox("", count));
        jQuery(".extendons_upload_files_price_single").append(div);
        jQuery('input[name="extendons_counter_btn"]').val(count);
    }
}
function extendons_upload_file_GetDynamicTextBox(
    extendons_upload_file_value,
    count
) {
    var ruleHTML = ewcpm_php_vars.rulesHtml;

    ruleHTML = ruleHTML.replace(/\{count_variable\}/g, count);
    ruleHTML = ruleHTML.replace(
        /\{extendons_upload_file_value_variable\}/g,
        extendons_upload_file_value
    );
    return ruleHTML;

    //return '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="panel panel-default"><div class="panel-heading" role="tab" id="heading'+count+'"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'+count+'" aria-expanded="true" aria-controls="collapse'+count+'" class="extendons_uf_accordian">Item #'+count+'</a></h4></div><div id="collapse'+count+'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne"><div class="panel-body"><div class="row" id="extendons_upload_files_FormSettingsupload"><div class="col-xs-4 col-md-3"><label id="extendons_upload_files_label">Label:<span class="woocommerce-help-tip" data-toggle="tooltip" data-placement="bottom" title="Enter Label for Upload File Button"></span></label><input type="Text" name="extendonspriceuploadfilelabel[]" value="'+extendons_upload_file_value+'" id="extendonspriceuploadfilelabel" style="width: 100%"></div><div class="col-xs-4 col-md-3"><label id="extendons_upload_files_label">Allowed Extension:<span cla<span class="woocommerce-help-tip" data-toggle="tooltip" data-placement="bottom" title="Add the allowed file formats, writing the extensions divided by comma (e.g., jpg, png, gif)."></span></span></label><input type="text" name="extendonspriceuploadfileallowedextension[]" value="'+extendons_upload_file_value+'" id="extendonspriceuploadfileallowedextension" style="width: 100%"></div><div class="col-xs-4 col-md-2"><label id="extendons_upload_files_label">Price:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" data-placement="bottom" title="Enter Price for file uploaded"></span></span></label><input type="number" min="1" oninput="validity.valid||(value='+extendons_upload_file_value+')"  name="extendonspriceuploadfileallowedprice[]" value="'+extendons_upload_file_value+'" id="extendonspriceuploadfileallowedprice" style="width: 100%"></div><div class="col-xs-4 col-md-2"><label id="extendons_upload_files_label">Discount Type:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-placement="bottom" data-toggle="tooltip" title="Select Discount Type Fixed or Percentage"></span></span></label><select id="extendonsDiscounttype" name="extendonsDiscounttype[]" style="width: 100%"><option value="">Select Type</option><option value="extendons_upload_files_fixed">Fixed</option><option value="extendons_upload_files_percentage">Percentage</option></select></div><div class="col-xs-4 col-md-2"><label id="extendons_upload_files_label">Discount Price:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-placement="bottom" data-toggle="tooltip" title="Enter Discount Price for file uploaded"></span></span></label><input type="number" min="1" name="extendonsDiscountvalue[]" value="'+extendons_upload_file_value+'" id="extendonsDiscountvalue" oninput="validity.valid||(value='+extendons_upload_file_value+')" style="width: 100%;"></div></div><div class="row" id="extendons_upload_files_Discount"><div class="col-xs-4 col-md-3"><label id="extendons_upload_files_label">Description:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Enter Description for Upload File Button"></span></span></label><textarea name="extendons_upload_files_description[]" style="width: 100%" rows="3" cols="50"></textarea></div><div class="col-md-5"><label id="extendons_upload_files_label">Upload File Button Text:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Enter Upload File Button Text"></span></span></label><input type="text" name="extendonspriceuploadfilebtntext[]"  value="'+extendons_upload_file_value+'" style="width: 100%"></div><div class="col-xs-4 col-md-3"><label id="extendons_upload_files_label">Maximum Upload Size:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Enter Maximum Upload Size for File Upload"></span></span></label><br><select id="extendons_file_size" name=extendons_file_size[] style="width: 25%;"><option value="extendons_upload_files_KB">KB</option><option value="extendons_upload_files_MB">MB</option></select><input type="number" min="0" placeholder="Enter Size" class="form-control extendons-upload-size" name="extendons-maximum-uploadsize[]" value="'+extendons_upload_file_value+'" id="extendons-maximum-uploadsize" oninput="validity.valid||(value='+extendons_upload_file_value+')" style="display: initial;width: 70%;"></div></div><div class="row"><div class="col-xs-4 col-md-3" style="margin-top: 20px;"><label id="extendons_upload_files_label">Customer Notes:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Enable to Allow Customer notes"></span></span></label><input type="checkbox" name="extendons_uf_allow_notes_checkbox[]" class="extendons_vt_checkboxes"><br><label id="extendons_upload_files_label">Required:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Enable to Upload File Required"></span></span></label><input type="checkbox" name="extendons_uf_required[]" class="extendons_vt_checkboxes"></div><div class="col-xs-4 col-md-3" style="margin-top: 20px;"><label id="extendons_upload_files_label">Customer Notes Label:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Label For Customer Notes"></span></span></label><input type="text" name="extendonspriceuploadfilecustomernotelabel[]" value="" id="extendonspriceuploadfilecustomernotelabel" style="width: 100%"></div><div class="col-md-3" style="margin-top: 20px;"><label id="extendons_upload_files_label">Maximum File Upload:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Enter Maximum Quantity for upload file button"></span></span></label><input type="number" min="0" name="extendons_uploadfile_allowed_max_qunatity[]" value="" id="extendons_uploadfile_allowed_max_qunatity" style="width: 100%"><span class="description">If Empty then customer can upload only 1 file.</span></div><div class="col-xs-4 col-md-3" style="margin-top: 20px;"><label id="extendons_upload_files_label">Background Color:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Choose Background Color for Upload File Button"></span></span></label><input type="color" class="jscolor" name="extendons_upload_files_color[]" id="extendons_upload_files_color" value=""><br><label id="extendons_upload_files_label">Text Color:<span class="tip" style="float: right;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Choose Text Color for Upload File Button"></span></span></label><input type="color" class="jscolor" name="extendons_upload_files_text_color[]" id="extendons_upload_files_text_color" value="#653232"></div></div><div class="row"><div class="col-md-5"><button type="button" class="btn btn-danger remove">Delete</button></div></div></div></div>';
}
