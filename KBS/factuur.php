<!DOCTYPE html>
<!--
Wesley Oosterveen
-->
<?php
include_once("global.php");

if (!isset($_SESSION["loggedin"])) {
    header("Location: /login.php");
    die();
} else if ($_SESSION["admin"] == false) {
    header("Location: /admin/");
    die();
}

$inputFactuurEditID = filter_input(INPUT_POST, "factuurtEditedID");
$klantID = filter_input(INPUT_GET, "klantID");
$serviceNieuw = filter_input(INPUT_POST, "service");
$bedragNieuw = filter_input(INPUT_POST, "bedrag");
$betaaldNieuw = filter_input(INPUT_POST, "betaald");
$errorMsg = "Vul gegevens in!";
$checker = TRUE;

//voegt het bewerkte factuur toe aan de database
if ($inputFactuurEditID != NULL && is_numeric($inputFactuurEditID)) {
    $factuurFormService = filter_input(INPUT_POST, "factuurServiceEdited", FILTER_SANITIZE_ENCODED);
    $factuurFormBedrag = filter_input(INPUT_POST, "factuurBedragEdited", FILTER_SANITIZE_ENCODED);
    $radioB = filter_input(INPUT_POST, "betaald", FILTER_SANITIZE_ENCODED);
    $klantID = filter_input(INPUT_POST, "klantID");
    if ($radioB === "betaald") {
        $betaald = 1;
    } else {
        $betaald = 0;
    }

//Controleer of input niet alleen uit spaties bestaat
    if (!(ltrim($factuurFormService, ' ') === '') && !(ltrim($factuurFormBedrag, ' ') === '')) {
        if (!$link->query("UPDATE factuur SET service = '" . $factuurFormService . "', prijs = " . $factuurFormBedrag . ", betaald = " . $betaald . " WHERE factuurID = " . $inputFactuurEditID)) {
            trigger_error("Fout bij bewerken factuur: " . $link->error, E_USER_ERROR);
        }
        header("Location: factuur.php?klantID=" . $klantID);
    }
}




//Voegt een nieuw factuur toe aan de database
if ($serviceNieuw != NULL || $bedragNieuw != NULL || $betaaldNieuw != NULL) {
    $klantIDNieuw = filter_input(INPUT_POST, "klantID");
    if (!is_numeric($bedragNieuw) || $bedragNieuw == "") {
        $errorMsg = "bedrag";
        $checker = FALSE;
        header("Location: factuur.php?klantID=" . $klantIDNieuw);
    }
    if ($serviceNieuw == "") {
       
        $errorMsg = "service";
        $checker = FALSE;
        header("Location: factuur.php?klantID=" . $klantIDNieuw);
    }

//  als de checker nog op true staat ( er zijn dus geen fouten in de service of het bedrag gevonden
//  voert dit stukje code uit, zodat de factuur in de db gezet kan worden.
    if ($checker) {
        $datumtoevoegen = date("Y-m-d H:i:s", getdate()[0]);
        if ($betaaldNieuw == "betaald") {
            $betaaldNieuw = 1;
        } else {
            $betaaldNieuw = 0;
        }
        if (!$link->query("INSERT INTO factuur(klant, service, prijs, betaald, fdatum) VALUES (" . $klantIDNieuw
                        . ", '" . $serviceNieuw . "', " . $bedragNieuw . ", " . $betaaldNieuw . ", ".$datumtoevoegen.")")) {
            trigger_error("Fout bij het toevoegen van het factuur: " . $link->error, E_USER_ERROR);
        }
//  staat checker wel op false, dan wordt dit stukje uitgevoert en wordt er dus niks aan de db toegevoegt.
    } else {
        if ($errorMsg === "bedrag") {
            echo "Vul een geldig bedrag in!";
        } else if ($errorMsg === "service") {
            echo "Vul een service in";
        }
    }
    header("Location: factuur.php?klantID=" . $klantIDNieuw);
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php
        printStyles();
        printScripts();
        echo "<script src = '/scripts/factuurbewerking.js' type = 'text/javascript' charset = 'utf-8'></script>";
        ?>
    </head>
    <body>
        <?php printHeader(); ?>


        <!--
        Hier is de "knop" voor het toevoegen van een factuur. Via deze "knop" wordt de factuurToevoegen functie
        aangeroepen en ga je naar javascript(factuurbewerking.js), het klantID word mee gegeven zodat, er in het
        form in het javascript een hidden input gemaakt kan worden. Hierdoor kan deze weer mee teruggegeven worden
        en ze de goede facturen van de goede klant weer geladen worden.
        -->
        <div class="pageElement" id="voegToe"> <a role="button" onclick="factuurToevoegen(<?php echo $klantID ?>)" >Nieuw factuur</a> </div>
        <?php
        $query = "SELECT klant, factuurID, service, prijs, betaald, voornaam, achternaam,woonplaats,huisnummer,postcode,adres "
                . "FROM factuur JOIN klant ON klantID = klant "
                . "WHERE klantID = " . $klantID . " ORDER BY factuurID DESC";
        $result = $link->query($query);
        $databaserij = $result->fetch_assoc();

        echo "<table>";
        $factuurNummer = 0;
        while ($databaserij) {

            echo "\n\t<div id ='factuur" . $databaserij["factuurID"] . "' class = 'pageElement'>";
            echo "<div class='flexRowSpace'><a onclick ='factuurBewerken(" . $databaserij["factuurID"] . ", " . $klantID . ");'>";
    echo "<div><img class='icon' src = 'imgs/pencil1.svg' alt = 'icoon-bewerken' /></a></div>";
    echo "<br/>\n\t\t<span id='factuurVoornaam' id= class = 'content'>" . $databaserij["voornaam"] . " " . $databaserij ["achternaam"] . "</span>";
            echo "<br/>\n\t\t<span id='factuurAdres' class = 'content'>" . $databaserij["adres"] . " " . $databaserij ["huisnummer"] . "</span>";
            echo "<br/>\n\t\t<span class = 'content'>" . $databaserij["postcode"] . " " . $databaserij ["woonplaats"] . "</span></br>";
            echo "<br/>\n\t\t<span id='factuurService" . $databaserij["factuurID"] . "' class = 'content'>" . urldecode($databaserij["service"]) . "</span>";
            echo "<br/>\n\t\t<span id='factuurBedrag" . $databaserij["factuurID"] . "' class = 'content'>€" . number_format($databaserij ["prijs"], 2) . "</span>";




    if ($databaserij ["betaald"] == 1) {
                echo "<br/>\n\t\t<span id='radioB" . $databaserij["factuurID"] . "' class = 'content'>Betaald</span>";
            } else {
                echo "<br/>\n\t\t<span id='radioB" . $databaserij["factuurID"] . "' class = 'content'>Niet betaald</span>";
            }

            echo "<br/><a href='pdffactuur2.php?id=" . $databaserij["factuurID"] . "'>Download factuur</a>";
    echo "\n\t</div></div>";


    $databaserij = mysqli_fetch_assoc($result);
        }
        echo "</table>";

        mysqli_free_result($result);
        mysqli_close($link);
        printFooter();
        ?>






    </body>
</html>