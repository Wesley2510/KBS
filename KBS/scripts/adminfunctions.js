//Hierin wordt de originele inhoud van de pageElement bewaard.
var messageOriginalText;

//Als er een bewerking geannuleerd moet worden, is de pageElement altijd parent van form "berichtForm".
function cancelComposingMessage() {
    document.getElementById("berichtForm").parentNode.innerHTML = messageOriginalText;
}

function submit() {
    if(document.forms["berichtForm"] !== undefined) {
        document.forms["berichtForm"].submit();
    } else if(document.forms["menuForm"] !== undefined) {
        document.forms["menuForm"].submit();
    }
}

function composeMessage() {
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("buttonPlaats").parentNode.parentNode;
    messageOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    pageElement.innerHTML += "<textarea id='berichtFormText' form='berichtForm' name='bericht'></textarea>";
    pageElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonPlaats'>Plaats bericht</a><a class='button' id='buttonAnnuleer'>Annuleer</a></div>";
    pageElement.innerHTML += "</form>";
    
    document.getElementById("buttonPlaats").addEventListener("click", submit);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function editMessage(berichtNum, ID) {
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("bericht" + ID);
    var pageElementContent = pageElement.getElementsByClassName("content")[0].innerHTML;
    
    messageOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    pageElement.innerHTML += "<textarea id='berichtFormText' form='berichtForm' name='berichtEdited'></textarea>";
    pageElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonBewerk'>Bewerk bericht</a><a class='button' id='buttonVerwijder'>Verwijder</a><a class='button' id='buttonAnnuleer'>Annuleer</a></div>";
    pageElement.innerHTML += "<input type='hidden' name='berichtEditedID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML += "</form>";
    
    document.getElementById("berichtFormText").value = pageElementContent;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteWarning(berichtNum, ID);});
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function deleteWarning(berichtNum, ID) {
    var pageElement = document.getElementById("berichtForm").parentNode;

    editOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    pageElement.innerHTML += "<h2 class='warningText'>Weet u zeker dat dit bericht verwijderd moet worden?</h2>";
    pageElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonJa'>Ja</a><a class='button' id='buttonNee'>Nee</a></div>";
    pageElement.innerHTML += "<input type='hidden' name='berichtToDeleteID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML += "</form>";

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editMessage(berichtNum, ID); } );
}



function moveMenuItem(position) {
    document.forms["moveDownForm" + position].submit();
}

function cancelMenuItemEdit() {
    document.getElementById("menuForm").parentNode.innerHTML = messageOriginalText;
}

function editMenuItem(itemNum, berichtCount) {
    if (document.getElementById("menuForm") !== null) {
        cancelMenuItemEdit();
    }
    
    var pageElement = document.getElementById("menuItem" + itemNum);
    var menuItemText = document.getElementById("menuItemText" + itemNum).innerHTML;
    
    messageOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='menuForm' method='post'>";
    pageElement.innerHTML += "<a class='button' id='buttonBewerk'>Bewerk</a><input type='text' class='textbox' id='menuFormText' form='menuForm' name='menuItemEdited'></textarea><input type='hidden' name='menuItemEditedPos' value='" + itemNum + "' form='menuForm' /><a class='button' id='buttonVerwijder'>Verwijder</a>";
    pageElement.innerHTML += "</form>";
    
    document.getElementById("menuFormText").value = menuItemText;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { /*deleteWarning(berichtNum, ID);*/});
}