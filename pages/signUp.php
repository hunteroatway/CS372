<?php
$validate = true;
$error = "";
$reg_Email = "/([a-zA-Z0-9\.\-\_]+)@[a-zA-Z]+.\.+[a-zA-Z]{2,5}$/";
$reg_Username = "/[a-zA-Z0-9\-\_\@\$]+$/";
$reg_Pswd = "/^(\S*)?\d+(\S*)?$/";
$reg_Bday = "/\d{4}-(0[1-9]|1[0-2])-([0-2][0-9]|3[0-1])$/";
$email = "";


if (isset($_POST["submitted"]) && $_POST["submitted"])
{
    // get the values from the input form
    $email = trim($_POST["email"]);
    $email = str_replace("'","&#039", $email);
    $username = trim($_POST['username']);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
       
    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }
    
    // check to see if the email or username is in the table
    $q1 = "SELECT * FROM Users WHERE email = '$email' OR username = '$username'";
    $r1 = $db->query($q1);

    
    // if the email address or username is already taken.
    if($r1->num_rows > 0)
    {
        $validate = false;
    }
    else
    {
        // check the email
        $emailMatch = preg_match($reg_Email, $email);
        if($email == null || $email == "" || $emailMatch == false){
            $validate = false;
        }
        
        // check the username
        $usernameMatch = preg_match($reg_Username, $email);
        if($username == null || $username == "" || $usernameMatch == false){
            $validate = false;
        }
        
        // check the password  
        $pswdLen = strlen($password);
        $pswdMatch = preg_match($reg_Pswd, $password);
        if($password == null || $password == "" || $pswdLen < 8 || $pswdMatch == false){
            $validate = false;
        }
        
        // check the confirm password
        $pswdLen = strlen($confirmPassword);
        $pswdMatch = preg_match($reg_Pswd, $confirmPassword);
        if($confirmPassword == null || $confirmPassword == "" || $pswdLen < 8 || $pswdMatch == false || $confirmPassword != $password){
            $validate = false;
        }
    }
    
    // if it is true insert into database. then try to upload the image
    if($validate == true)
    {
       
        // querry to inset into database
        $q2 = "INSERT INTO Users (username, first_name, last_name, email, password, DOB, city, province, country, avatar) VALUES ('$username', '$first_name', '$last_name', '$email', '$password', '1980-01-01', 'Regina', 'SK', 'Canada', '../avatar/default.png');";
        
        $r2 = $db->query($q2);

        // TODO: UPLOAD PHOTO
        // RENAME PHOTO BASED ON UID (CALL IT UID.(FILEEXTENSION))
        // UPDATE AVATAR IN DATABASE
    }
    // if there is an error. inform the user
    else
    {
        $error = "email address or username is not available. Signup failed.";
        $db->close();
    }

    // if successful send to homepage
    if ($r2 === true)
    {
        header("Location: index.php");
        $db->close();
        exit();
    }
        
}
?>

<?php 
  // start the php session
  session_start();
?>

<!DOCTYPE html>
<html>
    <head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet"
              type = "text/css"
              href = "../css/myStyle.css" />
        <title>SignUp Page</title> 
                <style>
            .err_msg{ color:red;}
        </style>
    </head>
	<header>
	<img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>
    <body>
    <body>

        <?php 
            // if logged in
            if(isset($_SESSION["username"])) {
        ?>

        <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
            <a href="listing.php">Post Ad <i class="fa fa-book"></i></a>
            <a href="profile.php">Profile <i class="fa fa-user"></i></a>
            <a href="logout.php">LogOut <i class="fa fa-sign-out"></i></a></a>
			  <div class="search-container">
				<form action="/action_page.php">
                <input id="pac-input" type="text" placeholder="City..">
				<input type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
            <div id="map"></div>
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
                <input id="pac-input" type="text" placeholder="City..">
				<input type="text" placeholder="Search.." name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
            <div id="map"></div>
        </div>

        <?php }?>

        <h1>Sign Up</h1>


        <form id="SignUp" action="signUp.php" method="post">
        <input type="hidden" name="submitted" value="1">
            <table>

                <tr><td></td><td><label id="email_msg" class="err_msg"></label></td></tr>
                <tr><td>Email: </td><td> <input type="text" name="email" size="30" /></td></tr>

                <tr><td></td><td><label id="sname_msg" class="err_msg"></label></td></tr>   
                <tr><td>User name: </td><td> <input type="text" name="username" size="30" /></td></tr>

                <tr><td></td><td><label id="uname_msg" class="err_msg"></label></td></tr>   
                <tr><td>First name: </td><td> <input type="text" name="first_name" size="30" /></td></tr>

                <tr><td></td><td><label id="name_msg" class="err_msg"></label></td></tr>   
                <tr><td>Last name: </td><td> <input type="text" name="last_name" size="30" /></td></tr>

                <tr><td></td><td><label id="pswd_msg" class="err_msg"></label></td></tr>
                <tr><td>Password: </td><td> <input type="password" name="password" size="30" /></td></tr>  

                <tr><td></td><td><label id="pswdr_msg" class="err_msg"></label></td></tr>    
                <tr><td>Confirm Password: </td><td> <input type="password" name="confirmPassword" size="30" /></td></tr>  

            </table>
            <br>
            <input type="submit" name="SignUp" value="SignUp" />
            <input type="reset" name="Reset" value="Reset" /><br><br>
            <p> Already have an account? <a href="Login.html">Sign in</a></p>

        </form>
        <script type="text/javascript" src="../js/JavaScript.js"></script>
    </body>
</html>