$(function(){
    // ajax code for submitting without forcing user to leave the page
    $("form").on("submit", function (e){

        // get the input and serilaize it and remove any single quotes
        var string = $(this).serialize().replace("'", "&#39;");

        // get the length of the string. if it is 0, return and do not send the request
        var strLength = $("#message-box").val().length;
        if(strLength == 0){
            return false;
        } else {
                
            // sends the data via post request to messageSend.php to be processed
            $.ajax({
                type: "POST",
                url: "messageSend.php",
                data: string,
                // on success, clear the chat box
                success: function(){
                    $("#message-box").val('');
                }
            });

            // prevents the page from refreshing
            e.preventDefault();
        }

    });
    // function to automatically change the size of the scroll bar
    $(document).scroll(function(e) {
        // if scrolled within the top nav bar
        if($(window).scrollTop() > 0 && $(window).scrollTop() <= 252){
            // compute how far from top of screen it is
            var top = 252 - $(window).scrollTop();
            // edit the top margin to be this new value to keep sidebar touching nav bar
            $('#sidebar').css( "margin-top", top);
            // edit the height of the sidebar so it is the full height under the nav bar
            height = $(window).height() - 60 - top;
            $('#sidebar').css( "height", height);
            console.log(top);
        // if past the nav bar, set the values to be the full height of the screen
        } else if ($(window).scrollTop() > 252){
            
            $('#sidebar').css( "margin-top", 0);
            $('#sidebar').css( "height", $(window).height()-60);

        // if scroll to top set values to default
        }  else if ($(window).scrollTop() == 0){
            
            $('#sidebar').css( "margin-top", 252);
            $('#sidebar').css( "height", $(window).height()-312);

        }

    });
});

