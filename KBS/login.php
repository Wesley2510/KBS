<!DOCTYPE html>

<?php
include_once("global.php");

$inputUsername = filter_input(INPUT_POST, "username");
$inputPassword = filter_input(INPUT_POST, "password");
$errorUsername = 0; //0 = No error, 1 = empty username
if($inputUsername !== NULL) {
    if(ltrim($inputUsername, ' ') === '') {
        $errorUsername = 1;
    } else {
        header('Location: #');
    }
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>TextBug - Login</title>
        
        <?php printStyles(); printScripts(); ?>
    </head>
    <body>
        <?php
        printHeader();
        ?>
        <form class="pageElement" action='#' id='loginForm' method="post">
            <div style="display:flex;flex-direction:column;align-items:center;">
                <h3>Gebruikersnaam</h3>
                <input type="text" name="username" <?php if($errorUsername > 0) {echo "class='error'";} ?> />
                <?php 
                    if($errorUsername == 1) {
                        echo "<h4 class='error'>Vul A.U.B. een gebruikersnaam in</h4>"; 
                    }
                ?>
                <h3>Wachtwoord</h3>
                <input type="password" name="password"/>
                <a role="button" onclick="login();" style="margin: 0.5rem;">Login</a>
            </div>
        </form>
    <?php
    printFooter()
    ?>
</body>
</html>
