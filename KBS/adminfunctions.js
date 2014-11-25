var messageOriginalText;
            
function composeMessage() {
    if (document.getElementById("berichtFormText") !== null) {
        cancelComposingMessage();
    }
    
    var topBarElement = document.getElementById("buttonPlaats").parentNode.parentNode;
    messageOriginalText = topBarElement.innerHTML;
    topBarElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    topBarElement.innerHTML += "<textarea id='berichtFormText' form='berichtForm' name='bericht'></textarea>";
    topBarElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonPlaats' href='#'>Plaats bericht</a><a class='button' id='buttonAnnuleer' href='#'>Annuleer</a></div>";
    topBarElement.innerHTML += "</form>";
    
    document.getElementById("buttonPlaats").addEventListener("click", submitMessage);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function cancelComposingMessage() {
    document.getElementById("berichtForm").parentNode.innerHTML = messageOriginalText;
}

function submitMessage() {
    document.forms["berichtForm"].submit();
}

function editMessage(berichtNum, ID) {
    if (document.getElementById("berichtFormText") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementsByClassName("iconEdit")[berichtNum].parentNode.parentNode;
    var pageElementContent = pageElement.getElementsByClassName("content")[0].innerHTML;
    
    messageOriginalText = pageElement.innerHTML;
    pageElement.innerHTML = "<form action='#' id='berichtForm' method='post'>";
    pageElement.innerHTML += "<textarea id='berichtFormText' form='berichtForm' name='berichtEdited'></textarea>";
    pageElement.innerHTML += "<div class='flexRowSpace'><a class='button' id='buttonBewerk' href='#'>Bewerk bericht</a><a class='button' id='buttonAnnuleer' href='#'>Annuleer</a></div>";
    pageElement.innerHTML += "<input type='hidden' name='berichtEditedID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML += "</form>";
    
    document.getElementById("berichtFormText").value = pageElementContent;
    document.getElementById("buttonBewerk").addEventListener("click", submitMessage);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}