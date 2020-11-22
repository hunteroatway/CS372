<?php
    //php code to deal with adding a message into the database

    // get the values from the post request
    $cid = trim($_POST["cid"]);
    $uid = trim($_POST["uid"]);
    $message = trim($_POST["message"]);

    // data has been cleaned in Jquery, use php to clean it again
    $message = stripslashes($message);
    $message = htmlspecialchars($message);
    $message = str_replace("'", "&#039;", $message);
    echo $message;

    // connect to the database
    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }

    // set up query to add message to database
    $q = "INSERT INTO Messages (uid_sender, cid, message, time_sent) VALUES ('$uid', '$cid', '$message', NOW());";
    $r = $db->query($q);

?>