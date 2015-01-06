<!--
Sander de Wilde
-->
<?php
$link = mysql_connect('localhost', 'root', 'usbw');
if (!$link) {
    die("Kan niet met de database verbinden. " . mysql_errno());
}
echo "verbinding succesvol";

mysql_select_db($database_name);


mysql_close($link);
?>





































