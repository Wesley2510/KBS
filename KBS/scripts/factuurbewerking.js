/* 
Wesley Oosterveen
 */

"use strict";

//een variabele waar de orginele HTML form in staat.
var originalHTML

//een functie wat de factuurForm submit, hier worden de gegevens van de form doorgestuurd naar de html
function submit() {
    if(document.forms["factuurForm"] !== undefined) {
        document.forms["factuurForm"].submit();
         
    }
}

//een functie om de aanpassing of toevoegen van een factuur te annuleren
//zodat de orginele html weer terug word gezet.
function cancelComposingFactuur() {
    //Controleer of de juiste form op de pagina is, en stop de originele HTML terug in de parent element
    if(document.forms["factuurForm"] !== undefined) {
        document.getElementById("factuurForm").parentNode.innerHTML = originalHTML;
    }
}

//een functie voor het toevoegen van facturen, als er geen factuurForm wordt gevonden, word het geannuleerd
//is deze er wel, dan zoekt hij de goede pageElement. De html form wordt nu in variabelen gezet zodat deze 
//getoont en gebruikt kunnen worden in de pageElement. er zijn 2 eventListerens die blijven kijken naar een klik
//zodat er zodra er op voeg toe of annuleren wordt gedrukt, er een actie uitgevoert kan worden.
function factuurToevoegen(klantID){
    
    if (document.getElementById("factuurForm") !== null) {
        cancelComposingMessage();
    }
    var pageElement = document.getElementById("voegToe");
   
  
    originalHTML = pageElement.innerHTML;
  var temp =    "<form id='factuurForm' method='post' action='factuur.php'>";
      temp +=   "Geleverde service:<br>";
      temp +=   "<input id='serviceFactuur' class='textbox' type='text' name='service'>";
      temp +=   "Bedrag:<br>";
      temp +=   "<input id='bedragFactuurF' class='textbox' type='number' name='bedrag'>";
      temp +=   "Betaald:";
      temp +=   "<input id='radioB' type='radio' name='betaald' value='betaald'>betaald";
      temp +=   "<input id='radioNB' type='radio' checked name='betaald' value='niet betaald' checked='true'>Niet betaald</br>";
      temp +=   "<div class='flexRowSpace'><a role='button' id='buttonSubmit'>Voeg toe</a>";
      temp +=   "<a role='button' id='buttonCancel'>Annuleren</a></div>";
      temp +=   "<input type='hidden' id='klantID' name='klantID' value='" + klantID + "'></form>";
      pageElement.innerHTML = temp;
    
    
    document.getElementById("buttonSubmit").addEventListener("click", submit);
    document.getElementById("buttonCancel").addEventListener("click", cancelComposingFactuur);
   
}
      

//een functie voor het bijwerken van facturen, dit werkt het zelfed als toevoegen
//alleen wordt hier ook het factuurID meegegeven zodat het goede factuur wordt aangepast.
function factuurBewerken(factuurID, klantID){
  if (document.getElementById("factuurForm") !== null) {
        cancelComposingMessage();
    }
   
  var pageElement   = document.getElementById("factuur" + factuurID);
  var service       = document.getElementById("factuurService" + factuurID).innerHTML;
  var bedrag        = document.getElementById("factuurBedrag" + factuurID).innerHTML;
  var radioB        = document.getElementById("radioB" + factuurID).innerHTML;
 
    
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='factuur.php' id='factuurForm' method='post'>";
    temp += "<input type='textbox' id='factuurFormService'  name='factuurServiceEdited'>";
    temp += "<input type='number' id='factuurFormBedrag'  name='factuurBedragEdited'>";
    if(radioB === "Betaald"){
      temp += "<input id='radioB' type='radio' name='betaald' value='betaald' checked='true' >betaald";
        temp += "<input id='radioNB' type='radio' name='betaald' value='niet betaald' > niet betaald";
    } else {
        temp += "<input id='radioB' type='radio' name='betaald' value='betaald'>betaald";
        temp += "<input id='radioNB' type='radio' name='betaald' value='niet betaald' checked='true' > niet betaald";
    }
    
    temp += "<div class='flexRowSpace'><a role='button' id='buttonBewerk'>Bewerk factuur</a><a role='button' id='buttonAnnuleer'>Annuleer</a></div>";
    temp += "<input type='hidden' name='factuurtEditedID' value='" + factuurID + "'>";
    temp +=   "<input type='hidden' id='klantID' name='klantID' value='" + klantID + "'></form>";
    pageElement.innerHTML = temp;
    
    document.getElementById("factuurFormService").value = service;
    document.getElementById("factuurFormBedrag").value = bedrag ;
  
       
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingFactuur);
   
}
   


