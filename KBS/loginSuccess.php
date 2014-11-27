<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once("global.php");
session_start();
if (!session_is_registered(myusername)) {
    header("location:login.php");
}
?>

<html>
    <body>
        Login Successful
    </body>
</html>