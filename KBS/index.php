<!DOCTYPE html>

<?php
$link = new mysqli("127.0.0.1", "root", "password", "Textbug", 3306);

if ($link->connect_error) {
  trigger_error('Database connection failed: '  . $link->connect_error, E_USER_ERROR);
}

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


        <nav id="headerbar">
            <section><h5>TextBug</h5></section>
            <?php
            //Haal alle text columns uit tabel Menuitem
            $menuitems = $link->query("SELECT naam FROM pagina;");
            if($menuitems === false) {
                trigger_error("SQL: \"" . sql .  "\" \n\r Error: \"" . $link->error, E_USER_ERROR);
            } else {
                $menuitems->data_seek(0);
                while($row = $menuitems->fetch_assoc()) {
                    //Plaats de text columns met behulp van <section> tags in het menu
                    echo "<section><a href =\"index.php?p=" . $row["naam"] . "\" ><h4>" . $row["naam"] . "</h4></a></section>";
                }
            }
            ?>
            <section><a href="Login.php"><h4>Login</h4></a></section>
        </nav>

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
                    echo "<div class=\"pageElement\">" . $row["inhoud"] . "</div>\n";
                }
            }
        ?>
    </body>
</html>
