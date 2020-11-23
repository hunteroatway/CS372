<?php

    // get the lid
    $lid = trim($_GET["lid"]);

    // connect to database
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection to database failed: " . $db->connect_error);
    }

    // prep the query to mark listing as sold, end all chats
    $q1 = "UPDATE Chats C SET C.active = 0 WHERE C.lid = '$lid'";
    $q2 = "UPDATE Listings L SET L.active = 0 WHERE  L.lid = '$lid'";

    // query the DB
    
    $r1 = $db->query($q1);
    $r2 = $db->query($q2);

    // redirect user to the listing page
    header("Location: listing.php?lid=".$lid);
    exit();

?>