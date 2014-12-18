<?php

 require_once('mpdf.php');
include_once ('global.php');



$link = $GLOBALS["link"];
$query1 = "SELECT voornaam, achternaam, adres, postcode, huisnummer, woonplaats FROM factuur JOIN klant ON klant=klantid WHERE factuurID=1 ";
//$query2 = "SELECT datum FROM factuur WHERE factuurid=1 ";
$query3 = "SELECT service, prijs FROM factuur WHERE factuurID=1 ";
$klantnaam = mysqli_fetch_assoc(mysqli_query($link, $query1));
//$datum == mysqli_fetch_assoc(mysqli_query($link, $query2));
$dienst = mysqli_fetch_assoc(mysqli_query($link, $query3));

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
<div class="datum">Factuurdatum: "// . $datum["datum"]<br></div>
<div class="bedrijfsgegevens">Bedrijfsnaam: Textbug<br>
Adres: Weetikveellaan 37<br>
E-mail: admin@textbug.com<br></div>
<div class="klantgegevens">Voornaam: '.$klantnaam["voornaam"].'<br>
        Acternaam: ' . $klantnaam["achternaam"] . '<br>
        Adres: ' .$klantnaam["adres"]." ".$klantnaam["huisnummer"].'<br>
        postcode: '.$klantnaam["postcode"]."    ".$klantnaam["woonplaats"].'<br>
<table><tr><th width=150mm >omschrijving</th><th width=30mm >prijs</th></tr>
<tr><td width=150mm >'.$dienst["service"].'</td><td width=30mm >'.$dienst["prijs"].'</td></tr></table>
</body>');

 

$mpdf->Output();

exit;
?>