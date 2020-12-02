<?php
$validate = true;
$error = "";
$reg_Email = "/([a-zA-Z0-9\.\-\_]+)@[a-zA-Z]+.\.+[a-zA-Z]{2,5}$/";
$reg_Username = "/[a-zA-Z0-9\-\_\@\$]+$/";
$reg_Pswd = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}/";
$reg_Bday = "/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/";
$city_v = "/^[a-zA-Z0-9 '.,&#]*$/";
$province_v = "/^[a-zA-Z0-9 '.,&#]*$/";
$country_v = "/^[a-zA-Z0-9 '.,&#]*$/";

if (isset($_POST["submitted"]) && $_POST["submitted"])
{
    // get the values from the input form
    $email = trim($_POST["email"]);
    $email = str_replace("'", "&#039", $email);
    $username = trim($_POST['username']);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $first_name = trim($_POST["first_name"]);
    $first_name = str_replace("'", "&#039", $first_name);
    $last_name = trim($_POST["last_name"]);
    $last_name = str_replace("'", "&#039", $last_name);
    $DOB = trim($_POST['DOB']);
    $city = trim($_POST['citySU']);
    $city = str_replace("'", "&#039", $city);
    $province = trim($_POST['provinceSU']);
    $province = str_replace("'", "&#039", $province);
    $country = trim($_POST['countrySU']);
    $country = str_replace("'", "&#039", $country);

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
            $error .= "Invalid Email Entered ";
            $validate = false;
        }

        // check the username
        $usernameMatch = preg_match($reg_Username, $email);
        if ($username == null || $username == "" || $usernameMatch == false)
        {   
            $error .= "Invalid Username Entered";
            $validate = false;
        }

        // check the password
        $pswdLen = strlen($password);
        $pswdMatch = preg_match($reg_Pswd, $password);
        if ($password == null || $password == "" || $pswdLen < 8 || $pswdMatch == false)
        {
            $error .= "Invalid Password. Must be at least 8 characters long with at least 1 lowercase, 1 uppercase and 1 number ";
            $validate = false;
        }

        // check the confirm password
        $pswdLen = strlen($confirmPassword);
        $pswdMatch = preg_match($reg_Pswd, $confirmPassword);
        if ($confirmPassword == null || $confirmPassword == "" || $pswdLen < 8 || $pswdMatch == false || $confirmPassword != $password)
        {
            $error .= "Invalid Confirm Password Entered.";
            $validate = false;
        }

        // test the names
        if ($first_name == null || $first_name == "")
        {
            
            $error .= "Invalid First Name ";
            $validate = false;
        }
        if ($last_name == null || $last_name == "")
        {
            $error .= "Invalid Last Name. ";
            $validate = false;
        }

        // test the location
        $cityV = preg_match($city_v, $city);
        $provinceV = preg_match($province_v, $province);
        $countryV = preg_match($country_v, $country);
        if($city == null || $province == null || $country == null || $city == "" || $province == "" || $country == "" || $cityV == false || $provinceV == false || $countryV == false)   
        {
            $error .= $city .= " ";
            $error .= $cityV .= " ";
            $error .= $province .= " ";
            $error .= $provinceV .= " ";
            $error .= $country .= " ";
            $error .= $countryV .= " ";
            $error .= "Invalid Location. ";
            $validate = false;
        }

    }

    // if it is true insert into database. then try to upload the image
    if ($validate == true)
    {

        // querry to inset into database
        $q2 = "INSERT INTO Users (username, first_name, last_name, email, password, DOB, city, province, country, avatar) VALUES ('$username', '$first_name', '$last_name', '$email', '$password', '$DOB', '$city', '$province', '$country', '../avatar/default.png');";

        $r2 = $db->query($q2);

        // php code to upload an image
        $target_dir = "../avatar/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // holds extension


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
        // get the userID to create a unique file name
        $q3 = "SELECT U.uid FROM Users U WHERE U.username = '$username' AND U.email = '$email'";
        $r3 = $db->query($q3);
        $row = $r3->fetch_assoc();
        $uid = $row["uid"];

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType ) {    
            // set error to this
            $error =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0)
        {
            $error .= "Sorry, your file was not uploaded.";
            
        // if everything is ok, try to upload file    
        }
        else
        {
            //change the name of the file to the userID
            $target_file = $target_dir . $uid . "." . $imageFileType;
            // upload the file
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file));
            
            // update the database
            $q4 = "UPDATE Users U SET U.avatar = '$target_file' WHERE U.uid = '$uid'";
            $r4 = $db->query($q4);
        }

    }
    // if there is an error. inform the user
    else
    {
        $error .= "email address or username is not available. Signup failed.";
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

    <body>
    <header>
	    <img src="../images/logo.png" alt = "Logo" style = "display:inline" width = "250" height = "200" />
    </header>
    
    <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
        <?php 
            // if logged in
            if(isset($_SESSION["username"])) {

                /* Redirect user to index page */
                header("Location: index.php"); 
                exit();

            //if not logged in have links to sign up
            } else {

        ?>

            <a href="signUp.php">SignUp <i class="fa fa-user-plus"> </i></a>
            <a href="Login.php">LogIn <i class="fa fa-sign-in"></i></a>

         <?php }?>

            <div class="search-container">
                <form action="search.php" method="get">
                <div class = "container">
                    <div id="map"></div>
                    <div id="search-box"></div>
                </div>
                <input type="hidden" id ="city" value = "" name="city">
                    <input type="hidden" id ="province" value = "" name="province">
                    <input type="hidden" id ="country" value = "" name="country">
				<input id = "bookSearch" type="text" placeholder="Search.." name="search" value="<?=$search?>">
				<button type="submit"><i class="fa fa-search"></i></button>
				</form>
            </div>
        </div>  

        <h1>Sign Up</h1>
             

        <form class="formTable" id="SignUp" action="signUp.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="submitted" value="1">
            <table>
                <tr><td></td><td><label id="picupload_msg" class="err_msg"></label></td></tr>  
                <tr><td>Upload an Avatar: </td><td><input type="file" name="fileToUpload" id="fileToUpload"></td></tr>

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
                <tr><td>Date of Birth: </td><td> <input type="date" name="DOB" size="30" /></td></tr>

            	<tr><td></td><td><label id="location_msg" class="err_msg"></label></td></tr>   
                <tr><td>Location: </td><td><div class = "container">
                    <div id="map2"></div>
                    <div id="search-boxSU"></div>
                </div></td></tr>
                <input type="hidden" id ="citySU" name = "citySU" value = "">
                <input type="hidden" id ="provinceSU" name ="provinceSU" value = "">
                <input type="hidden" id ="countrySU" name = "countrySU" value = "">

            </table>
            <br>
            <input type="submit" name="SignUp" value="SignUp" />
            <input type="reset" name="Reset" value="Reset" /><br><br>
            <p class = "err_msg"><?=$error?></p>
            <p> Already have an account? <a href="Login.php">LogIn</a></p>

        </form>
        <script type="text/javascript" src="../js/location.js"></script>
        <script type="text/javascript" src="../js/locationSU.js"></script>
        <script type="text/javascript" src="../js/JavaScript.js"></script>
    </body>
</html>
