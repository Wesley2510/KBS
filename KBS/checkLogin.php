<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->



<?php
include_once("global.php");
$host = "localhost"; // Host name 
$username = ""; // Mysql username 
$password = ""; // Mysql password 
$db_name = "texbug"; // Database name 
$tbl_name = "user"; // Table name 
// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

// username and password sent from form 
$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);
$sql = "SELECT * FROM $tbl_name WHERE username='$myusername' and wachtwoord='$mypassword'";
$result = mysql_query($sql);

// Mysql_num_row is counting table row
$count = mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if ($count == 1) {

// Register $myusername, $mypassword and redirect to file "login_success.php"
    session_register("myusername");
    session_register("mypassword");
    header("location:loginSuccess.php");
} else {
    echo "ongeldige gebruikersnaam of wachtwoord";
}
?>