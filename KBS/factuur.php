<!DOCTYPE html>
<!--
Wesley Oosterveen
-->
<?php
include_once("global.php");

//voegt het factuur toe aan de database
//trigger_error($factuurFormService);
$inputFactuurEditID = filter_input(INPUT_POST, "factuurEditedID");
if ($inputFactuurEditID != NULL && is_numeric($inputFactuurEditID)) {
    $factuurFormService = filter_input(INPUT_POST, "factuurFormService", FILTER_SANITIZE_ENCODED);
    $factuurFormBedrag = filter_input(INPUT_POST, "factuurFormBedrag", FILTER_SANITIZE_ENCODED);
    $radioB = filter_input(INPUT_POST, "radioB", FILTER_SANITIZE_ENCODED);



    //Controleer of input niet alleen uit spaties bestaat
    if (!(ltrim($factuurFormService, ' ') === '')) {

        if (!$link->query("UPDATE factuur SET service='" . $factuurFormService . "', prijs= " . $factuurFormBedrag . " WHERE factuurID=" . $inputFactuurEditID)) {
            trigger_error("Fout bij bewerken bericht: " . $link->error, E_USER_ERROR);
        }
        header("Location: factuur . php");
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php
        printStyles();
        printScripts();
        echo "<script src='/scripts/factuurbewerking.js' type='text/javascript' charset='utf-8'></script>";
        ?>
    </head>
    <body>
        <?php printHeader(); ?>
        <!--
        een formulier voor het toevoegen van een factuur aan een klant, van de vorige pagina is het klantID mee
        gekomen, hiermee kan een link tussen het factuur en de klant gelegt worden.
        -->


        <form class="pageElement" method="post" action="factuur.php">
            Geleverde service:<br>
            <input type="hidden" name="klantID" value="<?php $_POST["klantID"] ?>">
            <input id="serviceFactuur" class="textbox" type="text" name="service"<?php
            if (isset($_POST["service"]) && !empty($_POST["service"]) && is_string($_POST["service"])) {
                print("value=\"" . $_POST["service"] . "\"");
            }
            ?>><br>
            Bedrag:<br>
            <input id="bedragFactuur" class="textbox" type="number" name="bedrag" <?php
            if (isset($_POST["bedrag"]) && !empty($_POST["bedrag"]) && is_numeric($_POST["bedrag"])) {
                print("value=\"" . $_POST["bedrag"] . "\"");
            }
            ?>><br>
            Betaald:
            <input  type="radio" name="betaald" value="betaald">betaald
            <input  type="radio" checked name="betaald" value="niet betaald">Niet betaald
            </br>

            <input class="button" type="submit" name="submit" value="Voeg toe" >
            <input Class="button" type="button" name ="cancel" value="annuleren">

        </form>



        <?php
        $sorter = true;


//deze funcitie kijkt of er iets in $_POST staat, zodra deze er is ( er is dus info mee gekomen van de vorige pagina
        // dan kijkt die of er op submit of cancel is ingedrukt. Is het submit kijkt die of alle velden zijn ingevuld, zo niet
        // krijgt de gebruiker een melding.
        if ($_POST) {
            if (isset($_POST['submit'])) {
                if (!empty($_POST["service"]) && !empty($_POST["bedrag"]) && $_POST["service"] != "" && $_POST["bedrag"] != "" && !empty($_POST["betaald"]) && $_POST["betaald"] != "") {
                    submit();
                } else if (isset($_POST['cancel'])) {
                    cancel();
                } else {
                    echo 'ALLE VELDEN INVULLEN!';
                }
            }
        }

        function submit() {
            $link = $GLOBALS["link"];
            $betaald = 0;
            if ($_POST["betaald"] == "betaald") {
                $betaald = 1;
            } else {
                $betaald = 0;
            }


            $stmt = "INSERT INTO factuur(klant, service, prijs, betaald, papierenfactuur) VALUES (1,'" . $_POST["service"] . "'," . $_POST["bedrag"] . "," . $betaald . ", '')";
            if ($link->query($stmt) === FALSE) {
                echo "Error: " . $stmt . "<br>" . $link->error;
            }
            return mysql_query($stmt);
        }

        //    MOET VERWIJDERD WORDEN!
        $klantID = 1;
        //de facturen worden hier opgehaald uit de database, zodat ze mooi op de pagina weer gegeven kunnen worden
        $query = "SELECT factuurID, service, prijs, betaald, voornaam, achternaam,woonplaats,huisnummer,postcode,adres "
                . "FROM factuur JOIN klant ON klantID = klant "
                . "WHERE klantID = " . $klantID . " ORDER BY factuurID DESC";
        $result = $link->query($query);
        $databaserij = $result->fetch_assoc();

        echo "<table>";
        $factuurNummer = 0;
        while ($databaserij) {

            echo "\n\t<div id ='factuur" . $databaserij["factuurID"] . "' class = 'pageElement'>";
            echo "<a onclick ='factuurBewerken(" . $databaserij["factuurID"] . ");'";
            echo "> <img class = 'iconEdit' src = 'imgs/pencil1.svg' alt = 'icoon-bewerken' /></a>";
            echo "<br/>\n\t\t<span id='factuurVoornaam' id= class = 'content'>" . $databaserij["voornaam"] . " " . $databaserij["achternaam"] . "</span>";
            echo "<br/>\n\t\t<span id='factuurAdres' class = 'content'>" . $databaserij["adres"] . " " . $databaserij["huisnummer"] . "</span>";
            echo "<br/>\n\t\t<span class = 'content'>" . $databaserij["postcode"] . " " . $databaserij["woonplaats"] . "</span></br>";
            echo "<br/>\n\t\t<span id='factuurService" . $databaserij["factuurID"] . "' class = 'content'>" . $databaserij["service"] . "</span>";
            echo "<br/>\n\t\t<span id='factuurBedrag" . $databaserij["factuurID"] . "' class = 'content'>" . $databaserij["prijs"] . "</span>";


            if ($databaserij["betaald"] == 1) {
                echo "<br/>\n\t\t<span id='radioB" . $databaserij["factuurID"] . "' class = 'content'>Betaald</span>";
            } else {
                echo "<br/>\n\t\t<span id='radioB" . $databaserij["factuurID"] . "' class = 'content'>Niet betaald</span>";
            }

            echo "\n\t</div>";


            $databaserij = mysqli_fetch_assoc($result);
        }
        echo "</table>";

        mysqli_free_result($result);

        mysqli_close($link);
        ?>

        <?php printFooter(); ?>
    </body>
</html>