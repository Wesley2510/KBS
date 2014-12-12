<!DOCTYPE html>

<?php
include_once("../global.php");
?>

<html style="height: 100%;">
    <head>
        <meta charset="UTF-8">
        <title>Textbug - Bestandsbeheer</title>
        <?php printStyles(); printScripts(); ?>
    </head>
    <body style="min-height:100%; position:absolute; top:0; bottom:0; right:0; left:0;">
    <?php printHeader(); ?>
        <div class="pageElement" style="height:calc(100% - 5rem);">
            <iframe src="/filemanager/dialog.php?type=0" style="width: 100%; height:100%;"></iframe>
        </div>
    </body>
</html>
