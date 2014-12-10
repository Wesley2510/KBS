<!DOCTYPE html>

<?php
include_once("global.php");
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>TextBug - Login</title>
        
        <?php printStyles(); ?>
    </head>
    <body>
        <?php
        printHeader();
        ?>
        <form class="pageElement" name="form1" method="post" action="checkLogin.php">
            <div style="text-align:center">
                <h3>Gebruikersnaam</h3>
                <input type="text"/>
                <h3>Wachtwoord</h3>
                <input type="text"/>
                <a role="button">Login</a>
            </div>
        </form>
    <?php
    printFooter()
    ?>
</body>
</html>
