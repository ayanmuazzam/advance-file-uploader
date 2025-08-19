jQuery(document).ready(function () {
    "use strict";
    jQuery(".extendons_upload_file_order_data").remove();
    jQuery(".extendons_file_accept").css("background-color", "#007e33");
    jQuery(".extendons_file_accept").css("margin-left", "10px");
    jQuery(".extendons_file_reject").css("background-color", "#cc0000");
    jQuery(".extendons_file_accept").parent().parent().prev().hide();
    jQuery(".extendons_file_accept")
        .parent()
        .parent()
        .prev()
        .after("<th></th>");
    jQuery(".upload-file-status").hide();
    jQuery(".reset-upload-file-status").hide();
    jQuery(".extendons_download").on("click", function (e) {
        e.preventDefault();
        var url = jQuery(this).parent().attr("href");
        var link = document.createElement("a");
        link.href = url;
        var id = getUrlVars()["post"];
        link.download = "Order #" + id;
        link.click();
        link.remove();
    });

    jQuery("body").on("click", ".reset-upload-file-status", function (e) {
        e.preventDefault();

        jQuery(this).parent().find(".extendons_file_accept").show();
        jQuery(this).parent().find(".extendons_file_reject").show();
        jQuery(this).parent().find(".extendons_note_order").remove();
        jQuery(this).parent().find(".accepted").remove();
        jQuery(this).parent().find(".rejected").remove();
        jQuery(this).remove();
    });

    var accept_array = [];
    jQuery("body").on("click", "#extendons_accept_btn", function () {
        "use strict";
        var extendons_order_id = getUrlVars()["post"];
        var ajaxurl = ewcpm_php_vars.admin_url;
        var item_id = jQuery(this).attr("data-attr");
        var key = jQuery(this).attr("value");
        var data_rule_id = jQuery(this).attr("data-rule-id");

        jQuery(this).hide();
        jQuery(this).next().hide();
        jQuery(this).after(
            '<input type="hidden" class="accepted" name="extendons_order_staus_key[]" value="Accepted"><input type="hidden" class="accepted" name="extendons_order_meta_key[]" value="' +
                key +
                '"><input class="accepted" type="hidden" name="extendons_item_id[]" value="' +
                item_id +
                '"><span class="upload-file-status accepted">File will be <b>accepted</b>: please remember to save the order.</span><a href="#" class="reset-upload-file-status" data-item-id="' +
                item_id +
                '" data-rule-id="' +
                data_rule_id +
                '">Cancel</a><textarea class="extendons_note_order" name="extendons_order_file_note[]" class="order-file-note" placeholder="Insert here the message you want to send to the customer"></textarea>'
        );
    });

    var reject_array = [];
    jQuery("body").on("click", "#extendons_reject_btn", function () {
        "use strict";
        var extendons_order_id = getUrlVars()["post"];
        var ajaxurl = ewcpm_php_vars.admin_url;
        var item_id = jQuery(this).attr("data-attr");
        var key = jQuery(this).attr("value");
        var data_rule_id = jQuery(this).attr("data-rule-id");
        jQuery(this).hide();
        jQuery(this).prev().hide();
        jQuery(this).after(
            '<input type="hidden" class="rejected" name="extendons_order_staus_key[]" value="Rejected"><input type="hidden" class="rejected" name="extendons_order_meta_key[]" value="' +
                key +
                '"><input class="rejected" type="hidden" name="extendons_item_id[]" value="' +
                item_id +
                '"><span class="upload-file-status rejected">File will be <b>rejected</b>: please remember to save the order.</span><a href="#" class="reset-upload-file-status" data-item-id="' +
                item_id +
                '" data-rule-id="' +
                data_rule_id +
                '">Cancel</a><textarea class="extendons_note_order" name="extendons_order_file_note[]" class="order-file-note" placeholder="Insert here the message you want to send to the customer"></textarea>'
        );
        // jQuery.ajax({
        // 	url: ajaxurl,
        // 	type: 'post',
        // 	data: {
        // 		action: 'extendons_upload_files_status_disapproved',
        // 		extendons_order_id:extendons_order_id,
        // 		extendons_status:'Disapproved',
        // 		item_id:item_id,
        // 		key:key,
        // 		data_val:data_val,
        // 	},
        // 	success: function (data) {
        // 		var a =JSON.parse(data);
        // 		console.log(a);
        // 		reject_array.push(a);
        // 		var myJSON = JSON.stringify(reject_array);
        // 		jQuery('#extendons_reject_button_array').val(myJSON);
        // 	}
        // });
    });
    function getUrlVars() {
        "use strict";
        var vars = [],
            hash;
        var hashes = window.location.href
            .slice(window.location.href.indexOf("?") + 1)
            .split("&");
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split("=");
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
});
