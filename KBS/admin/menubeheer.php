<!DOCTYPE html>

<?php 
include_once '../global.php';

//Code om menuitem een positie omlaag te verplaatsen
$rowDown = filter_input(INPUT_POST, "rowDown");
if($rowDown != NULL && is_numeric($rowDown)) {
    $tempID = $GLOBALS["link"]->query("SELECT paginaID FROM pagina WHERE positie =" . $rowDown)->fetch_assoc()["paginaID"];
    $GLOBALS["link"]->query("UPDATE pagina SET positie =" . $rowDown . " WHERE positie =" . ($rowDown + 1));
    $GLOBALS["link"]->query("UPDATE pagina SET positie =" . ($rowDown + 1) . " WHERE paginaID =" . $tempID);
    header( 'Location: #' ) ;
}

//Code om menuItem te hernoemen
$menuItemEditedPos = filter_input(INPUT_POST, "menuItemEditedPos");
if($menuItemEditedPos != NULL && is_numeric($menuItemEditedPos)) {
    $menuItemEdited = filter_input(INPUT_POST, "menuItemEdited");
    $GLOBALS["link"]->query("UPDATE pagina SET naam ='" . $menuItemEdited . "' WHERE positie =" . $menuItemEditedPos);
    header( 'Location: #' ) ;
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
        $menuitems = $GLOBALS["link"]->query("SELECT paginaID, naam, positie FROM pagina WHERE positie != 0;");
        if($menuitems === false) {
            trigger_error("Error:" . $GLOBALS["link"]->error, E_USER_ERROR);
        } else {
            $list = array();

            while($row = $menuitems->fetch_assoc()) {
                //Plaats de menuitems op de juiste positie in de array $list
                $list[$row["positie"]] = $row["naam"];
            }
            $list[0] = NULL;

            $listCount = count($list);
            for($i = 1; $i < $listCount; $i++) {
                echo "<div id='menuItem" . $i . "' class='pageElement flexRowSpace'>";
                if($i < $listCount - 1) {
                    echo "<form id='moveDownForm" . $i . "' action='#' method='post'><input form='moveDownForm" . $i . "' type='hidden' name='rowDown' value='" . $i . "' /><img src='../imgs/the13.svg' alt='Positie omlaag' class='iconDown' onclick='moveMenuItem(" . $i . ");'/></form>";
                } else { echo "<div class='iconDown' style='cursor: default;'></div>"; }
                echo "<h2 id='menuItemText" . $i . "'>" . $list[$i] . "</h2>";
                echo "<img class='iconEdit' src='../imgs/pencil1.svg' alt='icoon-bewerken' onclick='editMenuItem(" . $i . ")' /></div>";
            }
        }
        ?>
        
        <?php printFooter() ?>
        
    </body>
</html>