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
        if($(window).scrollTop() > 0 && $(window).scrollTop() <= 203){
            // compute how far from top of screen it is
            var top = 252 - $(window).scrollTop();
            // edit the top margin to be this new value to keep sidebar touching nav bar
            $('#sidebar').css( "margin-top", top);
            // edit the height of the sidebar so it is the full height under the nav bar
            height = $(window).height() - 60 - top;
            $('#sidebar').css( "height", height);

            // allow nav bar to stick to top
            $('#pac-card').removeClass("fixedNav");
            $('.main').css( "margin-top", 0);
        // if past the nav bar, set the values to be the full height of the screen
        } else if ($(window).scrollTop() > 203){
            
            $('#sidebar').css( "margin-top", 49);
            $('#sidebar').css( "height", $(window).height()-109);
            // edit the nav bar/ top of messages by same values
            $('.main').css( "margin-top", 79);
            $('#pac-card').addClass("fixedNav");

        // if scroll to top set values to default
        }  else if ($(window).scrollTop() == 0){
            
            $('.main').css( "margin-top", 0);
            $('#sidebar').css( "margin-top", 252);
            $('#sidebar').css( "height", $(window).height()-312);
            $('#pac-card').removeClass("fixedNav");

        }

    });
});

//Function to load the message thread the user selected
function getMessages(cid, uid, title, lastUpdate) {
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200 && this.responseText != "") {
        document.getElementById("msgs").innerHTML=this.responseText;
      } 
      
    }

    //Change value of cid within message-area
    document.getElementById("cidValue").value = cid;

    title = encodeURIComponent(title);
    xmlhttp.open("GET","messagesLoad.php?cid="+cid+"&uid="+uid+"&title="+title+"&lastUpdate="+lastUpdate,true);
    xmlhttp.send();
    
}

// set it to refresh the page every 10s
setInterval(updatePage, 1000);

function updatePage(){

    // get the values from the current chat
    var cidValue = document.getElementById("cidValue").value;
    var title = document.getElementById("chatTitle").innerHTML;
    var uidValue = document.getElementById("uidValue").value;
    var lastUpdate  = document.getElementById("lastUpdate").innerHTML;
    lastUpdate = encodeURIComponent(lastUpdate);

    getMessages(cidValue, uidValue, title, lastUpdate);

}