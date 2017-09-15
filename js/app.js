$(document).ready(function () {
    // $('#expenselist').DataTable();
    $('#expenses').DataTable();
    $('.datepicker').datepicker({
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        format: "mm/dd/yyyy",
        //defaultDate: "11/1/2013",
    });

    $("#message").keyup(function () {
        $("#info").text("Characters left: " + (120 - $(this).val().length)).addClass('m-t-2');
        if ($(this).val().length > 120) {
            $("textarea").prop('disabled', true)
        }
    });
});