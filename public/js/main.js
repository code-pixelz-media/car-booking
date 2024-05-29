jQuery(document).ready(function ($) {
  var dates = jQuery("#datePick").data("blocked-date");
  var date;
  if (typeof dates !== "undefined") {
    date = dates.split(",").map(function (date) {
      return date.replace(/'/g, "").trim();
    });
  }
  console.log(date);
  // jQuery('input[name="daterange"]').daterangepicker(
  //   {
  //     opens: "left",
  //   },
  //   function (start, end, label) {
  //     console.log(
  //       "A new date selection was made: " +
  //         start.format("YYYY-MM-DD") +
  //         " to " +
  //         end.format("YYYY-MM-DD")
  //     );
  //     jQuery.ajax({
  //       type: "post",
  //       url: myajax.ajaxurl,
  //       data: {
  //         action: "book_date_range_for_car_booking",
  //         start_date: moment(start).format("Y-MM-DD HH:mm:ss"),
  //         end_date: moment(end).format("Y-MM-DD HH:mm:ss"),
  //       },
  //       success: function (response) {
  //         console.log("response", response);
  //         // location.reload();
  //       },
  //     });
  //   }
  // );

  // jQuery("#datePick").multiDatesPicker("show");
  jQuery("#datePick").multiDatesPicker({
    dateFormat: "yy-mm-dd",
    minDate: 0,
  });

  jQuery("#datePick").multiDatesPicker({
    disabled: true,
    addDates: date,
  });

  jQuery("#ui-datepicker").multiDatesPicker({
    dateFormat: "yy-mm-dd",
    minDate: 0,
  });
  jQuery("#ui-datepicker").multiDatesPicker({
    disabled: true,
    addDates: date,
  });

  jQuery("#ui-datepicker").multiDatesPicker("show");

  // sunder js

  jQuery('input[name="daterange"]').daterangepicker({
    minDate: new Date(),
    autoApply: true, // for hiding cancel
  });

  // for booking
  jQuery(".booking_button").click(function () {
    var dateRange = jQuery(".booking_date_range").val();
    var source_val = jQuery(".booking_source").val();
    var destination_val = jQuery(".booking_destination").val();
    var noOfTravellers = jQuery(".booking_no_of_travellers").val();
    var driverId = jQuery(".driver_id").val();

    var date_range_split = dateRange.split("-");
    var startDate = date_range_split[0];
    var endDate = date_range_split[1];

    if (
      dateRange == "" ||
      source_val == "" ||
      destination_val == "" ||
      noOfTravellers == ""
    ) {
      alert("Fields cannot be empty.");
      return;
    }

    jQuery.ajax({
      type: "post",
      url: myajax.ajaxurl,
      data: {
        action: "book_date_range_for_car_booking",
        driver_id: driverId,
        source: source_val,
        destination: destination_val,
        no_of_travellers: noOfTravellers,
        start_date: moment(startDate).format("Y-MM-DD HH:mm:ss"),
        end_date: moment(endDate).format("Y-MM-DD HH:mm:ss"),
      },
      success: function (response) {
        console.log("response", response);
        // alert(response);
        if (response != "") {
          jQuery(".paradise-msg").text(response);
          return;
        }

        location.reload();
      },
    });
  });

  jQuery(".drivers-details, .booking_button").hide();
  // for getting driver details
  jQuery(".booking_next_button").click(function () {
    var dateRange = jQuery(".booking_date_range").val();
    var source_val = jQuery(".booking_source").val();
    var destination_val = jQuery(".booking_destination").val();
    var noOfTravellers = jQuery(".booking_no_of_travellers").val();

    var date_range_split = dateRange.split("-");
    var startDate = date_range_split[0];
    var endDate = date_range_split[1];

    if (
      dateRange == "" ||
      source_val == "" ||
      destination_val == "" ||
      noOfTravellers == ""
    ) {
      alert("Fields cannot be empty.");
      return;
    }

    jQuery.ajax({
      type: "post",
      url: myajax.ajaxurl,
      data: {
        action: "paradise_random_driver",
        // source: source_val,
        // destination: destination_val,
        // no_of_travellers: noOfTravellers,
        start_date: moment(startDate).format("Y-MM-DD HH:mm:ss"),
        end_date: moment(endDate).format("Y-MM-DD HH:mm:ss"),
      },
      success: function (response) {
        // console.log("response", response);
        if (response.success == false) {
          jQuery(".paradise-msg").text(response.data);
          return;
        }
        if (response.success == true) {
          // console.log(response.data);
          jQuery(".driver_vehicle_image").attr(
            "src",
            response.data.vehicle_image
          );
          jQuery(".driver_profile_image").attr(
            "src",
            response.data.driver_image
          );
          jQuery(".driver_name").text(response.data.name);
          jQuery(".driver_contact").text(response.data.phone);
          jQuery(".driver_id").val(response.data.ID);

          jQuery(".drivers-details, .booking_button").show();
          jQuery(".booking_next_button").parent("div").hide();
          jQuery(".booking_details").hide();
          jQuery(".paradise-msg").text("");
        }
      },
    });
  });
});

//upload image of car-booking driver registraion

// jQuery(document).ready(function ($) {
//   function openMediaUploader(
//     e,
//     imgInputSelector,
//     // fileNameSelector,
//     // mediaType = "image"
//   ) {
//     e.preventDefault();
//     // Create a new media uploader instance
//     var mediaUploader = wp.media.frames.file_frame = wp.media({
//       title: 'Choose Image',
//       button: {
//           text: 'Choose Image'
//       },
//       multiple: true
//   });

//     // When a file is selected, grab the URL and set it as the text field's value
//     mediaUploader.on("select", function () {
//       var attachment = mediaUploader.state().get("selection").first().toJSON();
//       console.log(attachment);
//       $(imgInputSelector).val(attachment.id);
//       // jQuery(imgInputSelector)
//       // .parent()
//       // .parent()
//       // .find(fileNameSelector)
//       // .text(attachment.title);
//       jQuery(imgInputSelector).val(attachment.id);
//       console.log(jQuery(imgInputSelector).val());
//     });

//     // Open the uploader dialog
//     mediaUploader.open();
//   }

//   $("#update_driver_image").click(function (e) {
//     openMediaUploader(e, "#update-driver-img");
//   });
// });
