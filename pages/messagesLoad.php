<?php
    $cid = $_REQUEST['cid'];
    $uid = $_REQUEST['uid'];
    $title = $_REQUEST['title'];
    $lastUpdate = $_REQUEST['lastUpdate'];
    $lastUpdate = urldecode($lastUpdate);

    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }

    // fetch all the messages for that chat
    $q1 = "SELECT M.message, M.time_sent, M.uid_sender, U.avatar
    FROM Messages M INNER JOIN Users U
    ON M.uid_sender = U.uid
    WHERE M.cid = '$cid'
    ORDER BY M.time_sent";

    $r1 = $db->query($q1);
    
    // fetch the time for the last message, used to see if update needed
    $q2 = "SELECT C.last_message FROM Chats C WHERE C.cid = '$cid'";
    $r2 = $db->query($q2);

    $db->close();

?>
<?php

    // if the times have changed
    // store the last time
    $lastR = $r2->fetch_assoc();
    $last = $lastR["last_message"];

    if($last != $lastUpdate){

?>

<h2 id ="chatTitle"><?=$title?></h2>

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
        <p id = "lastUpdate" style="display:none;"><?=$last?></p>

</div>
<?php }?>