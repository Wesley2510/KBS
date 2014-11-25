<?php

/*
Lewis Clement
*/

 $GLOBALS["link"] = new mysqli("127.0.0.1", "root", "usbw", "Textbug", 3307);

if ( $GLOBALS["link"]->connect_error) {
  trigger_error('Database connection failed: '  . $link->connect_error, E_USER_ERROR);
}

function printHeader() {
   print("<nav id='headerbar'><section><h1>TextBug</h1></section>");
            
    //Haal alle text columns uit tabel Menuitem
    $menuitems = $GLOBALS["link"]->query("SELECT naam, positie FROM pagina;");
    if($menuitems === false) {
        trigger_error("SQL: \"" . sql .  "\" \n\r Error: \"" . $GLOBALS["link"]->error, E_USER_ERROR);
    } else {
        $list = array();

        while($row = $menuitems->fetch_assoc()) {
            //Plaats de menuitems op de juiste positie in de array $list
            $list[$row["positie"]] = $row["naam"];
        }
        $list[0] = NULL;

        for($i = 1; $i < count($list); $i++) {
            //Plaats de text columns met behulp van <section> tags in het menu
            echo "<section><a href ='index.php?p=" . $list[$i] . "' ><h2>" . $list[$i] . "</h2></a></section>";
        }
    }

    print("<section style='width:100%;'></section><section><a href='login.php'><h2>Login</h2></a></section></nav>");
}

function printFooter() {
    echo "<span id='footer'><div>Icons made by Stephen Hutchings from <a href='http://www.flaticon.com' title='Flaticon'>www.flaticon.com</a>         is licensed by <a href='http://creativecommons.org/licenses/by/3.0/' title='Creative Commons BY 3.0'>CC BY 3.0</a><br/>Gemaakt in opdracht van Hogeschool Windesheim</div></span>";
}

function printStyles() {
    echo "<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>";
    echo "<link rel='stylesheet' type='text/css' href='stylesheet.css' />";
}