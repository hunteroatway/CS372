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

function toggleVisible(e, v) {
  if (v) e.style.visibility = "visible";
  else if (!v) e.style.visibility = "hidden";
}

function searchBookByISBN() {
  var search = document.getElementById("isbn").value;

  // TODO: Add more input validation
  // * Empty fields
  // * Incorrect isbn-10 / isbn-13 formatting
  if (search == '') {
    alert("Please enter a value in the search field");
  } else {      
    var req_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + search;
    var req = new XMLHttpRequest();

    req.onreadystatechange = function() {    
      if (this.readyState == 4 && this.status == 200) {
        var obj = JSON.parse(this.responseText);
        console.log("Book Content from Google Books API");
        console.log(obj);          

        var authors = obj.items[0].volumeInfo.authors[0];
        var title = obj.items[0].volumeInfo.title;
        var cover = obj.items[0].volumeInfo.imageLinks.smallThumbnail;
        
        document.getElementById("auto-author").innerHTML = authors;
        document.getElementById("auto-title").innerHTML = title;
        document.getElementById("auto-cover").src = cover;
      }    
    };

    req.open("GET", req_url, false);
    req.send();
  }
  
  return false;
}