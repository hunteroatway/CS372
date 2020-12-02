// Regex values
var isbn_format = /^(97(8|9))?\d{9}(\d|X)$/;
var price_format = /[0-9]+[.]?[0-9]{1,2}/;

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

	// Reset error messages and field values
	resetError();
	resetField();
}

// Reset input field values
function resetField() {
	title.value = '';
	subtitle.value = '';
	author.value = '';
	description.value = '';
	publisher.value = '';
}

// Reset the error messages
function resetError() {
	document.getElementById("isbn_err").innerHTML = "";
	document.getElementById("title_err").innerHTML = "";
	document.getElementById("author_err").innerHTML = "";
	document.getElementById("price_err").innerHTML = "";
}

// Toggle readonly attribute for the supplied parameter
function toggleReadOnly(x) {
	if (x.readOnly) x.readOnly = false;
	else if (!x.readOnly) x.readOnly = true;
}

// Google API function to search by ISBN when the auto-fill button is pressed in automatic mode
function searchBookByISBN() {
	// API query URL
	var isbn = document.getElementById("isbn").value;
	var req_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
	var req = new XMLHttpRequest();
	
	// Reset error messages and field values
	resetError();
	resetField();	

	if (isbn != "") {
		// HTTP Request to Google Books API and parsing the resulting JSON data
		req.onreadystatechange = function() {    
			if (this.readyState == 4 && this.status == 200) {
				var obj = JSON.parse(this.responseText);
				console.log("Book Content from Google Books API");
				console.log(obj);          

				// Parse JSON object
				var title_val, subtitle_val, authors_val, description_val, publisher_val, cover_val = '';
				var num_authors = '';
				var emptyBook = 0;
				try {
					title_val = obj.items[0].volumeInfo.title;
					title.value = title_val;
				} catch (e) {emptyBook += 1;}
				try {
					subtitle_val = obj.items[0].volumeInfo.subtitle;
					subtitle.value = subtitle_val;
				} catch (e) {emptyBook += 1;}
				try {
					description_val = obj.items[0].volumeInfo.description;
					description.value = description_val;
				} catch (e) {emptyBook += 1;}
				try {
					publisher_val = obj.items[0].volumeInfo.publisher;
					publisher.value = publisher_val;
				} catch (e) {emptyBook += 1;}

				// If all 4 fields are empty, then the API could not find a book
				if (emptyBook == 4)
					document.getElementById("isbn_err").innerHTML = "Google Books could not find a book with this ISBN. Please enter a different ISBN."
				
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

		// Open and send HTTP request to the API
		req.open("GET", req_url, false);
		req.send();
		return false;
	} else {
		document.getElementById("isbn_err").innerHTML = "An ISBN value must be entered to auto-fill the other information!";
	}
}

// Submit Posting function, called when the submit button is pressed on the posting.php page
function submitPosting(event) {
	// Reset error messages
	resetError();

	// If the data is not validated, prevent the page from submitting the post
	if (!validate()) 
		event.preventDefault();

	// API query URL
	var isbn = document.getElementById("isbn").value;
	var req_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
	var req = new XMLHttpRequest();

	// HTTP Request to Google Books API and parsing the resulting JSON data
	req.onreadystatechange = function() {    
		if (this.readyState == 4 && this.status == 200) {
			var obj = JSON.parse(this.responseText);

			// Values to store hidden isbn values for accurate DB data
			var isbn_10;
			var isbn_13;
			var cover_val;

			// Try to get the thumbnail image from the API and store in the hidden value
			try {
				cover_val = obj.items[0].volumeInfo.imageLinks.smallThumbnail;
			} catch (e) {}

			// Try to get the ISBN-10 and ISBN-13 values from the API and store in their hidden values
			try{
				if(obj.items[0].volumeInfo.industryIdentifiers[0].type == "ISBN_10")
					isbn_10 = obj.items[0].volumeInfo.industryIdentifiers[0].identifier;
				else if(obj.items[0].volumeInfo.industryIdentifiers[0].type == "ISBN_13")
					isbn_13 = obj.items[0].volumeInfo.industryIdentifiers[0].identifier;
			} catch (e){}

			try{
				if(obj.items[0].volumeInfo.industryIdentifiers[1].type == "ISBN_10") 
					isbn_10 = obj.items[0].volumeInfo.industryIdentifiers[1].identifier;
				else if(obj.items[0].volumeInfo.industryIdentifiers[1].type == "ISBN_13")
					isbn_13 = obj.items[0].volumeInfo.industryIdentifiers[1].identifier;
			} catch (e) {}

			// If the values are empty for any of the above requests, use the following values
			if (cover_val == null || cover_val == "") 
				cover_val = "../images/imageNotFound.png";
			if (isbn_13 == null || isbn_13 == "") 
				isbn_13 = document.getElementById("isbn").value; // use the value given
			if (isbn_10 == null || isbn_10 == "") 
				isbn_10 = -404;

			// Update hidden values
			document.getElementById("isbn-10").value = isbn_10;
			document.getElementById("isbn-13").value = isbn_13;
			document.getElementById("cover-link").value = cover_val;

			return false;
		}
	}		
	
	// Open and send HTTP request to the API
    req.open("GET", req_url, false);
    req.send();
	return false;
}

// Input field validation function for the posting page
function validate() {
	var isValid = true;

	// Input field values
	var isbn = document.getElementById("isbn").value;
	var title = document.getElementById("title").value;
	var author = document.getElementById("author").value;
	var price = document.getElementById("price").value;

	// Verifies that the input fields conform to data value formats we want for the database
	// ISBN
	if (isbn == "" || isbn == null) {
		document.getElementById("isbn_err").innerHTML = "ISBN search field cannot be empty!";
		isValid = false;
	} else if (!isbn_format.test(isbn)) {
		document.getElementById("isbn_err").innerHTML = "Search must be in ISBN-10 or ISBN-13 format!";
		isValid = false;
	} else 
		document.getElementById("isbn_err").innerHTML = "";
	// Title
	if (title == "" || title == null) {
		document.getElementById("title_err").innerHTML = "Title field cannot be empty!";
		isValid = false;
	} else 
		document.getElementById("title_err").innerHTML = "";
	// Authors
	if (author == "" || author == null) {
		document.getElementById("author_err").innerHTML = "Author(s) field cannot be empty!";
		isValid = false;
	} else 
		document.getElementById("author_err").innerHTML = "";
	// Price
	if (price == "" || price == null) {
		document.getElementById("price_err").innerHTML = "Price field cannot be empty!";
		isValid = false;
	} else if (!price_format.test(price)) {
		document.getElementById("price_err").innerHTML = "Price values must be in the following format: 1.00";
		isValid = false;
	} else
		document.getElementById("price_err").innerHTML = "";

	return isValid;
}