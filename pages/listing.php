<?php

    // start the php session
    session_start();

?>

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
    </head>

    <header>
	    <img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>

    <body>
        <?php 
            // if logged in
            if(isset($_SESSION["username"])) {
        ?>

        <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
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

        <div class = "listing">

            <div class = "bookInfo">
                <div class ="slideShow">

                    <div class="image">
                        <div class = "number">1/3</div>
                        <img src="../images/logo.png" style = "display:inline" width = "600 " height = "600" />
                    </div>
                    <div class="image">
                        <div class = "number">2/3</div>
                        <img src="../images/book_placeholder.jpg" style = "display:inline" width = "600 " height = "600" />
                    </div>
                    <div class="image">
                        <div class = "number">3/3</div>
                        <img src="../images/test.gif" style = "display:inline" width = "600 " height = "600" />
                    </div>
 
                    <!-- Next and previous buttons -->
                    <a class="prev" onclick="change(-1)">&#10094;</a>
                    <a class="next" onclick="change(1)">&#10095;</a>
                </div>

                <div class = "dots">
                    <div class = "dot" onclick="slide(1)"></div>
                    <div class = "dot" onclick="slide(2)"></div>
                    <div class = "dot" onclick="slide(3)"></div>
                </div>

                <div class = "bookInfoText">

                    <h2 id = "bookTitle">
                        Book Title (PHP)
                    </h2>        
                    <h3 id = "bookSubitle">
                        Book Subtitle (Learn in 7 seconds)
                    </h3>      
                    <h4 id = "price">
                        Price: $73.73
                    </h4>

                    <p id = "authors">
                        Author: John Cena
                    </p>

                    <p id = "edition">
                        Edition: 120th
                    </p>

                    <p id = "publisher">
                        Publisher: Someone
                    </p>

                    <p id = "isbn">
                        isbn-10: 0130463469 
                        </br>
                        isbn-13: 9780130463463
                    </p>

                    <div class="boxed">
                        <p id = "description">
                            Demonstrates the construction and deployment of robust Web applications, covering syntax, scripts, functions, sorting, searching, parsing, program design, and debugging.
                        </p>
                    </div>

                    <p id = "condtion">
                        Condtion: amazing
                    </p>
                </div>
            </div>  

            <div class = "posterInfo">
                <h2>
                    <u>Poster Information:</u>
                </h2>
                <div class="avatar">
                    <img src="../images/avatar.gif" style = "display:inline" width = "100 " height = "100" />
                </div>

                <p id = "poster">
                    <b>Listing posted by: Franics Z.</b>
                </p>        
                <p id = "listing">
                    Located in: Atlantas, SE, UnderWater
                </p> 
                <p id = "postingDate">
                    Posted on: December 17, 3059
                </p>

                <?php 
                    // if logged in
                    if(isset($_SESSION["username"])) {
		        ?>

                <a href="index.php" style="text-decoration:none;">
                    <div class = "messageListing sendMessage">
                        <span class = "messageListingText">Click here to message this seller.</span>
                    </div>
                </a>

                <?php
                    //if not logged in
                    } else {

                ?>
                <div class = "messageListing">
                    <span class = "messageListingText">Please <a href="Login.php">log in</a> to message the poster about this item.</span>
                </div>
                    <?php }?>

            </div>
        </div> 

    </body>

    <script type="text/javascript" src="../js/JavaScript.js"></script>
    <script type="text/javascript" src="../js/slideshow.js"></script>
</html>