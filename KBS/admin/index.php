<!DOCTYPE html>

<?php
include_once '../global.php';

if(session_status() == PHP_SESSION_NONE) {
    header("Location: /;");
}
?>

<html>
    <head>
        <title>TextBug - Admin</title>
        
        <?php printStyles(); ?>
        
        <style type="text/css">
            .pageElementButton {
                display: block;
                background-color: lightgrey;
                border:0.15rem solid black;
                text-align: center;
                color:black;
            }
            .pageElementButton:active {
                background-color: grey;
            }
            
            .link {
                text-decoration: none;
            }
        </style>
    </head>
    <body>
    
    <?php printHeader(); ?>
        
        
        
    <?php 
    //if (admin)
    {
        echo "<a href='menubeheer.php' class='link pageElement pageElementButton'><h2>Menubeheer</h2></a>";
    }
    echo "<a href='/templogout.php' class='link pageElement pageElementButton'><h2>Uitloggen</h2></a>";
    ?>
        
    <?php printFooter(); ?>
        
    </body>
</html>

