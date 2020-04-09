/*********************
 Dom Controls
*********************/
function addDomIcons() {
    const openContent =  document.querySelectorAll(".dom-nav--opencontent");
    for (let i = 0; i < openContent.length; i++) {

        setOpenArrow(openContent[i])
    }

    const closedContent =  document.querySelectorAll(".dom-nav--closedcontent");
    for (let i = 0; i < closedContent.length; i++) {
        setClosedArrow(closedContent[i])
    }
}

function setOpenArrow(element) {
    const arrowOpen = document.createElement("div");
    arrowOpen.innerHTML = "⮛";
    arrowOpen.classList.add("dom-arrows");
    arrowOpen.classList.add("arrowOpen");

    const parent = element.parentNode;
    parent.insertBefore(arrowOpen ,element);
}

function setClosedArrow(element) {
    const arrowClosed = document.createElement("div");
    arrowClosed.innerHTML = "⮚";
    arrowClosed.classList.add("dom-arrows");
    arrowClosed.classList.add("arrowClosed");

    const parent = element.parentNode;
    parent.insertBefore(arrowClosed ,element);
}
