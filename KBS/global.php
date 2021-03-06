<?php

/*
Lewis Clement
*/

session_start();

date_default_timezone_set('Europe/Amsterdam');

$GLOBALS["link"] = new mysqli("127.0.0.1", "root", "usbw", "Textbug", 3307);

if ( $GLOBALS["link"]->connect_error) {
  trigger_error('Database connection failed: '  . $link->connect_error, E_USER_ERROR);
}

function printHeader() {
    if(!isset($_SESSION["headerBarHTML"]) || $_SESSION["headerBarEdited"] == true) {
        $HTML = "<nav id='headerbar'><div><h1>TextBug</h1></div>";

         //Haal alle text columns uit tabel Menuitem
         $menuitems = $GLOBALS["link"]->query("SELECT naam, positie FROM pagina;");
         if($menuitems === false) {
             trigger_error("Error:" . $GLOBALS["link"]->error, E_USER_ERROR);
             $HTML .= "<div class='menuItem'><a><h2>Probleem bij laden</h2></a></div>";
         } else {
             $list = array();

             while($row = $menuitems->fetch_assoc()) {
                 //Plaats de menuitems op de juiste positie in de array $list
                 $list[$row["positie"]] = $row["naam"];
             }
             $list[0] = NULL;

             for($i = 1; $i < count($list); $i++) {
                 $HTML .= "<div id='menu" . $i . "' class='menuItem' style='left: 0px;'><a href='/index.php?p=" . $list[$i] . "' ><h2>" . $list[$i] . "</h2></a></div>";
             }
         }

         $HTML .= "<div style='flex:1;'></div><div class='menuItem'>";
         $_SESSION["headerBarHTML"] = $HTML;
         $_SESSION["headerBarEdited"] = false;
    }
    
    echo $_SESSION["headerBarHTML"];
    if(!isset($_SESSION["loggedin"])) {
        echo "<a href='/login.php'><h2>Login</h2></a></div></nav>";
    } else {
        echo "<a href='/admin/'><h2>" . $_SESSION["voornaam"] . " " . $_SESSION["achternaam"] . "</h2></a></div></nav>";
    }
    
    echo "<div id='backgroundDiv' style='position:absolute; overflow:hidden;z-index:-1;'>";
    echo "<img class='backgroundText' src='/backgroundtext1.png' alt='' />";
    //echo "<img class='backgroundText' src='/SOMEOTHERPICTURES.png' alt='' />";
    echo "</div>";
}

function printFooter() {
    echo "<div id='footer'>";
    if(isset($_SESSION["loggedin"]) && $_SESSION["admin"] == true) {
        echo "<div>Icons made by Stephen Hutchings and Freepik from <a href='http://www.flaticon.com' title='Flaticon'>www.flaticon.com</a> is licensed by <a href='http://creativecommons.org/licenses/by/3.0/' title='Creative Commons BY 3.0'>CC BY 3.0</a></div>";
    }
    echo "Gemaakt in opdracht van Hogeschool Windesheim</div>";
}

function printStyles() {
    echo "<meta name=viewport content='width=device-width, initial-scale=1'> ";
    echo "<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>\n\t\t";
    echo "<link rel='stylesheet' type='text/css' href='/styles/stylesheet.css' />\n\t\t";
}

function printScripts() {
    echo "<script src='/scripts/userfunctions.js' type='text/javascript' charset='utf-8'></script>\n";
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["admin"] == true) {
        echo "<script src='/scripts/adminfunctions.js' type='text/javascript' charset='utf-8'></script>\n";
    }
}

function checkPass($pass) {
    if ($pass == NULL || ltrim($pass, ' ') == '') {
        return 1;
    } else if (preg_replace("/[^A-Za-z0-9 ]/", '', $pass) != $pass) {
        return 2;
    }
    return 0;
}
