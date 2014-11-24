var messageOriginalText;
            
function composeMessage() {
    var topBarElement = document.getElementsByClassName("topBarElement")[0].parentNode;
    messageOriginalText = topBarElement.innerHTML;
    topBarElement.innerHTML = "<form action=\"#\" id=\"berichtForm\" method=\"post\">";
    topBarElement.innerHTML += "<textarea id=\"berichtFormText\" form=\"berichtForm\" name=\"bericht\"></textarea>";
    topBarElement.innerHTML += "<div class=\"topBarElement\"><a class=\"button\" id=\"buttonPlaats\" href=\"#\">Plaats bericht</a><a class=\"button\" id=\"buttonAnnuleer\" href=\"#\">Annuleer</a></div>";
    topBarElement.innerHTML += "</form>";
    
    document.getElementById("buttonPlaats").addEventListener("click", submitMessage);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function cancelComposingMessage() {
    document.getElementsByClassName("topBarElement")[0].parentNode.innerHTML = messageOriginalText;
}

function submitMessage() {
    document.forms["berichtForm"].submit();
}