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
      $q1 = "SELECT L.lid, L.isbn_10, L.isbn_13, L.price, L.list_date, A.first_name, A.last_name, B.title, U.city, U.province, U.country
      FROM Listings L INNER JOIN Books B 
      ON L.isbn_13 = B.isbn_13 INNER JOIN Authors A
      ON B.isbn_13 = A.isbn_13 INNER JOIN Users U
      ON L.uid = U.uid
      WHERE L.active = true
      ORDER BY L.list_date, L.lid, A.last_name
      LIMIT 12";
  
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
            <a href="posting.php">Post Ad <i class="fa fa-book"></i></a>
            <a href="profile.php">Profile <i class="fa fa-user"></i></a>
            <a href="logout.php">LogOut <i class="fa fa-sign-out"></i></a></a>
            <div class="search-container">
                <form action="/action_page.php">
                <div class = "container">
                    <div id="map"></div>
                    <div id="search-box"></div>
                </div>
                <input type="hidden" id ="city" value = "">
                <input type="hidden" id ="province" value = "">
                <input type="hidden" id ="country" value = "">
				<input id = "bookSearch" type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
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
                <div class = "container">
                    <div id="map"></div>
                    <div id="search-box"></div>
                </div>
                <input type="hidden" id ="city" value = "">
                <input type="hidden" id ="province" value = "">
                <input type="hidden" id ="country" value = "">
				<input id = "bookSearch" type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
        </div>

        <?php }?>

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


            <div onclick="clickableSearch(<?=$lid?>)" class="post clickable">
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