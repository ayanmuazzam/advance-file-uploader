jQuery(document).ready(function () {
  "use strict";
  jQuery(".extendons_upload_file_order_data").remove();
  jQuery(".extendons_file_accept").css("background-color", "#007e33");
  jQuery(".extendons_file_accept").css("margin-left", "10px");
  jQuery(".extendons_file_reject").css("background-color", "#cc0000");
  jQuery(".extendons_file_accept").parent().parent().prev().hide();
  jQuery(".extendons_file_accept").parent().parent().prev().after("<th></th>");
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
    const urlParams = new URLSearchParams(window.location.search);
    var extendons_order_id = urlParams.get("post") || urlParams.get('post') || urlParams.get('id');
    let acceptBtn = jQuery(this);
    var ajaxurl = ewcpm_php_vars.admin_url;
    var item_id = jQuery(this).data('item-id');
    var key = jQuery(this).data("key");
    var data_rule_id = jQuery(this).data('rule-id');
    var btn_id = jQuery(this).data('btn-id');
    var modify_key = jQuery(this).data('modify-key');

    acceptBtn.prop("disabled", true);
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "extendons_upload_files_status_approved_rejected",
        extendons_order_id: extendons_order_id,
        extendons_status: "Approved",
        item_id: item_id,
        key: key,
        data_rule_id: data_rule_id,
        btn_id: btn_id,
        modify_key: modify_key,
      },
      success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert(response.data);
            }
            acceptBtn.prop("disabled", false);
        },
        error: function () {
            alert("An error occurred while processing your request.");
            acceptBtn.prop("disabled", false);
        }
    });
  });

  var reject_array = [];
  jQuery("body").on("click", "#extendons_reject_btn", function () {
    "use strict";
    const urlParams = new URLSearchParams(window.location.search);
    var extendons_order_id = urlParams.get("post") || urlParams.get('post') || urlParams.get('id');
    let rejectBtn = jQuery(this);
    var ajaxurl = ewcpm_php_vars.admin_url;
    var item_id = jQuery(this).data('item-id');
    var key = jQuery(this).data("key");
    var data_rule_id = jQuery(this).attr("data-rule-id");
    var btn_id = jQuery(this).data('btn-id');
    var modify_key = jQuery(this).data('modify-key');

    rejectBtn.prop("disabled", true);
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "extendons_upload_files_status_approved_rejected",
        extendons_order_id: extendons_order_id,
        extendons_status: "Rejected",
        item_id: item_id,
        key: key,
        data_rule_id: data_rule_id,
        btn_id: btn_id,
        modify_key: modify_key,
      },
      success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert(response.data);
            }
            rejectBtn.prop("disabled", false);
        },
      error: function () {
            alert("An error occurred while processing your request.");
            rejectBtn.prop("disabled", false);
        }
    });
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

  jQuery("body").on("click", ".extendons-accept-file", function (e) {
    e.preventDefault();
    let acceptBtn = jQuery(this);
    var rule_id = jQuery(this).data("rule-id");
    var order_id = jQuery(this).data("order-id");
    var ajaxurl = ewcpm_php_vars.admin_url;
    var key = jQuery(this).data("key");

    acceptBtn.prop("disabled", true);
    jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: {
            action: "extendons_upload_files_accept_reject_file",
            rule_id: rule_id,
            order_id: order_id,
            key: key,
            extendons_status: "Approved",
        },
        success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert(response.data);
            }
            acceptBtn.prop("disabled", false);
        },
        error: function () {
            alert("An error occurred while processing your request.");
            acceptBtn.prop("disabled", false);
        }
    });
  });

  jQuery("body").on("click", ".extendons-reject-file", function (e) {
    e.preventDefault();
    let rejectBtn = jQuery(this);
    var rule_id = jQuery(this).data("rule-id");
    var order_id = jQuery(this).data("order-id");
    var key = jQuery(this).data("key");
    var ajaxurl = ewcpm_php_vars.admin_url;

    rejectBtn.prop("disabled", true);

    jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: {
            action: "extendons_upload_files_accept_reject_file",
            rule_id: rule_id,
            order_id: order_id,
            key: key,
            extendons_status: "Rejected",
        },
        success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert(response.data);
            }
            rejectBtn.prop("disabled", false);
        },
        error: function () {
            alert("An error occurred while processing your request.");
            rejectBtn.prop("disabled", false);
        }
    });
  });

});
