/* 
Lewis Clement
 */

"use strict";

var originalHTML;
function submit() {
    if(document.forms["factuurForm"] !== undefined) {
        document.forms["factuurForm"].submit();
         
    }
}
function cancelComposingFactuur() {
    //Controleer of de juiste form op de pagina is, en stop de originele HTML terug in de parent element
    if(document.forms["factuurForm"] !== undefined) {
        document.getElementById("factuurForm").parentNode.innerHTML = originalHTML;
    }
}

function factuurToevoegen(){
    
    if (document.getElementById("factuurForm") !== null) {
        cancelComposingMessage();
    }
    var pageElement = document.getElementById("voegToe");
   
  
    originalHTML = pageElement.innerHTML;
  var temp =    "<form method='post' action='factuur.php'>";
      temp +=   "Geleverde service:<br>";
      temp +=   "<input id='serviceFactuur' class='textbox' type='text' name='service'>";
      temp +=   "Bedrag:<br>";
      temp +=   "<input id='bedragFactuur' class='textbox' type='number' name='bedrag'>";
      temp +=   "Betaald:";
      temp +=   "<input  type='radio' name='betaald' value='betaald'>betaald";
      temp +=   "<input  type='radio' checked name='betaald' value='niet betaald'>Niet betaald</br>";
      temp +=   "<a role='button' id='buttonSubmit'>Voeg toe</a>";
      temp +=   "<a role='button' id='buttonCancel'>Annuleren</a> </form>";
      pageElement.innerHTML = temp;
    
    
    document.getElementById("buttonSubmit").addEventListener("click", submit);
    document.getElementById("buttonCancel").addEventListener("click", cancelComposingFactuur);
   
}
      


function factuurBewerken(factuurID){
  if (document.getElementById("factuurForm") !== null) {
        cancelComposingMessage();
    }
   
   
  var pageElement   = document.getElementById("factuur" + factuurID);
  var service       = document.getElementById("factuurService" + factuurID).innerHTML;
  var bedrag        = document.getElementById("factuurBedrag" + factuurID).innerHTML;
  var radioB        = document.getElementById("radioB" + factuurID).innerHTML;
  

  
  
  
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='factuurForm' method='post'>";
    temp += "<input type='textbox' id='factuurFormService'  name='factuurServiceEdited'>";
    temp += "<input type='number' id='factuurFormBedrag'  name='factuurBedragEdited'>";
    temp += "<input id='radioB' type='radio' name='betaald' value='betaald'>betaald";
    temp += "<input id='radioNB' type='radio' name='nietbetaald' value='niet betaald'>niet betaald";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonBewerk'>Bewerk factuur</a><a role='button' id='buttonAnnuleer'>Annuleer</a></div>";
    temp += "<input type='hidden' name='factuurtEditedID' value='" + factuurID + "'></form>";
    pageElement.innerHTML = temp;
    
    document.getElementById("factuurFormService").value = service;
    document.getElementById("factuurFormBedrag").value = bedrag ;
   
    if (radioB === "Betaald"){
        document.getElementById("radioB").checked = true;
    } else {
        document.getElementById("radioNB").checked = true;
    }
    
    
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingFactuur);
   
}
   


