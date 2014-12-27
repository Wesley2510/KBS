<?php 
include_once '../global.php';

if(!isset($_SESSION["loggedin"])) {
    header("Location: /login.php");
    die();
} else if ($_SESSION["admin"] == false) {
    header("Location: /admin/");
    die();
}

//Code om menuitem een positie omlaag te verplaatsen
$rowDown = filter_input(INPUT_POST, "rowDown");
if($rowDown != NULL && is_numeric($rowDown)) {
    $result = $link->query("SELECT paginaID FROM pagina WHERE positie =" . $rowDown);
    if(!$result) {
        trigger_error("Fout bij wijzigen positie pagina: " . $link->error, E_USER_ERROR);
        
        echo "false";
        die();
    } else {
        $tempID = $result->fetch_assoc()["paginaID"];

        $link->query("UPDATE pagina SET positie =" . $rowDown . " WHERE positie =" . ($rowDown + 1));
        $link->query("UPDATE pagina SET positie =" . ($rowDown + 1) . " WHERE paginaID =" . $tempID);
        $_SESSION["headerBarEdited"] = true;
        
        echo "true";
        die();
    }
}

//Code om menuItem te hernoemen
$menuItemEditedPos = filter_input(INPUT_POST, "menuItemEditedPos");
if($menuItemEditedPos != NULL && is_numeric($menuItemEditedPos)) {
    $menuItemEdited = preg_replace("/[^A-Za-z0-9 ]/", '', filter_input(INPUT_POST, "menuItemEdited"));
    if(!$link->query("UPDATE pagina SET naam ='" . $menuItemEdited . "' WHERE positie =" . $menuItemEditedPos)){
        trigger_error("Fout bij bewerken naam pagina: " . $link->error, E_USER_ERROR);
        
        echo "false";
        die();
    }
    $_SESSION["headerBarEdited"] = true;
    
    echo $menuItemEdited;
    die();
}

//Code om pagina te verwijderen
$paginaToDeletePos = filter_input(INPUT_POST, "paginaToDeletePos");
if($paginaToDeletePos != NULL && is_numeric($paginaToDeletePos)) {
    $result = $link->query("SELECT paginaID FROM pagina WHERE positie =" . $paginaToDeletePos);
    //Controleer of paginaID gevonden is
    if(!$result) {
        trigger_error("Fout bij ophalen paginaID: " . $link->error, E_USER_ERROR);
        
        echo "false";
        die();
    } else {
        $paginaToDeleteID = $result->fetch_assoc()["paginaID"];
        //Controleer of berichten verwijderd zijn
        if(!$link->query("DELETE FROM bericht WHERE pagina =" . $paginaToDeleteID)){
            trigger_error("Fout bij verwijderen berichten: " . $link->error, E_USER_ERROR);
            
            echo "false";
            die();
        } else {
            //Controleer of pagina verwijderd is
            if(!$link->query("DELETE FROM pagina WHERE paginaID =" . $paginaToDeleteID)) {
                trigger_error("Fout bij verwijderen pagina: " . $link->error, E_USER_ERROR);
                
                echo "false";
                die();
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
    
    $_SESSION["headerBarEdited"] = true;
    echo "true";
    die();
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
    
    $_SESSION["headerBarEdited"] = true;
    header('Location: #');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TextBug - Menubeheer</title>
        
        <?php printStyles(); ?>
        <script src='/scripts/menubeheer.js' type='text/javascript' charset='utf-8'></script>
    </head>
    <body>
        <div style='height:0px;top:0px;visibility:hidden;'>body</div>
        <?php printHeader() ?><div></div>
        
        <h1 class='pageElement' style='text-align:center;'>Menubeheer</h1>
        
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
                
                echo "<div id='menuItem" . $i . "' class='pageElement flexRowSpace' style='top:0px;' data-pos='" . $i . "'>";
                $moveIconVisible = "visible";
                if($i >= $listCount - 1) {
                    $moveIconVisible = "hidden";
                }
                echo "<a id='moveDown" . $i . "' class='icon' style='flex:1;text-align:left;visibility:" . $moveIconVisible . ";' onclick='moveMenuItem(this.parentNode);'><img src='../imgs/the13.svg' alt='Positie omlaag' class='icon iconDown'/><span class='iconText'>Positie omlaag</span></a>";
                echo "<h2 style='flex:1;text-align:left;' class='menuItemText'>" . $list[$i] . "</h2>";
                echo "<span style='flex:1;text-align:right;'>" . $aantalBerichten . " berichten</span>";
                echo "<a class='icon' style='flex:1;'  onclick='editMenuItem(this.parentNode.getAttribute(\"data-pos\")," . $aantalBerichten . ")'><span class='iconText'>Bewerk</span><img class='icon' src='../imgs/pencil1.svg' alt='icoon-bewerken'/></a></div>";
            }
        }
        ?>
        <div id="newPageElement" class="pageElement flexRowSpace"><a id='iconPlaats' class='icon' style='text-align:left;' onclick='createNewMenuItem()'><img class='icon' src='/imgs/square181.svg' alt=''/><span class='iconText'>Nieuwe pagina</span></a></div>
        
        <?php printFooter() ?>
        
        <script src='/scripts/userfunctions.js' type='text/javascript' charset='utf-8'></script>
    </body>
</html>