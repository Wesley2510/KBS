<!DOCTYPE html>

<?php
include_once("global.php");

$inputP = filter_input(INPUT_GET, "p");
//Als er geen "p" in de URL aangegeven is terugvallen op de menuitem met de laagste positie (aka. de eerste link)
if($inputP === NULL) {
    $rows = $link->query("SELECT naam FROM pagina WHERE positie = 1;");
    if($rows === false) {
    trigger_error("SQL query: \"" . sql .  "\" \n\r Error: \"" . $link->error, E_USER_ERROR);
    } else {
        $rows->data_seek(0);
        $row = $rows->fetch_assoc();
        $inputP = $row["naam"];
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
        //Selecteer alle berichten met bijbehorende datums van de gewenste pagina
        //Subquery: vertaal de text van menuitems in een pagina ID
        $sql = "SELECT inhoud, datum FROM bericht WHERE pagina IN (SELECT paginaID FROM pagina WHERE naam='" . $inputP . "');";

        $berichten = $link->query($sql);
        if($berichten === false) {
            trigger_error("SQL query: \"" . sql .  "\" \n\r Error: \"" . $link->error, E_USER_ERROR);
        } else {
            $berichten->data_seek(0);
            while($row = $berichten->fetch_assoc()) {
                //Plaats alle berichten in een <div> container met class pageElement
                echo "<div class=\"pageElement\"><span class=\"datum\">" . $row["datum"] . "</span><br/><span class=\"content\">" . $row["inhoud"] . "</span></div>\n";
            }
        }
    ?>
    </body>
</html>
