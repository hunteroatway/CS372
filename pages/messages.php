<?php

    session_start();
    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
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
        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.css?v=0.1.7">
        <script src="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.js?v=0.1.7"></script>
    </head>

    <body>
    <header>
	    <img src="../images/logo.png" alt = "Logo" style = "display:inline" width = "250" height = "200" />
    </header>
    
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
            //if not logged in direct user to logon page.
            } else {

                /* Redirect user to login page */
                header("Location: Login.php"); 
                exit();
        }?>

            <div class="search-container">
                <form action="search.php" method="get">
                <div class = "container">
                    <div id="map"></div>
                    <div id="search-box"></div>
                </div>
                <input type="hidden" id ="city" value = "" name="city">
                    <input type="hidden" id ="province" value = "" name="province">
                    <input type="hidden" id ="country" value = "" name="country">
				<input id = "bookSearch" type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
        </div>    

        <div class = "splitPage">
            <div class = "one">
                <div class="sidebar" id ="sidebar">

                <?php
                    $uid = $_SESSION["uid"];
                    $cidGet = $_GET["cid"];

                    //get the title for the current chat
                    $q3 = "SELECT B.title FROM Chats C INNER JOIN Listings L
                    ON C.lid = L.lid INNER JOIN Users UB
                    ON C.uid_buyer = UB.uid INNER JOIN Users US
                    ON L.uid = US.uid INNER JOIN Books B
                    ON L.isbn_13 = B.isbn_13 WHERE C.cid = '$cidGet'";
                    $r3 = $db->query($q3);
                    $titleRow = $r3->fetch_assoc();
                    $titleGet = $titleRow["title"];
                
                    //Query to get all chats the signed in user is apart of
                    $q1 = "SELECT B.title, C.cid, C.uid_buyer as Buyer, L.uid as Seller, 
                        CASE
                            WHEN C.uid_buyer = '$uid' THEN CONCAT(US.first_name, ' ', US.last_name)
                            WHEN L.uid = '$uid' THEN CONCAT(UB.first_name, ' ', UB.last_name)
                        END AS name
                        FROM Chats C INNER JOIN Listings L
                        ON C.lid = L.lid INNER JOIN Users UB
                        ON C.uid_buyer = UB.uid INNER JOIN Users US
                        ON L.uid = US.uid INNER JOIN Books B
                        ON L.isbn_13 = B.isbn_13
                        WHERE C.active = true AND (C.uid_buyer = '$uid' OR L.uid = '$uid')
                        ORDER BY C.last_message DESC";
                
                    $r1 = $db->query($q1);
                
                    $q2 = "SELECT C.uid_buyer as buyer, L.uid as seller
                    FROM Chats C INNER JOIN Listings L
                    ON C.lid = L.lid
                    WHERE C.cid = '$cidGet'";

                    $r2 = $db->query($q2);
                    $numRestults = $r2->num_rows;

                    // if there is no rows with the given CID. get it to -404 for error
                    if($numRestults == 0)
                        $cidGet = -404;

                    $chat = $r2->fetch_assoc();
                    $b_uid = $chat["buyer"];
                    $s_uid = $chat["seller"];

                    $db->close();

                    for($i = 0; $i < $r1->num_rows; $i++) {
                        $row = $r1->fetch_assoc();
                        $title = $row["title"];
                        $name = $row["name"];
                        $cid = $row["cid"];

                ?>

                <div onclick="getMessages(<?=$cid?>,<?=$uid?>,'<?=$title?>',0)" class = "chat clickable">
                    <p><?=$title?></p>
                    <p class = "sellerName"><?=$name?></p>
                </div>
                    
                <?php
                    }
                ?>
                </div>
            </div>

            <div class = "two">
                <div class="main">
                    <div id="msgs"></div>

                    <div class="message-area">
                        <form name = "messageForm" id="test">
                            <input id="cidValue" type="hidden" name="cid" value="<?=$cidGet?>">
                            <input id="uidValue" type="hidden" name="uid" value="<?=$uid?>">
                            <input id="titleValue" type="hidden" name="title" value="<?=$titleGet?>">
                            <input id="buid" type="hidden" name="buid" value="<?=$b_uid?>">
                            <input id="suid" type="hidden" name="suid" value="<?=$s_uid?>">
                            <input type="text" name = "message" placeholder="Type your message here..." class="message-box" id ="message-box"/>
                            <input type="submit" name = "submit" id="submitButton" value="Send" class="message-button"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../js/JavaScript.js"></script>
        <script type="text/javascript" src="../js/ajax.js"></script>
    <script type="text/javascript" src="../js/location.js"></script>
    </body>
</html>