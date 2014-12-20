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

$inputEmail = filter_input(INPUT_POST, "username");
$inputPassword = filter_input(INPUT_POST, "password");
$errorEmail = 0; //0 = No error, 1 = empty username, 2 = wrong username, 3 = forbidden symbols, 4 = deactivated account
$errorPassword = 0; //0 = No error, 1 = empty password, 2 = spaces occurring, 3 = wrong password
if($inputEmail !== NULL) {
    $succes = true;
    if(ltrim($inputEmail, ' ') == '') {
        $errorEmail = 1;
        $succes = false;
    } else if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL) && strtolower($inputEmail) != 'admin') {
        $errorEmail = 3;
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
        $klant = $link->query("SELECT klantID, actief FROM klant WHERE emailadres = '" . strtolower($inputEmail) . "'")->fetch_assoc();
        if($klant["klantID"] == false) {
            $errorEmail = 2;
        } else if($klant["actief"] == false) {
            $errorEmail = 4;
        } else {
            $password = $link->query("SELECT wachtwoord FROM klant WHERE klantID =" . $klant["klantID"])->fetch_assoc()["wachtwoord"];
            if($password != $inputPassword) {
                $errorPassword = 3;
            } else {
                $row = $link->query("SELECT * FROM klant WHERE klantID =" . $klant["klantID"])->fetch_assoc();
                $_SESSION["loggedin"] = true;
                $_SESSION["userID"] = $row["klantID"];
                $_SESSION["admin"] = $row["admin"];
                $_SESSION["voornaam"] = $row["voornaam"];
                $_SESSION["achternaam"] = $row["achternaam"];

                session_write_close();

                header('Location: /admin/');
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
                <h3>Emailadres</h3>
                <input type="text" name="username" <?php if($errorEmail > 0) {echo "class='error'";} if($inputEmail !== NULL) {echo "value='" . $inputEmail . "'";} ?> />
                <?php 
                    if($errorEmail == 1) {
                        echo "<h4 class='error'>Vul A.U.B. een gebruikersnaam in</h4>"; 
                    } else if($errorEmail == 2) {
                        echo "<h4 class='error'>Deze gebruiker bestaat niet</h4>"; 
                    } else if ($errorEmail == 3) {
                        echo "<h4 class='error'>Verboden tekens in gebruikersnaam</h4>";
                    } else if ($errorEmail == 4) {
                        echo "<h4 class='error'>Dit account is gedeactiveerd</h4>";
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
                <a href="wachtwoordreset.php" style="font-size:0.5rem;">Wachtwoord vergeten</a>
            </div>
            <input type="submit" style="display:none;">
        </form>
    <?php
    printFooter()
    ?>
</body>
</html>
