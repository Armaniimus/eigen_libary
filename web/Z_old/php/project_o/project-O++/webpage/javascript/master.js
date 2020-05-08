// Adds the icons in front of the dom tree where applicable
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

/*===============
 controlAddChild
================*/
function controlAddChild() {
    const currentElementType = document.querySelector('.dom-selected').innerHTML;

    //createElement
    const inputFormSuround = document.createElement('div');
    const inputForm = addChildAddSelect(currentElementType);


    //addclasses
    inputFormSuround.classList.add('inputformsurounding');
    inputForm.classList.add('inputform');


    //append everything
    inputFormSuround.appendChild(inputForm);
    document.querySelector('body').prepend(inputFormSuround);

    //add a event listener
    document.getElementById('addchildbutton').addEventListener("click", procesAddChild);
}

function addChildAddSelect(type) {
    const inputField = document.createElement('div');

    let selectElementType = '<select class="form-addelement-select" id="addelement-type">';
    if (type == 'Header') {
        const HeaderOptions = ['Div','Ul','H1','H2', 'H3'];

        for (let i = 0; i < HeaderOptions.length; i++) {
             selectElementType += "<option>" + HeaderOptions[i] + "</option>";
        }
    }
    selectElementType += '</select>';

    inputField.innerHTML += '<label class="form-addelement-label" for="addelement-type">Specify element type</label>';
    inputField.innerHTML += selectElementType;

    inputField.innerHTML += '<button class="form-addelement-button" id="addchildbutton">Add child</button>';

    return inputField;
}

function procesAddChild() {

    // get all neccesary information
    const getElementType = document.getElementById('addelement-type');
    const selectedElement = document.querySelector('.dom-selected');
    const newElementTagName = getElementType.value;
    const parent = selectedElement.nextElementSibling;

    // create the new element
    const newElement = document.createElement('LI');
    newElement.classList.add("dom-nav--content")
    newElement.innerHTML = newElementTagName;

    if (parent.tagName == 'UL') {
        alert('yes');
    } else {
        // get neccesary information
        const container = parent.parentNode;

        // create container
        const newElementContainer = document.createElement('ul');
        newElementContainer.classList.add("dom-indent");
        newElementContainer.classList.add("page-box");

        // append element to new container
        newElementContainer.appendChild(newElement);

        // append container
        container.insertBefore(newElementContainer, parent);

    }

    // removes addChildForm
    const remove = document.querySelector('.inputformsurounding');
    remove.parentNode.removeChild(remove);

    // Set the arrows and classes for the controlling element
    selectedElement.classList.remove('dom-nav--content');
    selectedElement.classList.add('dom-nav--opencontent');

    setOpenArrow(selectedElement)

}



document.getElementById('addChild').addEventListener("click", controlAddChild)
document.querySelector('.dom-navigation').addEventListener("click", controlDomTree, 1);
addDomIcons();
