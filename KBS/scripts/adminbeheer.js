var originalHTML;

function submit() {
    if(document.forms["adminForm"] !== undefined) {
        document.forms["adminForm"].submit();
    }
}

function cancelComposingMessage() {
    //Controleer of de juiste form op de pagina is, en stop de originele HTML terug in de parent element
    if(document.forms["adminForm"] !== undefined) {
        document.getElementById("adminForm").parentNode.innerHTML = originalHTML;
    }
}

function editAdminData(ID) {
    if (document.getElementById("adminForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("admin" + ID);
    var adminNaam = document.getElementById("naam" + ID).innerHTML;
    var emailadres = document.getElementById("email" + ID).innerHTML;
    
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='adminForm' method='post'></form>";
    temp += "<a role='button' id='buttonBewerk'>Opslaan</a><input type='text' class='textbox' id='adminNameText' form='adminForm' name='adminNameEdited'><input type='hidden' name='adminEditedID' value='" + ID + "' form='adminForm' /><a role='button' id='buttonVerwijder'>Verwijder</a>";
    pageElement.innerHTML = temp;
    
    document.getElementById("adminNameText").value = adminNaam;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteMenuItem(itemNum, berichtCount);});
}
