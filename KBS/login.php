<?php
include_once("global.php");

$logout = filter_input(INPUT_POST, "logout");
if(isset($logout)) {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    if(!session_destroy()) {
        trigger_error("Kon sessie niet beeindigen.");
    } else {
        echo "true";
        die();
    }
}

if(isset($_SESSION["loggedin"])) {
    header('Location: /admin/');
    die();
}

$inputUsername = filter_input(INPUT_POST, "username", FILTER_SANITIZE_ENCODED);
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

    if($inputPassword == NULL || ltrim($inputPassword, ' ') == '') {
        $errorPassword = 1;
        $succes = false;
    } else if(strpos($inputPassword, ' ') !== false) {
        $errorPassword = 2;
        $succes = false;
    }

    if($succes) {
        //Admin login
        if($inputUsername == "admin" || substr_count($inputUsername, ' ') > 0){
            $firstSpacePos = strpos($inputUsername, ' ');
            if($inputUsername == "admin") {
                $voornaam = "admin";
                $achternaam = "";
            } else {
                $voornaam = substr($inputUsername, 0, $firstSpacePos);
                $achternaam = substr($inputUsername, $firstSpacePos + 1);
            }
            
            $adminID = $link->query("SELECT adminID FROM admin WHERE LOWER(voornaam) = '" . strtolower($voornaam) . "' AND LOWER(achternaam) = '" . strtolower($achternaam) . "'")->fetch_assoc()["adminID"];
            if($adminID == false) {
                $errorUsername = 2;
            } else {
                $password = $link->query("SELECT wachtwoord FROM admin WHERE adminID =" . $adminID)->fetch_assoc()["wachtwoord"];
                if($password != $inputPassword) {
                    $errorPassword = 3;
                } else {
                    $row = $link->query("SELECT voornaam, achternaam FROM admin WHERE adminID =" . $adminID)->fetch_assoc();
                    $_SESSION["loggedin"] = true;
                    $_SESSION["admin"] = true;
                    $_SESSION["voornaam"] = $row["voornaam"];
                    $_SESSION["achternaam"] = $row["achternaam"];

                    session_write_close();

                    header('Location: /admin/');
                }
            }
        } else { //Klant login
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
}

?>

<!DOCTYPE html>
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
                <a href="vergeten.php?type=gebruikersnaam" style="font-size:0.5rem;">Gebruikersnaam vergeten</a><br/>
                <a href="vergeten.php?type=wachtwoord" style="font-size:0.5rem;">Wachtwoord vergeten</a>
            </div>
            <input type="submit" style="display:none;">
        </form>
    <?php
    printFooter()
    ?>
</body>
</html>
