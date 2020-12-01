// on load, load the chat
$(document).ready(function(){

    // get the values
    var cidValue = document.getElementById("cidValue").value;
    var title = document.getElementById("titleValue").innerHTML;
    var uidValue = document.getElementById("uidValue").value;
    var buid = document.getElementById("buid").value;
    var suid = document.getElementById("suid").value;

    if(cidValue != ""){
        if(buid == uidValue || suid == uidValue || cidValue != -404){
            // make call to update chat
            getMessages(cidValue, uidValue, title, 0);
            //Scroll to bottom of chat
            $('html, body').animate({scrollTop:$(document).height()*5}, 'slow');
        } else{    
            // create error message
            var error = document.createElement("h1");      
            var errorNode = document.createTextNode("404 Chat Not Found");            
            error.appendChild(errorNode);            
            error.classList.add("errorList");
    
            // append error messages
            document.getElementById("msgs").appendChild(error);
            //window.location = 'messages.php';
        }
    }
});

$(function(){

    // ajax code for submitting without forcing user to leave the page
    $("#test").on("submit", function (e){

        // get the input and serilaize it and remove any single quotes
        var string = $(this).serialize();

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
                    console.log(string);
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
        if($(window).scrollTop() > 0 && $(window).scrollTop() <= 190){
            // compute how far from top of screen it is
            var top = 252 - $(window).scrollTop();
            // edit the top margin to be this new value to keep sidebar touching nav bar
            $('#sidebar').css( "margin-top", top);
            // edit the height of the sidebar so it is the full height under the nav bar
            height = $(window).height() - top;
            $('#sidebar').css( "height", height);

            // allow nav bar to stick to top
            $('#pac-card').removeClass("fixedNav");
            $('.main').css( "margin-top", 0);
        // if past the nav bar, set the values to be the full height of the screen
        } else if ($(window).scrollTop() > 190){
            
            $('#sidebar').css( "margin-top", 49);
            $('#sidebar').css( "height", $(window).height()-49);
            // edit the nav bar/ top of messages by same values
            $('.main').css( "margin-top", 79);
            $('#pac-card').addClass("fixedNav");

        // if scroll to top set values to default
        }  else if ($(window).scrollTop() == 0){
            
            $('.main').css( "margin-top", 0);
            $('#sidebar').css( "margin-top", 252);
            $('#sidebar').css( "height", $(window).height()-252);
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
        $('html, body').animate({scrollTop:$(document).height()*5}, 'slow');
      } 
      
    }

    //Change value of cid within message-area
    document.getElementById("cidValue").value = cid;

    title = encodeURIComponent(title);
    xmlhttp.open("GET","messagesLoad.php?cid="+cid+"&uid="+uid+"&title="+title+"&lastUpdate="+lastUpdate,true);
    xmlhttp.send();
    
}

// set it to refresh the page every 3s
setInterval(updatePage, 3000);

function updatePage(){

    // get the values from the current chat
    var cidValue = document.getElementById("cidValue").value;

    if (cidValue != -404) {
        var title = document.getElementById("chatTitle").innerHTML;
        var uidValue = document.getElementById("uidValue").value;
        var lastUpdate  = document.getElementById("lastUpdate").innerHTML;
        lastUpdate = encodeURIComponent(lastUpdate);

        getMessages(cidValue, uidValue, title, lastUpdate);
    }

}