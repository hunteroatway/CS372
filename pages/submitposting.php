<!-- 
  SAMPLE QUERIES
  ******************************************************************************************************************************************************
  INSERT INTO Books(isbn_10, isbn_13, title, subtitle, publisher, description, photo)
  VALUES (1000000000, 1300000000001, "TestTitle", "TestSub", "pub", "test book", "../images/book_placeholder.jpg");
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
    $cover = trim($_POST["cover"]);
    
    // change ' to &#039
    $isbn10 = str_replace("'","&#039", $isbn10);
    $isbn13 = str_replace("'","&#039", $isbn13);
    $title = str_replace("'","&#039", $title);
    $subtitle = str_replace("'","&#039", $subtitle);
    $authors = str_replace("'","&#039", $authors);
    $description = str_replace("'","&#039", $description);
    $publisher = str_replace("'","&#039", $publisher);
    $condition = str_replace("'","&#039", $condition);
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
        $q3 .= "('$isbn13, $firstName, $lastName)";
      }

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
    $target_dir = "../avatar/";
    $fileNames = array_filter($_FILES['fileToUpload']['name']); 
    if(!empty($fileNames)){ 
      foreach($_FILES['fileToUpload']['name'] as $key=>$val){ 
        // value to give each image
        $inc = 1;
        
        $fileName = basename($_FILES['fileToUpload']['name'][$key]); 
        $targetFilePath = $targetDir . $fileName; 
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType ) {    
          // set error to this
          $error =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }        
        // Check file size
        if ($_FILES["fileToUpload"]["size"][$key] > 500000)
        {
            echo "Sorry, your file is too large.";
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
            $target_file = $target_dir . $lid . "_" . $inc . "." . $imageFileType;
            // upload the file
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$key], $target_file));
            
            // append to query
            $q6 .= "('$target_file', '$lid') ";
        }
        $inc++;
      }
      // input the images to the DB
      $r6 = $db->query($q6);
    }
    // if there is an error. inform the user
    else {
        $error .= "Please select a file to upload";
        $db->close();
    }
    // if querys work. send to the listing page
    if ($r2 == true && $r3 == true && $r4 == true && $r6 === true)
    {
        header("Location: listing.php?lid=" . $lid);
        $db->close();
        exit();
    }
  } 
  }
?>