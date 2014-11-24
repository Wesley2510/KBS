var messageOriginalText;
            
function composeMessage() {
    var topBarElement = document.getElementsByClassName("topBarElement")[0].parentNode;
    messageOriginalText = topBarElement.innerHTML;
    topBarElement.innerHTML = "<form action=\"\" id=\"berichtForm\">";
    topBarElement.innerHTML += "<textarea id=\"berichtFormText\"></textarea>";
    topBarElement.innerHTML += "<div class=\"topBarElement\"><a class=\"button\" onclick=\"cancelComposingMessage();\" href=\"#\">Annuleer</a></div>";
    topBarElement.innerHTML += "</form>";
}

function cancelComposingMessage() {
    document.getElementsByClassName("topBarElement")[0].parentNode.innerHTML = messageOriginalText;
}