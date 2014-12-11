<!DOCTYPE html>

<?php 
include_once '../global.php';

if(!isset($_SESSION["loggedin"]) && $_SESSION["admin"] == true) {
    header("Location: /login.php");
    die();
}

//Code om menuitem een positie omlaag te verplaatsen
$rowDown = filter_input(INPUT_POST, "rowDown");
if($rowDown != NULL && is_numeric($rowDown)) {
    $result = $link->query("SELECT paginaID FROM pagina WHERE positie =" . $rowDown);
    if(!$result) {
        trigger_error("Fout bij wijzigen positie pagina: " . $link->error, E_USER_ERROR);
    } else {
        $tempID = $result->fetch_assoc()["paginaID"];

        $link->query("UPDATE pagina SET positie =" . $rowDown . " WHERE positie =" . ($rowDown + 1));
        $link->query("UPDATE pagina SET positie =" . ($rowDown + 1) . " WHERE paginaID =" . $tempID);
        header( 'Location: #' ) ;
    }
}

//Code om menuItem te hernoemen
$menuItemEditedPos = filter_input(INPUT_POST, "menuItemEditedPos");
if($menuItemEditedPos != NULL && is_numeric($menuItemEditedPos)) {
    $menuItemEdited = preg_replace("/[^A-Za-z0-9 ]/", '', filter_input(INPUT_POST, "menuItemEdited"));
    if(!$link->query("UPDATE pagina SET naam ='" . $menuItemEdited . "' WHERE positie =" . $menuItemEditedPos)){
        trigger_error("Fout bij bewerken naam pagina: " . $link->error, E_USER_ERROR);
    }
    header( 'Location: #' ) ;
}

//Code om pagina te verwijderen
$paginaToDeletePos = filter_input(INPUT_POST, "paginaToDeletePos");
if($paginaToDeletePos != NULL && is_numeric($paginaToDeletePos)) {
    $result = $link->query("SELECT paginaID FROM pagina WHERE positie =" . $paginaToDeletePos);
    //Controleer of paginaID gevonden is
    if(!$result) {
        trigger_error("Fout bij ophalen paginaID: " . $link->error, E_USER_ERROR);
    } else {
        $paginaToDeleteID = $result->fetch_assoc()["paginaID"];
        //Controleer of berichten verwijderd zijn
        if(!$link->query("DELETE FROM bericht WHERE pagina =" . $paginaToDeleteID)){
            trigger_error("Fout bij verwijderen berichten: " . $link->error, E_USER_ERROR);
        } else {
            //Controleer of pagina verwijderd is
            if(!$link->query("DELETE FROM pagina WHERE paginaID =" . $paginaToDeleteID)) {
                trigger_error("Fout bij verwijderen pagina: " . $link->error, E_USER_ERROR);
            } else {
                $paginasHigherPos = $link->query("SELECT paginaID FROM pagina WHERE positie > " . $paginaToDeletePos . " ORDER BY positie ASC;");
                $i = 0;
                while($row = $paginasHigherPos->fetch_assoc()) {
                    $pID = $row["paginaID"];
                    if($pID === NULL) {
                        break;
                    }

                    $link->query("UPDATE pagina SET positie=" . ($paginaToDeletePos + $i) . " WHERE paginaID=" . $pID);
                    $i++;
                }
            }
        }
    }
    
    header( 'Location: #' ) ;
}

//Code om menuItem aan te maken
$newMenuItemName = filter_input(INPUT_POST, "newMenuItemName");
if($newMenuItemName != NULL) {
    if(!(ltrim($newMenuItemName, ' ') === '')) {
        $result = $link->query("SELECT MAX(positie) AS max FROM pagina;");
        if(!$result) {
            trigger_error("Fout opvragen hoogste positie: " . $link->error, E_USER_ERROR);
        } else {
            $highestPos = $result->fetch_assoc()["max"];
            if(!$link->query("INSERT INTO pagina(naam, positie) VALUES ('" . $newMenuItemName . "'," . ($highestPos + 1) . ");")) {
                trigger_error("Fout bij aanmaken nieuwe pagina: " . $link->error, E_USER_ERROR);
            }
        }
    }
    header('Location: #');
}
?>

<html>
    <head>
        <title>TextBug - Menubeheer</title>
        
        <?php printStyles(); printScripts(); ?>
    </head>
    <body>
        
        <?php printHeader() ?>
        
        <?php 
        $menuitems = $link->query("SELECT paginaID, naam, positie FROM pagina WHERE positie != 0;");
        if($menuitems === false) {
            trigger_error("Error:" . $link->error, E_USER_ERROR);
        } else {
            $list = array();

            while($row = $menuitems->fetch_assoc()) {
                //Plaats de menuitems op de juiste positie in de array $list
                $list[$row["positie"]] = $row["naam"];
            }
            $list[0] = NULL;

            $listCount = count($list);
            for($i = 1; $i < $listCount; $i++) {
                $sql = "SELECT COUNT(berichtID) AS aantal FROM bericht WHERE pagina = (SELECT paginaID FROM pagina WHERE positie =" . $i . ");";
                $result = $link->query($sql);
                if(!$result) {
                    trigger_error("Fout bij ophalen aantal berichten: " . $link->error, E_USER_ERROR);
                    $aantalBerichten = "FOUT";
                } else {
                    $aantalBerichten = $result->fetch_assoc()["aantal"];
                }
                
                echo "<div id='menuItem" . $i . "' class='pageElement flexRowSpace'>";
                if($i < $listCount - 1) {
                    echo "<form id='moveDownForm" . $i . "' action='#' method='post'><input form='moveDownForm" . $i . "' type='hidden' name='rowDown' value='" . $i . "' /><img src='../imgs/the13.svg' alt='Positie omlaag' class='iconDown' onclick='moveMenuItem(" . $i . ");'/></form>";
                } else { echo "<div class='iconDown' style='cursor: default;'></div>"; }
                echo "<div class='flexRowSpace flexAdjust'><h2 id='menuItemText" . $i . "'>" . $list[$i] . "</h2>";
                echo $aantalBerichten . " berichten</div>";
                echo "<img class='iconEdit' src='../imgs/pencil1.svg' alt='icoon-bewerken' onclick='editMenuItem(" . $i . "," . $aantalBerichten . ")' /></div>";
            }
        }
        ?>
        <div id="newPageElement" class="pageElement flexRowSpace"><a role="button" onclick="createNewMenuItem()">Nieuwe pagina</a></div>
        
        <?php printFooter() ?>
        
    </body>
</html>