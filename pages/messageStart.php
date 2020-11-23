<?php

    // get information from the url
    $uid = trim($_GET["uid"]);
    $lid = trim($_GET["lid"]);

    // connect to database
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection to database failed: " . $db->connect_error);
    }

    // set up query to start chat
    $q1 = "INSERT INTO Chats(uid_buyer, lid, chat_open, active, last_message) VALUES ('$uid', '$lid', NOW(), 1, NOW())";
    $r1 = $db->query($q1);

    // get the chat id
    $q2 = "SELECT C.cid FROM Chats C WHERE C.uid_buyer = '$uid' AND C.lid = '$lid'";
    $r2 = $db->query($q2);

    $chat = $r2->fetch_assoc();
    $cid = $chat["cid"];

    // redirect user to the chat page
    header("Location: messages.php?cid=".$cid);
    exit();

?>