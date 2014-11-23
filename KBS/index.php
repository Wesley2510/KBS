<!DOCTYPE html>

<?php
$link = new mysqli("127.0.0.1", "root", "password", "Textbug", 3306);

if ($link->connect_error) {
  trigger_error('Database connection failed: '  . $link->connect_error, E_USER_ERROR);
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>TextBug - Frontpage</title>

        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
    </head>
    <body>


        <nav id="headerbar">
            <section><h5>TextBug</h5></section>
            <?php
            //Haal alle text columns uit tabel Menuitem
            $menuitems = $link->query("SELECT text FROM Menuitem;");
            if($menuitems === false) {
                trigger_error("SQL: \"" . sql .  "\" \n\r Error: \"" . $link->error, E_USER_ERROR);
            } else {
                $menuitems->data_seek(0);
                while($row = $menuitems->fetch_assoc()) {
                    //Plaats de text columns met behulp van <section> tags in het menu
                    echo "<section><a href =\"index.php?p=" . $row["text"] . "\" ><h4>" . $row["text"] . "</h4></a></section>";
                }
            }
            ?>
            <section><a href="Login.php"><h4>Login</h4></a></section>
        </nav>

        <?php
            //Selecteer alle berichten met bijbehorende datums van de gewenste pagina
            //Subquery: vertaal de text van menuItems in een pagina ID
            $sql = "SELECT bericht, datum FROM Bericht WHERE pagina IN (SELECT pagina FROM Pagina WHERE naam='" . filter_input(INPUT_GET, "p") . "');";
            
            $berichten = $link->query($sql);
            if($berichten === false) {
                trigger_error("SQL query: \"" . sql .  "\" \n\r Error: \"" . $link->error, E_USER_ERROR);
            } else {
                $berichten->data_seek(0);
                while($row = $berichten->fetch_assoc()) {
                    //Plaats alle berichten in een <div> container met class pageElement
                    echo "<div class=\"pageElement\">" . $row["bericht"] . "</div>\n";
                }
            }
        ?>
    </body>
</html>
