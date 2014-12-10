<!DOCTYPE html>
<!--
Wesley Oosterveen
-->
<?php
include_once("global.php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php
        printStyles();
        printScripts();
        ?>
    </head>
    <body>
        <?php printHeader(); ?>
        <!--
        een formulier voor het toevoegen van een factuur aan een klant, van de vorige pagina is het klantID mee
        gekomen, hiermee kan een link tussen het factuur en de klant gelegt worden.
        -->


        <form class="pageElement" action="factuur.php">
            Geleverde service:<br>
            <input type="hidden" name="klantID" value="<?php $_GET["klantID"] ?>">
            <input class="textbox" type="text" name="service"<?php
            if (isset($_GET["service"]) && !empty($_GET["service"]) && is_string($_GET["service"])) {
                print("value=\"" . $_GET["service"] . "\"");
            }
            ?>><br>
            Bedrag:<br>
            <input class="textbox" type="number" name="bedrag" <?php
            if (isset($_GET["bedrag"]) && !empty($_GET["bedrag"]) && is_numeric($_GET["bedrag"])) {
                print("value=\"" . $_GET["bedrag"] . "\"");
            }
            ?>><br>
            Betaald:
            <input type="radio" name="betaald"
            <?php if (isset($betaald) && $betaald == "1") echo "checked"; ?>
                   value="betaald">betaald
            <input type="radio" name="betaald" checked
            <?php if (isset($betaald) && $betaald == "0") echo "checked"; ?>
                   value="niet betaald">Niet betaald
            </br>

            <input class="button" type="submit" name="submit" value="Voeg toe" >
            <input class="button" type="button" name ="cancel" value="annuleren">

        </form>



        <?php
        $sorter = true;


//deze funcitie kijkt of er iets in $_GET staat, zodra deze er is ( er is dus info mee gekomen van de vorige pagina
        // dan kijkt die of er op submit of cancel is ingedrukt. Is het submit kijkt die of alle velden zijn ingevuld, zo niet
        // krijgt de gebruiker een melding.
        if ($_GET) {
            if (isset($_GET['submit'])) {
                if (!empty($_GET["service"]) && !empty($_GET["bedrag"]) && $_GET["service"] != "" && $_GET["bedrag"] != "" && !empty($_GET["betaald"]) && $_GET["betaald"] != "") {
                    submit();
                } else if (isset($_GET['cancel'])) {
                    cancel();
                } else {
                    echo 'ALLE VELDEN INVULLEN!';
                }
            }
        }

        //voegt het factuur toe aan de database
        function submit() {

            $link = $GLOBALS["link"];
            $betaald = 0;
            if ($_GET["betaald"] == "betaald") {
                $betaald = 1;
            } else {
                $betaald = 0;
            }

            $stmt = "INSERT INTO factuur(klant, service, prijs, betaald, papierenfactuur) VALUES (1,'" . $_GET["service"] . "'," . $_GET["bedrag"] . "," . $betaald . ", '')";
            if ($link->query($stmt) === FALSE) {
                echo "Error: " . $stmt . "<br>" . $link->error;
            }
            return mysql_query($stmt);
        }

        //Het factuur wordt hier aangepast.
        function edit(
        $factuurID) {

        }

        function sorter() {

        }

        //MOET VERWIJDERD WORDEN!
        $klantID = 1;
        //de facturen worden hier opgehaald uit de database, zodat ze mooi op de pagina weer gegeven kunnen worden
        $link = $GLOBALS["link"];
        $query = "SELECT factuurID, service, prijs, betaald, voornaam, achternaam,woonplaats,huisnummer,postcode,adres "
                . "FROM factuur JOIN klant ON klantID = klant "
                . "WHERE klantID = " . $klantID . " ORDER BY factuurID DESC";
        $result = mysqli_query($link, $query);
        $databaserij = mysqli_fetch_assoc($result);

//        echo "<table>";
//        while ($databaserij) {
//            echo"<tr><td>";
//            echo $databaserij["service"] . "</td><td>";
//            echo $databaserij["prijs"] . "</td></tr></br>";
//            $databaserij = mysqli_fetch_assoc($result);
//        }
//        echo "</table>";

        echo "<table>";
        $factuurNummer = 0;
        while ($databaserij) {

            echo "\n\t<div id = 'bericht" . $databaserij["factuurID"] . "' class = 'pageElement'>";
            echo "<a onclick = edit(" . $databaserij["factuurID"] . ");
            > <img class = 'iconEdit' src = 'imgs/pencil1.svg' alt = 'icoon-bewerken' /></a>";
            echo "<br/>\n\t\t<span class = 'content'>" . $databaserij["voornaam"] . " " . $databaserij["achternaam"] . "</span>";
            echo "<br/>\n\t\t<span class = 'content'>" . $databaserij["adres"] . " " . $databaserij["huisnummer"] . "</span>";
            echo "<br/>\n\t\t<span class = 'content'>" . $databaserij["postcode"] . " " . $databaserij["woonplaats"] . "</span></br>";
            echo "<br/>\n\t\t<span class = 'content'>" . $databaserij["service"] . "</span>";
            echo "<br/>\n\t\t<span class = 'content'>" . $databaserij["prijs"] . " Euro </span>";

            if ($databaserij["betaald"] == 1) {
                echo "<br/>\n\t\t<span class = 'content'>Betaald</span>";
            } else {
                echo "<br/>\n\t\t<span class = 'content'>Niet betaald</span>";
            }

            echo "\n\t</div>";

//echo $databaserij["service"] . "</td><td>";
            // echo $databaserij["prijs"] . "</td></tr></br>";
            $databaserij = mysqli_fetch_assoc($result);
        }
        echo "</table>";

        mysqli_free_result($result);

        mysqli_close($link);
        ?>

        <?php printFooter(); ?>
    </body>
</html>