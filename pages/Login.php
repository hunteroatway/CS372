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
            $db = mysqli_connect("localhost", "username", "password", "username");

            if($_SERVER["REQUEST_METHOD"] == "POST"){

                $email = mysqli_real_escape_string($db,$_POST['email']);
                $password = mysqli_real_escape_string($db,$_POST['pswd']);
                $sql = "SELECT * FROM Users WHERE email = '$email' and password = '$password'";
                $result = mysqli_query($db,$sql);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $active = $row['active'];
                $count = mysqli_num_rows($result);

                if($count == 1) {
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    header("location: profile.php");
                }
				else {
					echo "Your Login Name or Password is invalid";
					echo "This page will be redirected to logIn page.";
				}
			}
			?>
        <div class="topnav">
                <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
                <a href="signUp.php">SignUp <i class="fa fa-user"> </i></a>
                <a href=".html">Manage</a>
                <a href=".html">Book <i class="fa fa-book"> </i></a>
                <div class="search-container">
                    <form action="/action_page.php">
                        <input type="text" placeholder="City.." name="city">
                        <input type="text" placeholder="Search.." name="search">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                </div>
            <div class="login">
                <h1>Log In</h1>
                <form id="LogIn" action="login.php" method="POST" enctype="multipart/form-data">
                        <table>
                            <tr><td></td><td><label id="email_msg" class="err_msg"></label></td></tr>
                            <tr><td>Email: </td><td> <input type="text" name="email" size="30" /></td></tr>
                            <tr><td></td><td><label id="pswd_msg" class="err_msg"></label></td></tr>
                            <tr><td>Password: </td><td> <input type="password" name="password" size="30" /></td></tr>  
                        </table>
                        <br>
                        <button type="Login" class= "btn">Log in</button>
                        <input type="reset" name="Reset" value="Reset" /><br>
                        <p> Create an Account <a href="signUp.php">Sign Up</a></p>
                </form>
            </div>
                <script type="text/javascript" src="../js/JavaScript.js"></script>
                </body>
            </html>