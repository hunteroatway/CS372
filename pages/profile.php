<?php
    session_start();
     if (!isset($_SESSION["username"])) {
        echo ("<script LANGUAGE='JavaScript'>
            window.alert('You need to LogIn in order to access Profile page.');
            window.location.href='Login.php';
            </script>");
    }

    // get the uid
    $uid = $_SESSION["uid"];

    // connect to database
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection to database failed: " . $db->connect_error);
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

        <?php

            // query to get users information
            $q1 = "SELECT U.first_name, U.last_name, U.city, U.province, U.country, U.avatar FROM Users U WHERE U.uid = '$uid'";
            $r1 = $db->query($q1);
            $userInfo = $r1->fetch_assoc();   

            // query to get number of open chats
            $q3 = "SELECT COUNT(C.cid) AS count, C.last_message, C.cid FROM Chats C INNER JOIN Listings L ON C.lid = L.lid WHERE L.uid = '$uid' AND C.active = '1' AND L.active = '1' ORDER BY C.last_message DESC";
            $r3 = $db->query($q3);
            $chats = $r3 ->fetch_assoc();
            $count = $chats["count"];
            $cid = $chats["cid"];

        ?>

        <div class = "listingProfile">
            <div class = "profile">
                <div class = "profileInfo">
                    <h2>Profile</h2>
                    <div class="avatar">
                        <img src="<?=$userInfo["avatar"]?>" style = "display:inline" width = "100 " height = "100" />
                    </div>
                    <p><b><?=$userInfo["first_name"]?> <?=$userInfo["last_name"][0]?>'s page</b></p>
                    <p>Located in: <?=$userInfo["city"]?>, <?=$userInfo["province"]?>, <?=$userInfo["country"]?></p>
                </div>
                <div class = "clickable profileMessages">                              
                    <a href="messages.php?cid=<?=$cid?>" style="text-decoration:none;">
                        <div>
                        <p>Currently have <?=$count?> selling chats open. </p>      
                            <span class = "messageListingText">Click Here To View Chats.</span>
                        </div>
                    </a>
                </div>
            </div> 

            <div class = "profileListings">

                <label>Sort by: </label>
                <select name="sort" id="sort" onchange="sortListing(<?=$uid?>)">
                    <option value="postAsc">Posted: Ascending</option>
                    <option value="postDesc">Posted: Descending</option>
                    <option value="titleAsc">Book Title: Ascending</option>
                    <option value="titleDesc">Book Title: Descending</option>
                </select>

                <div id="selfListing">
                    <?php
                        // query to get users listings
                        $q2 = "SELECT L.lid, L.isbn_10, L.isbn_13, L.price, L.list_date, A.first_name, A.last_name, B.title, U.city, U.province, U.country
                        FROM Listings L INNER JOIN Books B 
                        ON L.isbn_13 = B.isbn_13 INNER JOIN Authors A
                        ON B.isbn_13 = A.isbn_13 INNER JOIN Users U
                        ON L.uid = U.uid
                        WHERE L.active = true AND L.uid = '$uid'
                        ORDER BY L.list_date, L.lid, A.last_name";

                        $r2 = $db->query($q2);

                        //Generate the listings for each listing in the search result
                        //Has to incorporate the fact that there may be multiple tuples for when there is more than one author of a book
                        $currentRow = $r2->fetch_assoc();
                        $multipleAuthors = false;
                        for($i = 0; $i < $r2->num_rows; $i++) {
                            
                            $nextRow = $r2->fetch_assoc();

                            if ($currentRow["lid"] == $nextRow["lid"]) {
                                //Determine if the current row is the same listing but different author
                                if ($multipleAuthors == false) {
                                    $author = $currentRow["last_name"] . ", " . $currentRow["first_name"] . " ...";
                                    $multipleAuthors = true;
                                }
                            } else {
                                $lid = $currentRow["lid"];
                                //Query to get image for the book
                                $q4 = "SELECT image FROM Images WHERE lid = '$lid'";
                                $r4 = $db->query($q4);
                                $row = $r4->fetch_assoc();

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

            </div>
        </div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript" src="../js/JavaScript.js"></script>
        <script type="text/javascript" src="../js/location.js"></script>
        <script type="text/javascript" src="../js/profileAjax.js"></script>
    </body>
</html>
