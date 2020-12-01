// regex values
var isbn_format = /^(97(8|9))?\d{9}(\d|X)$/;
var price_format = /(\d+\.\d{1,2})/;

var postingPage = document.getElementById("submit-form")
postingPage.addEventListener("submit", submitPosting, false);

// Changes input fields based on toggle switch input for automatic or manual data entry
function selectPostingType(event) {
	var title = document.getElementById("title");
	var subtitle = document.getElementById("subtitle");
	var author = document.getElementById("author");
	var description = document.getElementById("description");
	var publisher = document.getElementById("publisher");

	// Toggle readonly attribute for each field
	toggleReadOnly(title);
	toggleReadOnly(subtitle);
	toggleReadOnly(author);	
	toggleReadOnly(description);
	toggleReadOnly(publisher);

	// Reset placeholder values
	title.value = '';
	subtitle.value = '';
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
	var isbn = document.getElementById("isbn").value;

	// API query URL
	var req_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
  	var req = new XMLHttpRequest();

	// HTTP Request to Google Books API and parsing the resulting JSON data
	req.onreadystatechange = function() {    
      if (this.readyState == 4 && this.status == 200) {
        var obj = JSON.parse(this.responseText);
        console.log("Book Content from Google Books API");
        console.log(obj);          

		// Parse JSON object
		var title_val, subtitle_val, authors_val, description_val, publisher_val, cover_val = '';
		var num_authors = '';
		try {
			title_val = obj.items[0].volumeInfo.title;
			title.value = title_val;
		} catch (e) {}
		try {
			subtitle_val = obj.items[0].volumeInfo.subtitle;
			subtitle.value = subtitle_val;
		} catch (e) {}
		try {
			description_val = obj.items[0].volumeInfo.description;
			description.value = description_val;
		} catch (e) {}
		try {
			publisher_val = obj.items[0].volumeInfo.publisher;
			publisher.value = publisher_val;
		} catch (e) {}

		
		// Handle multiple authors for one book
		 try {
			authors_val = "";
			num_authors = obj.items[0].volumeInfo.authors.length;
			for (var i = 0; i < num_authors; i++) {
				if (i == num_authors-1) {
					authors_val += obj.items[0].volumeInfo.authors[i];
				} else {
					authors_val += obj.items[0].volumeInfo.authors[i] + ", ";
				}
			}
			author.value = authors_val;
		 } catch (e) {}

		// Handle undefined values
		 if (title_val == null) title.value = '';
		 if (subtitle_val == null) subtitle.value = '';
		 if (authors_val == null) author.value = '';
		 if (description_val == null) description.value = '';
		 if (publisher_val == null) publisher.value = '';
	}
};

	// Open and send HTTP request
    req.open("GET", req_url, false);
    req.send();
	return false;
}  

function submitPosting(event) {
	var isbn = document.getElementById("isbn").value;
	var title = document.getElementById("title").value;
	var author = document.getElementById("author").value;
	var description = document.getElementById("description").value;
	var publisher = document.getElementById("publisher").value;
	var price = document.getElementById("price").value;

	valid = 1;

	// ISBN
	if (isbn == "" || isbn == null) {
		document.getElementById("isbn_err").innerHTML = "ISBN search field cannot be empty!";
		valid = 0;
	} else if (!isbn_format.test(isbn)) {
		document.getElementById("isbn_err").innerHTML = "Search must be in ISBN-10 or ISBN-13 format!";
		valid = 0;
	} else {
		document.getElementById("isbn_err").innerHTML = "";
	}

	// Title
	if (title == "" || title == null) {
		document.getElementById("title_err").innerHTML = "Title field cannot be empty!";
		valid = 0;
	} else {
		document.getElementById("title_err").innerHTML = "";
	}

	// Authors
	if (author == "" || author == null) {
		document.getElementById("author_err").innerHTML = "Author(s) field cannot be empty!";
		valid = 0;
	} else {
		document.getElementById("author_err").innerHTML = "";
	}
	// price
	if (price == "" || price == null) {
		document.getElementById("price_err").innerHTML = "Price field cannot be empty!";
		valid = 0;
	} else if (!price_format.test(price)) {
		document.getElementById("price_err").innerHTML = "Price values must be in the following format: 1.00";
		valid = 0;
	} else {
		document.getElementById("price_err").innerHTML = "";
	}

	if (valid == 0)
		event.preventDefault();

	// secretly get the default photo from google as well as the missing ISBN values
	// API query URL
	var req_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
	var req = new XMLHttpRequest();

	// HTTP Request to Google Books API and parsing the resulting JSON data
	req.onreadystatechange = function() {    
		if (this.readyState == 4 && this.status == 200) {
			var obj = JSON.parse(this.responseText);

			// get the correct isbn values
			var isbn_10;
			var isbn_13;
			var cover_val;
			// deal with situations where no image
			try {
				cover_val = obj.items[0].volumeInfo.imageLinks.smallThumbnail;
			} catch (e) {}

			// deal with situations where there is no isbn values (Google has no information)
			try{
				if(obj.items[0].volumeInfo.industryIdentifiers[0].type === "ISBN_10")
					isbn_10 = obj.items[0].volumeInfo.industryIdentifiers[0].identifier;
				else if(obj.items[0].volumeInfo.industryIdentifiers[0].type === "ISBN_13")
					isbn_13 = obj.items[0].volumeInfo.industryIdentifiers[0].identifier
			} catch (e){}

			try{
				if(obj.items[0].volumeInfo.industryIdentifiers[1].type === "ISBN_10")
					isbn_10 = obj.items[0].volumeInfo.industryIdentifiers[1].identifier;
				else if(obj.items[0].volumeInfo.industryIdentifiers[1].type === "ISBN_13")
					isbn_13 = obj.items[0].volumeInfo.industryIdentifiers[1].identifier
			} catch (e) {}

			// fix null values
			if (cover_val == null) cover_val = "../images/imageNotFound.png";
			if (isbn_13 == null) isbn_13 = document.getElementById("isbn").value; // use the value given
			if (isbn_10 == null) isbn_10 = -404;
			// Update hidden values
			document.getElementById("isbn-10").value = isbn_10;
			document.getElementById("isbn-13").value = isbn_13;
			document.getElementById("cover-link").value = cover_val;

			console.log(cover_val);
			return false;
		}
	}		
	
	// Open and send HTTP request
    req.open("GET", req_url, false);
    req.send();
	return false;
}
