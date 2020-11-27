<?php

    session_start();
    if(!isset($_SESSION["username"])){
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('You need to LogIn in order to access Profile page.');
        window.location.href='Login.php';
        </script>");
    }
?>

<!DOCTYPE html>
<html>
    <head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet"
              type = "text/css"
              href = "../css/myStyle.css" />
        <title>Profile page</title> 
                <style>
            .err_msg{ color:red;}
        </style>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.css?v=0.1.7">
        <script src="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.js?v=0.1.7"></script>
    </head>

    <header>
	<img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>

    <body>
    <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
        <?php 
            // if logged in
            if(isset($_SESSION["username"])) {
        ?>


            <a href="posting.php">Post Ad <i class="fa fa-book"></i></a>
            <a href="profile.php">Profile <i class="fa fa-user"></i></a>
            <a href="manage.php">Manage <i class="fa fa-bars"></i></a>
            <a href="logout.php">LogOut <i class="fa fa-sign-out"></i></a></a>

        <?php
            //if not logged in have links to sign up
            } else {

        ?>

            <a href="signUp.php">SignUp <i class="fa fa-user-plus"> </i></a>
            <a href="Login.php">LogIn <i class="fa fa-sign-in"></i></a>

         <?php }?>

            <div class="search-container">
                <form action="search.php" method="get">
                <div class = "container">
                    <div id="map"></div>
                    <div id="search-box"></div>
                </div>
                <input type="hidden" id ="city" value = "" name="city">
                    <input type="hidden" id ="province" value = "" name="province">
                    <input type="hidden" id ="country" value = "" name="country">
				<input id = "bookSearch" type="text" placeholder="Search.." name="search" value="<?=$search?>">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
        </div>    
<br>
        <img class="pic" src="../images/avatar.gif" alt="profile" style = "display:inline" width = "100" height = "100" />
        <p>UserID: hello@34</p>
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