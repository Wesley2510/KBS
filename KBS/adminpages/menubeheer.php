<!DOCTYPE html>

<?php 
include_once '../global.php';

//Code om menuitem een positie omlaag te verplaatsen
$rowDown = filter_input(INPUT_POST, "rowDown");
if($rowDown != NULL && is_numeric($rowDown)) {
    $tempID = $GLOBALS["link"]->query("SELECT paginaID FROM pagina WHERE positie =" . $rowDown)->fetch_assoc()["paginaID"];
    $GLOBALS["link"]->query("UPDATE pagina SET positie=" . $rowDown . " WHERE positie =" . ($rowDown + 1));
    $GLOBALS["link"]->query("UPDATE pagina SET positie=" . ($rowDown + 1) . " WHERE paginaID =" . $tempID);
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
                echo "<div class='pageElement flexRowSpace'>";
                if($i < $listCount - 1) {
                    echo "<form id='moveDownForm" . $i . "' action='#' method='post'><input form='moveDownForm" . $i . "' type='hidden' name='rowDown' value='" . $i . "' /><img src='../imgs/the13.svg' alt='Positie omlaag' onclick='moveMenuItem(" . $i . ");' style='cursor: pointer;' /></form>";
                }
                echo "<span>" . $list[$i] . "</span>";
                echo "<div style='flex:1;'></div></div>";
            }
        }
        ?>
        
        <?php printFooter() ?>
        
    </body>
</html>