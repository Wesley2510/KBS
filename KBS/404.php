<!DOCTYPE html>

<?php
http_response_code(404);
include_once("global.php");
?>

<html>
    <head>
        <meta charset="UTF-8">
        <?php echo "<title>TextBug - 404</title>"; ?>

        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
    </head>
    <body>

    <?php printHeader(); ?>
        
    <h1 style="width:100%;height:100%;text-align:center;margin-top:20%;">Deze pagina bestaat niet!</h1>
    </body>
</html>
