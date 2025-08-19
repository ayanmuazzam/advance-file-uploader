jQuery(document).ready(function ($) {
  // Store rule-specific data
  // const ruleDataMap = {};
  //
  var ruleDataMap = {};

  // Initialize upload container
  function initializeUploadContainer($container) {
    const ruleId = $container
      .find(".extendons_upload_files_hidden_input")
      .attr("data-rule-id");
    const key = $container
      .find(".extendons_upload_files_main_wrapper")
      .attr("id")
      .split("_")
      .pop();

    if (!ruleDataMap[ruleId]) {
      ruleDataMap[ruleId] = {};
    }
    if (!ruleDataMap[ruleId][key]) {
      ruleDataMap[ruleId][key] = {
        fileChosenArray: [],
        validatedFiles: [],
        customerNotes: "",
      };
    }

    // Fetch session files from server
    function fetchSessionFiles() {
      $.ajax({
        url: ewcpm_php_vars.admin_url,
        type: "POST",
        data: {
          action: "get_session_files",
          rule_id: ruleId,
          key: key,
          requestID: ewcpm_php_vars.ext_fu_requestID,
        },
        success(response) {
          if (response.success && response.data.files) {
            ruleDataMap[ruleId][key].fileChosenArray = response.data.files.map(
              (file) => ({
                file_id: file.file_id,
                name: file.name,
                url: file.url,
                thumbnail: file.thumbnail,
                path: file.path,
                user_id: file.user_id,
              })
            );
            ruleDataMap[ruleId][key].validatedFiles =
              ruleDataMap[ruleId][key].fileChosenArray;
            // Load customer notes if available in response
            if (response.data.notes) {
              ruleDataMap[ruleId][key].customerNotes = response.data.notes;
              $container
                .find(`#extendons_upload_files_note_textarea_${ruleId}_${key}`)
                .val(response.data.notes);
            }
            $container
              .find(`#extendons_upload_files_counter_${ruleId}`)
              .val(ruleDataMap[ruleId][key].fileChosenArray.length);
            displaySelectedImages($container, ruleId, key);
          }
        },
      });
    }

    // Start by fetching session files
    fetchSessionFiles();
  }

  // Validate uploaded files
  function validateFiles(files, $container, ruleId, key) {
    const $fileInput = $container.find(".extendons_upload_files_hidden_input");
    const $counter = $container.find(
      `#extendons_upload_files_counter_${ruleId}`
    );
    const maxFiles = parseInt($fileInput.attr("max-file-upload")) || 5;
    const allowedExtensions = $fileInput
      .attr("accept")
      .split(",")
      .map((ext) => ext.trim().replace(".", "").toLowerCase());
    const maxSize = parseInt($fileInput.attr("file-size-allow-to-upload"));
    const sizeType = $fileInput.attr("type-file-format").toLowerCase();
    const currentCount = parseInt($counter.val()) || 0;

    if (currentCount + files.length > maxFiles) {
      showMessage(
        `Maximum ${maxFiles} files allowed.`,
        "error",
        $container,
        ruleId,
        key
      );
      return [];
    }

    const validFiles = [];
    for (const file of files) {
      const fileExtension = file.name.split(".").pop().toLowerCase();
      const fileSize =
        sizeType === "kb"
          ? file.size / 1024
          : sizeType === "mb"
          ? file.size / (1024 * 1024)
          : sizeType === "gb"
          ? file.size / (1024 * 1024 * 1024)
          : file.size;

      if (!allowedExtensions.includes(fileExtension)) {
        showMessage(
          `Invalid file type. Allowed: ${allowedExtensions.join(", ")}.`,
          "error",
          $container,
          ruleId,
          key
        );
        return [];
      }
      if (fileSize > maxSize) {
        showMessage(
          `File size exceeds ${maxSize} ${sizeType}.`,
          "error",
          $container,
          ruleId,
          key
        );
        return [];
      }
      validFiles.push(file);
    }

    $counter.val(currentCount + validFiles.length);
    ruleDataMap[ruleId][key].fileChosenArray = [
      ...ruleDataMap[ruleId][key].fileChosenArray,
      ...validFiles,
    ];
    ruleDataMap[ruleId][key].validatedFiles =
      ruleDataMap[ruleId].fileChosenArray;
    return validFiles;
  }

  // Display error or success message
  function showMessage(message, type, $container, ruleId, key, show = true) {
    const $target = $container.find(
      type === "error"
        ? "#extendons_upload_files_error_" + ruleId + "_" + key
        : "#extendons_upload_files_success_" + ruleId + "_" + key
    );

    if (type === "error") {
      $target.text(message).show();
      setTimeout(() => $target.hide(), 4000);
    } else {
      if (show) {
        $target.text(message).show();
      } else {
        setTimeout(() => $target.hide(), 4000);
      }
    }
  }

  // Display selected images
  function displaySelectedImages($container, ruleId, key) {
    const $imageList = $container.find(
      ".extendons_upload_files_selected_images"
    );

    const $buttonLabel = $container.find(
      `#extendons_upload_files_button_label_${ruleId}_${key}`
    );

    $imageList.empty().show();

    ruleDataMap[ruleId][key].fileChosenArray.forEach((file, index) => {
      const $imageItem = $("<div>", {
        class: "extendons_upload_files_image_item",
        id: `extendons_upload_files_image_${ruleId}_${index}`,
      });

      const $thumbnail = $("<div>", {
        class: `extendons_upload_files_image_thumbnail ${
          index % 2 === 1 ? "extendons_upload_files_dark_theme" : ""
        }`,
      });
      console.log("File object:", file);
      let imgSrc =
        file.objectURL ||
        (file instanceof File ? URL.createObjectURL(file) : file.url || "");
      if (file instanceof File && !file.objectURL) file.objectURL = imgSrc;
      let thumbnailUrl = file.thumbnail || "";

      const $img = $("<img>", {
        src: thumbnailUrl || imgSrc,
        alt: file.name,
        "data-object-url": imgSrc,
      });
      $thumbnail.append($img);

      const $imageInfo = $("<div>", {
        class: "extendons_upload_files_image_info",
      }).append(
        $("<div>", {
          class: "extendons_upload_files_image_name",
          text: file.name,
        })
      );

      const $actionsContainer = $("<div>", {
        class: "extendons_upload_files_image_actions",
      });

      const $previewButton = $("<button>", {
        class:
          "extendons_upload_files_action_button extendons_upload_files_preview_button",
        html: `<svg fill="currentColor" class="extendons_upload_files_action_icon" viewBox="0 0 24 24">
                 <path d="M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17Z" />
               </svg>`,
        "data-img-src": imgSrc,
      });
      const $deleteButton = $("<button>", {
        class:
          "extendons_upload_files_action_button extendons_upload_files_delete_button",
        html: `<svg fill="currentColor" class="extendons_upload_files_action_icon" viewBox="0 0 24 24">
                 <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
               </svg>`,
        "data-index": index,
        "data-file-id": file.file_id,
      });

      $actionsContainer.append($previewButton, $deleteButton);
      $imageItem.append($thumbnail, $imageInfo, $actionsContainer);
      $imageList.append($imageItem);
    });
    if (ruleDataMap[ruleId][key].fileChosenArray.length > 0) {
      $buttonLabel.text(
        `${ruleDataMap[ruleId][key].fileChosenArray.length} file(s) selected`
      );
    }
  }

  // Remove an image
  function removeImage(index, $imageItem, $container, ruleId, key, fileId) {
    const $counter = $container.find(
      `#extendons_upload_files_counter_${ruleId}`
    );
    const $fileInput = $container.find(".extendons_upload_files_hidden_input");
    const $imageList = $container.find(
      ".extendons_upload_files_selected_images"
    );

    const imgSrc = $imageItem.find("img").data("object-url");
    if (imgSrc) URL.revokeObjectURL(imgSrc);
    const file = ruleDataMap[ruleId][key].fileChosenArray[index];
    if (!(file instanceof File) && (file.url || !imgSrc)) {
      showMessage(
        "Removing file. Please wait...",
        "success",
        $container,
        ruleId,
        key,
        true
      );
      $.ajax({
        url: ewcpm_php_vars.admin_url,
        type: "POST",
        data: {
          action: "delete_session_file",
          file_id: fileId,
          rule_id: ruleId,
          key: key,
          index,
          requestID: ewcpm_php_vars.ext_fu_requestID,
        },
        success(response) {
          if (response.success) {
            // ruleDataMap[ruleId][key].fileChosenArray = response.data.files.map(
            //   (file) => ({
            //     file_id: file.file_id,
            //     name: file.name,
            //     url: file.url,
            //     path: file.path,
            //     user_id: file.user_id,
            //   })
            // );
            // ruleDataMap[ruleId][key].validatedFiles =
            //   ruleDataMap[ruleId][key].fileChosenArray;
            // $counter.val(ruleDataMap[ruleId][key].fileChosenArray.length);
            // displaySelectedImages($container, ruleId, key);
            // if (!ruleDataMap[ruleId][key].fileChosenArray.length) {
            //   $imageList.hide();
            //   $fileInput.val("");
            // }
            // Update cart
            if (
              $(".wp-block-woocommerce-cart-line-items-block").length ||
              $(".wp-block-woocommerce-checkout-terms-block").length 
              // || $("body").find('[class*="wp-block"]').length > 0
            ) {
              $(window).on("load", () => initializeUploadContainer($container));
              window.location.reload();
            } else if ($('[name="update_cart"]').length) {
              $('[name="update_cart"]').removeAttr("disabled").trigger("click");
              initializeUploadContainer($container);
            } else {
              $("body").trigger("update_checkout");
              initializeUploadContainer($container);
            }
            // Show success message
            showMessage(
              "Image removed successfully!",
              "success",
              $container,
              ruleId,
              key,
              false
            );
          } else {
            showMessage(
              response.data.message || "Failed to remove file.",
              "error",
              $container,
              ruleId,
              key
            );
          }
        },
        error() {
          showMessage("Error removing file.", "error", $container, ruleId, key);
        },
      });
    } else {
      ruleDataMap[ruleId][key].fileChosenArray.splice(index, 1);
      ruleDataMap[ruleId][key].validatedFiles =
        ruleDataMap[ruleId].fileChosenArray;
      $counter.val(Math.max(0, parseInt($counter.val()) - 1));
      displaySelectedImages($container, ruleId, key);
      if (!ruleDataMap[ruleId][key].fileChosenArray.length) {
        $imageList.hide();
        $fileInput.val("");
      }
      showMessage(
        "Image removed successfully!",
        "success",
        $container,
        ruleId,
        key,
        false
      );
    }
  }

  // Upload files and customer notes via AJAX
  function uploadFiles(files, ruleId, $container, customerNotes = "", key) {
    const formData = new FormData();
    files.forEach((file) => formData.append("files[]", file));
    formData.append("action", "extendons_handle_file_upload_cart");
    formData.append("rule_id", ruleId);
    formData.append("key", key);
    formData.append("requestID", ewcpm_php_vars.ext_fu_requestID);
    if (customerNotes) {
      formData.append("customer_notes", customerNotes);
    }
    $(document.body).trigger("wc_fragment_refresh");
    showMessage(
      "Uploading files, please wait...",
      "success",
      $container,
      ruleId,
      key,
      true
    );
    $.ajax({
      url: ewcpm_php_vars.admin_url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      xhr() {
        const xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", (e) => {
          if (e.lengthComputable) {
            console.log(
              `Upload progress: ${Math.ceil((e.loaded / e.total) * 100)}%`
            );
          }
        });
        return xhr;
      },
      success(response) {
        if (response.success) {
          displaySelectedImages($container, ruleId, key);
          showMessage(
            "Files uploaded successfully.",
            "success",
            $container,
            ruleId,
            key,
            false
          );
          console.log(
            `Session files for rule ${ruleId}:`,
            ruleDataMap[ruleId][key].fileChosenArray
          );
          if (response.data.notes) {
            ruleDataMap[ruleId][key].customerNotes = response.data.notes;
          }
          if ($(".extendons-block-theme-active").length > 0) {
            window.location.reload();
          }
          if (
            $(".wp-block-woocommerce-cart-line-items-block").length ||
            $(".wp-block-woocommerce-checkout-terms-block").length
            // $('body').find('[class*="wp-block"]').length > 0
          ) {
            $(window).on("load", () => initializeUploadContainer($container));
            window.location.reload();
          } else if ($('[name="update_cart"]').length) {
            $('[name="update_cart"]').removeAttr("disabled").trigger("click");
            initializeUploadContainer($container);
          } else {
            $("body").trigger("update_checkout");
            initializeUploadContainer($container);
          }
        } else {
          showMessage(
            response.data.message || "Upload failed.",
            "error",
            $container,
            ruleId,
            key
          );
        }
      },
      error() {
        showMessage(
          "An error occurred during upload.",
          "error",
          $container,
          ruleId,
          key
        );
      },
    });
  }

  // Event delegation for dynamic elements
  $("body").on("change", ".extendons_upload_files_hidden_input", function (e) {
    const $fileInput = $(this);
    const $container = $fileInput.closest(".extendons_upload_files_container");
    const ruleId = $fileInput.attr("data-rule-id");
    const files = Array.from(this.files);
    const key = $container
      .find(".extendons_upload_files_main_wrapper")
      .attr("id")
      .split("_")
      .pop();

    if (files.length) {
      const validFiles = validateFiles(files, $container, ruleId, key);
      if (validFiles.length) {
        const customerNotes = ruleDataMap[ruleId][key].customerNotes || "";
        displaySelectedImages($container, ruleId, key);
        // Update the hidden input with the valid files
        uploadFiles(validFiles, ruleId, $container, customerNotes, key);
      } else {
        $fileInput.val("");
      }
    }
  });

  if ($(".extendons-block-theme-active").length > 0) {
    // Block theme is active
    console.log("Block theme detected");
  }

  // Event listener for customer notes textarea blur
  $("body").on("blur", ".extendons_upload_files_note_textarea", function (e) {
    const $textarea = $(this);
    const $container = $textarea.closest(".extendons_upload_files_container");
    const ruleId = $container
      .find(".extendons_upload_files_hidden_input")
      .attr("data-rule-id");
    const customerNotes = $textarea.val().trim();
    const key = $container
      .find(".extendons_upload_files_main_wrapper")
      .attr("id")
      .split("_")
      .pop();

    // Update stored notes
    ruleDataMap[ruleId][key].customerNotes = customerNotes;

    // Only send notes if there are files or notes have changed
    if (ruleDataMap[ruleId][key].fileChosenArray.length || customerNotes) {
      uploadFiles([], ruleId, $container, customerNotes, key);
    }
  });

  $("body").on(
    "dragover",
    ".extendons_upload_files_main_wrapper",
    function (e) {
      e.preventDefault();
      $(this).css({ "border-color": "#9ca3af", "background-color": "#f9fafb" });
    }
  );

  $("body").on(
    "dragleave",
    ".extendons_upload_files_main_wrapper",
    function (e) {
      e.preventDefault();
      $(this).css({ "border-color": "#d1d5db", "background-color": "white" });
    }
  );

  $("body").on("drop", ".extendons_upload_files_main_wrapper", function (e) {
    e.preventDefault();
    const $dropZone = $(this);
    const $container = $dropZone.closest(".extendons_upload_files_container");
    const ruleId = $container
      .find(".extendons_upload_files_hidden_input")
      .attr("data-rule-id");
    $dropZone.css({ "border-color": "#d1d5db", "background-color": "white" });
    const files = Array.from(e.originalEvent.dataTransfer.files);
    const key = $container
      .find(".extendons_upload_files_main_wrapper")
      .attr("id")
      .split("_")
      .pop();

    if (files.length) {
      const validFiles = validateFiles(files, $container, ruleId, key);
      if (validFiles.length) {
        const dataTransfer = new DataTransfer();
        ruleDataMap[ruleId][key].fileChosenArray.forEach((file) => {
          if (file instanceof File) dataTransfer.items.add(file);
        });
        $container.find(".extendons_upload_files_hidden_input")[0].files =
          dataTransfer.files;
        const customerNotes = ruleDataMap[ruleId][key].customerNotes || "";
        displaySelectedImages($container, ruleId, key);
        uploadFiles(validFiles, ruleId, $container, customerNotes, key);
        showMessage(
          "Files dropped successfully.",
          "success",
          $container,
          ruleId,
          key,
          false
        );
      }
    }
  });

  $("body").on("click", ".extendons_upload_files_preview_button", function (e) {
    e.preventDefault();
    window.open($(this).data("img-src"), "_blank");
  });

  $("body").on("click", ".extendons_upload_files_delete_button", function (e) {
    e.preventDefault();
    const $button = $(this);
    const index = $button.data("index");
    const fileId = $button.data("file-id");
    const $imageItem = $button.closest(".extendons_upload_files_image_item");
    const $container = $imageItem.closest(".extendons_upload_files_container");
    const key = $(this)
      .closest(".extendons_upload_files_main_wrapper")
      .attr("id")
      .split("_")
      .pop();
    // Get rule ID from the hidden input
    const ruleId = $container
      .find(".extendons_upload_files_hidden_input")
      .attr("data-rule-id");
    if (confirm("Are you sure you want to remove this image?")) {
      removeImage(index, $imageItem, $container, ruleId, key, fileId);
    }
  });

  // Initialize existing containers
  function initializeContainers() {
    $(".extendons_upload_files_container").each(function () {
      const $container = $(this);
      initializeUploadContainer($container);
    });
  }

  // Initial call with fallback delay
  initializeContainers();
  setTimeout(initializeContainers, 1000); // Retry after 1 second

  // Re-initialize after WooCommerce checkout updates
  $(document).on("updated_checkout", function () {
    initializeContainers();
  });

  // Re-initialize after WooCommerce cart updates
  $(document).on("wc_fragments_refreshed updated_cart", () => {
    $(".extendons_upload_files_container").each(function () {
      initializeUploadContainer($(this));
    });
  });

  // <!-- Below is the code for the download and change file functionality -->

  // Download file functionality
  $(document).on("click", ".extendons-download-file", function (e) {
    e.preventDefault();

    var fileUrl = $(this).data("file-url");
    var fileName = $(this).data("file-name");

    // Create a temporary link element and trigger download
    var link = document.createElement("a");
    link.href = fileUrl;
    link.download = fileName;
    link.style.display = "none";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });

  // Change file functionality - Open modal
  $(document).on("click", ".extendons-change-file", function (e) {
    e.preventDefault();

    var orderId = $(this).data("order-id");
    var ruleId = $(this).data("rule-id");
    var key = $(this).data("key");
    var fileIndex = $(this).data("file-index");
    var fileExtensions = $(this).data("allowed-extensions");
    var fileId = $(this).data("file-id");

    // Populate hidden fields
    $("#extendons-order-id").val(orderId);
    $("#extendons-rule-id").val(ruleId);
    $("#extendons-key").val(key);
    $("#extendons-file-index").val(fileIndex);
    $("#extendons-file-extensions").val(fileExtensions);
    $("#extendons-file-id").val(fileId);

    // Clear file input
    $("#extendons-new-file").val("");

    // Show modal
    $("#extendons-file-change-modal").show();
  });

  // Close modal
  $(document).on("click", "#extendons-cancel-change", function (e) {
    e.preventDefault();
    $("#extendons-file-change-modal").hide();
  });

  // Close modal on outside click
  $(document).on("click", "#extendons-file-change-modal", function (e) {
    if (e.target === this) {
      $(this).hide();
    }
  });

  // Handle file change form submission
  $(document).on("submit", "#extendons-change-file-form", function (e) {
    e.preventDefault();

    var fileInput = $("#extendons-new-file")[0];
    if (!fileInput.files || !fileInput.files[0]) {
      alert("Please select a file to upload.");
      return;
    }
	
    var formData = new FormData();
    formData.append("action", "extendons_change_uploaded_file");
    formData.append("nonce", ewcpm_php_vars.nonce); // You'll need to localize this
    formData.append("order_id", $("#extendons-order-id").val());
    formData.append("rule_id", $("#extendons-rule-id").val());
    formData.append("key", $("#extendons-key").val());
    formData.append("file_index", $("#extendons-file-index").val());
    formData.append("new_file", fileInput.files[0]);
    formData.append("file_extensions", $("#extendons-file-extensions").val());
    formData.append("file_id", $("#extendons-file-id").val());

    // Show loading state
    var submitBtn = $(this).find('button[type="submit"]');
    var originalText = submitBtn.text();
    submitBtn.text("Uploading...").prop("disabled", true);

    $.ajax({
      url: ewcpm_php_vars.admin_url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          // Reload the page to show updated file
          location.reload();
        } else {
          alert("Error: " + (response.data || "Failed to change file"));
        }
      },
      error: function (xhr, status, error) {
        alert("Error: " + error);
      },
      complete: function () {
        // Reset button state
        submitBtn.text(originalText).prop("disabled", false);
        $("#extendons-file-change-modal").hide();
      },
    });
  });
});
