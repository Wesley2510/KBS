var originalHTML;

//Als er een bewerking geannuleerd moet worden, is de pageElement altijd parent van form "berichtForm".
function cancelComposingMessage() {
    //Controleer of de juiste form op de pagina is, en stop de originele HTML terug in de parent element
    if(document.forms["berichtForm"] !== undefined) {
        document.getElementById("berichtForm").parentNode.innerHTML = originalHTML;
    }
}

function submit() {
    //Controleer of de juiste form op de pagina is, en submit
    if(document.forms["berichtForm"] !== undefined) {
        document.forms["berichtForm"].submit();
    } else if(document.forms["menuForm"] !== undefined) {
        document.forms["menuForm"].submit();
    }
}

function composeMessage() {
    //Als er een berichtForm op de pagina is wordt er iets bewerkt, en moet dat dus gestopt worden.
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
    }
    
    //Vindt de pageElement welke de parent van de parent van de plaats button is
    var pageElement = document.getElementById("buttonPlaats").parentNode.parentNode;
    
    //Sla huidige html op in originalHTML
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<textarea id='berichtFormText' form='berichtForm' name='bericht'></textarea>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonPlaats'>Plaats bericht</a><a role='button' id='buttonAnnuleer'>Annuleer</a></div>";
    pageElement.innerHTML = temp;
    
    initEditor();
    
    //Voegt event listeners toe aan de anchors
    document.getElementById("buttonPlaats").addEventListener("click", submit);
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function editMessage(berichtNum, ID) {
    //Als er een berichtForm op de pagina is wordt er iets bewerkt, en moet dat dus gestopt worden.
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
    }
    
    var pageElement = document.getElementById("bericht" + ID);
    var pageElementContent = pageElement.getElementsByClassName("content")[0].innerHTML;
    
    //Sla huidige html op in originalHTML
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<textarea id='berichtFormText' form='berichtForm' name='berichtEdited'></textarea>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonBewerk'>Bewerk bericht</a><a role='button' id='buttonVerwijder'>Verwijder</a><a role='button' id='buttonAnnuleer'>Annuleer</a></div>";
    temp += "<input type='hidden' name='berichtEditedID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML = temp;
    
    initEditor(pageElementContent);
    
    //Voegt event listeners toe aan de anchors
    document.getElementById("berichtFormText").value = pageElementContent;
    document.getElementById("buttonBewerk").addEventListener("click", submit);
    document.getElementById("buttonVerwijder").addEventListener("click", function() { deleteWarning(berichtNum, ID);});
    document.getElementById("buttonAnnuleer").addEventListener("click", cancelComposingMessage);
}

function deleteWarning(berichtNum, ID) {
    var pageElement = document.getElementById("berichtForm").parentNode;

    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<h2 class='warningText'>Weet u zeker dat dit bericht verwijderd moet worden?</h2>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonJa'>Ja</a><a role='button' id='buttonNee'>Nee</a></div>";
    temp += "<input type='hidden' name='berichtToDeleteID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML = temp;

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editMessage(berichtNum, ID); } );
}

function initEditor(content) {
    tinymce.init({
        selector: "textarea",
        mode: "exact",
        language : 'nl',
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks fullscreen",
            "insertdatetime media table contextmenu paste responsivefilemanager"
        ],
        content_css: "/styles/stylesheet.css",
        body_class: "pageElement contentEditor",
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager",
        external_filemanager_path:"/filemanager/",
        filemanager_title: "Bestanden" ,
        external_plugins: { "filemanager" : "/filemanager/plugin.min.js"},
        setup: function(editor) {
            editor.on('init', function(e) {
                if(content == undefined)
                    content = "";
                tinymce.get("berichtFormText").setContent(content);
            });
        }
    });
}