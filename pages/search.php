<?php

    session_start();
    
    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }

    //Get search info from GET
    $search = $_GET['search'];
    //Clean the string the user searched and prep a variable for isbn to ensure it is in the same format ad DB
    $search = str_replace("'","&#039", $search);
    $isbn = str_replace("-","", $search);
    $city = $_GET['city'];
    $province = $_GET['province'];
    $country = $_GET['country'];

    //Query for searching the DB depending on what the User search.
    $q1 = "SELECT L.lid, L.isbn_10, L.isbn_13, L.price, L.list_date, A.first_name, A.last_name, B.title, U.city, U.province, U.country
    FROM Listings L INNER JOIN Books B 
    ON L.isbn_13 = B.isbn_13 INNER JOIN Authors A
    ON B.isbn_13 = A.isbn_13 INNER JOIN Users U
    ON L.uid = U.uid
    WHERE L.active = true
    AND U.city = '$city'
    AND U.province = '$province'
    AND U.country = '$country'
    AND ((B.title LIKE CONCAT('%', '$search', '%')) OR (A.first_name LIKE CONCAT('%', '$search', '%')) OR (A.last_name LIKE CONCAT('%', '$search', '%')) OR (B.publisher LIKE CONCAT('%', '$search', '%')) OR L.isbn_10 = '$isbn' OR L.isbn_13 = '$isbn')
    ORDER BY L.list_date, L.lid, A.last_name";

    $r1 = $db->query($q1);

    //Query for coutning how many results are shown. Similar to the above, but only counts
    $q2 = "SELECT COUNT(DISTINCT L.lid) as total
    FROM Listings L INNER JOIN Users U
    ON L.uid = U.uid INNER JOIN Books B
    ON L.isbn_13 = B.isbn_13 INNER JOIN Authors A
    ON B.isbn_13 = A.isbn_13
    WHERE L.active = true
    AND U.city = '$city'
    AND U.province = '$province'
    AND U.country = '$country'
    AND ((B.title LIKE CONCAT('%', '$search', '%')) OR (A.first_name LIKE CONCAT('%', '$search', '%')) OR (A.last_name LIKE CONCAT('%', '$search', '%')) OR (B.publisher LIKE CONCAT('%', '$search', '%')) OR L.isbn_10 = '$isbn' OR L.isbn_13 = '$isbn')";

    $r2 = $db->query($q2);
    $resultsRow = $r2->fetch_assoc();
    $totalResults = $resultsRow["total"];

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
        
        <div class="search-term"><p>Showing <?=$totalResults?> results for <i><?=$search?></i></p></div>

        <div class="result">

            <?php

                //Generate the listings for each listing in the search result
                //Has to incorporate the fact that there may be multiple tuples for when there is more than one author of a book
                $currentRow = $r1->fetch_assoc();
                $multipleAuthors = false;
                for($i = 0; $i < $r1->num_rows; $i++) {
                    
                    $nextRow = $r1->fetch_assoc();

                    if ($currentRow["lid"] == $nextRow["lid"]) {
                        //Determine if the current row is the same listing but different author
                        if ($multipleAuthors == false) {
                            $author = $currentRow["last_name"] . ", " . $currentRow["first_name"] . " ...";
                            $multipleAuthors = true;
                        }
                    } else {
                        $lid = $currentRow["lid"];
                        //Query to get image for the book
                        $q2 = "SELECT image FROM Images WHERE lid = '$lid'";
                        $r2 = $db->query($q2);
                        $row = $r2->fetch_assoc();

                        //Prep info to be shown in listing
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


            <div onclick="clickableSearch(<?=$lid?>)" class="post clickable">
                <img class="postImage" src="<?=$image?>" width="200" height="220" alt="Book Image"/>
                <div class="postInfo">
                    <p class="overflow"><?=$title?></p>
                    <p class="overflow"><?=$author?></p>
                    <p>ISBN: <?=$isbn13?></p>
                    <p>$<?=$price?></p>
                    <p><?=$location?></p>
                </div>
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