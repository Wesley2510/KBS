<!DOCTYPE html>

<?php
include_once("global.php");

$inputP = filter_input(INPUT_GET, "p");
$pID = null;
//Als er geen "p" in de URL aangegeven is terugvallen op de menuitem met de laagste positie (aka. de eerste link)
//Vertaal tevens de naam naar paginaID
if($inputP === NULL) {
    $rows = $link->query("SELECT paginaID, naam FROM pagina WHERE positie = 1;");
    if($rows === false) {
        trigger_error(" \n\r Error: \"" . $link->error, E_USER_ERROR);
    } else {
        $row = $rows->fetch_assoc();
        $inputP = $row["naam"];
        $pID = $row["paginaID"];
    }
} else {
    $rows = $link->query("SELECT paginaID FROM pagina WHERE naam = \"" . $inputP . "\";");
    if($rows === false) {
        trigger_error(" \n\r Error: \"" . $link->error, E_USER_ERROR);
    } else {
        $row = $rows->fetch_assoc();
        $pID = $row["paginaID"];
        
        if($pID === NULL) {
            header("Location: 404.php");
            die();
        }
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <?php echo "<title>TextBug - " . $inputP . "</title>"; ?>

        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
    </head>
    <body>

    <?php printHeader(); ?>

    <?php
        $sql = "SELECT COUNT(berichtID) AS A FROM bericht WHERE pagina =" . $pID;
        $aantalBerichten = $link->query($sql)->fetch_assoc();
        $unit = "berichten";
        if ($aantalBerichten["A"] === "1") {
            $unit = "bericht";
        }
        echo "<div class=\"pageElement topBarElement\"><a class=\"button\" href=\"#\">Nieuw bericht</a><span class=\"textRightAlign\">" . $aantalBerichten["A"] . " " . $unit . "</span></div>";
    
        //Selecteer alle berichten met bijbehorende datums van de gewenste pagina
        //Subquery: vertaal de text van menuitems in een pagina ID
        $sql = "SELECT inhoud, datum FROM bericht WHERE pagina =" . $pID;

        $berichten = $link->query($sql);
        if($berichten === false) {
            trigger_error("SQL query: \"" . $sql .  "\" \n\r Error: \"" . $link->error, E_USER_ERROR);
        } else {
            while($row = $berichten->fetch_assoc()) {
                //Plaats alle berichten in een <div> container met class pageElement
                echo "<div class=\"pageElement\"><span class=\"datum\">" . date("d-m-Y", strtotime($row["datum"])) . "</span><br/><span class=\"content\">" . $row["inhoud"] . "</span></div>\n";
            }
        }
    ?>
    </body>
</html>
