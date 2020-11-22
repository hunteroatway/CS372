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
        <div class="topnav">
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

                <div class = "chat">
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
                <h2>
                    Fancy book that is for sale
                </h2>

                    <div class ="messages">

                        <?php
                            $cid = 1;

                            $q2 = "SELECT M.message, M.time_sent, M.uid_sender, U.avatar
                            FROM Messages M INNER JOIN Users U
                            ON M.uid_sender = U.uid
                            WHERE M.cid = '$cid'
                            ORDER BY M.time_sent";

                            $r2 = $db->query($q2);
                        
                            $db->close();

                            for($i = 0; $i < $r2->num_rows; $i++) {
                                $row = $r2->fetch_assoc();
                                $message = $row["message"];
                                $uidSender = $row["uid_sender"];
                                $avatar = $row["avatar"];
                                $date = date("M jS, Y g:i:s a", strtotime($row["time_sent"]));

                                if($uidSender == $uid){
                                    $class1 = "avatarRight";
                                    $class2 = "message you";
                                    $class3 = "timeRight";
                                }
                                else {
                                    $class1 = "avatarLeft";
                                    $class2 = "message other";
                                    $class3 = "timeLeft";
                                }

                        ?>
                        
                        <div class = "<?=$class2?>">
                            <img class = "<?=$class1?>" src="<?=$avatar?>" style = "display:inline" width = "64 " height = "64" /> 
                            <p><?=$message?></p>
                            <span class = "<?=$class3?>"> <?=$date?></span>
                        </div>

                        <?php
                            }
                        ?>

                    </div>

                    <div class="message-area">
                        <form name = "messageForm">
                            <input type="hidden" name="cid" value="<?=$cid?>">
                            <input type="hidden" name="uid" value="<?=$uid?>">
                            <input type="text" name = "message" placeholder="Type your message here..." class="message-box" id ="message-box"/>
                            <input type="submit" name = "submit" id="submitButton" value="Send" class="message-button"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="../js/JavaScript.js"></script>
        <script type="text/javascript" src="../js/ajax.js"></script>
    </body>
</html>