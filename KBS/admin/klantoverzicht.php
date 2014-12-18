<!--
Lewis Clement
-->

<?php
include_once "../global.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Textbug - Klanten</title>
        <?php printStyles(); ?>
    </head>
    <body>
        <?php printHeader(); ?>

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
