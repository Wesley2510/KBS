<!--

-->

<?php
include_once "../global.php";
$voornaam = filter_input(INPUT_POST, 'voornaam');
$achternaam = filter_input(INPUT_POST, 'achternaam');
$emailadres = filter_input(INPUT_POST, 'emailadres');
$postcode = filter_input(INPUT_POST, 'postcode');
$huisnummer = filter_input(INPUT_POST, 'huisnummer');
$woonplaats = filter_input(INPUT_POST, 'woonplaats');
$adres = filter_input(INPUT_POST, 'adres');
$telefoon = filter_input(INPUT_POST, 'telefoon');
$sql = 'INSERT INTO klant(voornaam, achternaam, emailadres, postcode, huisnummer, woonplaats, adres, telefoon) VALUES' . $voornaam . $achternaam . $emailadres . $postcode . $huisnummer . $woonplaats . $adres . $telefoon;
$link->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Textbug - Klanten</title>
        <?php printStyles(); ?>
        <script type="text/javascript" src="../scripts/klantoverzicht.js"></script>
    </head>
    <body>
        <?php printHeader(); ?>


        <form class="pageElement" id="newClientForm" action="#" method="post">
            <input type="text" name="voornaam" placeholder="voornaam"/>
            <input type="text" name="achternaam" placeholder="achternaam"/>
            <input type="text" name="emailadres" placeholder="emailadres"/>
            <input type="text" name="postcode" placeholder="postcode"/>
            <input type="text" name="huisnummer" placeholder="huisnummer"/>
            <input type="text" name="woonplaats" placeholder="woonplaats"/>
            <input type="text" name="adres" placeholder="adres"/>
            <input type="text" name="telefoon" placeholder="telefoon" />
            <input type="submit" />
        </form>

        <?php
        $klanten = $link->query("SELECT * FROM klant WHERE admin != 1");
        while ($klant = $klanten->fetch_assoc()) {
            echo "<div id='klant" . $klant["klantID"] . "' class='pageElement flexRowSpace'><h2>" . $klant["voornaam"] . " " . $klant["achternaam"] . "</h2>";
            echo "<img class='icon iconEdit' src='../imgs/pencil1.svg' alt='icoon-bewerken' onclick='editKlant(" . $klant["klantID"] . ")' /></div></div>";
        }
        ?>

        <?php printFooter(); ?>
    </body>
</html>
