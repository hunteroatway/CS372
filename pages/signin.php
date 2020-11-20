<?php

    $validate = true;
    $reg_Email = "/([a-zA-Z0-9\.\-\_]+)@[a-zA-Z]+.\.+[a-zA-Z]{2,5}$/";
    $reg_Pswd = "/^(\S*)?\d+(\S*)?$/";
    
    $email = "";
    $error = "";
    
    if (isset($_POST["submitted"]) && $_POST["submitted"])
    {
        $email = trim($_POST["email"]);
        $email = str_replace("'","&#039", $email);
        $password = trim($_POST["password"]);
        $password = str_replace("'","&#039", $password);
        
        $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
        if ($db->connect_error)
        {
            die ("Connection to database failed: " . $db->connect_error);
        }
      
        // Get users information from table
        $q = "SELECT email, password, username, uid, avatar FROM Users WHERE email = '$email' AND password = '$password'";
        
        $return = $db->query($q);
        $row = $return->fetch_assoc();
        // if the entered email and password do not match
        if($email != $row["email"] && $password != $row["password"])
        {
            // set validate to false
            $validate = false;
        }
        // continue
        else
        {
            
            // validate the email with the reg ex
            $emailMatch = preg_match($reg_Email, $email);
            // check to see if theres a value, not null and is valid
            if($email == null || $email == "" || $emailMatch == false){
                $validate = false;
            }
            
            // get length of password and check the password with the reg ex
            $pswdLen = strlen($password);
            $passwordMatch = preg_match($reg_Pswd, $password);
            
            if($password == null || $password == "" || $pswdLen < 8 || $passwordMatch == false){
                $validate = false;
            }
        }
        
        // if every thing is valid log them in
        if($validate == true)
        {
            
            // start a session and assign the global supervariable to have the username
            session_start();
            $_SESSION["username"] = $row["username"];
            $_SESSION["uid"] = $row["uid"];
            $_SESSION["avatar"] = $row["avatar"];
            // redirect back to home page
            header("Location: index.php");
            $db->close();
            exit();
        }
        else
        {
            // start a session and assign the global supervariable to have the error message
            session_start();
            // redirect back to homepage
            header("Location: Login.php");
            // have the error message to inform the user
            $error = "The email/password combination was incorrect. Login failed.";
            $_SESSION["error"] = $error;
            $db->close();
        }
    }

?>