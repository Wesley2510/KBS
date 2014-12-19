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
    $adminEmailError = 0; //1 = no input, 2 = not a valid adress, 3 = al geregistreerd
    $adminPassError = 0; //1 = no input, 2 = not a valid password
    $adminPassRepeatError = 0; //1 = no input, 2 = not same as pass
    $succesNew = true; 
    $trimmedName = NULL;
    
    $inputNameEdited = filter_input(INPUT_POST, "adminNameEdited");
    $inputEmailEdited = filter_input(INPUT_POST, "adminEmailEdited");
    $inputNewPass = filter_input(INPUT_POST, "adminNewPassword");
    $inputNewPassRepeat = filter_input(INPUT_POST, "adminRepeatPassword");
    $inputAdminEditID = filter_input(INPUT_POST, "adminEditedID");
    $succesEdit = true;

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
        } else if ($link->query("SELECT COUNT(klantID) FROM klant WHERE emailadres=" . $inputEmail) > 0) {
            $adminEmailError = 3;
            $succesNew = false;
        }
        $adminPassError = checkPass($inputPass);
        if ($adminPassError > 0) {
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
            $sql = "INSERT INTO klant (voornaam, achternaam, emailadres, wachtwoord, admin) "
                    . "VALUES ('" . $voornaam . "','" . $achternaam . "','" . $inputEmail . "','" . $inputPass . "', 1)";
            if(!$link->query($sql)) {
                trigger_error("Fout bij toevoegen administrator :" . $sql . $link->error());
            } 
            
            header('Location: #');
        }
    } else if(($inputNameEdited != NULL || $inputEmailEdited != NULL || $inputNewPass != NULL) && $inputAdminEditID !== NULL && is_numeric($inputAdminEditID)) {
        if($inputNameEdited != NULL) {
            if(ltrim($inputNameEdited, ' ') == '') {
                $adminNameError = 1;
                $succesEdit = false;
            } else if(preg_replace("/[^A-Za-z ]/", '', $inputNameEdited) != $inputNameEdited) {
                $adminNameError = 3;
                $succesEdit = false;
            } else {
                //Verwijder alle spaties aan het begin
                $trimmedName = preg_replace("/^\s\s*/", '', $inputNameEdited);
                //Verwijder alle spaties aan het eind
                $trimmedName = preg_replace("/\s\s*$/", '', $trimmedName);
                //Verwijder alle dubbele spaties
                $trimmedName = preg_replace("/ +(?= )/", '', $trimmedName);

                if(!strpos($trimmedName, ' ')) {
                    $adminNameError = 2;
                    $succesEdit = false;
                } else {
                    $voornaam = substr($trimmedName, 0, strpos($trimmedName, ' '));
                    $achternaam = substr($trimmedName, strpos($trimmedName, ' ') + 1);
                }
            }
        }
        if($inputEmailEdited != NULL) {
            if(ltrim($inputEmailEdited, ' ') == '') {
                $adminEmailError = 1;
                $succesEdit = false;
            } else if (!filter_var($inputEmailEdited, FILTER_VALIDATE_EMAIL)) {
                $adminEmailError = 2;
                $succesEdit = false;
            }
        }
        if ($inputNewPass != NULL) {
            $adminPassError = checkPass($inputNewPass);
            if ($adminPassError == 0) {
                if($inputNewPassRepeat == NULL || ltrim($inputNewPassRepeat, ' ') == '') {
                    $adminPassRepeatError = 1;
                    $succesEdit = false;
                } else if ($inputNewPass !== $inputNewPassRepeat) {
                    $adminPassRepeatError = 2;
                    $succesEdit = false;
                }
            } else {
                $succesEdit = false;
            }
        }
        
        if($succesEdit) {
            $sql = "UPDATE klant SET ";
            if($inputNameEdited !== NULL && $inputNameEdited !== '') {
                $sql .= "voornaam='" . $voornaam . "', achternaam='" . $achternaam . "' ";
            }
            if($inputEmailEdited !== NULL && $inputEmailEdited !== '') {
                if($inputNameEdited !== NULL) {
                    $sql .= ",";
                }
                $sql .= "emailadres='" . $inputEmailEdited . "' ";
            }
            if($inputNewPass !== NULL && $inputNewPass !== '') {
                if($inputNameEdited !== NULL || $inputEmailEdited !== NULL) {
                    $sql .= ",";
                }
                $sql .= "wachtwoord='" . $inputNewPass . "' ";
            }
            $sql .= "WHERE klantID =" . $inputAdminEditID;
            
            if(!$link->query($sql)) {
                trigger_error("Fout bij wijzingen admin data : " . $sql);
            } else {
                if($_SESSION["userID"] == $inputAdminEditID) {
                    $_SESSION["voornaam"] = $voornaam;
                    $_SESSION["achternaam"] = $achternaam;
                }
            }
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
        $newAdminPageElement = "<div class='flexRowSpace'><a class='icon' style='text-align:left;' id='buttonNewAdmin' onclick='createNewAdmin()'><img class='icon' src='/imgs/add110.svg' alt=''/>"; 
        $newAdminPageElement .= "</a><a class='icon' href='adminbeheer.php?actief=0'><img class='icon' src='/imgs/delete51.svg' alt=''/></a></div>";
        
        $newAdminForm = "<form action='#' id='adminForm' method='post'></form>";
        $newAdminForm .= "<div class='flexRowSpace'><input class='' type='text' id='adminFormName' form='adminForm' name='name' placeholder='Naam' />";
        $newAdminForm .= "<input type='text' id='adminFormEmail' form='adminForm' name='email' placeholder='Emailadres' /></div>";
        $newAdminForm .= "<div class='flexRowSpace'><h4 id='adminNameErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4>";
        $newAdminForm .= "<h4 id='adminEmailErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4></div>";
        $newAdminForm .= "<div class='flexRowSpace'><input type='password' id='adminFormPass' form='adminForm' name='pass' placeholder='Wachtwoord' />";
        $newAdminForm .= "<input type='password' id='adminFormPassRepeat' form='adminForm' name='passrepeat' placeholder='Herhaal wachtwoord' /></div>";
        $newAdminForm .= "<div class='flexRowSpace'><h4 id='adminPassErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4>";
        $newAdminForm .= "<h4 id='adminPassRepeatErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4></div>";
        $newAdminForm .= "<div class='flexRowSpace'><a class='icon' style='text-align:left;' id='iconRegister' onclick='submit()'><img class='icon' src='/imgs/done.svg' alt=''/></a>";
        $newAdminForm .= "<a class='icon' style='text-align:right;' id='iconAnnuleer' onclick='cancelComposingMessage()'><img class='icon' src='/imgs/delete85.svg' alt=''/></a></div>";
        
        $editAdminForm = "<form action='#' id='adminForm' method='post'></form>";
        $editAdminForm .= "<div style='width: 100%;'>";
        $editAdminForm .= "<div class='flexRowSpace'>";
        $editAdminForm .= "<input type='text' id='adminFormName' form='adminForm' name='adminNameEdited' placeholder='Voornaam Achternaam' />";
        $editAdminForm .= "<input type='text' id='adminFormEmail' form='adminForm' name='adminEmailEdited' placeholder='Emailadres' /></div>";
        $editAdminForm .= "<div class='flexRowSpace'><h4 id='adminNameErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4>";
        $editAdminForm .= "<h4 id='adminEmailErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4></div>";
        $editAdminForm .= "<div class='flexRowSpace'><input type='password' id='adminFormPass' form='adminForm' name='adminNewPassword' placeholder='Nieuw wachtwoord'>";
        $editAdminForm .= "<input type='password' id='adminFormPassRepeat' form='adminForm' name='adminRepeatPassword' placeholder='Herhaal wachtwoord'/></div>";
        $editAdminForm .= "<div class='flexRowSpace'><h4 id='adminPassErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4>";
        $editAdminForm .= "<h4 id='adminPassRepeatErrorMessage' class='error' style='flex-grow:1;width:100%;'></h4></div>";
        $editAdminForm .= "<input type='hidden' name='adminEditedID' id='adminEditedID' value='' form='adminForm' />";
        $editAdminForm .= "<div class='flexRowSpace'><a class='icon' style='text-align:left;' id='iconBewerk' onclick='submit()'><img class='icon' src='/imgs/done.svg' alt=''/></a>";
        $editAdminForm .= "<a class='icon' style='text-align:center;' id='iconDeactiveer'><img class='icon' src='/imgs/delete52.svg' alt=''/></a>";
        $editAdminForm .= "<a class='icon' style='text-align:right;' id='iconAnnuleer' onclick='cancelComposingMessage()'><img class='icon' src='/imgs/delete85.svg' alt=''/></a></div></div>";

        echo "var editAdminForm = \"" . $editAdminForm . "\";\n";
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
    $admins = $link->query("SELECT klantID, voornaam, achternaam, emailadres FROM klant WHERE admin = 1");
    while($admin = $admins->fetch_assoc()) {
        echo "<div id='admin" . $admin["klantID"] . "' class='pageElement flexRowSpace'><h2 id='naam" . $admin["klantID"] . "'>";
        echo $admin["voornaam"] . " " . $admin["achternaam"] . "</h2><h4 id='email" . $admin["klantID"] . "' style='text-align:right;padding-right:2rem;flex:1;'>";
        echo $admin["emailadres"] . "</h4>";
        if($admin["klantID"] != 1) {echo "<img class='icon iconEdit' src='../imgs/pencil1.svg' alt='icoon-bewerken' onclick='editAdminData(" . $admin["klantID"] . ")' /></div>";}
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
    if(!$succesNew || !$succesEdit) {
        if(!$succesNew) {
            echo "<script type='text/javascript'>
            document.getElementById('adminFormName').value = '" . $inputName . "';
            document.getElementById('adminFormEmail').value = '" . $inputEmail . "';";
        }
        
        if(!$succesEdit) {
            echo "<script type='text/javascript'>";
            echo "originalHTML = document.getElementById('admin" . $inputAdminEditID . "').innerHTML;";
            echo "document.getElementById('admin" . $inputAdminEditID . "').innerHTML = editAdminForm;";
            echo "document.getElementById('adminEditedID').value = " . $inputAdminEditID . ";";
            echo "document.getElementById('adminFormName').value = '" . $inputNameEdited . "';";
            echo "document.getElementById('adminFormEmail').value = '" . $inputEmailEdited . "';";
        }
        
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
            } else if ($adminEmailError == 3) {
                echo "'Dit emailadres is al in gebruik';";
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