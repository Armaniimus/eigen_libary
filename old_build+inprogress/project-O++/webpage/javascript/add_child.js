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
