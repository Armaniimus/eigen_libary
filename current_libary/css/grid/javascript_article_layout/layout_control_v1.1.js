/*************************************************************************
 this program is free to use on the following 2 conditions
 1. I am not responsible for any damage resulted by use of this program
 2. its required to let the following notification stand

 copyright owned by
 Armaniimus-webdevelopment
 website:http://Armaniimus-webdevelopment.nl/
**************************************************************************/



// tests width to define how many columns there are inside the html
let globalColumns = 0;
let globalChildren;

function setGlobalColumns(newValue) {
    globalColumns = newValue;
}

function setGlobalChildren() {

    let i = 1;
    globalChildren = [];
    let num;

    while (i !== "end" || i < 500) {

        // gets content boxes
        const id = "articlebox-" + (i);
        const child = document.getElementById(id);

        // test if box exists
        if (child == null) {
            i = "end";

        } else {
            // set global
            num = -1 + i;
            globalChildren[num] = [];

            for (let ii = 0; ii < child.children.length; ii++) {
                // globalChildren[num] = child.children[ii];

                globalChildren[num][ii] = {head:"", main:""};
                globalChildren[num][ii].head = getHeight(child.children[ii].children[0]);
                globalChildren[num][ii].main = getHeight(child.children[ii].children[1].children[0]);
                globalChildren[num][ii].main = globalChildren[num][ii].main + 30;
            }
            i++;
        }
    }
}

// gets the height of the specified element
function getHeight(element) {
    let height = window.getComputedStyle(element, null);
    height = height.getPropertyValue("height");
    height = parseInt(height);

    return height;
}

function testForColumns() {

    // gets width of the page
    const pWidth = window.innerWidth;

    if (pWidth < 600) {

        // set localCol
        const localCol = 1;
        return localCol;

    } else if (pWidth < 768) {

        // set localCol
        const localCol = 2;
        return localCol;

    } else if (pWidth < 1000) {

        // set localCol
        const localCol = 3;
        return localCol;

    } else {

        // set localCol
        const localCol = 4;
        return localCol;
    }
}

function setStyles(columns) {

    let count = 1;
    let countI;
    while (count < 500 && countI != "end") {

        if (document.getElementById('articlebox-' + count) == null ) {
            console.log("end");
            countI = "end";
        } else {
            const box = document.getElementById('articlebox-' + count).children;

            for (let i = 0; i < box.length; i++) {
                box[i].style.cssFloat = "left";
                box[i].style.padding = "10px 10px";
            }
            count++;

            if (columns == 1) {
                for (let i = 0; i < box.length; i++) {
                    box[i].style.width = "100%";
                }

            } else if (columns == 2) {
                for (let i = 0; i < box.length; i++) {
                    box[i].style.width = "50%";
                }

            } else if (columns == 3) {
                for (let i = 0; i < box.length; i++) {
                    box[i].style.width = "33.33%";
                }

            } else if (columns == 4) {
                for (let i = 0; i < box.length; i++) {
                    box[i].style.width = "25%";
                }
            }
        }
    }
}

function setChangeArray() {
    if (globalColumns == 1) {
        let changeArray = [];

        for (let ii = 0; ii < 12; ii++) {
            changeArray[ii] = [ii];
        }
        return changeArray;

    } else if (globalColumns == 2) {
        const changeArray = [[0, 1], [2, 3], [4, 5], [6, 7], [8, 9], [10, 11]];
        return changeArray;

    } else if (globalColumns == 3) {
        const changeArray = [[0, 1, 2], [3 ,4, 5], [6, 7, 8], [9, 10, 11]];
        return changeArray;

    } else if (globalColumns == 4){
        const changeArray = [[0, 1, 2, 3],[4, 5, 6, 7],[8, 9, 10, 11]];
        return changeArray;
    }
}

function getHTMLHeights(elements, start, end, mode) {
    let height = 0;
    for (let i = start; i <= end; i++) {

        let testHeight;
        if (typeof elements[i] != 'undefined') {

            //gets height based on the mode used and take the heighest height of the provided set
            if (mode == "head") {
                testHeight = elements[i].head;
                if (testHeight > height) {
                    height = testHeight;
                }

            } else if (mode == "main") {
                testHeight = elements[i].main;
                if (testHeight > height) {
                    height = testHeight;
                }
            }

        }
    }
    return height;
}

function setHTMLHeights(elements, start, end, heightHead, heightMain) {
    for (let i = start; i <= end; i++) {

        //tests if an element exists and before adding height
        if (elements[i] != null && typeof elements[i] != "undefined") {
            elements[i].children[0].style.height = heightHead + "px";
            elements[i].children[1].style.height = heightMain + "px";
        }
    }
}

function controlHeights() {

    // test amount of columns
    const localColumns = testForColumns();

    //set globalBoxStyles
    setStyles(localColumns);

    for (var num = 0; num < globalChildren.length; num++) {

        // set globalColumns
        setGlobalColumns(localColumns);

        //set variables
        const savedChildren = globalChildren[num];
        const id = num + 1;
        const htmlChildren = document.getElementById('articlebox-' + id).children;
        const changeArray = setChangeArray();
        let start;
        let end;
        let suppVar;

        //loops trough the rows of a articlebox
        for (var i = 0; i < changeArray.length; i++) {
            // set start variable
            start = changeArray[i][0];

            // set end variable
            suppVar = changeArray[i].length - 1;
            end = changeArray[i][suppVar];

            //get variables
            const setHeadHeight = getHTMLHeights(savedChildren, start, end, "head");
            const setMainHeight = getHTMLHeights(savedChildren, start, end, "main");

            // set variables in the html dom
            setHTMLHeights(htmlChildren, start, end, setHeadHeight, setMainHeight);
        }
    }
}

setGlobalChildren();
controlHeights();

setGlobalChildren();
controlHeights();

window.addEventListener("resize", setGlobalChildren);
window.addEventListener("resize", controlHeights);
