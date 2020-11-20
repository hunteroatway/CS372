<!DOCTYPE html>
<html>
    <head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet"
              type = "text/css"
              href = "../css/myStyle.css" />
        <title>Pick-a-Book</title> 
                <style>
            .err_msg{ color:red;}
        </style>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIwzALxUPNbatRBj3Xi1Uhp0fFzwWNBkE&callback=initMap&libraries=places&v=weekly" defer></script>
    </head>

    <header>
	<img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>


    <body>
        <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
            <a href="signUp.php">SignUp <i class="fa fa-user"> </i></a>
            <a href=".html">Manage</a>
            <a href=".html">Book <i class="fa fa-book"> </i></a>
			  <div class="search-container">
				<form action="/action_page.php">
                <input id="pac-input" type="text" placeholder="City..">
				<input type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
            <div id="map"></div>
        </div>
		<p> Click here to login <a href="Login.php">LogIn</a></p>
		<hr/>
        <h1>Most Recent Posting</h1>
        <div class="result">

            <div class="post">
                <img class="bookImage" src="../images/book_placeholder.jpg" width="200" height="200" alt="Book Image"/>
                <p>Book Title</p>
                <p>Book Author</p>
                <p>ISBN</p>
                <p>Price</p>
                <p>Location</p>
            </div>

            <div class="post">
                <img class="bookImage" src="../images/book_placeholder.jpg" width="200" height="200" alt="Book Image"/>
                <p>Book Title</p>
                <p>Book Author</p>
                <p>ISBN</p>
                <p>Price</p>
                <p>Location</p>
            </div>

            <div class="post">
                <img class="bookImage" src="../images/book_placeholder.jpg" width="200" height="200" alt="Book Image"/>
                <p>Book Title</p>
                <p>Book Author</p>
                <p>ISBN</p>
                <p>Price</p>
                <p>Location</p>
            </div>

            <div class="post">
                <img class="bookImage" src="../images/book_placeholder.jpg" width="200" height="200" alt="Book Image"/>
                <p>Book Title</p>
                <p>Book Author</p>
                <p>ISBN</p>
                <p>Price</p>
                <p>Location</p>
            </div>
            
        </div>

              <script type="text/javascript" src="../js/JavaScript.js"></script>
              <script type="text/javascript" src="../js/location.js"></script>
    </body>
</html>