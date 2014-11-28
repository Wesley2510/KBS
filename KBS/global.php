<?php

/*
Lewis Clement
*/

date_default_timezone_set('Europe/Amsterdam');

 $GLOBALS["link"] = new mysqli("127.0.0.1", "root", "usbw", "Textbug", 3307);

if ( $GLOBALS["link"]->connect_error) {
  trigger_error('Database connection failed: '  . $link->connect_error, E_USER_ERROR);
}

function printHeader() {
   echo "<nav id='headerbar'><div><h1>TextBug</h1></div>";
            
    //Haal alle text columns uit tabel Menuitem
    $menuitems = $GLOBALS["link"]->query("SELECT naam, positie FROM pagina;");
    if($menuitems === false) {
        trigger_error("Error:" . $GLOBALS["link"]->error, E_USER_ERROR);
    } else {
        $list = array();

        while($row = $menuitems->fetch_assoc()) {
            //Plaats de menuitems op de juiste positie in de array $list
            $list[$row["positie"]] = $row["naam"];
        }
        $list[0] = NULL;

        for($i = 1; $i < count($list); $i++) {
            echo "<div class='menuItem'><a href='/index.php?p=" . $list[$i] . "' ><h2>" . $list[$i] . "</h2></a></div>";
        }
    }

    echo "<div style='flex:1;'></div><div class='menuItem'><a href='/admin/'><h2>Login</h2></a></div></nav>";
}

function printFooter() {
    echo "<div id='footer'><div>Icons made by Stephen Hutchings from <a href='http://www.flaticon.com' title='Flaticon'>www.flaticon.com</a> is licensed by <a href='http://creativecommons.org/licenses/by/3.0/' title='Creative Commons BY 3.0'>CC BY 3.0</a><br/>Gemaakt in opdracht van Hogeschool Windesheim</div></div>";
}

function printStyles() {
    echo "<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>\n\t\t";
    echo "<link rel='stylesheet' type='text/css' href='/styles/stylesheet.css' />\n\t\t";
}

function printScripts() {
    //if(admin ingelogd)
    {
        echo "<script src='/scripts/adminfunctions.js' type='text/javascript' charset='utf-8'></script>\n";
    }
}