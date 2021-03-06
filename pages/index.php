<?php 
  // start the php session
  session_start();

      // connect to DB and check connection
      $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
      if ($db->connect_error)
      {
          die ("Connection failed: " . $db->connect_error);
      }
  
      //Query to pull most recent posts
      $q1 = "SELECT L.lid, L.isbn_10, L.isbn_13, L.price, L.list_date, A.first_name, A.last_name, B.title, B.photo, U.city, U.province, U.country
      FROM Listings L INNER JOIN Books B 
      ON L.isbn_13 = B.isbn_13 INNER JOIN Authors A
      ON B.isbn_13 = A.isbn_13 INNER JOIN Users U
      ON L.uid = U.uid
      WHERE L.active = true
      ORDER BY L.list_date DESC, L.lid DESC, A.last_name LIMIT 12";
  
      //Query the DB
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

        <h1>Most Recent Posting</h1>
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

                        $image = $currentRow["photo"]; 
                        $title = $currentRow["title"];                        
                        $isbn13 = $currentRow["isbn_13"];
                        $price = $currentRow["price"];
                        $location = $currentRow["city"] . ", " . $currentRow["province"] . ", " . $currentRow["country"];

                        //If there is only one author, store that name
                        if ($multipleAuthors == false) {
                            //Check to ensure author has both first and last name
                            if($currentRow["last_name"] != "" && $currentRow["first_name"] != "")
                                $author = $currentRow["last_name"] . ", " . $currentRow["first_name"];
                            else if($currentRow["last_name"] != "")
                                $author = $currentRow["last_name"];
                            else
                                $author = $currentRow["first_name"];
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
