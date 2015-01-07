<!--
Sander de Wilde
-->

<?php
include_once("global.php");
?>
<html>
    <head>
        <?php
        printStyles();
        ?>
        <title>Contactformulier</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
        printHeader();
        ?>


        <form class="pageElement" method="post" action="#">

            <p>Voornaam: <input type ="text" name="Voornaam" /></p>
            <p>Achternaam: <input type ="text" name="Achternaam" /></p>
            <p>Email: <input type ="text" name="Email" /></p>
            <p>Bericht <textarea cols="50" rows="12" name="Bericht"></textarea></p>
            <p><input type="submit"/> <input type="reset" value="Opnieuw" /> <a href="index.php" role="button"> "terug naar de hoofdpagina"</a></p>


        </form>
    </body>
</html>


<?php
$eadres = ''; // Hier komt nog een email adres!
$onderwerp = 'Contactformulier';

$headers = "MIME-version: 1.0\r\n";
$headers .= "content-type: text/html;charset=utf-8\r\n";

$cnaam = ($_POST['Voornaam']);
$cachternaam = ($_POST['Achternaam']);
$Email = ($_POST['Email']);
$cbericht = ($_POST['Bericht']);


if (empty($cnaam)) {
    print '<p>Naam is verplicht</p>';
}
if (empty($cachternaam)) {
    print '<p>Achternaam is verplicht</p>';
}
if (empty($Email)) {
    print '<p>E-mail is verplicht</p>';
}
if (empty($cbericht)) {
    print '<p>Geen Bericht ingevuld</p>';
}

if ($cbericht == false) {
    $headers .= 'From: ' . $cnaam . ' ' . $cachternaam . '<' . $Email . '>';

    if (mail($eadres, $onderwerp, nl2br($cbericht), $headers)) {
        print '<p>Het bericht is succesvol verzonden!</p>';
    } else {
        print '<p>Helaas, er is wat fout gegaan tijdens het verzenden van het formulier.</p>';
    }
}
