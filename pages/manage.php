<?php
$validate = true;
$error = "";
$reg_Pswd = "/^(\S*)?\d+(\S*)?$/";
$city_v = "/^([a-zA-Z\u0080-\u024F]+(?:. |-| |'))*[a-zA-Z\u0080-\u024F]*$/";
$province_v = "/^[a-zA-Z '.,]*$/";
$country_v = "/^[a-zA-Z '.,]*$/";
$nameREG = "/^[a-z ,.'-]+$/";

session_start();
// if user is logged in, continue, else put to index.php
if(!$_SESSION["username"]) {
    header("Location: index.php");
}
$uid = $_SESSION["uid"];

if (isset($_POST["submitted"]) && $_POST["submitted"])
{
    // get the values from the input form
    //replace ' with &#39
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $first_name = trim($_POST["first_name"]);
    $first_name = str_replace("'", "&#039", $first_name);
    $last_name = trim($_POST["last_name"]);
    $last_name = str_replace("'", "&#039", $last_name);
    $city = trim($_POST['citySU']);
    $city = str_replace("'", "&#039", $city);
    $province = trim($_POST['provinceSU']);
    $province = str_replace("'", "&#039", $province);
    $country = trim($_POST['countrySU']);
    $country = str_replace("'", "&#039", $country);

    //get the check boxes
    $avCK = $_POST['changeAvatar'];
    $fnCK = $_POST['changeFN'];
    $lnCK = $_POST['changeLN'];
    $pwCK = $_POST['changePW'];
    $locCK = $_POST['changeLoc'];
    

    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die("Connection failed: " . $db->connect_error);
    }


    // check the password
    $pswdLen = strlen($password);
    $pswdMatch = preg_match($reg_Pswd, $password);
    if ($pwCK == 1 && ($password == null || $password == "" || $pswdLen < 8 || $pswdMatch == false))
    {
        $error .= "Invalid Password ";
        $validate = false;
    }

    // check the confirm password
    $pswdLen = strlen($confirmPassword);
    $pswdMatch = preg_match($reg_Pswd, $confirmPassword);
    if ($pwCK == 1 && ($confirmPassword == null || $confirmPassword == "" || $pswdLen < 8 || $pswdMatch == false || $confirmPassword != $password))
    {
        $error .= "Invalid Password ";
        $validate = false;
    }

    // test the names
    $first_nameV = preg_match($nameREG, $first_name);
    if ($fnCK == 1 && ($first_name == null || $first_name == "" || $first_nameV == false))
    {
        
        $error .= "Invalid First Name ";
        $validate = false;
    }
    $last_nameV = preg_match($nameREG, $last_name);
    if ($lnCK == 1 && ($last_name == null || $last_name == "" || $last_nameV == false))
    {
        $error .= "Invalid Last Name. ";
        $validate = false;
    }

    // test the location
    $cityV = preg_match($city, $city_v);
    $provinceV = preg_match($province, $province_v);
    $countryV = preg_match($country, $country_v);
    if($locCK == 1 && ($city == null || $province == null || $country == null || $city == "" || $province == "" || $country == "" || $cityV == false || $provinceV == false || $countryV == false))    
    {
        $error .= "Invalid Location. ";
        $error .= $locCK;
        $validate = false;
    }

    // if it is true insert into database. then try to upload the image
    if ($validate == true)
    {
        
        // prep base query
        $q = "UPDATE Users U SET ";

        // if Avatar is checked off
        if($avCK == 1){

            // php code to upload an image
            $target_dir = "../avatar/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // holds extension


            // Check if file already exists
            if (file_exists($target_file))
            {
                $error .= "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000)
            {
                $error .= "Sorry, your file is too large. ";
                $uploadOk = 0;
            }
            
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType ) {    
                // set error to this
                $error .=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0)
            {
                $error .= "Sorry, your file was not uploaded. ";
                // if everything is ok, try to upload file
                
            }
            else
            {
                //change the name of the file to the userID
                $target_file = $target_dir . $uid . "." . $imageFileType;
                // if the file name exists, unlink it
                if(file_exists($target_file)) unlink($target_file);
                // upload the file
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file));
            }

            // append to query
            $q .= "U.avatar = '$target_file' ";
            
        }

        // if PW checked off 
        if($pwCK == 1){
            
            // append to query
            $q .= "U.password = '$password' ";
        }
        // if first name checked off 
        if($fnCK == 1){
            
            // append to query
            $q .= "U.first_name = '$first_name' ";
        }
        // if last name checked off 
        if($lnCK == 1){
            
            // append to query
            $q .= "U.last_name = '$last_name' ";
        }
        // if location checked off 
        if($locCK == 1){
            
            // append to query
            $q .= "U.city = '$city_v' U.province = '$province' U.country = '$country' ";
        }

        // finish the query
        $q .= "WHERE U.uid = '$uid'";

        // update the database based on what has changed
        $r = $db->query($q);

    }
    // if there is an error. inform the user
    else
    {
        $error .= "Update To Profile Failed.";
        $db->close();
    }

    // if successful send to homepage
    if ($r === true)
    {
        header("Location: profile.php");
        $db->close();
        exit();
    }

}
?>

<!DOCTYPE html>
<html>
    <head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet"
              type = "text/css"
              href = "../css/myStyle.css" />
        <title>Manage Information</title> 
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

        <h1>Manage Information</h1>
             

        <form id="manage" action="manage.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="submitted" value="1">
            <table>
                <tr><td></td><td><label id="avatar_msg" class="err_msg"></label></td></tr>  
                <tr><td>Upload an Avatar: </td><td><input type="file" name="fileToUpload" id="fileToUpload"></td><td><input type="checkbox" id="changeAvatar" name="changeAvatar" value=1><label for="changeAvatar"> Change Avatar?</label></td></tr>

                <tr><td></td><td><label id="fname_msg" class="err_msg"></label></td></tr>   
                <tr><td>First name: </td><td> <input type="text" name="first_name" placeholder= "Ex. John" size="30" /></td><td><input type="checkbox" id="changeFN" name="changeFN" value=1><label for="changeFN"> Change First Name?</label></td></tr>

                <tr><td></td><td><label id="lname_msg" class="err_msg"></label></td></tr>   
                <tr><td>Last name: </td><td> <input type="text" name="last_name" placeholder= "Ex. Vick" size="30" /></td><td><input type="checkbox" id="changeLN" name="changeLN" value=1><label for="changeLN"> Change Last Name?</label></td></tr>

                <tr><td></td><td><label id="pswd_msg" class="err_msg"></label></td></tr>
                <tr><td>Password: </td><td> <input type="password" name="password" placeholder= "Ex. Password" size="30" /></td><td><input type="checkbox" id="changePW" name="changePW" value=1><label for="changePW"> Change Password?</label></td></tr>  

                <tr><td></td><td><label id="pswdr_msg" class="err_msg"></label></td></tr>    
                <tr><td>Confirm Password: </td><td> <input type="password" name="confirmPassword" placeholder= "Ex. confirmPassword" size="30" /></td></tr>  

            	<tr><td></td><td><label id="location_msg" class="err_msg"></label></td></tr>   
                <tr><td>Location: </td><td><div class = "container">
                    <div id="map2"></div>
                    <div id="search-boxSU"></div>
                </div></td><td><input type="checkbox" id="changeLoc" name="changeLoc" value=1><label for="changeLoc"> Change Location?</label></td></tr>
                <input type="hidden" id ="citySU" name = "citySU" value = "">
                <input type="hidden" id ="provinceSU" name ="provinceSU" value = "">
                <input type="hidden" id ="countrySU" name = "countrySU" value = "">

            </table>
            <br>
            <input type="submit" name="manage" value="Update" />
            <input type="reset" name="Reset" value="Reset" /><br><br>
            <p class = "err_msg"><?=$error?></p>

        </form>
        <script type="text/javascript" src="../js/location.js"></script>
        <script type="text/javascript" src="../js/locationSU.js"></script>
        <script type="text/javascript" src="../js/JavaScript.js"></script>
    </body>
</html>
