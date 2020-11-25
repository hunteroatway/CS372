<!-- 
  SAMPLE QUERIES
  ******************************************************************************************************************************************************
  INSERT INTO Books(isbn_10, isbn_13, title, subtitle, publisher, description, edition, photo)
  VALUES (1000000000, 1300000000001, "TestTitle", "TestSub", "pub", "test book", "8th", "../image/book_placeholder.jpg");
  // will need to get these values from the api, initially do a search for the isbn, if nothing, insert into the DB, otherwise continue to listing
  // image here is the image obtained from the google API. can simply use the URL given

  // INSERT INTO Authors(isbn_13, first_name, last_name)
  VALUES (1300000000001, "John", "Cena");
  // similar to above, get from api, if the book exists in the DB, these authors will as well, do not insert duplicate copies

  INSERT INTO Listings (isbn_10, isbn_13, uid, book_condition, price, list_date, active) 
  VALUES (1000000001, 1300000000001, 1, "good", 63.14, curdate(), true);
  // using the isbn values from the API, the current users ID and the populated fields

  // get the listing ID in order to add it to the images table
  // loop through all the images uploaded by the user
  // name them as {lid}_{1,2,3...}.{fileExtension} in order to keep it organized
  INSERT INTO Images(image, lid)
  VALUES("../images/testBook.png", 1);

  // after successful posting redirect to listing page with the LID in the url

 -->
<?php
    session_start();
?>
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
          <div class="switch-container">
            <label>Manual</label>
            <label class="switch">
              <input id="toggle-switch" type="checkbox" checked>
              <span class="slider round"></span>
            </label>
            <label>Automatic</label>
          </div>
          <button id="submit-posting" type="button">Submit</button>
        </form>
        <form id="manual-fields" style="visibility:hidden">
          <input id="title" type="text" placeholder="Title.." name="title" /> 
          <input id="subtitle" type="text" placeholder="Subtitle.." name="subtitle" /> 
          <input id="author" type="text" placeholder="Author.." name="author" /> 
          <input id="description" type="text" placeholder="Description.." name="description" /> 
          <input id="publisher" type="text" placeholder="Publisher.." name="publisher" /> 
          <input id="isbn-10" type="text" placeholder="ISBN-10.." name="isbn-10" /> 
          <input id="isbn-13" type="text" placeholder="ISBN-13.." name="isbn-13" /> 
        </form>
        </div>
        <form id="automatic-fields" style="visibility:visible">
          <label id="search_msg" class="err_msg"></label>
          <input id="isbn" type="text" placeholder="ISBN" name="isbn" /> 
          <button id="auto-fill" type="button">Auto-Fill</button> 
        </form>
      </div>
      <div class="book-info">
        <p id="auto-title">Title: </p>
        <p id="auto-subtitle">Subtitle: </p>
        <p id="auto-author">Author(s): </p>
        <p id="auto-description">Description: </p>
        <p id="auto-publisher">Publisher: </p>
        <img id="auto-cover"></img>
      </div>
    <script type="text/javascript" src="../js/JavaScript.js"></script>
    <script type="text/javascript" src="../js/search.js"></script>
    <script type="text/javascript" src="../js/search-r.js"></script>
    <script type="text/javascript" src="../js/location.js"></script>
    </body>
</html>
