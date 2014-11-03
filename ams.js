var dropMenu = document.getElementById('dropdown_menu'),
    menuButton = document.getElementById('welcomebutton'),
    dropUL = document.getElementById('container').childNodes;

function hide_menu(evt){

    evt = evt || window.event;                        // get window.event if evt is falsy (IE)
    var targetElement = evt.target || evt.srcElement; // get srcElement if target is falsy (IE)

    if(targetElement === menuButton || targetElement.nodeName.toLowerCase() === 'li' ){
        dropMenu.style.display = 'block'
    } else {
        dropMenu.style.display = 'none'
    }
}

// For legacy broser(IE8 and IE7) support
function addEvent(el, type, fn){
    if(typeof addEventListener !== 'undefined'){
        el.addEventListener(type, fn, false)
    } else {
        el.attachEvent('on'+type, fn);
    }
}

addEvent(document, 'click', hide_menu);