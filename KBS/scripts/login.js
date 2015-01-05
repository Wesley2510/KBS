"use strict";

function login() {
    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            if(xmlhttp.responseText == "true") {
                window.location.replace('/admin/');
            }
            
            var response = JSON.parse(xmlhttp.responseText);
            if(response.errorEmail !== undefined) {
                document.getElementById("nameError").innerHTML = response.errorEmail;
                document.getElementById("inputUserName").setAttribute("class", "error");
            } else {
                document.getElementById("nameError").innerHTML = "";
                document.getElementById("inputUserName").setAttribute("class", "");
            }
            
            if(response.errorPassword !== undefined) {
                document.getElementById("passwordError").innerHTML = response.errorPassword;
                document.getElementById("inputPassword").setAttribute("class", "error");
            } else {
                document.getElementById("passwordError").innerHTML = "";
                document.getElementById("inputPassword").setAttribute("class", "");
            }
        }
    }
    
    xmlhttp.open("POST", "/login.php", true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("username=" + document.getElementById("inputUserName").value + "&password=" + document.getElementById("inputPassword").value);
}

var checkMail = function () {
    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var response = JSON.parse(xmlhttp.responseText);
            if(response.errorEmail !== undefined) {
                document.getElementById("nameError").innerHTML = response.errorEmail;
                document.getElementById("inputUserName").setAttribute("class", "error");
            } else {
                document.getElementById("nameError").innerHTML = "";
                document.getElementById("inputUserName").setAttribute("class", "");
            }
            
//            if(response.errorPassword !== undefined) {
//                document.getElementById("passwordError").innerHTML = response.errorEmail;
//                document.getElementById("inputPassword").setAttribute("class", "error");
//            } else {
//                document.getElementById("passwordError").innerHTML = "";
//                document.getElementById("inputPassword").setAttribute("class", "");
//            }
        }
    }
    
    xmlhttp.open("POST", "/login.php", true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("username=" + document.getElementById("inputUserName").value);
};

document.getElementById("inputUserName").addEventListener("blur", checkMail);