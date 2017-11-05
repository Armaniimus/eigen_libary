function controlRemoveChild() {
    const selectedElement = document.querySelector('.dom-selected');
    let next;
    let previous;
    let nextNext;
    let previousPrevious;
    let testValidElement = "valid";

    if (selectedElement.innerHTML == 'Page'){
        testValidElement = "invalid";

    } else if (selectedElement.innerHTML == 'Meta'){
        testValidElement = "invalid";

    } else if (selectedElement.innerHTML == 'Body'){
        testValidElement = "invalid";

    } else if (selectedElement.innerHTML == 'Header'){
        testValidElement = "invalid";

    } else if (selectedElement.innerHTML == 'Main'){
        testValidElement = "invalid";

    } else if (selectedElement.innerHTML == 'Footer') {
        testValidElement = "invalid";
    }


    if (testValidElement == "valid") {
        /*****************
        Set next variable*/
        if (selectedElement.nextElementSibling == null) {
            next = '0';
        } else {
            next = selectedElement.nextElementSibling.tagName;
        }

        /*********************
        Set pervious variable*/
        if (selectedElement.previousElementSibling == null) {
            previous = '0';
        } else {
            previous = selectedElement.previousElementSibling.tagName;
        }

        /****************************************************
        test if there is an element next to selected element*/
        if (next == '0' && previous == '0') {
            subFunc__removeParent()
        }

        /*******************************************************************
        Test if there is a li element directly next to the selected element*/
        else if (previous == 'LI') {
            subFunc__removeElement()
        }

        else if (next == 'LI') {
            subFunc__removeElement()
        }

        /******************************************************************
        Test if there is an element container nxt to the selected Element*/
        else if (next == 'DIV' && selectedElement.tagName == 'LI') {
            subFunc__removeElement()
        }

        else if (previous == 'UL' && selectedElement.tagName == 'LI') {
            subFunc__removeElement()
        }

        else {
            /*********************
            Set nextNext variable*/
            if (selectedElement.nextElementSibling.nextElementSibling == null) {
                nextNext = '0';
            } else {
                nextNext = selectedElement.nextElementSibling.nextElementSibling.tagName;
            }

            /*****************************
            Set previousPrevious variable*/
            if (selectedElement.previousElementSibling.previousElementSibling == null) {
                previousPrevious = '0';
            } else {
                previousPrevious = selectedElement.previousElementSibling.previousElementSibling.tagName;
            }

            /***************************************************
            tests if the selected element is a lonely container*/
            if (nextNext == '0' && previousPrevious == '0') {
                subFunc__removeParent()

            /*****************************************************************
            tests if the selected element container has an element next to it*/
            } else if (nextNext == 'LI' || nextNext == 'DIV') {
                subFunc__removeContainer();

            } else if (previousPrevious == 'LI' || previousPrevious == 'UL') {
                subFunc__removeContainer()
            }
        }

        function subFunc__removeElement() {
            /**********************
            removeSelectedElement*/
            selectedElement.parentNode.removeChild(selectedElement);
        }

        function subFunc__removeParent() {
            /***********************************
            Sets the class of the visual parent right*/
            if (selectedElement.parentNode.previousElementSibling.classList.contains('dom-nav--opencontent')) {
                selectedElement.parentNode.previousElementSibling.classList.remove('dom-nav--opencontent');
            }
            if (selectedElement.parentNode.previousElementSibling.classList.contains('dom-nav--closedcontent')) {
                selectedElement.parentNode.previousElementSibling.classList.remove('dom-nav--closedcontent');
            }
            selectedElement.parentNode.previousElementSibling.classList.add('dom-nav--content');

            /************************************************************
            removes the domArrow and the Ul wrapper around the selected*/
            selectedElement.parentNode.parentNode.removeChild(selectedElement.parentNode.previousElementSibling.previousElementSibling);
            selectedElement.parentNode.parentNode.removeChild(selectedElement.parentNode);
        }

        function subFunc__removeContainer() {
            /*************************************************************
            removes the Container selectedelement and the arrow in front*/
            selectedElement.parentNode.removeChild(selectedElement.previousElementSibling);
            selectedElement.parentNode.removeChild(selectedElement.nextElementSibling);
            selectedElement.parentNode.removeChild(selectedElement);
        }

    } else {
        alert("You can't remove this element");
    }
}
