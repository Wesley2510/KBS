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
    
    
    submitFunction = function() {
        //Regex1 verwijdert alle spaties aan het begin, regex2 aan het eind en regex3 alle dubbele
        //var name = document.getElementById("adminFormName").value.replace(/^\s\s*/, '').replace(/\s\s*$/, '').replace(/ +(?= )/g,'');
        /*if(name.indexOf(' ') >= 0) {
            alert(name);
        } else {
            alert("Vul een voornaam en een achternaam in.");
        }*/
        
        if(document.getElementById("adminFormName").value == '' &&
                document.getElementById("adminFormEmail").value == '' &&
                document.getElementById("adminFormPass").value == '' &&
                document.getElementById("adminFormPassRepeat").value == '') {
            cancelComposingMessage();
        } else {
            submit();
        }
    }
    
    //Voegt event listeners toe aan de anchors
    document.getElementById("buttonRegister").addEventListener("click", submitFunction);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
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
    temp += "<div style='width: 100%;'>";
    if(ID !== 1) { 
        temp += "<div class='flexRowSpace'>";
        temp += "<input type='text' class='textbox' id='adminNameText' form='adminForm' name='adminNameEdited' />";
        temp += "<input type='text' class='textbox' id='adminEmailText' form='adminForm' name='adminEmailEdited' placeholder='Emailadres' /></div>"; 
    }
    temp += "<div class='flexRowSpace'><input type='text' class='textbox' id='adminNewPasswordText' form='adminForm' name='adminNewPassword' placeholder='Nieuw wachtwoord'>";
    temp += "<input type='text' class='textbox' id='adminRepeatPasswordText' form='adminForm' name='adminRepeatPassword' placeholder='Herhaal wachtwoord'/></div>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonBewerk'>Opslaan</a>";
    temp += "<input type='hidden' name='adminEditedID' value='" + ID + "' form='adminForm' />";
    if(ID !== 1) { temp += "<a role='button' id='buttonVerwijder'>Verwijder</a>"; }
    temp += "<a role='button' id='buttonAnnuleer'>Annuleer</a></div></div>";
    pageElement.innerHTML = temp;
    
    if(ID !== 1) { 
        document.getElementById("adminNameText").value = adminNaam; 
        document.getElementById("adminEmailText").value = emailadres;
    }
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    if(ID !== 1) { document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteMenuItem(itemNum, berichtCount);}); }
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}
