<?php

    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }

    $uid = 1;

    $q1 = "SELECT B.title, C.uid_buyer as Buyer, L.uid as Seller, 
        CASE
            WHEN C.uid_buyer = '$uid' THEN CONCAT(US.first_name, ' ', US.last_name)
            WHEN L.uid = '$uid' THEN CONCAT(UB.first_name, ' ', UB.last_name)
        END AS name
        FROM Chats C INNER JOIN Listings L
        ON C.lid = L.lid INNER JOIN Users UB
        ON C.uid_buyer = UB.uid INNER JOIN Users US
        ON L.uid = US.uid INNER JOIN Books B
        ON L.isbn_13 = B.isbn_13
        WHERE C.uid_buyer = '$uid' OR L.uid = '$uid'
        ORDER BY C.last_message DESC";

    $r1 = $db->query($q1);
    $db->close();

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
    </head>

    <header>
	<img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>

    <body>
        <div class="topnav" id = "topNav">
            <a class="active" href="index.html">Home <i class="fa fa-fw fa-home"> </i></a>
            <a href="signUp.html">SignUp <i class="fa fa-user"> </i></a>
            <a href=".html">Manage</a>
            <a href=".html">Book <i class="fa fa-book"> </i></a>
			  <div class="search-container">
				<form action="/action_page.php">
                <input type="text" placeholder="City.." name="city">
				<input type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
			</div>
        </div>

        <div class = "splitPage">
            <div class = "one">
                <div class="sidebar" id ="sidebar">

                <?php
                    for($i = 0; $i < $r1->num_rows; $i++) {
                        $row = $r1->fetch_assoc();
                        $title = $row["title"];
                        $name = $row["name"];
                ?>

                <div onclick="getMessages(1,<?=$uid?>)" class = "chat">
                        <form><input type="hidden" name="cid" value="<?=$cid?>"></form>
                    <p><?=$title?></p>
                    <p class = "sellerName"><?=$name?></p>
                </div>
                    
                <?php
                    }
                ?>
                </div>
            </div>

            <div class = "two">
                <div class="main" id="msgs">

                </div>
            </div>
        </div>

        <script type="text/javascript" src="../js/JavaScript.js"></script>
        <script type="text/javascript" src="../js/ajax.js"></script>
    </body>
</html>