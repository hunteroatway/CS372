<?php 
  // start the php session
  session_start();
?>

<?php

    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }

    $q1 = "SELECT L.lid, L.isbn_10, L.isbn_13, L.price, L.list_date, L.active, A.first_name, A.last_name, B.title, U.city, U.province, U.country
    FROM Listings L INNER JOIN Books B 
    ON L.isbn_13 = B.isbn_13 INNER JOIN Authors A
    ON B.isbn_13 = A.isbn_13 INNER JOIN Users U
    ON L.uid = U.uid
    ORDER BY L.list_date, L.lid, A.last_name";

    $r1 = $db->query($q1);

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
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIwzALxUPNbatRBj3Xi1Uhp0fFzwWNBkE&callback=initMap&libraries=places&v=weekly" defer></script>
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
            <a href="listing.php">Post Ad <i class="fa fa-book"></i></a>
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

        
		<hr/>
        <div class="search-term"><p>Showing 1-40 of 50 results for <i>Search</i></p></div>

        <div class="result">

            <?php
                $currentRow = $r1->fetch_assoc();
                $multipleAuthors = false;
                for($i = 0; $i < $r1->num_rows; $i++) {
                    
                    $nextRow = $r1->fetch_assoc();

                    if ($currentRow["lid"] == $nextRow["lid"]) {
                        if ($multipleAuthors == false) {
                            $author = $currentRow["last_name"] . ", " . $currentRow["first_name"] . " ...";
                            $multipleAuthors = true;
                        }
                    } else {
                        $lid = $currentRow["lid"];
                        $q2 = "SELECT image FROM Images WHERE lid = '$lid'";

                        $r2 = $db->query($q2);

                        $row = $r2->fetch_assoc();

                        $image = $row["image"]; 

                        $title = $currentRow["title"];                        
                        $isbn13 = $currentRow["isbn_13"];
                        $price = $currentRow["price"];
                        $location = $currentRow["city"] . ", " . $currentRow["province"] . ", " . $currentRow["country"];

                        if ($multipleAuthors == false) {
                            $author = $currentRow["last_name"] . ", " . $currentRow["first_name"];
                        }

                        $multipleAuthors = false;
                        $currentRow = $nextRow;

            ?>


            <div class="post">
                <img class="bookImage" src="<?=$image?>" width="200" height="200" alt="Book Image"/>
                <p><?=$title?></p>
                <p><?=$author?></p>
                <p><?=$isbn13?></p>
                <p><?=$price?></p>
                <p><?=$location?></p>
            </div>

            <?php
                    }
                }
                $db->close();
            ?>
            
        </div>

        <script type="text/javascript" src="../js/JavaScript.js"></script>
        <script type="text/javascript" src="../js/location.js"></script>
    </body>
</html>