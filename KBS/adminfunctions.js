var messageOriginalText;

function cancelComposingMessage() {
    document.getElementById("berichtForm").parentNode.innerHTML = messageOriginalText;
}

function submit() {
    document.forms["berichtForm"].submit();
}

function composeMessage() {
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("buttonPlaats").parentNode.parentNode;
    messageOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    pageElement.innerHTML += "<textarea id='berichtFormText' form='berichtForm' name='bericht'></textarea>";
    pageElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonPlaats' href='#'>Plaats bericht</a><a class='button' id='buttonAnnuleer' href='#'>Annuleer</a></div>";
    pageElement.innerHTML += "</form>";
    
    document.getElementById("buttonPlaats").addEventListener("click", submit);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function editMessage(berichtNum, ID) {
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementsByClassName("iconEdit")[berichtNum].parentNode.parentNode;
    var pageElementContent = pageElement.getElementsByClassName("content")[0].innerHTML;
    
    messageOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    pageElement.innerHTML += "<textarea id='berichtFormText' form='berichtForm' name='berichtEdited'></textarea>";
    pageElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonBewerk' href='#'>Bewerk bericht</a><a class='button' id='buttonVerwijder'>Verwijder</a><a class='button' id='buttonAnnuleer' href='#'>Annuleer</a></div>";
    pageElement.innerHTML += "<input type='hidden' name='berichtEditedID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML += "</form>";
    
    document.getElementById("berichtFormText").value = pageElementContent;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteWarning(berichtNum, ID, false);});
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function deleteWarning(berichtNum, ID) {
    var pageElement = document.getElementById("berichtForm").parentNode;

    editOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    pageElement.innerHTML += "<h2>Weet u zeker dat dit bericht verwijderd moet worden?</h2>";
    pageElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonJa' href='#'>Ja</a><a class='button' id='buttonNee' href='#'>Nee</a></div>";
    pageElement.innerHTML += "<input type='hidden' name='berichtToDeleteID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML += "</form>";

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editMessage(berichtNum, ID); } );
}