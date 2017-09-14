$(document).ready(function() {
    // $('#expenselist').DataTable();
    
    $("#message").keyup(function(){
        $("#info").text("Characters left: " + (120 - $(this).val().length)).addClass('m-t-2');
        if($(this).val().length > 120){
            $("textarea").prop('disabled',true)
        }
      });
} );