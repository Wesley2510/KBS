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

function deactivateAdmin() {
    if(document.getElementById("adminDeactivateID") !== undefined) {
        if(activeAdmins == 1) {
            document.getElementById("adminDeactivateID").value = 0;
        } else {
            document.getElementById("adminDeactivateID").value = 1;
        }
        submit();
    }
}

function createNewAdmin() {
    //Als er een berichtForm op de pagina is wordt er iets bewerkt, en moet dat dus gestopt worden.
    if (document.getElementById("adminForm") !== null) {
        cancelComposingMessage();
    }
    
    //Vindt de pageElement welke de parent van de parent van de plaats button is
    var pageElement = document.getElementById("buttonNewAdmin").parentNode.parentNode;
    
    //Sla huidige html op in originalHTML
    originalHTML = pageElement.innerHTML;
    pageElement.innerHTML = newAdminForm;
    
    document.getElementById("adminActiveID").value = parseInt(activeAdmins);
}

function editAdminData(ID) {
    if(ID === 1) {
        return;
    }
    if (document.getElementById("adminForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("admin" + ID);
    var adminNaam = document.getElementById("naam" + ID).innerHTML;
    var emailadres = document.getElementById("email" + ID).innerHTML;
    
    originalHTML = pageElement.innerHTML;
    pageElement.innerHTML = editAdminForm;
    document.getElementById('adminEditedID').value = ID;
    
    document.getElementById("adminFormName").value = adminNaam; 
    document.getElementById("adminFormEmail").value = emailadres;
    document.getElementById("iconBewerk").addEventListener("click", submit);
}

function deleteWarning(ID, name) {
    var pageElement = document.getElementById("admin" + ID);

    var temp = "<form action='#' id='adminForm' method='post'></form>";
    temp += "<div style='width:100%;'><h2 class='warningText'>Weet u zeker dat admin " + name + " gedeactiveerd moet worden?</h2>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonJa'>Ja</a><a role='button' id='buttonNee'>Nee</a></div></div>";
    temp += "<input type='hidden' name='adminToDeleteID' value='" + ID + "' form='adminForm'>";
    pageElement.innerHTML = temp;

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editAdminData(ID); } );
}