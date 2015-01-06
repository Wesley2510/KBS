"use strict";

//Hierin wordt de originele inhoud van de pageElement bewaard.
var originalHTML;

function factuurSubmit() {
    if(document.forms["factuurForm"] !== undefined) {
        document.forms["factuurForm"].submit();
    } else if(document.forms["menuForm"] !== undefined) {
        document.forms["menuForm"].submit();
    }
}
function editFactuur(factuurNum, ID) {
    if (document.getElementById("factuurForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("factuur" + ID);
    var pageElementContent = pageElement.getElementsByClassName("service")[0].innerHTML;
    
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='factuurForm' method='post'></form>";
    temp += "<textarea id='factuurFormText' form='factuurForm' name='factuurEdited'></textarea>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonBewerk'>Bewerk factuur</a><a role='button' id='buttonVerwijder'>Verwijder</a><a role='button' id='buttonAnnuleer'>Annuleer</a></div>";
    temp += "<input type='hidden' name='factuurEditedID' value='" + ID + "' form='factuurForm'>";
    pageElement.innerHTML = temp;
    
    document.getElementById("factuurFormText").value = pageElementContent;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteWarning(berichtNum, ID);});
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function deleteWarning(berichtNum, ID) {
    var pageElement = document.getElementById("berichtForm").parentNode;

    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<h2 class='warningText'>Weet u zeker dat dit bericht verwijderd moet worden?</h2>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonJa'>Ja</a><a role='button' id='buttonNee'>Nee</a></div>";
    temp += "<input type='hidden' name='berichtToDeleteID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML = temp;

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editMessage(berichtNum, ID); } );
}
