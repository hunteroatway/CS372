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
    </head>

    <header>
	<img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>

    <body>
    <body>
        <?php 
            // if logged in
            if(isset($_SESSION["username"])) {
        ?>

        <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
            <a href="posting.php">Post Ad <i class="fa fa-book"></i></a>
            <a href="profile.php">Profile <i class="fa fa-user"></i></a>
            <a href="logout.php">LogOut <i class="fa fa-sign-out"></i></a></a>
			  <div class="search-container">
				<form action="/action_page.php">
                <input id="pac-input" type="text" placeholder="City..">
				<input type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
            <div id="map"></div>
        </div>

        
        <?php
            //if not logged in have links to sign up
            } else {

        ?>
        <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
            <a href="signUp.php">SignUp <i class="fa fa-user-plus"> </i></a>
            <a href="Login.php">LogIn <i class="fa fa-sign-in"></i></a>
			  <div class="search-container">
				<form action="/action_page.php">
                <input id="pac-input" type="text" placeholder="City..">
				<input type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
            <div id="map"></div>
        </div>

        <?php }?>
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
    </body>
</html>