/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

function factuurBewerken(factuurID){
  // var pageElement = document.getElementById("factuur" + ID);
  if (document.getElementById("factuurForm") !== null) {
        cancelComposingMessage();
    }
   
   
  var pageElement   = document.getElementById("factuur" + factuurID);
  var service       = document.getElementById("factuurService" + factuurID).innerHTML;
  var bedrag        = document.getElementById("factuurBedrag" + factuurID).innerHTML;
  var radioB        = document.getElementById("radioB" + factuurID).innerHTML;
  

  
  
  
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='factuurForm' method='post'></form>";
    temp += "<input type='textbox' id='factuurFormService' form='factuurForm' name='factuurServiceEdited'>";
    temp += "<input type='number' id='factuurFormBedrag' form='factuurForm' name='factuurBedragEdited'>";
    temp += "<input id='radioB' type='radio' name='betaald' value='betaald'>betaald";
    temp += "<input id='radioNB' type='radio' name='nietbetaald' value='niet betaald'>niet betaald";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonBewerk'>Bewerk factuur</a><a role='button' id='buttonVerwijder'>Verwijder</a><a role='button' id='buttonAnnuleer'>Annuleer</a></div>";
    temp += "<input type='hidden' name='factuurtEditedID' value='" + factuurID + "' form='berichtForm'>";
    pageElement.innerHTML = temp;
    
    document.getElementById("factuurFormService").value = service;
    document.getElementById("factuurFormBedrag").value = bedrag ;
   
    if (radioB === "Betaald"){
        document.getElementById("radioB").checked = true;
    } else {
        document.getElementById("radioNB").checked = true;
    }
    
    
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteWarning(berichtNum, ID);});
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingFactuur);
   
}
   


