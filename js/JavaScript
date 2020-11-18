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

    var a = elements[0].value;
    var b = elements[1].value;


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

    var q = elements[0].value;
    var t = elements[1].value;
    var w = elements[2].value;
    var x = elements[3].value;
    var e = elements[4].value;
    var r = elements[5].value;

    var result = true;

    var email_v = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var sname_v = /^[a-zA-Z0-9_-]+$/;
    var uname_v = /^[a-zA-Z0-9_-]+$/;
    var pswd_v = /^(\S*)?\d+(\S*)?$/;


    document.getElementById("email_msg").innerHTML = "";
    document.getElementById("sname_msg").innerHTML = "";
    document.getElementById("uname_msg").innerHTML = "";
    document.getElementById("name_msg").innerHTML = "";
    document.getElementById("pswd_msg").innerHTML = "";
    document.getElementById("pswdr_msg").innerHTML = "";


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
        document.getElementById("pswdr_msg").innerHTML = "The confirmed password should match with he password above";
        result = false;
    }

    if (result == false) {
        event.preventDefault();
    }

}




