<!--
Sander de Wilde
-->
<?php
$link = mysql_connect('localhost', 'root', 'usbw');
if (!$link) {
    die("Kan niet met de database verbinden. " . mysql_errno());
}
echo "verbinding succesvol";
mysql_select_db('textbug');

require_once("stats.php");
$stats = new statistieken;
echo $stats->show_stats(10);
