// Changes input fields based on toggle switch input for automatic or manual data entry
function selectPostingType(event) {
  var e = event.currentTarget;
  var af = document.getElementById("automatic-fields");
  var mf = document.getElementById("manual-fields");

  if (!e.hasAttribute("checked")) {
    e.setAttribute("checked", true);
    toggleVisible(af, true);
    toggleVisible(mf, false);
  } else if (e.hasAttribute("checked")) {
    e.removeAttribute("checked");
    toggleVisible(mf, true);
    toggleVisible(af, false); 
  }
}

// Utility function that toggles a DOM elements visibility
function toggleVisible(e, v) {
  if (v) e.style.visibility = "visible";
  else if (!v) e.style.visibility = "hidden";
}

// Google API function to search by ISBN
function searchBookByISBN() {
  var search = document.getElementById("isbn").value;

  // isbn-10 or isbn-13 regex
  var isbn_format = /^(97(8|9))?\d{9}(\d|X)$/;

  // Input field validation, must be non-empty, and be in either isbn-10 or isbn-13 format
  if (search == null || search == "") {
    document.getElementById("search_msg").innerHTML = "Search field cannot be empty!";
  } else if (!isbn_format.test(search)) {
    document.getElementById("search_msg").innerHTML = "Search field must be in ISBN-10 or ISBN-13 format!";
  } else {   
    document.getElementById("search_msg").innerHTML = "";

    var req_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + search;
    var req = new XMLHttpRequest();

    req.onreadystatechange = function() {    
      if (this.readyState == 4 && this.status == 200) {
        var obj = JSON.parse(this.responseText);
        console.log("Book Content from Google Books API");
        console.log(obj);          

        var title = obj.items[0].volumeInfo.title;
        var subtitle = obj.items[0].volumeInfo.subtitle;
        var authors = obj.items[0].volumeInfo.authors[0];
        var description = obj.items[0].volumeInfo.description;
        var publisher = obj.items[0].volumeInfo.publisher;
        var cover = obj.items[0].volumeInfo.imageLinks.smallThumbnail;
        
        document.getElementById("auto-title").innerHTML += title;
        document.getElementById("auto-subtitle").innerHTML += subtitle;
        document.getElementById("auto-author").innerHTML += authors;
        document.getElementById("auto-description").innerHTML += description;
        document.getElementById("auto-publisher").innerHTML += publisher;
        document.getElementById("auto-cover").src = cover;
      }    
    };

    req.open("GET", req_url, false);
    req.send();
  }
  
  return false;
}