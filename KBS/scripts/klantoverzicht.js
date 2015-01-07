/* 
Lewis Clement
 */

"use strict";

var originalHTML;
function submit() {
    if(document.forms["klantForm"] !== undefined) {
        document.forms["klantForm"].submit();
         
    }
}
function cancelComposingKlant() {
    //Controleer of de juiste form op de pagina is, en stop de originele HTML terug in de parent element
    if(document.forms["klantForm"] !== undefined) {
        document.getElementById("klantForm").parentNode.innerHTML = originalHTML;
    }
}

function editKlant(klantID){
  // var pageElement = document.getElementById("factuur" + ID);
  if (document.getElementById("klantForm") !== null) {
        cancelComposingMessage();
    }
   
   
  var pageElement   = document.getElementById("klant" + klantID);
  
  
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='klantForm' method='post'>";
    temp += "<input type='text'  name='voornaamEdited' placeholder='voornaam' />";
    temp += "<input type='text'  name='achternaamEdited' placeholder='achternaam' />";
    temp += "<input type='text'  name='emailEdited' placeholder='emailadres' />";
    temp += "<input type='text'  name='postcodeEdited' placeholder='postcode' />";
    temp += "<input type='number'  name='huisnummerEdited' placeholder='huisnummer' />";
    temp += "<input type='text'  name='adresEdited' placeholder='adres' />";
    temp += "<input type='text'  name='woonplaatsEdited' placeholder='woonplaats' />";
    temp += "<input type='text'  name='telefoonEdited' placeholder='telefoonnummer' />";
    temp += "<input type='hidden' name='klantID' value='" + klantID + "' />";
    temp += "<input type='submit' />";
    temp += "</form>";
    pageElement.innerHTML = temp;
   
}
   


