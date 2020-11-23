<?php
    $cid = $_REQUEST['cid'];
    $uid = $_REQUEST['uid'];
    $title = $_REQUEST['title'];

    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }

    $q1 = "SELECT M.message, M.time_sent, M.uid_sender, U.avatar
    FROM Messages M INNER JOIN Users U
    ON M.uid_sender = U.uid
    WHERE M.cid = '$cid'
    ORDER BY M.time_sent";

    $r1 = $db->query($q1);

    $db->close();


?>


<h2><?=$title?></h2>

<div class ="messages">

    <?php

        for($i = 0; $i < $r1->num_rows; $i++) {
            $row = $r1->fetch_assoc();
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