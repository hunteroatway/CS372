var a;

if (a = document.getElementById("LogIn")){
    a.addEventListener("submit", LogInForm, false);
}

var b;

if ( b = document.getElementById("SignUp")){
    b.addEventListener("submit", SignUpForm, false);
}


var managePage;

if ( managePage = document.getElementById("manage")){
    managePage.addEventListener("submit", managePageForm, false);
}

function LogInForm(event) {

    var elements = event.currentTarget;

    var a = elements[1].value;
    var b = elements[2].value;

    console.log(a);
    console.log(b);

    var result = true;

    var email_v = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var pswd_v = /^(\S*)?\d+(\S*)?$/;

    document.getElementById("email_msg").innerHTML = "";
    document.getElementById("pswd_msg").innerHTML = "";


    // email can not be empty or wrong format
    if (a == null || a == "" || !email_v.test(a)) {
        document.getElementById("email_msg").innerHTML = "Email is empty or invalid(example: email@gmail.com)";
        result = false;
    }


    if (b == null || b == "" || !pswd_v.test(b) || b.length < 8) {
        document.getElementById("pswd_msg").innerHTML = "Password is empty or invalid (Need to be 8 characters or longer,no space)";
        result = false;
    }

    // prevent form to be submitted if one of above field is invalid
    if (result == false) {
        event.preventDefault();
    }

}

/********************************************************************************************************************************************************/


function SignUpForm(event) {

    var elements = event.currentTarget;

    var q = elements[2].value;
    var t = elements[3].value;
    var w = elements[4].value;
    var x = elements[5].value;
    var e = elements[6].value;
    var r = elements[7].value;
    var h = elements[8].value;
    var j = elements[9].value;
    var i = elements[10].value;
    var k = elements[11].value;

    var result = true;

    var email_v = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var sname_v = /^[a-zA-Z0-9_-]+$/;
    var uname_v = /^[a-zA-Z '.-]*$/;
    var pswd_v = /^(\S*)?\d+(\S*)?$/;
    //var bday_v = /^((0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2})*$/; needs to be updated
    var city_v = /^([a-zA-Z\u0080-\u024F]+(?:. |-| |'))*[a-zA-Z\u0080-\u024F]*$/;
    var province_v = /^[a-zA-Z '.,]*$/;
    var country_v = /^[a-zA-Z '.,]*$/;


    document.getElementById("email_msg").innerHTML = "";
    document.getElementById("sname_msg").innerHTML = "";
    document.getElementById("uname_msg").innerHTML = "";
    document.getElementById("name_msg").innerHTML = "";
    document.getElementById("pswd_msg").innerHTML = "";
    document.getElementById("pswdr_msg").innerHTML = "";
    document.getElementById("bday_msg").innerHTML = "";
    document.getElementById("location_msg").innerHTML = "";

    if (q == null || q == "" || !email_v.test(q)) {
        document.getElementById("email_msg").innerHTML = "Email is empty or invalid(example: email@gmail.com)";
        result = false;
    }

    if (t == null || t == "" || !sname_v.test(t)) {
        document.getElementById("sname_msg").innerHTML = "User name is empty or invalid(no spaces or other non-word characters)";
        result = false;
    }

    if (w == null || w == "" || !uname_v.test(w)) {
        document.getElementById("uname_msg").innerHTML = "First name is empty or invalid(No spaces before or after)";
        result = false;
    }

    if (x == null || x == "" || !uname_v.test(x)) {
        document.getElementById("name_msg").innerHTML = "Last name is empty or invalid(No spaces before or after)";
        result = false;
    }

    if (e == null || e == "" || !pswd_v.test(e) || e.length < 8) {
        document.getElementById("pswd_msg").innerHTML = "Password is empty or invalid (Need to be 8 characters, and one non-letter)";
        result = false;
    }


    if (r == null || r == "" || !pswd_v.test(r) || r != e) {
        document.getElementById("pswdr_msg").innerHTML = "The confirmed password must match with the password above";
        result = false;
    }
   
    /* if (h == null || h == "" || !bday_v.test(h)) {
        document.getElementById("bday_msg").innerHTML = "Birth date is empty or invalid (Enter date in mm/dd/yyyy Or mm-dd-yyyy)";
        result = false;
    } */

    if (j == null || j == "" || !city_v.test(j) || i == null || i == "" || !province_v.test(i) || k == null || k == "" || !country_v.test(k)) {
        document.getElementById("location_msg").innerHTML = "Location is empty or invalid (Please enter a valid location)";
        result = false;
    } 

    if (result == false) {
        event.preventDefault();
    }

}



/********************************************************************************************************************************************************/

function managePageForm(event){

    var elements = event.currentTarget;
    var valid = true;

    // get the values from the form
    var changeAvatarCK = elements[2].checked;
    var firstCK = elements[4].checked;
    var lastCK = elements[6].checked;
    var pwCK = elements[8].checked;
    var locCK = elements[11].checked;
    var avatar = elements[1].value;
    var FN = elements[3].value;
    var LN = elements[5].value;
    var PW = elements[7].value;
    var PWC = elements[9].value;
    var city = elements[12].value;
    var province = elements[13].value;
    var country = elements[14].value;

    //regex variables
    var pswd_v = /^(\S*)?\d+(\S*)?$/;    
    var city_v = /^([a-zA-Z\u0080-\u024F]+(?:. |-| |'))*[a-zA-Z\u0080-\u024F]*$/;
    var province_v = /^[a-zA-Z '.,]*$/;
    var country_v = /^[a-zA-Z '.,]*$/;
    var nameREG = /^[a-z ,.'-]+$/;


    // perform regex on each field with input

    //ensure there is a file for avatar
    // test first name
    if(changeAvatarCK && (avatar == null || avatar == "" )){
        document.getElementById("avatar_msg").innerHTML = "Please upload an avatar";
        valid = false;
    } else {
        document.getElementById("avatar_msg").innerHTML = "";
    }

    // test first name
    if(firstCK && (FN == null || FN == "" || !nameREG.test(FN))){
        document.getElementById("fname_msg").innerHTML = "First name is empty or invalid(No spaces before or after)";
        valid = false;
    } else {
        document.getElementById("fname_msg").innerHTML = "";
    }

    // test last name
    if(lastCK && (LN == null || LN == "" || !nameREG.test(LN))){
        document.getElementById("lname_msg").innerHTML = "Last name is empty or invalid(No spaces before or after)";
        valid = false;
    } else {
        document.getElementById("lname_msg").innerHTML = "";
    }

    // test password
    if (pwCK && (PW == null || PW == "" || !pswd_v.test(PW) || PW.length < 8)) {
        document.getElementById("pswd_msg").innerHTML = "Password is empty or invalid (Need to be 8 characters, and one non-letter)";
        valid = false;
    } else {
        document.getElementById("pswd_msg").innerHTML = "";
    }
    if (pwCK && (PWC == null || PWC == "" || !pswd_v.test(PWC) || PWC != PW)) {
        document.getElementById("pswdr_msg").innerHTML = "The confirmed password must match with the password above";
        valid = false;
    } else {
        document.getElementById("pswdr_msg").innerHTML = "";
    }
    
    //test location
    if(locCK && (city == null || province == null || country == null || city == "" || province == "" || country == "" || !city_v.test(city) || !province_v.test(province) || !country_v.test(country))){
        document.getElementById("location_msg").innerHTML = "Please enter a valid location";
        valid = false;
    } else {
        document.getElementById("location_msg").innerHTML = "";
    }

    // if validate fails, do not send form
    if (valid == false) {
        console.log("lol");
        event.preventDefault();
    }

}


/********************************************************************************************************************************************************/

function ValidateFileUpload() {
    var fuData = document.getElementById('fileToUpload');
    var FileUploadPath = fuData.value;

    if (FileUploadPath == '') {
        //alert("Please chosse a profile picture");
        console.log("not done");

    } else {
        var Extension = FileUploadPath.substring(
            FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

        if (Extension == "gif" || Extension == "png" || Extension == "jpeg" || Extension == "jpg") {

            if (fuData.files && fuData.files[0]) {
                var reader = new FileReader();
            }
        } else {
            alert("Photo only allows file types of GIF, PNG, JPG, and JPEG.");

        }
    }
}


//Function for clickable divs in the search results page
function clickableSearch(lid){
    window.location = 'listing.php?lid=' + lid;
}