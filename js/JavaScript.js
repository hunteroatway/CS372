var loginPage;

if (loginPage = document.getElementById("LogIn")){
    loginPage.addEventListener("submit", LogInForm, false);
}

var signUpPage;

if ( signUpPage = document.getElementById("SignUp")){
    signUpPage.addEventListener("submit", SignUpForm, false);
}


var managePage;

if ( managePage = document.getElementById("manage")){
    managePage.addEventListener("submit", managePageForm, false);
}


/**************************************************************************************************************/
function LogInForm(event) {

    var elements = event.currentTarget;

    // get the values from the form
    var email = elements[1].value;
    var password = elements[2].value;

    var result = true;

    // variables for the reg ex
    var email_v = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var pswd_v = /^(\S*)?\d+(\S*)?$/;


    // get the locations for the error messages
    document.getElementById("email_msg").innerHTML = "";
    document.getElementById("pswd_msg").innerHTML = "";


    // email can not be empty or wrong format
    if (email == null || email == "" || !email_v.test(email)) {
        document.getElementById("email_msg").innerHTML = "Email is empty or invalid(example: email@gmail.com)";
        result = false;
    }

    // password can not be empty
    if (password == null || password == "" || !pswd_v.test(password) || password.length < 8) {
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

    // get the values from the form
    var picupload = elements[1].value;
    var email = elements[2].value;
    var uname = elements[3].value;
    var firstName = elements[4].value;
    var lastName = elements[5].value;
    var password = elements[6].value;
    var passwordConfirm = elements[7].value;
    var DOB = elements[8].value;
    var city = elements[10].value;
    var province = elements[11].value;
    var country = elements[12].value;

    var result = true;

    // variables for the reg ex
    var picupload_v = /\.(gif|jpe?g|png)$/i;
    var email_v = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var sname_v = /^[a-zA-Z0-9_-]+$/;
    var uname_v = /^[a-zA-Z '.-]*$/;
    var pswd_v = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}/;
    var bday_v = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
    var city_v = /^([a-zA-Z\u0080-\u024F]+(?:. |-| |'))*[a-zA-Z\u0080-\u024F]*$/;
    var province_v = /^[a-zA-Z '.,]*$/;
    var country_v = /^[a-zA-Z '.,]*$/;

    // get the locations for the error messages
    document.getElementById("picupload_msg").innerHTML = "";
    document.getElementById("email_msg").innerHTML = "";
    document.getElementById("sname_msg").innerHTML = "";
    document.getElementById("uname_msg").innerHTML = "";
    document.getElementById("name_msg").innerHTML = "";
    document.getElementById("pswd_msg").innerHTML = "";
    document.getElementById("pswdr_msg").innerHTML = "";
    document.getElementById("bday_msg").innerHTML = "";
    document.getElementById("location_msg").innerHTML = "";

    // test the avatar upload, if invalid print message
    if (picupload == null || picupload == "" || !picupload_v.test(picupload)) {
        document.getElementById("picupload_msg").innerHTML = "Please upload an avatar (Must be .Jpg, .Jpeg, .Png, or .gif file)";
        result = false;
    }


    // test the email, if invalid print message
    if (email == null || email == "" || !email_v.test(email)) {
        document.getElementById("email_msg").innerHTML = "Email is empty or invalid(example: email@gmail.com)";
        result = false;
    }

    // test the username, if invalid print message
    if (uname == null || uname == "" || !sname_v.test(uname)) {
        document.getElementById("sname_msg").innerHTML = "User name is empty or invalid(no spaces or other non-word characters)";
        result = false;
    }

    // test the firstname, if invalid print message
    if (firstName == null || firstName == "" || !uname_v.test(firstName)) {
        document.getElementById("uname_msg").innerHTML = "First name is empty or invalid(No spaces before or after)";
        result = false;
    }

    // test the lastname, if invalid print message
    if (lastName == null || lastName == "" || !uname_v.test(lastName)) {
        document.getElementById("name_msg").innerHTML = "Last name is empty or invalid(No spaces before or after)";
        result = false;
    }

    // test the password, if invalid print message
    if (password == null || password == "" || !pswd_v.test(password) || password.length < 8) {
        document.getElementById("pswd_msg").innerHTML = "Password is empty or invalid (Need to be 8 characters, at least 1 lowercase, 1 uppercase and 1 number)";
        result = false;
    }

    // test the confirm password, if invalid print message
    if (passwordConfirm == null || passwordConfirm == "" || !pswd_v.test(passwordConfirm) || passwordConfirm != password) {
        document.getElementById("pswdr_msg").innerHTML = "The confirmed password must match with the password above";
        result = false;
    }
   
    // test the DOB, if invalid print message
    if (DOB == null || DOB == "" || !bday_v.test(DOB)) {
        document.getElementById("bday_msg").innerHTML = "Birth date is empty or invalid (Enter date in yyyy-mm-dd)";
        result = false;
    } 

    //test the location, if invalid print message
    if(city == null || province == null || country == null || city == "" || province == "" || country == "" || !city_v.test(city) || !province_v.test(province) || !country_v.test(country)){
        document.getElementById("location_msg").innerHTML = "Please enter a valid location";
        valid = false;
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
    var avatar_v = /\.(gif|jpe?g|png)$/i;
    var pswd_v = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}/;    
    var city_v = /^([a-zA-Z\u0080-\u024F]+(?:. |-| |'))*[a-zA-Z\u0080-\u024F]*$/;
    var province_v = /^[a-zA-Z '.,]*$/;
    var country_v = /^[a-zA-Z '.,]*$/;
    var nameREG = /^[a-zA-Z '.-]*$/;

    // perform regex on each field with input

    //ensure there is a file for avatar
    // test first name
    if(changeAvatarCK && (avatar == null || avatar == "" || !avatar_v.test(avatar))){
        document.getElementById("avatar_msg").innerHTML = "Please upload an avatar (Must be .Jpg, .Jpeg, .Png, or .gif file)";
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
        document.getElementById("pswd_msg").innerHTML = "Password is empty or invalid (Need to be 8 characters, at least 1 lowercase, 1 uppercase and 1 number)";
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
        event.preventDefault();
    }

}

/********************************************************************************************************************************************************/

//Function for clickable divs in the search results page
function clickableSearch(lid){
    window.location = 'listing.php?lid=' + lid;
}