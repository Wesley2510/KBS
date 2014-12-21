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

function moveMenuItem(element) {
    var xmlhttp = new XMLHttpRequest();
    var position = parseInt(element.getAttribute("pos"));
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            if(xmlhttp.responseText == "true") {
                var element1 = element;
                var element2 = document.getElementById("menuItem" + (position + 1));
                var moveIcon1 = document.getElementById("moveDown" + position);
                var moveIcon2 = document.getElementById("moveDown" + (position + 1));
                
                var rect1 = element1.getBoundingClientRect();
                var rect2 = element2.getBoundingClientRect();
                
                
                element1.style.transition = "top 0.4s";
                element2.style.transition = "top 0.4s";
                
                element1.style.top = parseFloat(element1.style.top) + rect2.top - rect1.top + "px";
                element1.setAttribute("pos", position + 1);
                element1.setAttribute("id", "menuItem" + (position + 1));
                moveIcon1.setAttribute("id", "moveDown" + (position + 1))
                
                element2.style.top = parseFloat(element2.style.top) + rect1.top - rect2.top + "px";
                element2.setAttribute("pos", position);
                element2.setAttribute("id", "menuItem" + position);
                moveIcon2.setAttribute("id", "moveDown" + position);
                
                if(moveIcon2.style.visibility == "hidden") {
                    moveIcon2.style.visibility = "visible";
                    moveIcon1.style.visibility = "hidden";
                }
                
                
                var menu1 = document.getElementById("menu" + position);
                var menu2 = document.getElementById("menu" + (position+1));
                
                var menuRect1 = menu1.getBoundingClientRect();
                var menuRect2 = menu2.getBoundingClientRect();
                
                menu1.style.left = parseFloat(menu1.style.left) + menuRect2.left - menuRect1.left + "px";
                menu1.setAttribute("id", "menu" + (position + 1));
                
                menu2.style.left = parseFloat(menu2.style.left) + menuRect1.left - menuRect2.left + "px";
                menu2.setAttribute("id", "menu" + position);
            }
        }
    }
    
    xmlhttp.open("POST", "/admin/menubeheer.php", true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("rowDown=" + position);
}

function editMenuItem(itemNum, berichtCount) {
    if (document.getElementById("menuForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("menuItem" + itemNum);
    var menuItemText = pageElement.getElementsByClassName("menuItemText")[0].innerHTML;
    
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='menuForm' method='post'></form>";
    temp += "<a class='icon' id='iconBewerk' style='text-align:left;'><img src='/imgs/done.svg' alt='' class='icon'/><span class='iconText'>Opslaan</span></a>";
    temp += "<input type='text' class='textbox' id='titleEdit' form='menuForm' name='menuItemEdited' placeholder='Paginanaam' /><input type='hidden' name='menuItemEditedPos' value='" + itemNum + "' form='menuForm' />";
    temp += "<a class='icon' id='iconVerwijder' style='text-align:right;'><span class='iconText'>Verwijder</span><img src='/imgs/delete104.svg' alt='' class='icon'/></a>";
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
    temp += "<a class='icon' id='iconMaak' style='text-align:left;'><img src='/imgs/done.svg' alt='' class='icon'/><span class='iconText'>Maak</span></a>";
    temp += "<input type='text' class='textbox' id='titleEdit' form='menuForm' name='newMenuItemName' placeholder='Paginanaam' />";
    temp += "<a class='icon' id='iconAnnuleer' style='text-align:right;'><span class='iconText'>Annuleer</span><img src='/imgs/delete85.svg' alt='' class='icon'/></a>";
    pageElement.innerHTML = temp;
    
    document.getElementById("iconMaak").addEventListener("click", function() { if(document.getElementById("titleEdit").value === "") {cancelComposingMessage();} else {submit();} });
    document.getElementById("iconAnnuleer").addEventListener("click", cancelComposingMessage);
}