// Changes input fields based on toggle switch input for automatic or manual data entry
function selectPostingType(event) {
	var title = document.getElementById("title");
	var author = document.getElementById("author");
	var description = document.getElementById("description");
	var publisher = document.getElementById("publisher");

	// Toggle readonly attribute for each field
	toggleReadOnly(title);
	toggleReadOnly(author);	
	toggleReadOnly(description);
	toggleReadOnly(publisher);

	// Reset placeholder values
	title.value = '';
	author.value = '';
	description.value = '';
	publisher.value = '';
}

// Toggle readonly attribute for the supplied parameter
function toggleReadOnly(x) {
	if (x.readOnly) x.readOnly = false;
	else if (!x.readOnly) x.readOnly = true;
}

// Google API function to search by ISBN
function searchBookByISBN() {
  var search = document.getElementById("isbn").value;

  // isbn-10 or isbn-13 regex
  var isbn_format = /^(97(8|9))?\d{9}(\d|X)$/;
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
		return false;
}  
