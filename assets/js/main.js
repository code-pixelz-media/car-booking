jQuery(document).ready(function () {
  var dates = jQuery("#datePick").data("blocked-date");
  var date;
  if (typeof dates !== "undefined") {
    date = dates.split(",").map(function (date) {
      return date.replace(/'/g, "").trim();
    });
  }

  jQuery('input[name="daterange"]').daterangepicker(
    {
      opens: "left",
    },
    function (start, end, label) {
      console.log(
        "A new date selection was made: " +
          start.format("YYYY-MM-DD") +
          " to " +
          end.format("YYYY-MM-DD")
      );
      jQuery.ajax({
        type: "post",
        url: myajax.ajaxurl,
        data: {
          action: "book_date_range_for_car_booking",
          start_date: moment(start).format("Y-MM-DD HH:mm:ss"),
          end_date: moment(end).format("Y-MM-DD HH:mm:ss"),
        },
        success: function (response) {
          console.log("response", response);
          // location.reload();
        },
      });
    }
  );

  jQuery("#datePick").multiDatesPicker({
    dateFormat: "yy-mm-dd",
  });
  jQuery("#datePick").multiDatesPicker({
    disabled: true,
    addDates: date,
  });
});
