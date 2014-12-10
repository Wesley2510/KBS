<!DOCTYPE html>

<?php
include_once("global.php");

/* $inputP              = de GET value voor p (paginanaam)
 * $pID                 = de ID bij de paginanaam. Deze wordt uit de database gehaald.
 * $inputB              = de GET value voor b (bladzij)
 * $inputBericht        = Form input voor een nieuw bericht
 * $inputBerichtEditID  = Form input van ID van te bewerken bericht
 * $inputBerichtEdit    = Form input van bewerkt bericht
 * $inputBerichtVerwijderID = Form input van ID voor te verijderen bericht
 */

//Code om juiste berichten voor pagina uit de database te halen
$inputP = filter_input(INPUT_GET, "p");
$pID = null;
//Als er geen "p" in de URL aangegeven is terugvallen op de menuitem met de laagste positie (aka. de eerste link)
//Vertaal tevens de naam naar paginaID
if ($inputP === NULL) {
    $rows = $link->query("SELECT paginaID, naam FROM pagina WHERE positie = 1;");
    if ($rows === false) {
        trigger_error("Error: " . $link->error, E_USER_ERROR);
    } else {
        $row = $rows->fetch_assoc();
        $inputP = $row["naam"];
        $pID = $row["paginaID"];
    }
} else {
    $rows = $link->query("SELECT paginaID FROM pagina WHERE naam = '" . $inputP . "';");
    if ($rows === false) {
        trigger_error("Error: " . $link->error, E_USER_ERROR);
        echo "";
    } else {
        $row = $rows->fetch_assoc();
        $pID = $row["paginaID"];

        //Als er in de query geen id gevonden is bestaat de pagina niet, dus wordt er naar 404 doorverwezen
        if ($pID === NULL) {
            header("Location: 404.php");
            die();
        }
    }
}


//Vind uit op welke "bladzijde" de gebruiker zich bevindt
$inputB = filter_input(INPUT_GET, "b");
if (!is_numeric($inputB)) {
    $inputB = 0;
}

if(!isset($_SESSION["loggedin"])) {
    //Code voor toevoegen bericht
    $inputBericht = filter_input(INPUT_POST, "bericht");
    if ($inputBericht != NULL) {
        //Controleer of input niet alleen uit spaties bestaat
        if (!(ltrim($inputBericht, ' ') === '')) {
            $day = date("Y-m-d H:i:s", getdate()[0]);
            if (!$link->query("INSERT INTO bericht (inhoud, datum, pagina) VALUES ('" . $inputBericht . "','" . $day . "'," . $pID . ");")) {
                trigger_error("Fout bij toevoegen nieuw bericht: " . $link->error, E_USER_ERROR);
            }
        }
        header('Location: ?p=' . $inputP . '&b=0');
    }

    //Code voor bewerken bericht
    $inputBerichtEditID = filter_input(INPUT_POST, "berichtEditedID");
    if ($inputBerichtEditID != NULL && is_numeric($inputBerichtEditID)) {
        $inputBerichtEdit = filter_input(INPUT_POST, "berichtEdited");

        //Controleer of input niet alleen uit spaties bestaat
        if (!(ltrim($inputBerichtEdit, ' ') === '')) {
            if (!$link->query("UPDATE bericht SET inhoud='" . $inputBerichtEdit . "' WHERE berichtID=" . $inputBerichtEditID)) {
                trigger_error("Fout bij bewerken bericht: " . $link->error, E_USER_ERROR);
            }
        }
        header('Location: ?p=' . $inputP . '&b=' . $inputB . "&pos=" . $inputBerichtEditID);
    }

    //Code voor verwijderen bericht
    $inputBerichtVerwijderID = filter_input(INPUT_POST, "berichtToDeleteID");
    if ($inputBerichtVerwijderID != NULL && is_numeric($inputBerichtVerwijderID)) {
        if (!$link->query("DELETE FROM bericht WHERE berichtID=" . $inputBerichtVerwijderID)) {
            trigger_error("Fout bij verwijderen bericht: " . $link->error, E_USER_ERROR);
        }
        header('Location: ?p=' . $inputP . '&b=0');
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <?php echo "<title>TextBug - " . $inputP . "</title>"; ?>

<?php printStyles();
printScripts(); ?>
    </head>
    <body>

        <?php printHeader(); ?>

        <?php
        $sql = "SELECT COUNT(berichtID) AS aantal FROM bericht WHERE pagina =" . $pID;
        $aantalBerichten = $link->query($sql)->fetch_assoc();
        if (!$aantalBerichten) {
            trigger_error("Fout bij ophalen aantal berichten: " . $link->error, E_USER_ERROR);
        }

        if(isset($_SESSION["loggedin"])) {
            $unit = "berichten";
            if ($aantalBerichten["aantal"] === "1") {
                $unit = "bericht";
            }
            echo "<div class='pageElement'><div class='flexRowSpace'><a id='buttonPlaats' role='button' onclick='composeMessage();'>Nieuw bericht</a><span class='textRightAlign'>" . $aantalBerichten["aantal"] . " " . $unit . "</span></div></div>";
        }

        //Selecteer alle berichten met bijbehorende datums van de gewenste pagina
        //Subquery: vertaal de text van menuitems in een pagina ID
        $sql = "SELECT berichtID, inhoud, datum FROM bericht WHERE pagina =" . $pID . " ORDER BY datum DESC LIMIT " . ($inputB * 5) . ", 5;";

        $berichten = $link->query($sql);
        if ($berichten === false) {
            trigger_error("Fout bij selecteren berichten. SQL query: " . $sql . "Error: " . $link->error, E_USER_ERROR);
        } else {
            $berichtNum = 0;
            while ($row = $berichten->fetch_assoc()) {
                //Plaats alle berichten in een <div> container met class pageElement
                echo "\n\t<div id='bericht" . $row["berichtID"] . "' class='pageElement'>";
                echo "\n\t\t<span class='datum'>" . date("d-m-Y", strtotime($row["datum"])) . "</span>";
                if(isset($_SESSION["loggedin"])) {echo "<a onclick='editMessage(" . $berichtNum++ . "," . $row["berichtID"] . ");'><img class='iconEdit' src='imgs/pencil1.svg' alt='icoon-bewerken' /></a>";}
                echo "<br/>\n\t\t<span class='content'>" . $row["inhoud"] . "</span>";
                echo "\n\t</div>";
            }

            //Plaats een pageElement om door de oudere berichten te navigeren
            if ($aantalBerichten["aantal"] > 5) {
                echo "\n\t<div class='pageElement flexRowSpace'>";

                if (!( ($inputB + 1) * 5 >= $aantalBerichten["aantal"])) {
                    echo "\n\t\t<a role='button' href='?p=" . $inputP . "&amp;b=" . ($inputB + 1) . "'>Oudere berichten</a>";
                } else {
                    echo "<a></a>";
                } //Zodat de "Nieuwere berichten" knop rechts komt te staan

                if ($inputB > 0) {
                    echo "\n\t\t<a role='button' href='?p=" . $inputP . "&amp;b=" . ($inputB - 1) . "'>Nieuwere berichten</a>";
                }

                echo "\n\t</div>";
            }
        }
        ?>

        <?php printFooter(); ?>

        <?php
        //Script om na bewerking van bericht te focussen op de bijbehorende pageElement.
        //Deze methode is gebruikt omdat met gewone internal links (#bericht) het bericht onder de menubar landde.
        $berichtFocus = filter_input(INPUT_GET, "pos");
        if ($berichtFocus != NULL && is_numeric($berichtFocus)) {
            //Bestaat uit convertRem, een functie om rem values naar px te vertalen,
            //en getPosition om de y positie van het object te verkrijgen.
            //Vervolgens scrolled de pagina naar de y van de pageElement - 4.4rem
            echo "<script type='text/javascript'>
        function convertRem(value) {
            return value * parseFloat(getComputedStyle(document.documentElement).fontSize);
        }

        function getPosition(element) {
            var yPosition = 0;

            while(element) {
                yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
                element = element.offsetParent;
            }
            return yPosition;
        }
        window.scrollTo(0, getPosition(document.getElementById(\"bericht\"+" . $berichtFocus . ")) - convertRem(4.4));</script>";
        }
        ?>

    </body>
</html>
