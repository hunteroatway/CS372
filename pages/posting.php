<!DOCTYPE html>
<html>
  <head>
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel = "stylesheet" type = "text/css" href = "../css/myStyle.css" />
    <style>
      .err_msg { color:red; }
    </style>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.3/leaflet.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.css?v=0.1.7">
    <script src="https://maps.locationiq.com/v2/libs/leaflet-geocoder/1.9.6/leaflet-geocoder-locationiq.min.js?v=0.1.7"></script>
    </head>
    <title>Pick-a-Book</title> 
    </head>
    <header>
	    <img src="../images/logo.png" style = "display:inline" width = "250" height = "200" />
    </header>
    <body>
    <div class="topnav" id="pac-card">
            <a class="active" href="index.php">Home <i class="fa fa-fw fa-home"> </i></a>
        <?php 
            // if logged in
            if(isset($_SESSION["username"])) {
        ?>


            <a href="posting.php">Post Ad <i class="fa fa-book"></i></a>
            <a href="profile.php">Profile <i class="fa fa-user"></i></a>
            <a href="logout.php">LogOut <i class="fa fa-sign-out"></i></a></a>

        <?php
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
    
      <div class="type-selection">
        <form id="create-posting">
            <label>Manual</label>
            <label class="switch">
              <input id="toggle-switch" type="checkbox" checked>
              <span class="slider round"></span>
            </label>
            <label>Automatic</label>
          </div>
				</form>
					<form id="submit-form" class="input" action="submitposting.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="submitted" value="1">
            
						<label>ISBN:</label>
          	<input id="isbn" type="text" placeholder="ISBN.." name="isbn" />
						<button id="auto-fill" type="button">Auto-Fill</button>
            <label id="isbn_err" class="err_msg"></label><br />

            <input type="hidden" id="isbn-10" name="isbn-10">
            <input type="hidden" id="isbn-13" name="isbn-13">

						<label>Title:</label>
						<input id="title" type="text" placeholder="Title.." name="title" readonly/>
            <label id="title_err" class="err_msg"></label><br />

						<label>Subtitle:</label>
						<input id="subtitle" type="text" placeholder="Subtitle.." name="subtitle" readonly>
            <label id="subtitle_err" class="err_msg"></label><br />

						<label>Author:</label>
						<input id="author" type="text" placeholder="Author.." name="author" readonly/>
            <label id="author_err" class="err_msg"></label><br />

						<label>Description:</label>
						<input id="description" type="text" placeholder="Description.." name="description" readonly/>
            <label id="description_err" class="err_msg"></label><br />

						<label>Publisher:</label>
						<input id="publisher" type="text" placeholder="Publisher.." name="publisher" readonly/>
            <label id="publisher_err" class="err_msg"></label><br />

            <label>Price:</label>
            <input id="price" type="number" placeholder="Price.." name="price" step="0.01" min="0"/>
            <label id="price_err" class="err_msg"></label><br />

            <label>Condition:</label>
            <select id="condition" name="condition">
              <option value="good">Good</option>
              <option value="fair">Fair</option>
              <option value="poor">Poor</option>
            </select><br />

						<label>Upload Images:</label>
            <input type="file" name="files[]" multiple ><br />

						<img id="cover" src=""></img><br />
            <input type="hidden" id="cover-link" name="cover-link">
            <button id="submit-posting" type="submit">Submit</button>
					</form>
      </div>
    <script type="text/javascript" src="../js/JavaScript.js"></script>
    <script type="text/javascript" src="../js/googlebooksapi.js"></script>
    <script type="text/javascript" src="../js/googlebooksapi-r.js"></script>
    <script type="text/javascript" src="../js/location.js"></script>
    </body>
</html>
