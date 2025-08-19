jQuery(document).ready(function () {
  "use strict";
  jQuery(".extendons_preview_image").hide();
  jQuery(".extendons_delete_img").hide();
  jQuery(".extendons_customer_note").hide();
  // jQuery('.extendons_file_accept').parent().prev().hide();
  jQuery(".single_add_to_cart_button")
    .parent()
    .before('<span class="extendons_modal_upload_file"></span>');

  jQuery("body").on("click", ".extendons_upload_file_order_data", function (e) {
    e.preventDefault();
    var extendons_order_id = jQuery('input[name="extendons_order_id"]').val();
    var data_btnindex_key = jQuery(this).attr("data-btn-id");
    var data_p_id = jQuery(this).attr("data-p-id");
    var data_id = jQuery(this).attr("data-id");
    var data_cart_key = jQuery(this).attr("data-cart-key");
    var data_rule_id = jQuery(this).attr("data-rule-id");
    var data_item_id = jQuery(this).attr("data-item-id");
    var data_meta_key = jQuery(this).attr("data-meta-key");
    var ajaxurl = ewcpm_php_vars.admin_url;
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "extendons_update_orderfile_data",
        extendons_order_id: extendons_order_id,
        data_btnindex_key: data_btnindex_key,
        data_p_id: data_p_id,
        data_id: data_id,
        data_cart_key: data_cart_key,
        data_rule_id: data_rule_id,
        data_item_id: data_item_id,
        data_meta_key: data_meta_key,
      },
      beforeSend: function () {
        // loader.css("display", "inline-flex");
      },
      complete: function () {
        // setTimeout(function () {
        //     loader.hide();
        // }, 1000);
      },
      success: function (data) {
        // console.log(data);
        jQuery(".extendons_modal_upload_order_file").html(data);
        jQuery(".custom-model-main").addClass("model-open");
      },
    });
  });

  jQuery("body").on("click", ".extendons_order_data_val_change", function () {
    console.log("choose file");
    var indexarray = jQuery(this).attr("index-array");
    var btn_key = jQuery(this).attr("btn_key");
    let fileId = jQuery(this).data("file-id");
    let data_meta_key = jQuery(this).data("meta-key");
    jQuery(".showimage1")
      .find(".extendons_file_input")
      .attr("indexarray", indexarray);
    jQuery(".showimage1")
      .find(".extendons_file_input")
      .attr("btn_key", btn_key);
    jQuery(".showimage1")
      .find(".extendons_file_input")
      .attr("file-id", fileId);
      jQuery(".showimage1")
      .find(".extendons_file_input")
      .attr("data-meta-key", data_meta_key);
  });
});

jQuery(document).ready(function () {
  jQuery('input[name="extendons_file_input[]"]').val("");
  jQuery("body").on("click", ".extendons_uploadfiles_btn", function () {
    var data_allow_upload_file = jQuery(this).attr("data-allow-upload_file");
    var extendons_post_id = jQuery(this).attr("data-id");
    var index_key = jQuery(this).attr("index-key");
    var productid = jQuery(this).attr("p-id");
    var pagetype = jQuery(this).attr("page_type");
    var loader = jQuery(this).prev();
    var ajaxurl = ewcpm_php_vars.admin_url;
    extendons_file_chosen_array = Array();
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "extendons_upload_cfiles_data",
        data_allow_upload_file: data_allow_upload_file,
        extendons_post_id: extendons_post_id,
        index_key: index_key,
        productid: productid,
        pagetype: pagetype,
      },
      beforeSend: function () {
        loader.css("display", "inline-flex");
      },
      complete: function () {
        setTimeout(function () {
          loader.hide();
        }, 1000);
      },
      success: function (data) {
        jQuery(".extendons_modal_upload_file").html(data);
        jQuery(".custom-model-main").addClass("model-open");
      },
    });
  });

  jQuery("body").on("click", ".extendons_uploadfiles_ccta_btn", function () {
    var data_allow_upload_file = jQuery(this).attr("data-allow-upload_file");
    var extendons_post_id = jQuery(this).attr("data-id");
    var index_key = jQuery(this).attr("data-index-key");
    var rule_id_index = jQuery(this).attr("data-rule-id");
    var productid = jQuery(this).attr("data-p-id");
    var pagetype = jQuery(this).attr("data-page-type");
    var cart_key = jQuery(this).attr("data-cart-key");
    var extendons_order_id = jQuery('input[name="extendons_order_id"]').val();
    var loader = jQuery(this).prev();
    var ajaxurl = ewcpm_php_vars.admin_url;
    extendons_file_chosen_array = Array();

    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "extendons_uploadfile_ccta_data",
        data_allow_upload_file: data_allow_upload_file,
        extendons_post_id: extendons_post_id,
        index_key: index_key,
        rule_id_index: rule_id_index,
        productid: productid,
        pagetype: pagetype,
        cart_key: cart_key,
        extendons_order_id: extendons_order_id,
      },
      beforeSend: function () {
        loader.css("display", "inline-flex");
      },
      complete: function () {
        setTimeout(function () {
          loader.hide();
        }, 1000);
      },
      success: function (data) {
        jQuery(".extendons_modal_upload_cart_file").html(data);
        jQuery(".custom-model-main").addClass("model-open");
      },
    });
  });

  jQuery("body").on("dragover", ".extendons_upload_dropzone", function (e) {
    e.preventDefault();
    e.stopPropagation();
    jQuery(this).css({ opacity: "0.5" });
    jQuery(this).addClass("drag-over");
  });

  jQuery("body").on("dragleave", ".extendons_upload_dropzone", function (e) {
    e.preventDefault();
    e.stopPropagation();
    jQuery(this).css({ opacity: "1" });
    jQuery(this).removeClass("drag-over");
  });

  jQuery("body").on("drop", ".extendons_upload_dropzone", function (e) {
    e.preventDefault();
    e.stopPropagation();
    jQuery(this).removeClass("drag-over");
    jQuery(this).css({ opacity: "1" });

    var files = e.originalEvent.dataTransfer.files;
    var input = jQuery(this).find(".extendons_file_input")[0];
    processFileUpload(input, files);
  });

  jQuery("body").on(
    "click",
    ".extendons_uploadfiles_ccta_order_btn",
    function () {
      var data_allow_upload_file = jQuery(this).attr("data-allow-upload_file");
      var extendons_post_id = jQuery(this).attr("data-id");
      var index_key = jQuery(this).attr("data-index-key");
      var data_rule_index_id = jQuery(this).attr("data-rule-index-id");
      var productid = jQuery(this).attr("data-p-id");
      var pagetype = jQuery(this).attr("data-page-type");
      var cart_key = jQuery(this).attr("data-cart-key");
      var extendons_order_id = jQuery('input[name="extendons_order_id"]').val();
      var loader = jQuery(this).prev();
      var ajaxurl = ewcpm_php_vars.admin_url;
      extendons_file_chosen_array = Array();

      jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: {
          action: "extendons_uploadfiles_ccta_order_btn",
          data_allow_upload_file: data_allow_upload_file,
          extendons_post_id: extendons_post_id,
          index_key: index_key,
          productid: productid,
          pagetype: pagetype,
          cart_key: cart_key,
          data_rule_index_id: data_rule_index_id,
          extendons_order_id: extendons_order_id,
        },
        beforeSend: function () {
          loader.css("display", "inline-flex");
        },
        complete: function () {
          setTimeout(function () {
            loader.hide();
          }, 1000);
        },
        success: function (data) {
          jQuery(".extendons_modal_upload_order_file").html(data);
          jQuery(".custom-model-main").addClass("model-open");
        },
      });
    }
  );

  jQuery("body").on("click", ".extendons_btn_upload", function () {
    var indexes = jQuery(this).parent().prev().val();
    jQuery(".extendons_file_input_order_page").attr("indexarray", indexes);
  });

  var extendons_file_chosen_array123 = [];
  // modify file upload
  // PIN:
  jQuery("body").on("change", ".extendons_file_input", function (e) {
    extendons_file_chosen_array123 = [];
    processFileUpload(this);
  });

  function processFileUpload(inputElement, file = null) {

    var extendons_file_chosen_array = [];
    var datapostid = jQuery(inputElement).attr("data-post-id");
    var productid = jQuery(inputElement).attr("pro-id");
    var indexkey = jQuery(inputElement).attr("indexkey");
    var data_rule_id = jQuery(inputElement).attr("data-rule-id");
    var cart_key = jQuery(inputElement).attr("cart-key");
    var pagetype = jQuery(inputElement).attr("page");
    var file = file || jQuery(inputElement)[0].files;
    var counter_length = jQuery(
      'input[name="extendons_hidden_array' + productid + '"]'
    ).val();

    var maxuploadfile = jQuery(inputElement).attr("max-file-upload");
    var acceptextension = jQuery(inputElement).attr("accept");
    var extendons_file_sizetype = jQuery(inputElement).attr("type-file-format");
    var filesizeallowtoupload = jQuery(inputElement).attr(
      "file-size-allow-to-upload"
    );
    length = file.length;
    var i;
    var file_size;

    for (i = 0; i < length; i++) {
      var fileName = file[i].name;
      fileExtension = fileName.replace(/^.*\./, "");
      var validExtensions = acceptextension;

      console.log("fileExtension");
      console.log(fileExtension);
      var validExtensions = validExtensions.split(",").map((ext) => ext.trim());
      if (jQuery.inArray(fileExtension, validExtensions) == -1) {
        jQuery(".extendonsrrormsg").text("");
        jQuery(".extendonsrrormsg").text(
          "Invalid file type Please choose only " + validExtensions + " files"
        );
        jQuery(".extendonsrrormsg").css("color", "red");
        jQuery(".extendonsrrormsg").show();
        jQuery(".extendonsrrormsg").delay(4000).fadeOut("slow");
        jQuery(inputElement).val("");
        extendons_file_chosen_array123 = [];
        return false;
      } else {
        if (filesizeallowtoupload != "" && extendons_file_sizetype != "") {
          if (extendons_file_sizetype == "extendons_upload_files_KB") {
            var extendons_selected_file_size = file[i].size;
            file_size = extendons_selected_file_size / 1024;
          } else if (extendons_file_sizetype == "extendons_upload_files_MB") {
            var extendons_selected_file_size = file[i].size;
            file_size = extendons_selected_file_size / 1024;
            file_size = file_size / 1024;
          }
        }
        if (file_size > filesizeallowtoupload) {
          extendons_file_sizetype = extendons_file_sizetype.replace(
            "extendons_upload_files_",
            ""
          );
          jQuery(".extendonsrrormsg").text("");
          jQuery(".extendonsrrormsg").text(
            "File Size must be Less than " +
              filesizeallowtoupload +
              " " +
              extendons_file_sizetype
          );
          jQuery(".extendonsrrormsg").css("color", "red");
          jQuery(".extendonsrrormsg").show();
          jQuery(".extendonsrrormsg").delay(4000).fadeOut("slow");
          jQuery(inputElement).val("");
          extendons_file_chosen_array123 = [];
          return false;
        } else {
          counter_length++;
          extendons_file_chosen_array.push(file[i]);
          extendons_file_chosen_array123.push(file[i]);
        }
      }
    }

    if (counter_length > maxuploadfile) {
      jQuery(".extendonsrrormsg").text("");
      jQuery(".extendonsrrormsg").text(
        "You can only upload a maximum of " + maxuploadfile + " files"
      );
      jQuery(".extendonsrrormsg").css("color", "red");
      jQuery(".extendonsrrormsg").show();
      jQuery(".extendonsrrormsg").delay(4000).fadeOut("slow");
      jQuery(inputElement).val("");
      extendons_file_chosen_array123 = [];
      return false;
    } else {
      var i;

      for (i = 0; i < extendons_file_chosen_array.length; i++) {
        filetype = extendons_file_chosen_array[i].type.split("/")[0];
        var path = (window.URL || window.webkitURL).createObjectURL(
          extendons_file_chosen_array[i]
        );
        jQuery('input[name="extendons_hidden_array' + productid + '"]').val(
          counter_length
        );
        var selectedextension = extendons_file_chosen_array[i].name
          .split(".")
          .pop()
          .toLowerCase();
        var file_src;
        if (filetype == "image") {
          file_src = "<img src=" + path + ' width="100">';
        } else if (filetype == "application") {
          file_src =
            '<img src="https://www.svgrepo.com/show/424860/pdf-file-type.svg" width="50">';
        } else if (filetype == "video") {
          file_src =
            '<img src="https://iconarchive.com/download/i61405/hadezign/hobbies/Movies.ico" width="50">';
        } else if (filetype == "audio") {
          file_src =
            '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyYf66jcjiKLRac02OnFXPkx-gULXJNxtsgQ&usqp=CAU" width="50">';
        } else {
          file_src =
            '<img src="https://cdn3.iconfinder.com/data/icons/brands-applications/512/File-512.png" width="100">';
        }
        if (pagetype == "productpage") {
          var deleteclass = "extendons_delete_file";
        } else {
          var deleteclass = "extendons_delete_ccta_file";
        }

        jQuery(".showimage").append(
          '<tr class="extendons-image-list"><td>' +
            file_src +
            "</td><td><small>" +
            extendons_file_chosen_array[i].name +
            '</small></td><td><span style="display:inline-flex;cursor:pointer;"><a href="' +
            path +
            '" target="_blank;"><img src="https://cdn.iconscout.com/icon/premium/png-256-thumb/view-file-461477.png" width="30"></a><button type="button" index_key="' +
            indexkey +
            '" data-proid="' +
            productid +
            '" datapostid="' +
            datapostid +
            '" index="' +
            i +
            '" class="' +
            deleteclass +
            '" style="background-color: white;padding: unset;"><img src="https://cdn.iconscout.com/icon/premium/png-256-thumb/delete-1432400-1211078.png" width="30"></button></span></td></tr>'
        );
      }

      var getpagename = jQuery(inputElement).attr("page");
      if (getpagename == "thankyou_account_page") {
        var data = new FormData();
        var file = jQuery(inputElement)[0].files[0];

        var extendons_order_id = jQuery(inputElement).attr("order_id");
        let fileId = jQuery(inputElement).attr("file-id");
        var indexarray = jQuery(inputElement).attr("indexarray");
        var btn_key = jQuery(inputElement).attr("btn_key");
        var data_item_id = jQuery(inputElement).attr("data-item-id");
        var data_meta_key = jQuery(inputElement).attr("data-meta-key");
        data.append("file", file);
        data.append("btn_key", btn_key);
        data.append("rule_index_id", data_rule_id);
        data.append("productid", productid);
        data.append("postid", datapostid);
        data.append("pagetype", pagetype);
        data.append("action", "extendons_ext_upload_order_file");
        data.append("cart_key", cart_key);
        data.append("extendons_order_id", extendons_order_id);
        data.append("indexarray", indexarray);
        data.append("data_item_id", data_item_id);
        data.append("data_meta_key", data_meta_key);
        data.append("requestId", ewcpm_php_vars.ext_fu_requestID);
        if (fileId) {
          data.append("fileId", fileId);
        }

        var ajaxurl = ewcpm_php_vars.admin_url;

        jQuery.ajax({
          url: ajaxurl,
          type: "POST",
          enctype: "multipart/form-data",
          contentType: false,
          processData: false,
          data: data,
          cache: false,
          timeout: 600000,
          beforeSend: function () {
            // console.log("before send modify file ");
            // console.log("data");
            // console.log(data["file"]);
            // for (let [key, value] of data.entries()) {
            //     console.log(`${key}: ${value}`);
            // }
          },
          success: function (response) {
            console.log(" modify response");
            console.log(response);
            // console.log(JSON.parse(response));
            filetype = file.type.split("/")[0];
            var path = (window.URL || window.webkitURL).createObjectURL(file);
            var selectedextension = file.name.split(".").pop().toLowerCase();
            var file_src;
            if (filetype == "image") {
              file_src = path;
            } else if (filetype == "application") {
              file_src =
                "https://www.svgrepo.com/show/424860/pdf-file-type.svg";
            } else if (filetype == "video") {
              file_src =
                "https://iconarchive.com/download/i61405/hadezign/hobbies/Movies.ico";
            } else if (filetype == "audio") {
              file_src =
                "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyYf66jcjiKLRac02OnFXPkx-gULXJNxtsgQ&usqp=CAU";
            } else {
              file_src =
                "https://cdn3.iconfinder.com/data/icons/brands-applications/512/File-512.png";
            }

            jQuery("#thumb" + indexarray + data_item_id + btn_key)
              .find("img")
              .attr("src", file_src);
            jQuery("#title" + indexarray + data_item_id + btn_key).text(
              file.name
            );
            jQuery("#view" + indexarray + data_item_id + btn_key)
              .find(".extendonsvieworderfile")
              .attr("href", path);
            setTimeout(function () {
              console.log("preventing modify Reload");
              location.reload();
            }, 1000);
          },
          error: function (e) {
            console.log("ERROR : ", e);
          },
        });
      }
    }
  }

  jQuery("body").on("click", ".extendons_save_cart_data", function (e) {
    e.preventDefault();
    let btnText = jQuery(this).val();
    jQuery(this).val("Uploading...");
    jQuery(this).attr("disabled", "disabled");
    // var extendons_file_chosen_array = [];
    var inputfile = jQuery(".extendons_file_input");
    var datapostid = jQuery(inputfile).attr("data-post-id");
    var productid = jQuery(inputfile).attr("pro-id");
    var indexkey = jQuery(inputfile).attr("indexkey");
    var data_rule_id = jQuery(inputfile).attr("data-rule-id");
    var cart_key = jQuery(inputfile).attr("cart-key");
    var pagetype = jQuery(inputfile).attr("page");
    var extendons_customer_note_val = jQuery(this)
      .parent()
      .find("#extendons_customer_note_textareas" + indexkey + datapostid)
      .val();
    var data = new FormData();
    var file = jQuery(".extendons_file_input")[0].files;
    var datarecaptcha = jQuery(this).attr("data-recaptcha");
    var extendons_recaptcha_site_key = jQuery(this)
      .parent()
      .find(".extendons_recaptcha_site_key")
      .val();
    var extendons_recaptcha_secret_key = jQuery(this)
      .parent()
      .find(".extendons_recaptcha_secret_key")
      .val();

    // for(i= 0; i < file.length; i++) {
    // 	extendons_file_chosen_array.push(file[i]);
    // }

    extendons_file_chosen_array = extendons_file_chosen_array123;

    // console.log(extendons_file_chosen_array123);
    // die();

    var data = new FormData();
    for (j = 0; j < extendons_file_chosen_array.length; j++) {
      data.append(j, extendons_file_chosen_array[j]);
    }

    data.append("indexkey", indexkey);
    data.append("data_rule_id", data_rule_id);
    data.append("productid", productid);
    data.append("postid", datapostid);
    data.append("pagetype", pagetype);
    data.append("action", "extendons_ext_upload_file");
    data.append("cart_key", cart_key);
    data.append("extendons_customer_note", extendons_customer_note_val);
    data.append("requestId", ewcpm_php_vars.ext_fu_requestID);
    var ajaxurl = ewcpm_php_vars.admin_url;
    if (
      datarecaptcha == "Recaptcha_enable" &&
      extendons_recaptcha_site_key != "" &&
      extendons_recaptcha_secret_key != ""
    ) {
      var v = grecaptcha.getResponse();
      recaptcha_length = v.length;
    } else {
      recaptcha_length = "1";
    }
    if (recaptcha_length == "0") {
      jQuery(".recaptchaval").show();
      jQuery(".recaptchaval").delay(4000).fadeOut("slow");
    } else {
      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        enctype: "multipart/form-data",
        contentType: false,
        processData: false,
        data: data,
        cache: false,
        timeout: 600000,
        success: function (response) {
          if (pagetype == "") {
            jQuery(".custom-model-main").removeClass("model-open");
            if (jQuery("[name='update_cart']").length > 0) {
              jQuery("[name='update_cart']").removeAttr("disabled");
              jQuery("[name='update_cart']").trigger("click");
            } else {
              jQuery("body").trigger("update_checkout");
            }
          } else {
            // jQuery('#message_upload_file_data').show();
            setTimeout(function () {
              // console.log("preventing reload upload file");
              // location.reload();

              // history.replaceState(
              //     null,
              //     "",
              //     window.location.href
              // );
              // // Reload the page
              // window.location.reload();

              // Reload the page without form resubmission
              window.location.href = window.location.href;
            }, 500);
          }
          if (
            jQuery(".wp-block-woocommerce-cart-line-items-block").length ||
            jQuery(".wp-block-woocommerce-checkout-terms-block").length
          ) {
            setTimeout(function () {
              window.location.href = window.location.href;
            }, 500);
          }
        },
        error: function (e) {
          console.log("ERROR : ", e);
          jQuery(this).val(btnText);
          jQuery(this).removeAttr("disabled");
        },
      });
      return true;
    }
  });

  jQuery("body").on("click", ".extendons_delete_file", function (e) {
    e.preventDefault();

    var index = jQuery(this).attr("index");
    let fileId = jQuery(this).data("file-id");
    extendons_file_chosen_array123.splice(index, 1);
    jQuery(this).parent().parent().parent().remove();
    var datapostid = jQuery(this).attr("datapostid");
    var dataproid = jQuery(this).attr("data-proid");
    var indexkey = jQuery(this).attr("index_key");
    var customer_note = jQuery(".extendons_save_cart_data")
      .parent()
      .find(".extendons_customer_note_textareas")
      .val();
    var ajaxurl = ewcpm_php_vars.admin_url;
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "extendons_upload_delete_file",
        indexkey: indexkey,
        datapostid: datapostid,
        dataproid: dataproid,
        arrindex: index,
        customer_note: customer_note,
        fileId: fileId, // Pass the file ID for deletion
      },
      success: function (data) {
        var counter = jQuery(
          'input[name="extendons_hidden_array' + dataproid + '"]'
        ).val();
        counter = counter - 1;
        jQuery('input[name="extendons_hidden_array' + dataproid + '"]').val(
          counter
        );
      },
    });
  });

  jQuery("body").on("click", ".extendons_delete_ccta_file", function (e) {
    e.preventDefault();

    var index = jQuery(this).attr("index");
    let fileId = jQuery(this).data("file-id");
    extendons_file_chosen_array123.splice(index, 1);
    jQuery(this).parent().parent().parent().remove();
    var datapostid = jQuery(this).attr("datapostid");
    var dataproid = jQuery(this).attr("data-proid");
    var indexkey = jQuery(this).attr("index_key");
    var rule_id_index = jQuery(this).attr("rule_id_index");
    var delete_cart_key = jQuery(this).attr("delete_cart_key");
    var extendons_order_id = jQuery('input[name="extendons_order_id"]').val();
    var pagetype = jQuery(this).attr("page_type");
    var ajaxurl = ewcpm_php_vars.admin_url;
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "extendons_upload_ccta_delete_file",
        indexkey: indexkey,
        datapostid: datapostid,
        dataproid: dataproid,
        arrindex: index,
        rule_id_index: rule_id_index,
        delete_cart_key: delete_cart_key,
        extendons_order_id: extendons_order_id,
        pagetype: pagetype,
        fileId: fileId, // Pass the file ID for deletion
      },
      success: function (data) {
        var counter = jQuery(
          'input[name="extendons_hidden_array' + dataproid + '"]'
        ).val();
        counter = counter - 1;
        jQuery('input[name="extendons_hidden_array' + dataproid + '"]').val(
          counter
        );

        if (pagetype != "thankyou_page") {
          jQuery(".custom-model-main").removeClass("model-open");
          if (jQuery("[name='update_cart']").length > 0) {
            jQuery("[name='update_cart']").removeAttr("disabled");
            jQuery("[name='update_cart']").trigger("click");
          } else {
            jQuery("body").trigger("update_checkout");
          }
        }
        if (
          jQuery(".wp-block-woocommerce-cart-line-items-block").length ||
          jQuery(".wp-block-woocommerce-checkout-terms-block").length
        ) {
          setTimeout(function () {
            window.location.href = window.location.href;
          }, 1000);
        }
      },
    });
  });
});
