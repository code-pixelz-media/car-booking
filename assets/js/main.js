jQuery(document).ready(function () {
  var dates = jQuery("#datePick").data("blocked-date");
  var date;
  if (typeof dates !== "undefined") {
    date = dates.split(",").map(function (date) {
      return date.replace(/'/g, "").trim();
    });
  }

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

  jQuery("#datePick").multiDatesPicker({
    dateFormat: "yy-mm-dd",
    minDate: 0,
    
  });
  jQuery("#datePick").multiDatesPicker({
    disabled: true,
    addDates: date,
  });

  // sunder js

  jQuery('input[name="daterange"]').daterangepicker({
    minDate:new Date(),
    autoApply: true, // for hiding cancel 
  });

  jQuery(".booking_button").click(function () {
    var dateRange = jQuery(".booking_date_range").val();
    var source_val = jQuery(".booking_source").val();
    var destination_val = jQuery(".booking_destination").val();
    var noOfTravellers = jQuery(".booking_no_of_travellers").val();

    var date_range_split = dateRange.split('-');
    var startDate = date_range_split[0];
    var endDate = date_range_split[1];

    // if(dateRange == '' || source_val == '' || destination_val == '' || noOfTravellers == '' || booking_number =''){
    //   alert('Fields cannot be empty.');
    //   return;
    // }

    jQuery.ajax({
          type: "post",
          url: myajax.ajaxurl,
          data: {
            action: "book_date_range_for_car_booking",
            source : source_val,
            destination : destination_val,
            no_of_travellers : noOfTravellers,
            start_date: moment(startDate).format("Y-MM-DD HH:mm:ss"),
            end_date: moment(endDate).format("Y-MM-DD HH:mm:ss"),
          },
          success: function (response) {
            console.log("response", response);
            // alert(response);
            location.reload();
          },
        });
  });
});
