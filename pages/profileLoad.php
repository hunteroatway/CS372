<?php
    // connect to database
    $db = new mysqli("localhost", "ottenbju", "Passw0rd", "ottenbju");
    if ($db->connect_error)
    {
        die ("Connection to database failed: " . $db->connect_error);
    }

    $sortMethod = $_GET["sort"];
    $uid = $_GET["uid"];

    //Convert the value from the selection tag into what will be changed in the SQL query
    switch ($sortMethod){
        case "postDesc":
            $sort = "L.list_date DESC";
            break;
        case "titleAsc":
            $sort = "B.title ASC";
            break;
        case "titleDesc":
            $sort = "B.title DESC";
            break;
        default:
        case "postAsc":
            $sort = "L.list_date ASC";
            break;    
    }
    

    // query to get users listings
    $q1 = "SELECT L.lid, L.isbn_10, L.isbn_13, L.price, L.list_date, A.first_name, A.last_name, B.title, U.city, U.province, U.country
    FROM Listings L INNER JOIN Books B 
    ON L.isbn_13 = B.isbn_13 INNER JOIN Authors A
    ON B.isbn_13 = A.isbn_13 INNER JOIN Users U
    ON L.uid = U.uid
    WHERE L.active = true AND L.uid = '$uid'
    ORDER BY $sort, L.lid, A.last_name;";

    $r1 = $db->query($q1);
    
    //Generate the listings for each listing in the search result
    //Has to incorporate the fact that there may be multiple tuples for when there is more than one author of a book
    $currentRow = $r1->fetch_assoc();
    $multipleAuthors = false;
    for($i = 0; $i < $r1->num_rows; $i++) {
        
        $nextRow = $r1->fetch_assoc();

        if ($currentRow["lid"] == $nextRow["lid"]) {
            //Determine if the current row is the same listing but different author
            if ($multipleAuthors == false) {
                $author = $currentRow["last_name"] . ", " . $currentRow["first_name"] . " ...";
                $multipleAuthors = true;
            }
        } else {
            $lid = $currentRow["lid"];
            //Query to get image for the book
            $q2 = "SELECT image FROM Images WHERE lid = '$lid'";
            $r2 = $db->query($q2);
            $row = $r2->fetch_assoc();

            //Prep info to be shown in listing
            $image = $row["image"]; 
            $title = $currentRow["title"];                        
            $isbn13 = $currentRow["isbn_13"];
            $price = $currentRow["price"];
            $location = $currentRow["city"] . ", " . $currentRow["province"] . ", " . $currentRow["country"];

            if ($multipleAuthors == false) {
                $author = $currentRow["last_name"] . ", " . $currentRow["first_name"];
            }

            $multipleAuthors = false;
            $currentRow = $nextRow;

?>

<div onclick="clickableSearch(<?=$lid?>)" class="post clickable">
    <img class="postImage" src="<?=$image?>" width="200" height="220" alt="Book Image"/>
    <div class="postInfo">
        <p class="overflow"><?=$title?></p>
        <p class="overflow"><?=$author?></p>
        <p>ISBN: <?=$isbn13?></p>
        <p>$<?=$price?></p>
        <p><?=$location?></p>
    </div>
</div>

<?php
        }
    }
    $db->close();
?>