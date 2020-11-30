 <?php
  session_start();

  //get the uid
  $uid = $_SESSION["uid"];
  if (isset($_POST["submitted"]) && $_POST["submitted"]) {
    // get the values from the form
    $isbn10 = trim($_POST["isbn-10"]);
    $isbn13 = trim($_POST["isbn-13"]);
    $title = trim($_POST["title"]);
    $subtitle = trim($_POST["subtitle"]);
    $authorList = trim($_POST["author"]);
    $description = trim($_POST["description"]);
    $publisher = trim($_POST["publisher"]);
    $condition = trim($_POST["condition"]);    
    $price = trim($_POST["price"]);
    $cover = trim($_POST["cover-link"]);
    
    // change ' to &#039
    $isbn10 = str_replace("'","&#039", $isbn10);
    $isbn13 = str_replace("'","&#039", $isbn13);
    $title = str_replace("'","&#039", $title);
    $subtitle = str_replace("'","&#039", $subtitle);
    $authors = str_replace("'","&#039", $authors);
    $description = str_replace("'","&#039", $description);
    $publisher = str_replace("'","&#039", $publisher);
    $condition = str_replace("'","&#039", $condition);
    $cover = str_replace("'","&#039", $cover);
    $price = str_replace("'","&#039", $price);

    // connect to DB and check connection
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection failed: " . $db->connect_error);
    }

    // check to see if the book is in the DB
    $q1 = "SELECT B.isbn_13 FROM Books B WHERE B.isbn_13 = '$isbn13' OR B.isbn_10 = '$isbn10'";
    $r1 = $db->query($q1);

    // if number of rows == 0 add book. otherwise go to adding the listing
    if($r1->num_rows == 0) {

      // add the book to the DB
      $q2 = "INSERT INTO Books(isbn_10, isbn_13, title, subtitle, publisher, description, photo) VALUES ('$isbn10', '$isbn13', '$title', '$subtitle', '$publisher', '$description', '$cover')";
      $r2 = $db->query($q2);

      // split the authors up based off ", "
      $authors = explode(", ", $authorList);
      // go through the list of authors and seperate via first/ lastname and upload into DB
      // prep query
      $q3 = "INSERT INTO Authors(isbn_13, first_name, last_name) VALUES ";
      foreach ($authors as $value){
        // get first and last name
        list($firstName, $lastName) = explode(" ", $value);
        // append to query
        $q3 .= "('$isbn13', '$firstName', '$lastName'), ";
      }
      
      $q3 = substr($q3, 0, -2);
      // insert all authors into DB
      $r3 = $db->query($q3);
    }

    // create query for listing, and insert into DB
    $q4 = "INSERT INTO Listings (isbn_10, isbn_13, uid, book_condition, price, list_date, active) VALUES ('$isbn10', '$isbn13', '$uid', '$condition', '$price', curdate(), true)"; 
    $r4 = $db->query($q4); 
    //get the lid
    $q5 = "SELECT L.lid FROM Listings L WHERE uid = '$uid' AND isbn_13 = '$isbn13' ORDER BY L.lid DESC";
    $r5 = $db->query($q5); 
    $lidQ = $r5->fetch_assoc();
    $lid = $lidQ["lid"];
    //prep query
    $q6 = "INSERT INTO Images(image, lid) VALUES ";
    // loop through all the images uploaded and upload them to the DB
    $target_dir = "../images/";
    $fileNames = $_FILES['files']['name']; 
    if(!empty($fileNames)){ 
      $inc = 1;
      foreach($_FILES['files']['name'] as $key=>$val){ 
        // value to give each image
        $uploadOk = 1;
        $fileName = basename($_FILES['files']['name'][$key]); 
        $targetFilePath = $targetDir . $fileName; 
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
        // Allow certain file formats
        if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" && $fileType ) {    
          // set error to this
          $error .=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }        
        // Check file size
        if ($_FILES["files"]["size"][$key] > 500000)
        {
            $error .= "Sorry, your file is too large.";
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
            $target_file = $target_dir . $lid . "_" . $inc . "." . $fileType;
            // upload the file
            if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $target_file));
            
            // append to query
            $q6 .= "('$target_file', '$lid'), ";
        }
        $inc++;
      }
      // remove extra comma from query
      $q6 = substr($q6, 0, -2);
      // input the images to the DB
      $r6 = $db->query($q6);
    } 
    // if there is an error. inform the user
    else {
        $error .= "Please select a file to upload";
        $db->close();
    }
    // if querys work. send to the listing page
    if ($r4 == true && $r6 === true)
    {
        header("Location: listing.php?lid=" . $lid);
        $db->close();
        exit();
    }
  } 
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
            //if not logged in direct user to logon page.
            } else {

                /* Redirect user to login page */
                header("Location: Login.php"); 
                exit();
        }?>

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
    
            
          <table class="formTable">
					  <form id="submit-form" class="input" action="posting.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="submitted" value="1">
              <tr>
                <td><label>ISBN:</label></td>
                <td><input id="isbn" type="text" placeholder="ISBN.." name="isbn" /><button id="auto-fill" type="button">Auto-Fill</button><div class = "tooltip"><i class="fa fa-info-circle" id = "isbnToolTip" aria-hidden="true">  <span class="tooltiptext">The ISBN value can either be found on the back of the book near the barcode or on the inside cover near the publication information. It will be either 10 or 13 digits long. Enter those digits without the dashes (EX: ISBN-13: 9781565926103)</span></i></div></td>
                <td><label id="isbn_err" class="err_msg"></label></td>
              </tr>

              <input type="hidden" id="isbn-10" name="isbn-10">
              <input type="hidden" id="isbn-13" name="isbn-13">

              <tr>
                <td><label>Title:</label></td>
                <td><input id="title" type="text" placeholder="Title.." name="title" readonly/></td>
                <td><label id="title_err" class="err_msg"></label></td>
              </tr>

              <tr>
                <td><label>Subtitle:</label></td>
                <td><input id="subtitle" type="text" placeholder="Subtitle.." name="subtitle" readonly></td>
                <td><label id="subtitle_err" class="err_msg"></label></td>
              </tr>

              <tr>
                <td><label>Author:</label></td>
                <td><input id="author" type="text" placeholder="Author.." name="author" readonly/></td>
                <td><label id="author_err" class="err_msg"></label></td>
              </tr>

              <tr>
                <td><label>Description:</label></td>
                <td><textarea rows="5" cols="69" id="description" type="text" placeholder="Description.." name="description" readonly></textarea></td>
                <td><label id="description_err" class="err_msg"></label></td>
              </tr>

              <tr>
                <td><label>Publisher:</label></td>
                <td><input id="publisher" type="text" placeholder="Publisher.." name="publisher" readonly/></td>
                <td><label id="publisher_err" class="err_msg"></label></td>
              </tr>

              <tr>
                <td><label>Price:</label></td>
                <td><input id="price" type="number" placeholder="Price.." name="price" step="0.01" min="0"/></td>
                <td><label id="price_err" class="err_msg"></label></td>
              </tr>

              <tr>
                <td><label>Condition:</label></td>
                <td><select id="condition" name="condition">
                  <option value="excellent">Excellent</option>
                  <option value="good">Good</option>
                  <option value="fair">Fair</option>
                  <option value="poor">Poor</option>
                </select></td>
              </tr>

              <tr>
                <td><label>Upload Images:</label></td>
                <td><input type="file" name="files[]" multiple ></td>
              </tr>

              <img id="cover" src=""></img><br />
              <input type="hidden" id="cover-link" name="cover-link">

              <tr>
                <td></td><td><button id="submit-posting" type="submit">Submit</button></td><td><p class = "err_msg"><?=$error?></td>
              </tr>

              </form>
            </table>
          
          <div class="type-selection">
            <form id="create-posting">
              <label>Manual</label>
              <label class="switch">
                <input id="toggle-switch" type="checkbox" checked>
                <span class="slider round"></span>
              </label>
              <label>Automatic</label>
            </form>
          </div>

      </div>
    <script type="text/javascript" src="../js/JavaScript.js"></script>
    <script type="text/javascript" src="../js/googlebooksapi.js"></script>
    <script type="text/javascript" src="../js/googlebooksapi-r.js"></script>
    <script type="text/javascript" src="../js/location.js"></script>
    </body>
</html>
