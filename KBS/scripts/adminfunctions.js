//Hierin wordt de originele inhoud van de pageElement bewaard.
var messageOriginalText;

//Als er een bewerking geannuleerd moet worden, is de pageElement altijd parent van form "berichtForm".
function cancelComposingMessage() {
    if(document.forms["berichtForm"] !== undefined) {
        document.getElementById("berichtForm").parentNode.innerHTML = messageOriginalText;
    } else if(document.forms["menuForm"] !== undefined) {
        document.getElementById("menuForm").parentNode.innerHTML = messageOriginalText;
    }
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
    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<textarea id='berichtFormText' form='berichtForm' name='bericht'></textarea>";
    temp += "<div class='flexRowSpace'><a class='button' id='buttonPlaats'>Plaats bericht</a><a class='button' id='buttonAnnuleer'>Annuleer</a></div>";
    pageElement.innerHTML = temp;
    
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
    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<textarea id='berichtFormText' form='berichtForm' name='berichtEdited'></textarea>";
    temp += "<div class='flexRowSpace'><a class='button' id='buttonBewerk'>Bewerk bericht</a><a class='button' id='buttonVerwijder'>Verwijder</a><a class='button' id='buttonAnnuleer'>Annuleer</a></div>";
    temp += "<input type='hidden' name='berichtEditedID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML = temp;
    
    document.getElementById("berichtFormText").value = pageElementContent;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteWarning(berichtNum, ID);});
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}
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
    
    messageOriginalText = pageElement.innerHTML;
    var temp = "<form action='#' id='factuurForm' method='post'></form>";
    temp += "<textarea id='factuurFormText' form='factuurForm' name='factuurEdited'></textarea>";
    temp += "<div class='flexRowSpace'><a class='button' id='buttonBewerk'>Bewerk factuur</a><a class='button' id='buttonVerwijder'>Verwijder</a><a class='button' id='buttonAnnuleer'>Annuleer</a></div>";
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
    temp += "<div class='flexRowSpace'><a class='button' id='buttonJa'>Ja</a><a class='button' id='buttonNee'>Nee</a></div>";
    temp += "<input type='hidden' name='berichtToDeleteID' value='" + ID + "' form='berichtForm'>";
    temp += "</form>";
    pageElement.innerHTML = temp;

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editMessage(berichtNum, ID); } );
}



function moveMenuItem(position) {
    document.forms["moveDownForm" + position].submit();
}

function editMenuItem(itemNum, berichtCount) {
    if (document.getElementById("menuForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("menuItem" + itemNum);
    var menuItemText = document.getElementById("menuItemText" + itemNum).innerHTML;
    
    messageOriginalText = pageElement.innerHTML;
    var temp = "<form action='#' id='menuForm' method='post'></form>";
    temp += "<a class='button' id='buttonBewerk'>Opslaan</a><input type='text' class='textbox' id='menuFormText' form='menuForm' name='menuItemEdited'><input type='hidden' name='menuItemEditedPos' value='" + itemNum + "' form='menuForm' /><a class='button' id='buttonVerwijder'>Verwijder</a>";
    pageElement.innerHTML = temp;
    
    document.getElementById("menuFormText").value = menuItemText;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteMenuItem(itemNum, berichtCount);});
}

function deleteMenuItem(itemNum, berichtCount) {
    var pageElement = document.getElementById("menuForm").parentNode;
    
    //Als er maar 0 berichten op de pagina staan, submit direct.
    if(berichtCount === 0) {
        xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", document.URL, true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
        xmlhttp.send("paginaToDeletePos=" + itemNum);
                
        pageElement.innerHTML = "<div></div><h2>Wordt verijderd...</h2>";
        
        location.reload(); 
        return;
    }

    var temp = "<form class='flexRowSpace' style='flex: 1;flex-direction:column;justify-content:center;' action='#' id='menuForm' method='post' />";
    temp += "<h2 class='warningText'>Weet u zeker dat deze pagina verwijderd moet worden?</h2><h3 class='warningText'>Er zullen " + berichtCount + " berichten verwijderd worden!</h3>";
    temp += "<div class='flexRowSpace' style='width:100%;'><a class='button' id='buttonJa'>Ja</a><a class='button' id='buttonNee'>Nee</a></div>";
    temp += "<input type='hidden' name='paginaToDeletePos' value='" + itemNum + "' form='menuForm' />";
    temp += "</form>";
    pageElement.innerHTML = temp;
    
    

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editMenuItem(itemNum, berichtCount); } );
}

function createNewMenuItem() {
    if (document.getElementById("menuForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("newPageElement");
    messageOriginalText = pageElement.innerHTML;
    var temp = "<form action='#' id='menuForm' method='post'></form>";
    temp += "<a class='button' id='buttonMaak'>Maak</a><input type='text' class='textbox' id='menuFormText' form='menuForm' name='newMenuItemName' /><a id='buttonAnnuleer' class='button'>Annuleer</a>";
    pageElement.innerHTML = temp;
    
    document.getElementById("buttonMaak").addEventListener("click", function() { if(document.getElementById("menuFormText").value === "") {cancelComposingMessage();} else {submit();} });
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}