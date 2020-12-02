<?php

    // start the php session
    session_start();

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

        <?php  // get information for the listing
		
        $lid = $_GET["lid"];
        $uid = $_SESSION["uid"];
        if(isset($lid)){ 
            // query to get the information needed
            $q1 = "SELECT L.isbn_13, L.isbn_10, L.uid, L.book_condition, L.price, L.list_date, L.active, A.first_name as auth_first, A.last_name as auth_last, B.title, B.subtitle, B.publisher, B.description, B.photo, U.first_name as user_first, U.last_name as user_last, U.avatar, U.city, U.province, U.country, U.uid as sellerID FROM Listings L INNER JOIN Books B on B.isbn_13 = L.isbn_13 INNER JOIN Authors A ON A.isbn_13 = L.isbn_13 INNER JOIN Users U on L.uid = U.uid WHERE L.lid = $lid";
            $q2 = "SELECT I.image from Images I WHERE I.lid = '$lid'";
            $q3 = "SELECT C.cid from Chats C where C.lid = '$lid' AND C.uid_buyer = '$uid'";

            $r1 = $db->query($q1);
            $r2 = $db->query($q2);
            $r3 = $db->query($q3);

            // see how many rows in the query for listing there is
            $rowsL = $r1->num_rows;

            // if less than 1. Go to error section
            if($rowsL < 1)
                goto error;

            // go through all the listings and combine the authors into one string
            $authors = "";
            for($i = 0; $i < $rowsL; $i++){
                $rowL = $r1->fetch_assoc();
                $authors .= $rowL["auth_first"] . " " . $rowL["auth_last"] . ", "; 
            }

        ?>
            <div class = "listing">

                <div class = "bookInfo">
                    <input type="hidden" id="slideValid" value = 1>
                    <div class ="slideShow">

                        <?php

                            // loop through images query and put the images into slideshow
                            $rows = $r2->num_rows;
                            $i = 0;
                            // initially put photo from google in
                            ?>
                            <div class="image">
                                <div class = "number"><?=$i+1?>/<?=$rows+1?></div>
                                <img src="<?=$rowL["photo"]?>" style = "display:inline" width = "600 " height = "600" />
                            </div>
                            <?php
                            for($i = 1; $i <= $rows; $i++){
                                $rowA = $r2->fetch_assoc();
                                ?>
                                    <div class="image">
                                        <div class = "number"><?=$i+1?>/<?=$rows+1?></div>
                                        <img src="<?=$rowA["image"]?>" style = "display:inline" width = "600 " height = "600" />
                                    </div>
                                <?php
                            }

                        ?>
    
                        <!-- Next and previous buttons -->
                        <a class="prev" onclick="change(-1)">&#10094;</a>
                        <a class="next" onclick="change(1)">&#10095;</a>
                    </div>

                    <div class = "dots">
                        <?php
                            for($i = 0; $i <= $rows; $i++){
                        ?>
                            <div class = "dot" onclick="slide(<?=$i+1?>)"></div>
                        <?php }?>
                    </div>

                    <div class = "bookInfoText">

                        <h2 id = "bookTitle">
                            <?php
                                // check to see if listing is active
                                if ($rowL["active"])
                                    echo $rowL["title"];
                                else 
                                    echo "<strike>" . $rowL["title"] . "</strike>";
                            ?>
                        </h2>        
                        <h3 id = "bookSubitle">
                            <?=$rowL["subtitle"]?>
                        </h3>      
                        <h4 id = "price">
                            Price: 
                            <?php
                                // check to see if listing is active
                                if ($rowL["active"])
                                    echo "$" . $rowL["price"];
                                else 
                                    echo "<b>SOLD</b>";
                            ?>
                        </h4>

                        <p id = "authors">
                            Author: <?=substr($authors, 0, -2)?>
                        </p>

                        <p id = "publisher">
                            Publisher: <?=$rowL["publisher"]?>
                        </p>

                        <?php 
                            //do a check on isbn_10 being -404. print a single isbn value that was supplied by user
                            if ($rowL["isbn_10"] == -404) {
                            ?>
                                <p>ISBN: <?=$rowL["isbn_13"]?> </p>
                            <?php
                            // otherwise print print both isbn values got from DB
                            } else {
                        ?>
                        <p id = "isbn">
                            ISBN-10: <?=$rowL["isbn_10"]?> 
                            </br>
                            ISBN-13: <?=$rowL["isbn_13"]?>
                        </p>
                        <?php } ?>

                        <div class="boxed">
                            <p id = "description">
                                <?=$rowL["description"]?>
                            </p>
                        </div>

                        <p id = "condition">
                            Condition: <?=$rowL["book_condition"]?>
                        </p>
                    </div>
                </div>  

                <div class = "posterInfo">
                    <h2>
                        <u>Poster Information:</u>
                    </h2>
                    <div class="avatar">
                        <img src="<?=$rowL["avatar"]?>" style = "display:inline" width = "100 " height = "100" />
                    </div>

                    <p id = "poster">
                        <b>Listing posted by: <?=$rowL["user_first"]?> <?=$rowL["user_last"][0]?>.</b>
                    </p>        
                    <p id = "listing">
                        Located in: <?=$rowL["city"]?>, <?=$rowL["province"]?>, <?=$rowL["country"]?>
                    </p> 
                    <p id = "postingDate">
                        Posted on: <?=date("M jS, Y", strtotime($rowL["list_date"]))?>
                    </p>

                    <?php 
                        // if logged in
                        if(isset($_SESSION["username"])) {
                    ?>

                    <?php
    
                        // if its not the seller 
                        if($uid != $rowL["sellerID"]) {
                            // if posting is active. display information to get to chats/ mark sold. Otherwise leave area blank
                            if($rowL["active"]){
                                // see if there is a chat open, if there is set redirect to it
                                $rowsC = $r3->num_rows;
                                if($rowsC > 0){
                                    $chat = $r3->fetch_assoc();
                                    $cid = $chat["cid"];
                                ?>
                                <a href="messages.php?cid=<?=$cid?>" style="text-decoration:none;">
                                <div class = "messageListing sendMessage clickable">
                                    <span class = "messageListingText">Click here to message this seller.</span>
                                </div>
                                </a>
                                <?php 
                                // if there isnt a chat open. redirect it to start a new chat
                                } else {
                                ?>
                                <a href="messageStart.php?uid=<?=$uid?>&lid=<?=$lid?>" style="text-decoration:none;">
                                <div class = "messageListing sendMessage clickable">
                                    <span class = "messageListingText">Click here to message this seller.</span>
                                </div>
                                </a>
                                <?php
                                }
                            }
                        // if seller
                        } else if ($uid == $rowL["sellerID"]){
                            // if posting is active. display information to get to chats/ mark sold. Otherwise leave area blank
                            if($rowL["active"]){
                                // fetch all the chats
                                $q4 = "SELECT C.cid FROM Chats C INNER JOIN Listings L on C.lid = L.lid WHERE L.lid = '$lid' AND L.uid = '$uid' ORDER BY C.last_message DESC";
                                $r4 = $db->query($q4);
                                $numChats = $r4->num_rows;
                                if($numChats == 0){
                                ?>
                                <a style="text-decoration:none;"> 
                                <div class = "messageListing sendMessage clickable">
                                    <span class = "messageListingText">No Chats Available.</span>
                                </div>
                                </a>
                                <?php
                                } else {
                                    // set it to the most recent chat
                                    $recentChat = $r4->fetch_assoc();
                                    $cid = $recentChat["cid"];
                                    ?>
                                    <a href="messages.php?cid=<?=$cid?>" style="text-decoration:none;"> 
                                    <div class = "messageListing sendMessage clickable">
                                        <span class = "messageListingText">View Chats.</span>
                                    </div>
                                    </a>
                                    <?php
                                }
                                // give area for seller to mark as sold
                                ?>
                                <a href="markSold.php?lid=<?=$lid?>" style="text-decoration:none;">
                                <div class = "messageListing sendMessage clickable">
                                    <span class = "messageListingText">Mark Item as Sold.</span>
                                </div>
                                </a>    
                                <?php
                            }
                        }
                    ?>

                    <?php
                        //if not logged in
                        } else {
                            // check to make sure it is active
                            if($rowL["active"]){

                        ?>
                        <div class = "messageListing">
                            <span class = "messageListingText">Please <a href="Login.php">log in</a> to message the poster about this item.</span>
                        </div>
                    <?php }
                        }
                    ?>
                    </div>
                </div> 

                <?php 
                
                    }  else {
                    error:
                    
                ?>

                    <h1 class = "errorList"> 404 Listing Not Found. </h1>
                    <input type="hidden" id="slideValid" value = 0>

                <?php } ?>


    <script type="text/javascript" src="../js/JavaScript.js"></script>
    <script type="text/javascript" src="../js/slideshow.js"></script>
    <script type="text/javascript" src="../js/location.js"></script>

    </body>
</html>
<?php 
	$db->close();
?>