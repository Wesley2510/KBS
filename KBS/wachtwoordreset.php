<!-- 
Lewis Clement
-->

<?php
include_once "global.php";

if(isset($_SESSION["loggedin"])) {
    header('Location: /admin/');
    die();
}

$inputResetEmail = filter_input(INPUT_POST, "resetEmail");
$resetErrorCode = 0; //1 = geen adres ingevuld, 2 = geen geldig adres, 3 = email niet geregistreerd, 4 = al reset opgevraagd voor adres
$klant = 0;
$succes = true;
$emailSent = false;

$inputCode = filter_input(INPUT_GET, "c"); //Resetcode
$inputEmailCode = filter_input(INPUT_GET, "e", FILTER_VALIDATE_EMAIL); //Email bij resetopdracht
$codeError = 0; //1 = geen resetcode, 2 = code niet (meer) geldig, 3 = code is incorrect
$code = NULL;

$inputResetPass = filter_input(INPUT_POST, "resetPass");
$inputResetPassRepeat = filter_input(INPUT_POST, "resetPassRepeat");
$resetPassError = 0; //1 = leeg, 2 = voldoet niet aan eisen
$resetPassRepeatError = 0; //1 = leeg, 2 = is niet gelijk aan wachtwoord
$succesPass = true;

if ($inputResetEmail !== NULL) {
    if (ltrim($inputResetEmail, ' ') == '') {
        $resetErrorCode = 1;
        $succes = false;
    } else if (!filter_var($inputResetEmail, FILTER_VALIDATE_EMAIL)) {
        $resetErrorCode = 2;
        $succes = false;
    } else if (!$klant = $link->query("SELECT klantID FROM klant WHERE emailadres ='" . $inputResetEmail . "';")->fetch_assoc()["klantID"]) {
        $resetErrorCode = 3;
        $succes = false;
    } else if ($link->query("SELECT * FROM resetcode WHERE user =" . $klant . " AND datumreset IS NULL")->fetch_assoc()) {
        $resetErrorCode = 4;
        $succes = false;
    }

    if ($succes) {
        if (!$link->query("INSERT INTO resetcode (user, code) VALUES (" . $klant . ", '" . substr(hash('MD5', gettimeofday()["usec"] + $klant), 0, 20) . "')")) {
            trigger_error("Fout bij aanamken resetcode: " . $link->error());
        }
        $emailSent = true;
    }
} else if ($inputCode !== NULL && $inputEmailCode !== NULL) {
    if (!ctype_alnum($inputCode) || strlen($inputCode) !== 20) {
        $codeError = 1;
        $succes = false;
    } else if (!$code = $link->query("SELECT code FROM resetcode WHERE datumreset IS NULL AND user = (SELECT klantID FROM klant WHERE emailadres ='" . $inputEmailCode . "');")->fetch_assoc()) {
        $codeError = 2;
        $succes = false;
    } else if ($code["code"] !== $inputCode) {
        $codeError = 3;
        $succes = false;
    }

    if ($succes && ($inputResetPass !== NULL || $inputResetPassRepeat !== NULL)) {
        $resetPassError = checkPass($inputResetPass);
        if ($resetPassError == 0) {
            if ($inputResetPassRepeat == NULL) {
                $resetPassRepeatError = 1;
                $succesPass = false;
            } else if ($inputResetPassRepeat !== $inputResetPass) {
                $resetPassRepeatError = 2;
                $succesPass = false;
            }
        } else {
            $succesPass = false;
        }
        
        if($succesPass) {
            if(!$link->query("UPDATE klant SET wachtwoord='" . $inputResetPass . "' WHERE emailadres='" . $inputEmailCode . "';")) {
                trigger_error("Fout bij wijzigen wachtwoord: " . $link->error);
            } else {
                $dateTime = date("Y-m-d H:i:s", getdate()[0]);
                if(!$link->query("UPDATE resetcode SET datumreset='" . $dateTime . "' WHERE datumreset IS NULL AND user = (SELECT klantID FROM klant WHERE emailadres='" . $inputEmailCode . "');")) {
                    trigger_error("Fout bij deactiveren resetcode: " . $link->error);
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
        <title>Textbug - Nieuw wachtwoord opvragen</title>
        <?php printStyles(); ?>
    </head>
    <body>
        <?php printHeader(); ?>

    <?php
    if ($emailSent) {
        echo "<div class='pageElement' style='display:flex;flex-direction:column;align-items:center;'>";
        echo "<h4>Aanvraag succesvol verzonden.</h4>";
        echo "<h4>U zal binnen 5 minuten een email ontvangen.</h4>";
        echo "<a role='button' href='/'>Naar hoofdpagina</a>";
    } else if ($inputCode !== NULL && $inputEmailCode !== NULL) {
        echo "<div class='pageElement' style='display:flex;flex-direction:column;align-items:center;'>";
        if (!$succes) {
            if ($codeError == 1) {
                echo "<h4 class='error'>Geen geldige resetcode</h4>";
            } else if ($codeError == 2) {
                echo "<h4 class='error'>Deze resetcode is niet geldig (meer)</h4>";
            } else if ($codeError == 3) {
                echo "<h4 class='error'>Incorrecte resetcode</h4>";
            }
            echo "<a role='button' href='/'>Naar hoofdpagina</a>";
        } else {
            if ($succes && (!$succesPass || $inputResetPass == NULL)) {
                echo "<form id='resetForm' action='#' method='post'></form>";
            echo "<h4>Vul een nieuw wachtwoord in</h4>";
            echo "<input type='password' name='resetPass' form='resetForm' ";
            if ($resetPassError > 0) {
                echo "class='error'";
            }
            echo "/>";
            if($resetPassError == 1) {
                echo "<h4 class='error'>Vul A.U.B. een wachtwoord in</h4>";
            } else if ($resetPassError == 2) {
                echo "<h4 class='error'>(a-Z, 0-9)</h4>";
            }
            echo "<h4>Herhaal</h4>";
            echo "<input type='password' name='resetPassRepeat' form='resetForm' ";
            if ($resetPassRepeatError > 0) {
                echo "class='error'";
            }
            echo "/>";
            if($resetPassRepeatError == 1) {
                echo "<h4 class='error'>Herhaal A.U.B. het wachtwoord</h4>";
            } else if ($resetPassRepeatError == 2) {
                echo "<h4 class='error'>Wachtwoorden zijn niet gelijk</h4>";
            }
            echo "<a role='button' onclick='submit()'>Opslaan</a>";
            } else {
                echo "<h4>Wachtwoord is succesvol gewijzigd</h4>";
                echo "<a role='button' href='/login.php'>Login</a>";
            }
        }
    } else {
        echo "<div class='pageElement' style='display:flex;flex-direction:column;align-items:center;'>";
        echo "<form id='resetForm' action='#' method='post'></form>";
        echo "<h3>Vraag een nieuw wachtwoord op</h3>";
        echo "<input type='text' name='resetEmail' placeholder='Emailadres' form='resetForm'";
        if (!$succes) {
            echo "class='error'";
        }
        echo "/>";

        if (!$succes) {
            echo "<h4 class='error'>";
            if ($resetErrorCode == 1) {
                echo "Vul A.U.B. een emailadres in";
            } else if ($resetErrorCode == 2) {
                echo "Vul A.U.B. een geldig emailadres in";
            } else if ($resetErrorCode == 3) {
                echo "Dit emailadres is niet geregistreerd";
            } else if ($resetErrorCode == 4) {
                echo "Er is voor dit emailadres al een reset aangevraagd";
            }
            echo "</h4>";
        }
        echo "<div class='flexRowSpace'><a role='button' href='login.php'>Terug naar login</a><a role='button' onclick='submit()'>Stuur resetcode</a></div>";
    }
    echo "</div>";
    ?>

    <?php printFooter(); ?>
    </body>
    <script type='text/javascript'>
        var submit = function() {document.forms["resetForm"].submit();};
    </script>
</html>
