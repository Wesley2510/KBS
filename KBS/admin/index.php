<!DOCTYPE html>

<?php
include_once '../global.php';

if(!isset($_SESSION["loggedin"])) {
    header("Location: /login.php");
    die();
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>TextBug - Admin</title>
        
        <?php printStyles(); ?>
        
        <style type="text/css">
            .pageElementButton {
                display: block;
                background-color: lightgrey;
                border:0.15rem solid black;
                text-align: center;
                color:black;
                cursor: pointer;
            }
            .pageElementButton:active {
                background-color: grey;
            }
            
            .link {
                text-decoration: none;
            }
        </style>
        <script type="text/javascript">
            var xmlhttp = new XMLHttpRequest();
            
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    if(xmlhttp.responseText == "true") {
                        window.location.href = "/login.php";
                    }
                }
            }
            
            function logout() {
                xmlhttp.open("POST", "/login.php", true);
                xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xmlhttp.send("logout=1");
            }
        </script>
    </head>
    <body>
    
    <?php printHeader(); ?>
        
        
        
    <?php 
    if($_SESSION["admin"] == true)
    {
        echo "<a href='klantoverzicht.php' class='link pageElement pageElementButton'><h2>Klantoverzicht</h2></a>";
        echo "<a href='menubeheer.php' class='link pageElement pageElementButton'><h2>Menubeheer</h2></a>";
        echo "<a href='bestandsbeheer.php' class='link pageElement pageElementButton'><h2>Bestandsbeheer</h2></a>";//
    } else {
        echo "<a href='factuuroverzicht.php' class='link pageElement pageElementButton'><h2>Fractuuroverzicht</h2></a>";
    }
    echo "<a onclick='logout();' class='link pageElement pageElementButton'><h2>Uitloggen</h2></a>";
    ?>
        
    <?php printFooter(); ?>
        
    </body>
</html>

