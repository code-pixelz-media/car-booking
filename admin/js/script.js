jQuery(document).ready(function ($) {

    // driver  vechile image uploader
  jQuery("body").on("click", ".upload_car_image", function (e) {
    e.preventDefault();

    var button = jQuery(this),
      cp_uploader = wp
        .media({
          title: "Select or Upload a Feature Image",

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
});
