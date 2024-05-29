jQuery(document).ready(function ($) {
  // driver  vechile image uploader
  jQuery("body").on("click", ".upload_car_image", function (e) {
    e.preventDefault();

    var button = jQuery(this),
      cp_uploader = wp
        .media({
          title: "Select or Upload a Vechile Image",

          library: {
            uploadedTo: wp.media.view.settings.post.id,

            type: "image",
          },

          button: {
            text: "Use this image",
          },

          multiple: false,
        })
        .on("select", function () {
          var attachment = cp_uploader
            .state()
            .get("selection")
            .first()
            .toJSON();

          jQuery("#car_image_id").val(attachment.id);
          jQuery("#driver-car-image-src").attr("src", attachment.url);
        })

        .open();
  });

  jQuery("body").on("click", ".upload_profile_image", function (e) {
    e.preventDefault();

    var button = jQuery(this),
      cp_uploader = wp
        .media({
          title: "Select or Upload a Profile Image",

          library: {
            uploadedTo: wp.media.view.settings.post.id,

            type: "image",
          },

          button: {
            text: "Use this image",
          },

          multiple: false,
        })
        .on("select", function () {
          var attachment = cp_uploader
            .state()
            .get("selection")
            .first()
            .toJSON();

          jQuery("#profile_image_id").val(attachment.id);
          jQuery("#profile-image-src").attr("src", attachment.url);
        })

        .open();
  });

  //other image field on backend
  // driver liscence image uploader
  jQuery("body").on("click", ".upload_liscence_image", function (e) {
    e.preventDefault();

    var button = jQuery(this),
      cp_uploader = wp
        .media({
          title: "Select or Upload a Liscence Image",

          library: {
            uploadedTo: wp.media.view.settings.post.id,

            type: "image",
          },

          button: {
            text: "Use this image",
          },

          multiple: false,
        })
        .on("select", function () {
          var attachment = cp_uploader
            .state()
            .get("selection")
            .first()
            .toJSON();

          jQuery("#liscence").val(attachment.id);
          jQuery("#liscence-image-src").attr("src", attachment.url);
        })

        .open();
  });

  //video  uploader
  jQuery("body").on("click", ".upload_video_image", function (e) {
    e.preventDefault();
    var button = jQuery(this),
      cp_uploader = wp
        .media({
          title: "Select or Upload a video",
          library: {
            uploadedTo: wp.media.view.settings.post.id,
            type: "video",
          },
          button: {
            text: "Use this video",
          },
          multiple: false,
        })
        .on("select", function () {
          var attachment = cp_uploader
            .state()
            .get("selection")
            .first()
            .toJSON();
          jQuery("#ps_video_id").val(attachment.id);
          jQuery("#video-src").attr("src", attachment.url);
        })
        .open();
  });

  // other document 1 image uploader
  jQuery("body").on("click", ".upload_other_doc1_image", function (e) {
    e.preventDefault();
    var button = jQuery(this),
      cp_uploader = wp
        .media({
          title: "Select or Upload a Other Document Image",
          library: {
            uploadedTo: wp.media.view.settings.post.id,
            type: "image",
          },
          button: {
            text: "Use this image",
          },
          multiple: false,
        })
        .on("select", function () {
          var attachment = cp_uploader
            .state()
            .get("selection")
            .first()
            .toJSON();
          jQuery("#other_doc1_id").val(attachment.id);
          jQuery("#other-doc1-src").attr("src", attachment.url);
        })
        .open();
  });
  // other document 2 image uploader
  jQuery("body").on("click", ".upload_other_doc2_image", function (e) {
    e.preventDefault();
    var button = jQuery(this),
      cp_uploader = wp
        .media({
          title: "Select or Upload a Other Document Image",
          library: {
            uploadedTo: wp.media.view.settings.post.id,
            type: "image",
          },
          button: {
            text: "Use this image",
          },
          multiple: false,
        })
        .on("select", function () {
          var attachment = cp_uploader
            .state()
            .get("selection")
            .first()
            .toJSON();
          jQuery("#other_doc2_id").val(attachment.id);
          jQuery("#other-doc2-src").attr("src", attachment.url);
        })
        .open();
  });

  jQuery('input[name="admin_daterange"]').daterangepicker({
    minDate: new Date(),
    autoApply: true, // for hiding cancel and apply button
  });

  // jQuery(".admin_booking_button").click(function () {
  //   var userEmail = jQuery(".admin_booking_email").val();
  //   var userEmailLower = userEmail.toLowerCase();
  //   var dateRange = jQuery(".admin_booking_date_range").val();
  //   var source_val = jQuery(".admin_booking_source").val();
  //   var destination_val = jQuery(".admin_booking_destination").val();
  //   var noOfTravellers = jQuery(".admin_booking_no_of_travellers").val();
  //   // var driverId = jQuery(".driver_id").val();

  //   var date_range_split = dateRange.split("-");
  //   var startDate = date_range_split[0];
  //   var endDate = date_range_split[1];

  //   if (
  //     dateRange == "" ||
  //     source_val == "" ||
  //     destination_val == "" ||
  //     noOfTravellers == ""
  //   ) {
  //     alert("Fields cannot be empty.");
  //     return;
  //   }
  //   jQuery.ajax({
  //     type: "post",
  //     url: myajax.ajaxurl,
  //     data: {
  //       action: "paradise_admin_booking",
  //       user_email: userEmailLower,
  //       source: source_val,
  //       destination: destination_val,
  //       no_of_travellers: noOfTravellers,
  //       start_date: moment(startDate).format("Y-MM-DD HH:mm:ss"),
  //       end_date: moment(endDate).format("Y-MM-DD HH:mm:ss"),
  //     },
  //     success: function (response) {
  //       console.log(response);
  //       if (response.success == false) {
  //         jQuery(".booking-msg").text(response.data);
  //         return;
  //       }

  //       location.reload();
  //     },
  //   });
  // });
});