/*====================
 DOM control functions
======================*/
function controlDomTree(e) {
    if (e.target.classList.contains('dom-arrows') ) {
        controlColapseAndApear(e)
    } else if (e.target.tagName == "LI") {
        controlSelected(e);
    }
};


/******************************
 Controls appear and collapse
 of the arrows
******************************/
function controlColapseAndApear(e) {
    if (e.target.classList.contains('arrowOpen')) {
        e.target.innerHTML = "⮚";
    } else {
        e.target.innerHTML = "⮛";
    }

    e.target.nextElementSibling.nextElementSibling.classList.toggle("page-box");
    e.target.nextElementSibling.nextElementSibling.classList.toggle("page-box-hidden");

    e.target.classList.toggle("arrowOpen");
    e.target.classList.toggle("arrowClosed");
}

/**********************************
 Controls the selected dom element
***********************************/
function controlSelected(e) {
    if (e.target.classList.contains('dom-selected') ) {

    } else {
        document.querySelector('.dom-selected').classList.remove('dom-selected');
        e.target.classList.add('dom-selected');
    }
}
