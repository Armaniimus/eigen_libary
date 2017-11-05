function controlRemoveChild() {
    const selectedElement = document.querySelector('.dom-selected');
    let next;
    let previous;
    let nextNext;
    let previousPrevious;
    /*=================
    Do existence tests*/

    if (selectedElement.nextElementSibling == null) {
        next = '0';
    } else {
        next = selectedElement.nextElementSibling.tagName;
    }

    if (selectedElement.previousElementSibling == null) {
        previous = '0';
    } else {
        previous = selectedElement.previousElementSibling.tagName;
    }

    if (next == '0' && previous == '0') {
        subFunc__removeParent()
    }

    else if (previous == 'LI') {
        subFunc__removeElement()
    }

    else if (next == 'LI') {
        subFunc__removeElement()
    }

    else if (next == 'DIV' && selectedElement.tagName == 'LI') {
        subFunc__removeElement()
    }

    else if (previous == 'UL' && selectedElement.tagName == 'LI') {
        subFunc__removeElement()
    }

    else {
        if (selectedElement.nextElementSibling.nextElementSibling == null) {
            nextNext = '0';
        } else {
            nextNext = selectedElement.nextElementSibling.nextElementSibling.tagName;
        }

        if (selectedElement.previousElementSibling.previousElementSibling == null) {
            previousPrevious = '0';
        } else {
            previousPrevious = selectedElement.previousElementSibling.previousElementSibling.tagName;
        }

        if (nextNext == '0' && previousPrevious == '0') {
            subFunc__removeParent()

        } else if (nextNext == 'LI' || nextNext == 'DIV') {
            subFunc__removeContainer();

        } else if (previousPrevious == 'LI' || previousPrevious == 'UL') {
            subFunc__removeContainer()
        }
    }

    function subFunc__removeElement() {
        selectedElement.parentNode.removeChild(selectedElement);
    }

    function subFunc__removeParent() {
        //sets the styling of the parent right
        if (selectedElement.parentNode.previousElementSibling.classList.contains('dom-nav--opencontent')) {
            selectedElement.parentNode.previousElementSibling.classList.remove('dom-nav--opencontent');
        }
        if (selectedElement.parentNode.previousElementSibling.classList.contains('dom-nav--closedcontent')) {
            selectedElement.parentNode.previousElementSibling.classList.remove('dom-nav--closedcontent');
        }
        selectedElement.parentNode.previousElementSibling.classList.add('dom-nav--content');

        //removes the domArrow and the Ul wrapper around the selected
        selectedElement.parentNode.parentNode.removeChild(selectedElement.parentNode.previousElementSibling.previousElementSibling);
        selectedElement.parentNode.parentNode.removeChild(selectedElement.parentNode);
    }

    function subFunc__removeContainer() {
        selectedElement.parentNode.removeChild(selectedElement.previousElementSibling);
        selectedElement.parentNode.removeChild(selectedElement.nextElementSibling);
        selectedElement.parentNode.removeChild(selectedElement);
    }
}
