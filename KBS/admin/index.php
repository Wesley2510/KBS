<!DOCTYPE html>

<?php
include_once '../global.php';
?>

<html>
    <head>
        <title>TextBug - Admin</title>
        
        <?php printStyles(); ?>
        
        <style type="text/css">
            .pageElementButton {
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
        
        <a href="menubeheer.php" class="link"><div class="pageElement pageElementButton"><h2>Menubeheer</h2></div></a>
        
    <?php printFooter(); ?>
        
    </body>
</html>

