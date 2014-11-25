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
        
        //Als er in de query geen id gevonden is bestaat de pagina niet, dus wordt er naar 404 doorverwezen
        if($pID === NULL) {
            header("Location: 404.php");
            die();
        }
    }
}


//Vind uit op welke "bladzijde" de gebruiker zich bevind
$inputB = filter_input(INPUT_GET, "b");
if(!is_numeric($inputB)) {$inputB = 0;}


/* Als er een bericht toegevoegd wordt, plaats in database */
$inputBericht = filter_input(INPUT_POST, "bericht");
if($inputBericht != NULL) {
    //Controleer of input niet alleen uit spaties bestaat
    if(!(ltrim($inputBericht, ' ') === '')) {
        $day = date("Y-m-d H:i:s", getdate()[0]);
        $link->query("INSERT INTO bericht (inhoud, datum, pagina) VALUES (\"" . $inputBericht . "\",\"" . $day . "\"," . $pID . ");");
    }
    header( 'Location: #' ) ;
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <?php echo "<title>TextBug - " . $inputP . "</title>"; ?>

        <?php printStyles(); ?>
        
        <script src="adminfunctions.js" type="text/javascript" charset="utf-8"></script>
    </head>
    <body>

    <?php printHeader(); ?>

    <?php
        $sql = "SELECT COUNT(berichtID) AS aantal FROM bericht WHERE pagina =" . $pID;
        $aantalBerichten = $link->query($sql)->fetch_assoc();
        $unit = "berichten";
        if ($aantalBerichten["aantal"] === "1") {
            $unit = "bericht";
        }
        echo "<div class=\"pageElement\"><div class=\"flexRowSpace\"><a class=\"button\" onclick=\"composeMessage();\" href=\"#\">Nieuw bericht</a><span class=\"textRightAlign\">" . $aantalBerichten["aantal"] . " " . $unit . "</span></div></div>";
    
        //Selecteer alle berichten met bijbehorende datums van de gewenste pagina
        //Subquery: vertaal de text van menuitems in een pagina ID
        $sql = "SELECT inhoud, datum FROM bericht WHERE pagina =" . $pID . " ORDER BY datum DESC LIMIT " . ($inputB*5) . ", 5;";

        $berichten = $link->query($sql);
        if($berichten === false) {
            trigger_error("SQL query: \"" . $sql .  "\" \n\r Error: \"" . $link->error, E_USER_ERROR);
        } else {
            while($row = $berichten->fetch_assoc()) {
                //Plaats alle berichten in een <div> container met class pageElement
                echo "<div class=\"pageElement\"><span class=\"datum\">" . date("d-m-Y", strtotime($row["datum"])) . "</span><br/><span class=\"content\">" . $row["inhoud"] . "</span></div>\n";
            }
            
            if($aantalBerichten["aantal"] > 5) {
                echo "<div class='pageElement flexRowSpace'>";
                
                if( !( ($inputB+1)*5 > $aantalBerichten["aantal"]) ) {
                    echo "<a class='button' href='?p=" . $inputP . "&b=" . ($inputB+1) . "'>Oudere berichten</a>";
                } else { echo "<a></a>";} //Zodat de "Nieuwere berichten" knop rechts komt te staan
                
                if ($inputB > 0) {
                    echo "<a class='button' href='?p=" . $inputP . "&b=" . ($inputB-1) . "'>Nieuwere berichten</a>";
                }
                
                echo "</div>";
            }
        }
    ?>
        
    <?php printFooter(); ?>
    </body>
</html>
