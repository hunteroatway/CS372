var a;

if (a = document.getElementById("LogIn")){
    a.addEventListener("submit", LogInForm, false);
}

var b;

if ( b = document.getElementById("SignUp")){
    b.addEventListener("submit", SignUpForm, false);
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

    var q = elements[1].value;
    var t = elements[2].value;
    var w = elements[3].value;
    var x = elements[4].value;
    var e = elements[5].value;
    var r = elements[6].value;
    var h = elements[7].value;
    var j = elements[8].value;
    var i = elements[9].value;
    var k = elements[10].value;

    var result = true;

    var email_v = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var sname_v = /^[a-zA-Z0-9_-]+$/;
    var uname_v = /^[a-zA-Z '.-]*$/;
    var pswd_v = /^(\S*)?\d+(\S*)?$/;
    var bday_v = /^((0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2})*$/;
    var city_v = /^([a-zA-Z\u0080-\u024F]+(?:. |-| |'))*[a-zA-Z\u0080-\u024F]*$/;
    var province_v = /^[a-zA-Z '.,]*$/;;
    var country_v = /^[a-zA-Z '.,]*$/;;


    document.getElementById("email_msg").innerHTML = "";
    document.getElementById("sname_msg").innerHTML = "";
    document.getElementById("uname_msg").innerHTML = "";
    document.getElementById("name_msg").innerHTML = "";
    document.getElementById("pswd_msg").innerHTML = "";
    document.getElementById("pswdr_msg").innerHTML = "";
    document.getElementById("bday_msg").innerHTML = "";
    document.getElementById("city_msg").innerHTML = "";
    document.getElementById("province_msg").innerHTML = "";
    document.getElementById("country_msg").innerHTML = "";


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
   
    if (h == null || h == "" || !bday_v.test(h)) {
        document.getElementById("bday_msg").innerHTML = "Birth date is empty or invalid (Enter date in mm/dd/yyyy Or mm-dd-yyyy)";
        result = false;
    }

    if (j == null || j == "" || !city_v.test(j)) {
        document.getElementById("city_msg").innerHTML = "City is empty or invalid (Please enter the valid city)";
        result = false;
    }

    if (i == null || i == "" || !province_v.test(i)) {
        document.getElementById("province_msg").innerHTML = "Province is empty or invalid (Please enter the valid province)";
        result = false;
    }

    if (k == null || k == "" || !country_v.test(k)) {
        document.getElementById("country_msg").innerHTML = "Country is empty or invalid (Please enter the valid country)";
        result = false;
    }

    if (result == false) {
        event.preventDefault();
    }

}



/********************************************************************************************************************************************************/

function ValidateFileUpload() {
    var fuData = document.getElementById('Choosepic');
    var FileUploadPath = fuData.value;

    if (FileUploadPath == '') {
        alert("Please chosse a profile picture");

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

