//Function to change the way messages are sorted within the profile page
function sortListing(uid){
    var sort = document.getElementById("sort").value;

    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200 && this.responseText != "")
        document.getElementById("selfListing").innerHTML=this.responseText;      
    }

    xmlhttp.open("GET","profileLoad.php?sort="+sort+"&uid="+uid,true);
    xmlhttp.send();
}