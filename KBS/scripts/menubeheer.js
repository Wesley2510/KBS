var originalHTML;

function submit() {
    if(document.forms["menuForm"] !== undefined) {
        document.forms["menuForm"].submit();
    }
}

//Als er een bewerking geannuleerd moet worden, is de pageElement altijd parent van form "berichtForm".
function cancelComposingMessage() {
    //Controleer of de juiste form op de pagina is, en stop de originele HTML terug in de parent element
    if(document.forms["menuForm"] !== undefined) {
        document.getElementById("menuForm").parentNode.innerHTML = originalHTML;
    }
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
    
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='menuForm' method='post'></form>";
    temp += "<a role='button' id='buttonBewerk'>Opslaan</a><input type='text' class='textbox' id='menuFormText' form='menuForm' name='menuItemEdited'><input type='hidden' name='menuItemEditedPos' value='" + itemNum + "' form='menuForm' /><a role='button' id='buttonVerwijder'>Verwijder</a>";
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
    temp += "<div class='flexRowSpace' style='width:100%;'><a role='button' id='buttonJa'>Ja</a><a role='button' id='buttonNee'>Nee</a></div>";
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
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='menuForm' method='post'></form>";
    temp += "<a role='button' id='buttonMaak'>Maak</a><input type='text' class='textbox' id='menuFormText' form='menuForm' name='newMenuItemName' /><a id='buttonAnnuleer' role='button'>Annuleer</a>";
    pageElement.innerHTML = temp;
    
    document.getElementById("buttonMaak").addEventListener("click", function() { if(document.getElementById("menuFormText").value === "") {cancelComposingMessage();} else {submit();} });
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}