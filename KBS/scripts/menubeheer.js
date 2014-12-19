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
    temp += "<a class='icon' id='iconBewerk' style='text-align:left;'><img src='/imgs/done.svg' alt='' class='icon'/></a>";
    temp += "<input type='text' class='textbox' id='titleEdit' form='menuForm' name='menuItemEdited' placeholder='Paginanaam' /><input type='hidden' name='menuItemEditedPos' value='" + itemNum + "' form='menuForm' />";
    temp += "<a class='icon' id='iconVerwijder' style='text-align:right;'><img src='/imgs/delete104.svg' alt='' class='icon'/></a>";
    pageElement.innerHTML = temp;
    
    document.getElementById("titleEdit").value = menuItemText;
    document.getElementById("iconBewerk").addEventListener("click", submit);
    document.getElementById("iconVerwijder").addEventListener("click", function() { deleteMenuItem(itemNum, berichtCount);});
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
    temp += "<a class='icon' id='iconMaak' style='text-align:left;'><img src='/imgs/done.svg' alt='' class='icon'/></a>";
    temp += "<input type='text' class='textbox' id='titleEdit' form='menuForm' name='newMenuItemName' placeholder='Paginanaam' />";
    temp += "<a class='icon' id='iconAnnuleer' style='text-align:right;'><img src='/imgs/delete85.svg' alt='' class='icon'/></a>";
    pageElement.innerHTML = temp;
    
    document.getElementById("iconMaak").addEventListener("click", function() { if(document.getElementById("titleEdit").value === "") {cancelComposingMessage();} else {submit();} });
    document.getElementById("iconAnnuleer").addEventListener("click", cancelComposingMessage);
}