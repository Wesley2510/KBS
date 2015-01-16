<?php

 require_once('mpdf/mpdf.php');
include_once ('global.php');



$link = $GLOBALS["link"];
$klantID = filter_input(INPUT_GET, "id");
$query1 = "SELECT voornaam, achternaam, adres, postcode, huisnummer, woonplaats FROM factuur JOIN klant ON klant=klantid WHERE factuurID=" . $klantID;
//$query2 = "SELECT datum FROM factuur WHERE factuurID=" . $klantID;
$query3 = "SELECT service, prijs FROM factuur WHERE factuurID=" . $klantID;
$klantnaam = $link->query($query1)->fetch_assoc();
//$datum = $link->query($query2)->fetch_assoc();
$dienst = $link->query($query3)->fetch_assoc();

$mpdf = new mPDF();



$mpdf->WriteHTML('<head>
<style> 
  h1   {text-align: center;}
  .klantgegevens {margin-top: 20mm;}
  .bedrijfsgegevens {margin-top: 10mm;}
  .datum {text-align: right;
          margin-top: 10mm;}
  table, tr, td {
   border: 0.25mm solid black;
   margin-top: 30mm; 
}        
</style>
</head>
<body>
<div> <h1>Textbug</h1></div>
<div class="datum">Factuurdatum: Work in progress<br></div>
<div class="bedrijfsgegevens">Bedrijfsnaam: Textbug<br>
Adres: Weetikveellaan 37<br>
E-mail: admin@textbug.com<br></div>
<div class="klantgegevens">Voornaam: '.$klantnaam["voornaam"].'<br>
        Acternaam: ' . $klantnaam["achternaam"] . '<br>
        Adres: ' .$klantnaam["adres"]." ".$klantnaam["huisnummer"].'<br>
        postcode: '.$klantnaam["postcode"]."    ".$klantnaam["woonplaats"].'<br>
<table><tr><th width=150mm >omschrijving</th><th width=30mm >prijs</th></tr>
<tr><td width=150mm >' . urldecode($dienst["service"]) . '</td><td width=30mm >€' . number_format($dienst ["prijs"], 2) . '</td></tr></table>
</body>');

 

$mpdf->Output();

exit;
?>