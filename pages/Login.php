<!DOCTYPE html>
<html>
    <head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet"
              type = "text/css"
              href = "../css/myStyle.css" />
        <title>Pick-a-Book</title> 
                <style>
            .err_msg{ color:red;}
        </style>
    </head>

    <header>
	<img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>

    <body>  
        <?php 
            session_start();
            $_SESSION["error"] = "";
		?>
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
		<div class="login">
            <h1>Log In</h1>
            <form id="LogIn" class = "input" action="signin.php" method="post">
                <input type="hidden" name="submitted" value="1">
                <form>
                <table>
                    <tr><td></td><td><label id="email_msg" class="err_msg"></label></td></tr>
                    <tr><td>Email: </td><td> <input type="text" name="email" size="30" /></td></tr>
                    <tr><td></td><td><label id="pswd_msg" class="err_msg"></label></td></tr>
                    <tr><td>Password: </td><td> <input type="password" name="password" size="30" /></td></tr>  
                </table>
                <br>
                <span class = "err"><?=$_SESSION["error"]?></span><input type="submit" class = "logIn btn" value="Log In">
                <input type="reset" name="Reset" value="Reset" /><br>
                <p> Create an Account <a href="signUp.php">Sign Up</a></p>
            </form>
        </div>
		<script type="text/javascript" src="../js/JavaScript.js"></script>
    </body>
</html>