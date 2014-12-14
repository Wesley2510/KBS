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

$voornaam = '';
$achternaam = '';

if(isset($_SESSION["loggedin"]) && $_SESSION["admin"] == true) {
    $inputName = filter_input(INPUT_POST, "name");
    $inputEmail = filter_input(INPUT_POST, "email");
    $inputPass = filter_input(INPUT_POST, "pass");
    $inputPassRepeat = filter_input(INPUT_POST, "passrepeat");
    $adminNameError = 0; //1 = no input, 2 = no firstname/surname, 3 = invalid name
    $adminEmailError = 0; //1 = no input, 2 = not a valid adress
    $adminPassError = 0; //1 = no input, 2 = not a valid password
    $adminPassRepeatError = 0; //1 = no input, 2 = not same as pass
    $succesNew = true; 
    $trimmedName = NULL;
    
    if ($inputName != NULL || $inputEmail != NULL || $inputPass != NULL || $inputPassRepeat != NULL) {
        if($inputName == NULL || ltrim($inputName, ' ') == '') {
            $adminNameError = 1;
            $succesNew = false;
        } else if(preg_replace("/[^A-Za-z ]/", '', $inputName) != $inputName) {
            $adminNameError = 3;
            $succesNew = false;
        } else {
            //Verwijder alle spaties aan het begin
            $trimmedName = preg_replace("/^\s\s*/", '', $inputName);
            //Verwijder alle spaties aan het eind
            $trimmedName = preg_replace("/\s\s*$/", '', $trimmedName);
            //Verwijder alle dubbele spaties
            $trimmedName = preg_replace("/ +(?= )/", '', $trimmedName);
            
            if(strpos($trimmedName, ' ') == false) {
                $adminNameError = 2;
                $succesNew = false;
            } else {
                $voornaam = substr($trimmedName, 0, strpos($trimmedName, ' '));
                $achternaam = substr($trimmedName, strpos($trimmedName, ' ') + 1);
            }
        }
        if($inputEmail == NULL || ltrim($inputEmail, ' ') == '') {
            $adminEmailError = 1;
            $succesNew = false;
        } else if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
            $adminEmailError = 2;
            $succesNew = false;
        }
        if($inputPass == NULL || ltrim($inputPass, ' ') == '') {
            $adminPassError = 1;
            $succesNew = false;
        } else if(preg_replace("/[^A-Za-z0-9 ]/", '', $inputPass) != $inputPass) {
            $adminPassError = 2;
            $succesNew = false;
        }
        if($inputPassRepeat == NULL || ltrim($inputPassRepeat, ' ') == '') {
            $adminPassRepeatError = 1;
            $succesNew = false;
        } else if ($inputPass !== $inputPassRepeat) {
            $adminPassRepeatError = 2;
            $succesNew = false;
        }
        
        if($succesNew) {
            $sql = "INSERT INTO admin (voornaam, achternaam, emailadres, wachtwoord) "
                    . "VALUES ('" . $voornaam . "','" . $achternaam . "','" . $inputEmail . "','" . $inputPass . "')";
            if(!$link->query($sql)) {
                trigger_error("Fout bij toevoegen administrator" . $sql);
            }
            
            header('Location: #');
        }
    }
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        
        <title>Textbug - Adminbeheer</title>
        <?php printStyles(); ?>
        <script src='/scripts/adminbeheer.js' type='text/javascript' charset='utf-8'></script>
        <script type='text/javascript'>
        <?php
        $newAdminPageElement = "<div class='flexRowSpace'><a role='button' id='buttonNewAdmin' onclick='createNewAdmin()'>Nieuwe administrator</a><a href='adminbeheer.php?actief=0'>Inactieve administrators</a></div>";
        
        $newAdminForm = "<form action='#' id='adminForm' method='post'></form>";
        $newAdminForm .= "<div class='flexRowSpace'><input class='' type='text' id='adminFormName' form='adminForm' name='name' placeholder='Naam' />";
        $newAdminForm .= "<input type='text' id='adminFormEmail' form='adminForm' name='email' placeholder='Emailadres' /></div>";
        if(!$succesNew) {
            $newAdminForm .= "<div class='flexRowSpace'><h4 id='adminNameErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4>";
            $newAdminForm .= "<h4 id='adminEmailErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4></div>";
        }
        $newAdminForm .= "<div class='flexRowSpace'><input type='password' id='adminFormPass' form='adminForm' name='pass' placeholder='Wachtwoord' />";
        $newAdminForm .= "<input type='password' id='adminFormPassRepeat' form='adminForm' name='passrepeat' placeholder='Herhaal wachtwoord' /></div>";
        if(!$succesNew) {
            $newAdminForm .= "<div class='flexRowSpace'><h4 id='adminPassErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4>";
            $newAdminForm .= "<h4 id='adminPassRepeatErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4></div>";
        }
        $newAdminForm .= "<div class='flexRowSpace'><a role='button' id='buttonRegister' onclick='submit()'>Registreer</a><a role='button' id='buttonAnnuleer' onclick='cancelComposingMessage()'>Annuleer</a></div>";
        
        echo "var newAdminForm = \"" . $newAdminForm . "\";\n";
        if(!$succesNew) {
            echo "originalHTML =\"" . $newAdminPageElement . "\";\n";
        }
        ?>
        </script>
    </head>
    <body>
    <?php printHeader(); ?>
    
    <?php 
    $admins = $link->query("SELECT adminID, voornaam, achternaam, emailadres FROM admin");
    while($admin = $admins->fetch_assoc()) {
        echo "<div id='admin" . $admin["adminID"] . "' class='pageElement flexRowSpace'><h2 id='naam" . $admin["adminID"] . "'>";
        echo $admin["voornaam"] . " " . $admin["achternaam"] . "</h2><h4 id='email" . $admin["adminID"] . "'>";
        echo $admin["emailadres"] . "</h4>";
        echo "<img class='iconEdit' src='../imgs/pencil1.svg' alt='icoon-bewerken' onclick='editAdminData(" . $admin["adminID"] . ")' /></div>";
        echo "</div>";
    }
    echo "<div id='newAdminElement' class='pageElement'>";
    if(!$succesNew) {
        echo $newAdminForm;
    } else {
        echo $newAdminPageElement;
    }
    echo "</div>";
    ?>

    <?php printFooter(); ?>
    
    <?php
    if(!$succesNew) {
        echo "<script type='text/javascript'>
            document.getElementById('adminFormName').value = '" . $inputName . "';
            document.getElementById('adminFormEmail').value = '" . $inputEmail . "';";
        if($adminNameError > 0) {
            echo "document.getElementById('adminFormName').className = 'error';document.getElementById('adminNameErrorMessage').innerHTML =";
            
            if($adminNameError == 1) {
                echo "'Vul A.U.B. een naam in';";
            } else if ($adminNameError == 2) {
                echo "'Vul A.U.B. voor- en achternaam in';";
            } else if (" . $adminNameError . " == 3) {
                echo "'Vul A.U.B. een geldige naam in';";
            }
        }
        
        if($adminEmailError > 0) {
            echo "document.getElementById('adminFormEmail').className = 'error';document.getElementById('adminEmailErrorMessage').innerHTML =";
            
            if($adminEmailError == 1) {
                echo "'Vul A.U.B. een emailadres in';";
            } else if ($adminEmailError == 2) {
                echo "'Vul A.U.B. een geldig emailadres in';";
            } 
        }
        
        if($adminPassError > 0) {
            echo "document.getElementById('adminFormPass').className = 'error';document.getElementById('adminPassErrorMessage').innerHTML =";
            
            if($adminPassError == 1) {
                echo "'Vul A.U.B. een wachtwoord in';";
            } else if ($adminPassError == 2) {
                echo "'Alphanumerieke tekens (a-Z, 0-9)';";
            } 
        }
        
        if($adminPassRepeatError > 0) {
            echo "document.getElementById('adminFormPassRepeat').className = 'error';document.getElementById('adminPassRepeatErrorMessage').innerHTML =";
            
            if($adminPassRepeatError == 1) {
                echo "'Herhaal A.U.B. het wachtwoord';";
            } else if ($adminPassRepeatError == 2) {
                echo "'Wachtwoorden komen niet overeen';";
            }
        }
        echo "</script>";}
    ?>
    </body>
</html>