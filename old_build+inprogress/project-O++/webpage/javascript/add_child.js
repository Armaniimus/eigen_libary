/*===============
 controlAddChild
================*/
function controlAddChild() {
    const currentElementType = document.querySelector('.dom-selected').innerHTML;

    //createElement
    const inputFormSuround = document.createElement('div');
    const inputForm = addChildAddform(currentElementType)


    //addclasses
    inputFormSuround.classList.add('inputformsurounding');
    inputForm.classList.add('inputform');


    //append everything
    inputFormSuround.appendChild(inputForm);
    document.querySelector('body').prepend(inputFormSuround);

    //add a event listener
    document.getElementById('addchildbutton').addEventListener("click", procesAddChild);
}

function addChildAddform(currentElementType) {
    const inputField = document.createElement('div');

    let selectElementType = '<select class="form-addelement-select" id="addelement-type">';

    selectElementType += addChildAddOptions(currentElementType);

    selectElementType += '</select>';

    inputField.innerHTML += '<label class="form-addelement-label" for="addelement-type">Specify element type</label>';
    inputField.innerHTML += selectElementType;

    inputField.innerHTML += '<button class="form-addelement-button" id="addchildbutton">Add child</button>';

    return inputField;
}

function addChildAddOptions(currentElementType) {
    let optionBodyArray;

    getData(currentElementType);

    optionBodyArray = subFunc__testElementMajor(currentElementType);

    if (optionBodyArray === 'false') {
        optionBodyArray = subFunc__testElementList(currentElementType);
    }

    if (optionBodyArray === 'false') {
        optionBodyArray = subFunc__testElementTable(currentElementType);
    }

    if (optionBodyArray === 'false') {
        optionBodyArray = subFunc__testElementForm(currentElementType);
    }

    if (optionBodyArray === 'false') {
        optionBodyArray = subFunc__testElementCommon(currentElementType);
    }

    if (optionBodyArray === 'false') {
        alert('Element is not supported');

    } else {
        let selectBody;
        for (let i = 0; i <  optionBodyArray.length; i++) {
             selectBody += "<option>" +  optionBodyArray[i] + "</option>";
        }
        return selectBody;
    }

    function subFunc__testElementMajor(currentElementType) {
        if (currentElementType == 'Header') {
             optionBodyArray = ['Div','Ul', 'Img', 'H1','H2', 'H3', 'Button', 'P'];

        } else if (currentElementType == 'Main') {
             optionBodyArray = ['Div','Ul', 'Ol', 'Table', 'Form', 'Img', 'H1','H2', 'H3', 'H4', 'H5','Aside', 'Article', 'Section', 'span', 'A', 'Button', 'P'];

        } else if (currentElementType == 'Footer') {
             optionBodyArray =  ['Div','Ul', 'Img', 'H1','H2', 'H3', 'Button', 'P'];

        } else if (currentElementType == 'Section') {
             optionBodyArray =  ['Div','Ul', 'Ol', 'Table', 'Form', 'Img', 'H1','H2', 'H3', 'H4', 'H5', 'span', 'A', 'Button', 'P', 'Article'];

        } else if (currentElementType == 'Article') {
             optionBodyArray =  ['Div','Ul', 'Ol', 'Table', 'Form', 'Img', 'H1','H2', 'H3', 'H4', 'H5', 'span', 'A', 'Button', 'P'];

        } else if (currentElementType == 'Aside') {
             optionBodyArray =  ['Div','Ul', 'Ol', 'Table', 'Img', 'H1','H2', 'H3', 'H4', 'H5', 'span', 'A', 'Button', 'P'];

        } else {
            return 'false'
        }

        return optionBodyArray;
    }

    function subFunc__testElementList(currentElementType) {
        if (currentElementType == 'Ul') {
             optionBodyArray =  ['Li'];

        } else if (currentElementType == 'Ol') {
             optionBodyArray =  ['Li'];

        } else if (currentElementType == 'Li') {
             optionBodyArray =  ['A', 'Ul', 'Ol', 'Div', 'Img', 'Button', 'P'];

        } else {
           return 'false';
       }

       return optionBodyArray;
    }

    function subFunc__testElementTable(currentElementType) {
        if (currentElementType == 'Table') {
             optionBodyArray =  ['Thead', 'Tfoot', 'Tbody', 'Caption', 'Colgroup'];

        } else if (currentElementType == 'Thead') {
             optionBodyArray =  ['Tr'];

        } else if (currentElementType == 'Tbody') {
             optionBodyArray =  ['Tr'];

        } else if (currentElementType == 'Tfoot') {
             optionBodyArray =  ['Tr'];

        } else if (currentElementType == 'Colgroup') {
             optionBodyArray =  ['Col'];

        } else {
           return 'false';
       }

       return optionBodyArray;
    }

    function subFunc__testElementForm(currentElementType) {
        if (currentElementType == 'Form') {
            optionBodyArray =  ['Input', 'Button', 'Select', 'Textarea', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'Fieldset', 'Label', 'Optgroup'];

       } else if (currentElementType == 'Select') {
            optionBodyArray =  ['Option'];

       } else if (currentElementType == 'Optgroup') {
            optionBodyArray =  ['Option'];

       } else if (currentElementType == 'Fieldset') {
            optionBodyArray =  ['Legend', 'Input', 'Textarea', 'Select', 'Label', 'Optgroup', 'Button'];

       } else {
           return 'false';
       }

       return optionBodyArray;
    }

    function subFunc__testElementCommon(currentElementType) {
        if (currentElementType == 'Div') {
            optionBodyArray =  ['Div','Ul', 'Ol', 'Table', 'Form', 'Img', 'H1','H2', 'H3', 'H4', 'H5','Aside', 'Article', 'Section', 'A', 'Button', 'P', 'Input'];
            getData(optionBodyArray);
        } else {
           return 'false';
       }

       return optionBodyArray;
    }
}


function procesAddChild() {

    // get all neccesary information
    const getElementType = document.getElementById('addelement-type');
    const selectedElement = document.querySelector('.dom-selected');
    const newElementTagName = getElementType.value;

    // create the new element
    const newElement = document.createElement('LI');
    newElement.classList.add("dom-nav--content");
    newElement.innerHTML = newElementTagName;

    addChildToDom(selectedElement, newElement, newElementTagName);

    /*========================
     removes addchild overlay*/
    const remove = document.querySelector('.inputformsurounding');
    remove.parentNode.removeChild(remove);
}

function addChildToDom(selectedElement, newElement, newElementTagName) {
    //if the next sibling doesn't exist
    if (selectedElement.nextElementSibling == undefined || selectedElement.nextElementSibling == null) {
        subFunc_creatElements();

    //if the next sibling exists and is an ul element
    } else if (selectedElement.nextElementSibling.tagName == 'UL') {
        selectedElement.nextElementSibling.appendChild(newElement);

    //if the next sibling exists and isn't an ul element
    } else {
        subFunc_creatElements();
    }

    function subFunc_creatElements() {

        // get neccesary information
        const parent = selectedElement.parentNode;

        // create container
        const newElementContainer = document.createElement('ul');
        newElementContainer.classList.add("dom-indent");
        newElementContainer.classList.add("page-box");

        // append element to new container
        newElementContainer.appendChild(newElement);

        // insert New element before old element
        parent.insertBefore(newElementContainer, selectedElement);

        // switch the 2 elements
        parent.insertBefore(selectedElement, newElementContainer);


        // Set the arrows and classes for the controlling element
        selectedElement.classList.remove('dom-nav--content');
        selectedElement.classList.add('dom-nav--opencontent');

        setOpenArrow(selectedElement)
    }
    HTMLApi__search( HTMLDOM__search(), 0, newElementTagName);
}
