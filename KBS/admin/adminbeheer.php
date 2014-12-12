<!DOCTYPE html>

<?php
include_once("../global.php");

if(!isset($_SESSION["loggedin"])) {
    header("Location: /login.php");
    die();
} else if ($_SESSION["admin"] == false) {
    header("Location: /admin/");
    die();
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        
        <title>Textbug - Adminbeheer</title>
        <?php printStyles(); ?>
        <script src='/scripts/adminbeheer.js' type='text/javascript' charset='utf-8'></script>
    </head>
    <body>
    <?php printHeader(); ?>
        
    <div id="newAdminElement" class="pageElement flexRowSpace"><a role="button" onclick="createNewAdmin()">Nieuwe administrator</a></div>
    
    <?php 
    $admins = $link->query("SELECT adminID, voornaam, achternaam, emailadres FROM admin");
    while($admin = $admins->fetch_assoc()) {
        echo "<div id='admin" . $admin["adminID"] . "' class='pageElement flexRowSpace'><h2 id='naam" . $admin["adminID"] . "'>";
        echo $admin["voornaam"] . " " . $admin["achternaam"] . "</h2><h3 id='email" . $admin["adminID"] . "'>";
        echo $admin["emailadres"] . "<h3>";
        echo "<img class='iconEdit' src='../imgs/pencil1.svg' alt='icoon-bewerken' onclick='editAdminData(" . $admin["adminID"] . ")' /></div>";
        echo "</div>";
    }
    ?>
    
    <?php printFooter(); ?>
    </body>
</html>