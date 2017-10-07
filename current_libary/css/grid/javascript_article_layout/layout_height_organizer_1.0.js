/*************************************************************************
 this program is free to use on the following 2 conditions
 1. I am not responsible for any damage resulted by use of this program
 2. its required to let the following notification stand

 copyright owned by
 Armaniimus-webdevelopment
 website:http://Armaniimus-webdevelopment.nl/
**************************************************************************/

/********************
 set global variables
*********************/
let htmlElements;

/************************
 Set all support elements
*************************/

// compares all the Children of the specified element
function compareChildren(allHeights, htmlElement) {

    /*  after comparing the heights switch the current
        compared child with the heighest after it if present */
    for (let ii = 0; ii < allHeights.length; ii++) {
        let compare = ii;
        for (let iii = ii+1; iii < allHeights.length; iii++) {

            // compares the heights
            if (allHeights[compare] < allHeights[iii]) {
                compare = iii;
            }
        }

        // switch the 2 defined elements
        const switchChild = htmlElement.children[ii].innerHTML; //temp <-- ii
        htmlElement.children[ii].innerHTML = htmlElement.children[compare].innerHTML; //ii <-- compare
        htmlElement.children[compare].innerHTML = switchChild; // compare <-- temp

        // switch the 2 defined heights
        const switchHeight = allHeights[ii];
        allHeights[ii] = allHeights[compare];
        allHeights[compare] = switchHeight;
    }
    return htmlElement;
}

// loops trough the elements to get all the required heights
function getAllHeights(htmlElement) {
    let allHeights = [];
    for (var i = 0; i < htmlElement.children.length; i++) {

        //gets the main height of the specified element
        allHeights[i] = getHeight(htmlElement.children[i].children[1].children[0]);
    }
    return allHeights;
}

// gets the height of the specified element
function getHeight(element) {
    let height = window.getComputedStyle(element, null);
    height = height.getPropertyValue("height");
    height = parseInt(height);

    return height;
}


/***************************************
 Start of the controller of the program
****************************************/

function sortHtmlOnHeights() {
    let i = 1;
    globalChildren = [];
    let num;

    while (i !== "end" || i < 500) {

        // gets content boxes
        const id = "articlebox-" + (i);
        const htmlElement = document.getElementById(id);

        // test if box exists
        if (htmlElement == null) {
            i = "end";
        } else {

            // set global
            const allHeights = getAllHeights(htmlElement);
            const sortedChildren = compareChildren(allHeights, htmlElement);
            htmlElement.innerHTML = sortedChildren.innerHTML;
            i++;
        }
    }
}

/****************************
 Set the Start of the progam
*****************************/
sortHtmlOnHeights();
