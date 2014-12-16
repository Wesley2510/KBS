<?php

include_once ('global.php');
require('fpdf.php');

$link = $GLOBALS["link"];
$query1 = "SELECT voornaam, achternaam, adres, postcode, huisnummer, woonplaats FROM factuur JOIN klant ON klant=klantid WHERE factuurid=1 ";
//$query2 = "SELECT datum FROM factuur WHERE factuurid=1 ";
$query3 = "SELECT service, prijs FROM factuur WHERE factuurid=1 ";
$klantnaam = mysqli_fetch_assoc(mysqli_query($link, $query1));
//$datum == mysqli_fetch_assoc(mysqli_query($link, $query2));
$dienst = mysqli_fetch_assoc(mysqli_query($link, $query3));
$euro = iconv('UTF-8', 'windows-1252', "€");


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 26);
$pdf->Cell(80);
$pdf->Cell(40, 10, "Textbug", 0, 1);
$pdf->SetFont('Arial', 'I', 14);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(40, 10, "Factuurdatum: "// . $datum["datum"]);
        );
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(40, 10, "Bedrijfsnaam: Textbug");
$pdf->Ln();
$pdf->Cell(40, 10, "Adres: Weetikveellaan 37");
$pdf->Ln();
$pdf->Cell(40, 10, "E-mail: admin@textbug.com");
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(40, 10, "Voornaam: " . $klantnaam["voornaam"], 0, 1);
$pdf->cell(40, 10, "Acternaam: " . $klantnaam["achternaam"], 0, 1);
$pdf->Cell(40, 10, "Adres: ".$klantnaam["adres"]." ".$klantnaam["huisnummer"], 0, 1);
$pdf->Cell(40, 10, "postcode: ".$klantnaam["postcode"]." ".$klantnaam["woonplaats"], 0, 1);
$pdf->Ln();
$pdf->Ln();
$pdf->cell(140, 10, "Omschrijving dienst");
$pdf->cell(40, 10, "prijs", 0, 1);

$pdf->Ln();
$pdf->cell(140, 10, $dienst["service"]);
$pdf->cell(40, 10, $euro." ".$dienst["prijs"]);
$pdf->Output();
mysqli_free_result($result);

mysqli_close($link);
?>