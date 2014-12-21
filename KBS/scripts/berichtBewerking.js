var originalHTML = "";
var originalHeight = 0;
var animating = false;

var globalFontSize = parseInt(window.getComputedStyle(document.getElementById("headerbar")).fontSize);
var editorHeight = window.innerHeight - 20*globalFontSize;

//Als er een bewerking geannuleerd moet worden, is de pageElement altijd parent van form "berichtForm".
function cancelComposingMessage() {
    //Controleer of de juiste form op de pagina is, en stop de originele HTML terug in de parent element
    if(animating) {return;}
    
    if(document.forms["berichtForm"] !== undefined) {
        animating = true;
        
        var pageElement = document.forms["berichtForm"].parentNode;
        pageElement.style.maxHeight = "2.8rem";
        
        window.setTimeout(function() {cancelRestore(pageElement)}, 1000);
    }
}
function cancelRestore(pageElement) {
    document.getElementById("berichtForm").parentNode.innerHTML = originalHTML;
    pageElement.style.maxHeight = originalHeight;
    
    animating = false;
}

function submit() {
    //Controleer of de juiste form op de pagina is, en submit
    if(document.forms["berichtForm"] !== undefined) {
        document.forms["berichtForm"].submit();
    }
}

function composeMessage() {
    //Als er een berichtForm op de pagina is wordt er iets bewerkt, en moet dat dus gestopt worden.
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
        
        animating = true;
        window.setTimeout(function() {startComposingMessage();}, 1500);
    } else {
        if(animating) {return;}
        
        animating = true;
        startComposingMessage();
    }
}
function startComposingMessage() {
    var pageElement = document.getElementById("newMessageElement");
    
    var date = new Date();
    var datestring = date.getDate();
    datestring += "-" + (date.getMonth() + 1);
    datestring += "-" + date.getFullYear();
    
    //Sla huidige html op in originalHTML
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<div class='titleBar flexRowSpace'>";
    
    temp += "<div class='switch'>";
    temp += "   <input type='checkbox' id='switchDateVisible' name='dateVisible' class='cmn-toggle cmn-toggle-yes-no' form='berichtForm' checked='true' />";
    temp += "   <label id='switchDateVisibleLabel' for='switchDateVisible' text='" + datestring + " zichtbaar'></label>";
    temp += "</div>";
    
    temp += "<input id='titleEdit' type='text' form='berichtForm' name='titel' placeholder='Titel' />";
    temp += "<a class='icon' id='iconAnnuleer'><span class='iconText'>Annuleer</span><img class='icon' src='/imgs/delete85.svg' alt=''/></a></div>";
    temp += "<textarea id='berichtFormText' form='berichtForm' name='bericht'></textarea>";
    temp += "<div class='posterFooter flexRowSpace'><div style='width:20%;text-align:left;margin-left:0.24rem;'><a class='icon' id='iconPlaats'><img class='icon' src='/imgs/done.svg' alt=''/><span class='iconText'>Plaats</span></a></div>";
    
    temp += "<div class='switch'>";
    temp += "   <input type='checkbox' id='switchPosterVisible' name='posterVisible' class='cmn-toggle cmn-toggle-yes-no' form='berichtForm' checked='true' />";
    temp += "   <label id='switchPosterVisibleLabel' for='switchPosterVisible'></label>";
    temp += "</div>";
    
    
    temp += "<span style='width:20%;'></div></div>";
    
    originalHeight = pageElement.style.maxHeight;console.log(parseInt(originalHeight) + editorHeight + "px");
    pageElement.style.maxHeight = parseInt((parseInt(originalHeight) + 10)*globalFontSize) + editorHeight + "px";
    
    pageElement.innerHTML = temp;
    
    initEditor();
    
    //Voegt event listeners toe aan de anchors
    var posterText = "Geplaatst door " + loggedinVoornaam + " " + loggedinAchternaam;
    document.getElementById("switchPosterVisibleLabel").setAttribute("text", posterText + " (zichtbaar)");
    document.getElementById("iconPlaats").addEventListener("click", submit);
    document.getElementById("iconAnnuleer").addEventListener("click", cancelComposingMessage);
    
    var switchDateVisibleLabel = document.getElementById("switchDateVisibleLabel");
    var switchDateVisible = document.getElementById("switchDateVisible");
    var changeText = function() {
        if(!switchDateVisible.checked) {
            switchDateVisibleLabel.setAttribute("text", datestring + " (niet zichtbaar)");
        } else {
            switchDateVisibleLabel.setAttribute("text", datestring + " (zichtbaar)");
        }
    }
    switchDateVisible.addEventListener("click", changeText);
    
    var switchPosterVisibleLabel = document.getElementById("switchPosterVisibleLabel");
    var switchPosterVisible = document.getElementById("switchPosterVisible");
    changeText = function() {
        if(!switchPosterVisible.checked) {
            switchPosterVisibleLabel.setAttribute("text", posterText + " (niet zichtbaar)");
        } else {
            switchPosterVisibleLabel.setAttribute("text", posterText + " (zichtbaar)");
        }
    }
    switchPosterVisible.addEventListener("click", changeText);
    
    animating = false;
}

function editMessage(berichtNum, ID) {
    
    //Als er een berichtForm op de pagina is wordt er iets bewerkt, en moet dat dus gestopt worden.
    if (document.getElementById("berichtForm") !== null) {
        cancelComposingMessage();
        
        animating = true;
        window.setTimeout(function() {editAnimate(berichtNum, ID)}, 1100);
    } else {
        if(animating) {return;}
        
        animating = true;
        editAnimate(berichtNum, ID);
    }
}
function editAnimate(berichtNum, ID) {
    var pageElement = document.getElementById("bericht" + ID);
    
    originalHeight = pageElement.style.maxHeight;
    pageElement.style.maxHeight = "2.8rem";
    
    window.setTimeout(function() {startEditing(berichtNum, ID)}, 1100);
}
function startEditing(berichtNum, ID) {
    var pageElement = document.getElementById("bericht" + ID);
    var pageElementTitle;
    if(pageElement.getElementsByClassName("title")[0] !== undefined) {
        pageElementTitle = pageElement.getElementsByClassName("title")[0].innerHTML;
    } else {
        pageElementTitle = "";
    }
    
    var pageElementDateElement = pageElement.getElementsByClassName("datum")[0];
    var pageElementDate = pageElementDateElement.innerHTML;
    var pageElementDateVisible = pageElementDateElement.style.visibility !== "hidden";
    
    var pageElementPosterVisible = true;
    if(pageElement.getElementsByClassName("posterName")[0] == undefined) {
        pageElementPosterVisible = false;
    }
    var pageElementPoster = loggedinVoornaam + " " + loggedinAchternaam;
    
    var pageElementContent = pageElement.getElementsByClassName("content")[0].innerHTML;
    
    //Sla huidige html op in originalHTML
    originalHTML = pageElement.innerHTML;
    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<div class='titleBar flexRowSpace'>";
    
    temp += "<div class='switch'>";
    temp += "<input type='checkbox' id='switchDateVisible' name='dateVisible' class='cmn-toggle cmn-toggle-yes-no' form='berichtForm' />";
    temp += "<label id='switchDateVisibleLabel' for='switchDateVisible'></label>";
    temp += "</div>";
    
    temp += "<input id='titleEdit' type='text' form='berichtForm' name='titelEdited' placeholder='Titel' />";
    
    temp += "<a class='icon' id='iconAnnuleer'><span class='iconText'>Annuleer</span><img class='icon iconDelete' src='/imgs/delete85.svg' alt=''/></a></div>";
    temp += "<textarea id='berichtFormText' form='berichtForm' name='berichtEdited'></textarea>";
    temp += "<div class='posterFooter flexRowSpace'><div style='width:20%;text-align:left;margin-left:0.24rem;'><a class='icon' id='iconBewerk'><img class='icon' src='/imgs/done.svg' alt=''/><span class='iconText'>Opslaan</span></a></div>";
    
    temp += "<div class='switch'>";
    temp += "<input type='checkbox' id='switchPosterVisible' name='posterVisible' class='cmn-toggle cmn-toggle-yes-no' form='berichtForm' />";
    temp += "<label id='switchPosterVisibleLabel' for='switchPosterVisible'></label>";
    temp += "</div>";
    
    temp += "<div style='width:20%;text-align:right;'><a class='icon' id='iconVerwijder'><span class='iconText'>Verwijder</span><img class='icon' src='/imgs/delete104.svg' alt=''/></a></div>";
    temp += "<input type='hidden' name='berichtEditedID' value='" + ID + "' form='berichtForm'>";
    pageElement.innerHTML = temp;
    
    initEditor(pageElementContent);
    
    //Verkrijg hoogte om hem terug uit te klappen
    pageElement.style.maxHeight = window.innerHeight + "px";
    
    //Voegt event listeners toe aan de anchors
    document.getElementById("titleEdit").value = pageElementTitle;
    document.getElementById("berichtFormText").value = pageElementContent;
    document.getElementById("iconBewerk").addEventListener("click", submit);
    document.getElementById("iconVerwijder").addEventListener("click", function() { deleteWarning(berichtNum, ID);});
    document.getElementById("iconAnnuleer").addEventListener("click", cancelComposingMessage);
    
    //Code voor datum switch
    var switchDateVisibleLabel = document.getElementById("switchDateVisibleLabel");
    var switchDateVisible = document.getElementById("switchDateVisible");
    if(pageElementDateVisible) {
        switchDateVisibleLabel.setAttribute("text", pageElementDate + " (zichtbaar)");
        switchDateVisible.checked = true;
    } else {
        switchDateVisibleLabel.setAttribute("text", pageElementDate + " (niet zichtbaar)");
        switchDateVisible.checked = false;
    }
    
    var changeText = function() {
        if(!switchDateVisible.checked) {
            switchDateVisibleLabel.setAttribute("text", pageElementDate + " (niet zichtbaar)");
        } else {
            switchDateVisibleLabel.setAttribute("text", pageElementDate + " (zichtbaar)");
        }
    }
    switchDateVisible.addEventListener("click", changeText);
    
    
    //Code voor plaatser switch
    var switchPosterVisibleLabel = document.getElementById("switchPosterVisibleLabel");
    var switchPosterVisible = document.getElementById("switchPosterVisible");
    if(pageElementPosterVisible) {
        switchPosterVisibleLabel.setAttribute("text", pageElementPoster + " (zichtbaar)");
        switchPosterVisible.checked = true;
    } else {
        switchPosterVisibleLabel.setAttribute("text", pageElementPoster + " (niet zichtbaar)");
        switchPosterVisible.checked = false;
    }
    
    changeText = function() {
        if(!switchPosterVisible.checked) {
            switchPosterVisibleLabel.setAttribute("text", pageElementPoster + " (niet zichtbaar)");
        } else {
            switchPosterVisibleLabel.setAttribute("text", pageElementPoster + " (zichtbaar)");
        }
    }
    switchPosterVisible.addEventListener("click", changeText);
    
    animating = false;
}

function deleteWarning(berichtNum, ID) {
    var pageElement = document.getElementById("berichtForm").parentNode;

    var temp = "<form action='#' id='berichtForm' method='post'></form>";
    temp += "<h2 class='warningText'>Weet u zeker dat dit bericht verwijderd moet worden?</h2>";
    temp += "<div class='flexRowSpace'><a role='button' id='buttonJa'>Ja</a><a role='button' id='buttonNee'>Nee</a></div>";
    temp += "<input type='hidden' name='berichtToDeleteID' value='" + ID + "' form='berichtForm' />";
    pageElement.innerHTML = temp;

    document.getElementById("buttonJa").addEventListener("click", submit);
    document.getElementById("buttonNee").addEventListener("click", function() { cancelComposingMessage(); editMessage(berichtNum, ID); } );
}

function initEditor(content) {
    tinymce.init({
        selector: "textarea",
        mode: "exact",
        language : 'nl',
        skin : 'textbug',
        height: editorHeight,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks fullscreen",
            "insertdatetime media table contextmenu paste responsivefilemanager",
            "textcolor colorpicker"
        ],
        content_css: "/styles/stylesheet.css",
        body_class: "pageElement contentEditor",
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image responsivefilemanager",
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


window.addEventListener("load", function() {
    var pageElements = document.getElementsByClassName("pageElement");
    for(index = 0; index < pageElements.length; index++) {
        var pageElement = pageElements[index];
        var height = 0;
        if(pageElement.getElementsByClassName("titleBar")[0] !== undefined) {
            height += pageElement.getElementsByClassName("titleBar")[0].offsetHeight;
        }
        if(pageElement.getElementsByClassName("content")[0] !== undefined) {
            height += pageElement.getElementsByClassName("content")[0].offsetHeight;
        }
        if(pageElement.getElementsByClassName("posterFooter")[0] !== undefined) {
            height += pageElement.getElementsByClassName("posterFooter")[0].offsetHeight;
        }
        
        if(height == 0) {
            height = pageElement.offsetHeight + editorHeight;
        }
        
        pageElement.style.maxHeight = height + maxHeight + "px";
        pageElement.style.transition = "max-height 1s";
    }
    
    document.getElementById("newMessageElement").style.maxHeight = "3rem";
});