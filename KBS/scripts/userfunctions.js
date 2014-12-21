function login() {
    if(document.forms["loginForm"] !== undefined) {
        document.forms["loginForm"].submit();
    }
}

var elements = document.getElementsByClassName("backgroundText");
var scrollSize = 800;
var halfScrollSize = scrollSize / 2;
var startSize = -20;
var recoilEffect = 0.3; //0 = none, 1 = strong
var currentlyVisibleElementID = -1;
var currentlyVisibleElement;

window.addEventListener("scroll", function () {
    if(window.innerWidth < 800) {
        return;
    }
    
    var scrollY = window.pageYOffset;
    var currentElementSelect = Math.floor((scrollY - startSize) / scrollSize);
    
    if(currentElementSelect !== currentlyVisibleElementID) {
        if(currentlyVisibleElement != undefined) {
            currentlyVisibleElement.style.display = "none";
        }
        currentlyVisibleElement = elements[currentElementSelect % elements.length];
        currentlyVisibleElementID = currentElementSelect;
        currentlyVisibleElement.style.display = "block";
        if(currentElementSelect % 2 == 1) {
            currentlyVisibleElement.style.left = 15 - currentlyVisibleElement.offsetWidth / 2 + "px";
            currentlyVisibleElement.style.right = "";
        } else {
            currentlyVisibleElement.style.left = "";
            currentlyVisibleElement.style.right = 15 - currentlyVisibleElement.offsetWidth / 2 + "px";
        }
    }
    
    if(currentlyVisibleElement !== undefined) {
        var difference = startSize + halfScrollSize + (scrollSize * currentlyVisibleElementID) - scrollY;
        if(difference < 0) {difference *= -1;}
        var opacity = 1 - (difference / halfScrollSize);
        if(opacity < 0) {opacity = 0;}
        currentlyVisibleElement.style.top = scrollY + window.innerHeight * 0.2 + difference * recoilEffect +"px";
        currentlyVisibleElement.style.opacity = opacity;
    }
});

function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
}

window.addEventListener("resize", function () {
    document.getElementById("backgroundDiv").style.width = window.innerWidth + "px";
    document.getElementById("backgroundDiv").style.height = getDocHeight() + "px";
});

window.addEventListener("load", function () {
    document.getElementById("backgroundDiv").style.width = window.innerWidth + "px";
    document.getElementById("backgroundDiv").style.height = getDocHeight() + "px";
});


var posterFooters = document.getElementsByClassName("posterFooter");
var maxHeight = 0;
function expand(element) {
    if(element.getAttribute("open") == "true") {
        element.setAttribute("open", false);
        element.style.maxHeight = "3rem";
    } else {
        element.setAttribute("open", true);
        element.style.maxHeight = maxHeight + "px";
    }
}

//Workaround for webkit/blink based browsers
for(index = 0; index < posterFooters.length; index++) {
    var currentPoster = posterFooters[index].getElementsByClassName("posterContent")[0];
    posterFooters[index].getElementsByClassName("poster")[0].style.minHeight = currentPoster.offsetHeight + "px";
    currentPoster.style.top = -currentPoster.offsetHeight + "px";
    maxHeight = Math.max(maxHeight, currentPoster.offsetHeight);
}
var globalFontSize = parseInt(window.getComputedStyle(document.getElementById("headerbar")).fontSize);
maxHeight = maxHeight + (4 * globalFontSize);