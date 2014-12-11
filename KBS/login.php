<!DOCTYPE html>

<?php
include_once("global.php");

$inputUsername = filter_input(INPUT_POST, "username");
$inputPassword = filter_input(INPUT_POST, "password");
$errorUsername = 0; //0 = No error, 1 = empty username, 2 = wrong username, 3 = forbidden symbols
$errorPassword = 0; //0 = No error, 1 = empty password, 2 = spaces occurring, 3 = wrong password
if($inputUsername !== NULL) {
    $succes = true;
    if(ltrim($inputUsername, ' ') == '') {
        $errorUsername = 1;
        $succes = false;
    } else if (preg_replace("/[^A-Za-z0-9 ]/", '', $inputUsername) != $inputUsername) {
        $errorUsername = 3;
        $succes = false;
    }

    if($inputPassword == NULL) {
        $errorPassword = 1;
        $succes = false;
    } else if(ltrim($inputPassword, ' ') == '') {
        $errorPassword = 2;
        $succes = false;
    }

    if($succes) {
        $klantID = $link->query("SELECT klantID FROM klant WHERE username = '" . strtolower($inputUsername) . "'")->fetch_assoc()["klantID"];
        if($klantID == false) {
            $errorUsername = 2;
        } else {
            $password = $link->query("SELECT wachtwoord FROM klant WHERE klantID =" . $klantID)->fetch_assoc()["wachtwoord"];
            if($password != $inputPassword) {
                $errorPassword = 3;
            } else {
                $row = $link->query("SELECT voornaam, achternaam FROM klant WHERE klantID =" . $klantID)->fetch_assoc();
                $_SESSION["loggedin"] = true;
                $_SESSION["admin"] = false;
                $_SESSION["voornaam"] = $row["voornaam"];
                $_SESSION["achternaam"] = $row["achternaam"];

                session_write_close();

                header('Location: /admin/');
            }
        }
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
                <input type="text" name="username" <?php if($errorUsername > 0) {echo "class='error'";} if($inputUsername !== NULL) {echo "value='" . $inputUsername . "'";} ?> />
                <?php 
                    if($errorUsername == 1) {
                        echo "<h4 class='error'>Vul A.U.B. een gebruikersnaam in</h4>"; 
                    } else if($errorUsername == 2) {
                        echo "<h4 class='error'>Deze gebruiker bestaat niet</h4>"; 
                    } else if ($errorUsername == 3) {
                        echo "<h4 class='error'>Verboden tekens in gebruikersnaam</h4>";
                    }
                ?>
                <h3>Wachtwoord</h3>
                <input type="password" name="password" <?php if($errorPassword > 0) {echo "class='error'";} ?>/>
                <?php 
                    if($errorPassword == 1) {
                        echo "<h4 class='error'>Vul A.U.B. uw wachtwoord in</h4>"; 
                    } else if($errorPassword == 2) {
                        echo "<h4 class='error'>Er mogen geen spaties in het wachtwoord voorkomen</h4>"; 
                    } else if($errorPassword == 3) {
                        echo "<h4 class='error'>Fout wachtwoord</h4>"; 
                    } 
                ?>
                <a role="button" onclick="login();" style="margin: 0.5rem;">Login</a>
            </div>
            <input type="submit" style="display:none;">
        </form>
    <?php
    printFooter()
    ?>
</body>
</html>
