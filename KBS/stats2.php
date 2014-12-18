<!--
Sander de Wilde
-->

<?php
include_once("global.php");



## Eerst zorgen voor een mysql-connectie
$l1nk = mysql_connect('localhost', 'root', 'usbw');
if (!$l1nk) {
    die("Kan niet met de database verbinden. " . mysql_errno());
}
//echo "verbinding succesvol";
mysql_select_db('textbug');

## Statistieken class aanroepen
require_once("stats.php");
$stats = new statistieken(date("Y") . "-" . date("n"));
## date() zorgt voor goede maand
## Echo
echo $stats->show_stats(10);

/* de 10 geeft aan van hoeveel maanden hij de stats moeten laten zien.
  bij 1 laat hij dus de stats van de laatste maand zien */
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Textbug - Template</title>
        <?php
        printStyles();
        printScripts();
        ?>
    </head>
    <body>
<?php printHeader(); ?>



<?php printFooter(); ?>
    </body>
</html>