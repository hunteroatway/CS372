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
    $email = str_replace("'", "&#039", $email);
    $username = trim($_POST['username']);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $DOB = trim($_POST['DOB']);
    $city = trim($_POST['city']);
    $province = trim($_POST['province']);
    $country = trim($_POST['country']);
    $avatar = trim($_POST['avatar']);

    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die("Connection failed: " . $db->connect_error);
    }

    // check to see if the email or username is in the table
    $q1 = "SELECT * FROM Users WHERE email = '$email' OR username = '$username'";
    $r1 = $db->query($q1);

    // if the email address or username is already taken.
    if ($r1->num_rows > 0)
    {
        $validate = false;
    }
    else
    {
        // check the email
        $emailMatch = preg_match($reg_Email, $email);
        if ($email == null || $email == "" || $emailMatch == false)
        {
            $validate = false;
        }

        // check the username
        $usernameMatch = preg_match($reg_Username, $email);
        if ($username == null || $username == "" || $usernameMatch == false)
        {
            $validate = false;
        }

        // check the password
        $pswdLen = strlen($password);
        $pswdMatch = preg_match($reg_Pswd, $password);
        if ($password == null || $password == "" || $pswdLen < 8 || $pswdMatch == false)
        {
            $validate = false;
        }

        // check the confirm password
        $pswdLen = strlen($confirmPassword);
        $pswdMatch = preg_match($reg_Pswd, $confirmPassword);
        if ($confirmPassword == null || $confirmPassword == "" || $pswdLen < 8 || $pswdMatch == false || $confirmPassword != $password)
        {
            $validate = false;
        }
    }

    // if it is true insert into database. then try to upload the image
    if ($validate == true)
    {

        // querry to inset into database
        $q2 = "INSERT INTO Users (username, first_name, last_name, email, password, DOB, city, province, country, avatar) VALUES ('$username', '$first_name', '$last_name', '$email', '$password', '$DOB', '$city', '$province', '$country', '$avatar');";

        $r2 = $db->query($q2);

        // UPLOAD PHOTO
        // RENAME PHOTO BASED ON UID (CALL IT UID.(FILEEXTENSION))
        // UPDATE AVATAR IN DATABASE
        
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["Choosepic"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submitted"]))
        {
            $check = getimagesize($_FILES["Choosepic"]["tmp_name"]);
            if ($check !== false)
            {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            }
            else
            {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file))
        {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000)
        {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
        {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0)
        {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            
        }
        else
        {
            if (move_uploaded_file($_FILES["Choosepic"]["tmp_name"], $target_file))
            {
                echo "The file " . htmlspecialchars(basename($_FILES["Choosepic"]["name"])) . " has been uploaded.";
            }
            else
            {
                echo "Sorry, there was an error uploading your file.";
            }
        }

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

        <h1>Sign Up</h1>
             

	<div>
            <p> Profile Picture: <input type="file" name="dataFile" id="Choosepic" onclick="return ValidateFileUpload()" onchange="return ValidateFileUpload()" /></p>
        </div> 

	<form id="SignUp" action="signUp.php" method="post">
        <input type="hidden" name="submitted" value="1">
            <table>

                <tr><td></td><td><label id="email_msg" class="err_msg"></label></td></tr>
                <tr><td>Email: </td><td> <input type="text" name="email" placeholder= "Ex. Email@email.com" size="30" /></td></tr>

                <tr><td></td><td><label id="sname_msg" class="err_msg"></label></td></tr>   
                <tr><td>User name: </td><td> <input type="text" name="username" placeholder= "Ex. John21" size="30" /></td></tr>

                <tr><td></td><td><label id="uname_msg" class="err_msg"></label></td></tr>   
                <tr><td>First name: </td><td> <input type="text" name="first_name" placeholder= "Ex. John" size="30" /></td></tr>

                <tr><td></td><td><label id="name_msg" class="err_msg"></label></td></tr>   
                <tr><td>Last name: </td><td> <input type="text" name="last_name" placeholder= "Ex. Vick" size="30" /></td></tr>

                <tr><td></td><td><label id="pswd_msg" class="err_msg"></label></td></tr>
                <tr><td>Password: </td><td> <input type="password" name="password" placeholder= "Ex. Password" size="30" /></td></tr>  

                <tr><td></td><td><label id="pswdr_msg" class="err_msg"></label></td></tr>    
                <tr><td>Confirm Password: </td><td> <input type="password" name="confirmPassword" placeholder= "Ex. confirmPassword" size="30" /></td></tr>  

                <tr><td></td><td><label id="bday_msg" class="err_msg"></label></td></tr>   
                <tr><td>Date of Birth: </td><td> <input type="text" name="birthday" placeholder= "Ex. mm/dd/yyyy or mm-dd-yyyy" size="30" /></td></tr>

            	<tr><td></td><td><label id="city_msg" class="err_msg"></label></td></tr>   
                <tr><td>City: </td><td> <input type="text" name="city" placeholder= "Ex. Regina" size="30" /></td></tr>

            	<tr><td></td><td><label id="province_msg" class="err_msg"></label></td></tr>   
                <tr><td>Province: </td><td> <input type="text" name="province" placeholder= "Ex. Saskatchewan" size="30" /></td></tr>

            	<tr><td></td><td><label id="country_msg" class="err_msg"></label></td></tr>   
                <tr><td>Country: </td><td> <input type="text" name="country" placeholder= "Ex. Canada" size="30" /></td></tr>

            </table>
            <br>
            <input type="submit" name="SignUp" value="SignUp" />
            <input type="reset" name="Reset" value="Reset" /><br><br>
            <p> Already have an account? <a href="Login.php">LogIn</a></p>

        </form>
        <script type="text/javascript" src="../js/location.js"></script>
        <script type="text/javascript" src="../js/JavaScript.js"></script>
    </body>
</html>
