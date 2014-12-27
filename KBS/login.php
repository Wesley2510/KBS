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

$emailErrors = array();
$emailErrors[1] = "Vul A.U.B. een emailadres in"; 
$emailErrors[2] = "Deze gebruiker bestaat niet"; 
$emailErrors[3] = "Dit is geen geldig emailadres";
$emailErrors[4] = "Dit account is gedeactiveerd";

$passwordErrors = array();
$passwordErrors[1] = "Vul A.U.B. een wachtwoord in";
$passwordErrors[2] = "Er mogen geen spaties in de naam voorkomen";
$passwordErrors[3] = "Incorrect wachtwoord";

$returnData = array();

if($inputEmail !== NULL) {
    $succes = true;
    
    if(ltrim($inputEmail, ' ') == '') {
        $returnData["errorEmail"] = $emailErrors[1];
        
        $succes = false;
    } else if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL) && strtolower($inputEmail) != 'admin') {
        $returnData["errorEmail"] = $emailErrors[3];
        
        $succes = false;
    }
    
    if(!$succes) {
        echo json_encode($returnData);
        die();
    }

    if($succes) {
        $klant = $link->query("SELECT klantID, actief FROM klant WHERE emailadres = '" . strtolower($inputEmail) . "'")->fetch_assoc();
        if($klant["klantID"] == false) {
            $returnData["errorEmail"] = $emailErrors[2];
            
            echo json_encode($returnData);
            die();
        } else if($klant["actief"] == false) {
            $returnData["errorEmail"] = $emailErrors[4];
            
            echo json_encode($returnData);
            die();
        } else {
            if($inputPassword == NULL || ltrim($inputPassword, ' ') == '') {
                $returnData["errorPassword"] = $passwordErrors[1];

                $succes = false;
            } else if(strpos($inputPassword, ' ') !== false) {
                $returnData["errorPassword"] = $passwordErrors[2];

                $succes = false;
            }

            if(!$succes) {
                echo json_encode($returnData);
                die();
            }
            
            $password = $link->query("SELECT wachtwoord FROM klant WHERE klantID =" . $klant["klantID"])->fetch_assoc()["wachtwoord"];
            if($password != $inputPassword) {
                $returnData["errorPassword"] = $passwordErrors[3];
                
                echo json_encode($returnData);
                die();
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
                <input id="inputUserName" type="text" name="username" <?php if($errorEmail > 0) {echo "class='error'";} if($inputEmail !== NULL) {echo "value='" . $inputEmail . "'";} ?> />
                <h4 id="nameError" class="error"></h4>
                <h3>Wachtwoord</h3>
                <input id="inputPassword" type="password" name="password" <?php if($errorPassword > 0) {echo "class='error'";} ?>/>
                <h4 id="passwordError" class="error"></h4>
                <a role="button" onclick="login();" style="margin: 0.5rem;">Login</a>
                <a href="wachtwoordreset.php" style="font-size:0.5rem;">Wachtwoord vergeten</a>
            </div>
            <input type="submit" style="display:none;">
        </form>
    <?php printFooter(); ?>
    <script src='/scripts/login.js' type='text/javascript' charset='utf-8'></script>
</body>
</html>
