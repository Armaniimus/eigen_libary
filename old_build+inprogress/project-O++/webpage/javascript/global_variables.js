//createhtml
let HTMLglobal = HTMLglobal__construct();


function HTMLglobal__construct() {
    object = Htmlglobal__createChild('html');
    object.children.push(Htmlglobal__createChild('head'));
    object.children.push(Htmlglobal__createChild('body'));

    object.children[1].children.push(Htmlglobal__createChild('header'))
    object.children[1].children.push(Htmlglobal__createChild('main'))
    object.children[1].children.push(Htmlglobal__createChild('footer'))

    return object
}


function Htmlglobal__createChild(objectName) {
    let object = {elementTagName: objectName, children: []};
    return object;
}


function HTMLglobal__addClass(className) {

}

function HTMLglobal__addId(idName) {

}

function HTMLglobal__addAttribute(attributeName, value) {

}

function HTMLglobal__search(searchStr) {
    searchStr = searchStr.split("/");
    let strCount = 0;
    let savePath = [];
    let object = HTMLglobal;

    if (object.elementTagName == searchStr[strCount]) {
        savePath.push(strCount);
        strCount ++;

        /***************************************
        look trough everything after the html */
        for (var i = 1; i < searchStr.length; i++) {
            let returns = subFunc__searchInObject(object);

            //tests if there is a good return
            if (returns !== false) {

                //process valid return
                savePath.push(returns.savePath);
                strCount = returns.strCount;
                object = returns.returnObject
            } else {
                console.log('invalid dom traverse');

                // return false;
            }
        }
    }

    function subFunc__searchInObject(object) {
        // create return object
        let returns = {savePath:'', strCount:'', returnObject:''}


        // create or reset test object
        let multibleElement = {active: 0, count: 0, countTo: 0};


        // when there are mutlible elements set test data
        let strSplit = searchStr[strCount].split('-');
        if (strSplit.length > 1) {
            console.log(strSplit);
            searchStr[strCount] = strSplit[0];
            multibleElement.active = 1;
            multibleElement.countTo = strSplit[1];
        }

        // loop trough the objects to see if one matches.
        for (var i = 0; i < object.children.length; i++) {

            if (object.children[i].elementTagName == searchStr[strCount]) {

                /**************************
                Test for multible Element*/
                if (multibleElement.active == 1) {

                    //Return element if this is the requested element
                    if (multibleElement.countTo == multibleElement.count) {

                        //fill return object
                        returns.savePath = i;
                        returns.strCount = strCount + 1;
                        returns.returnObject = object.children[i];

                        //send return object
                        return returns;


                    // Increment the counter
                    } else {
                        multibleElement.count ++;
                    }

                /************************
                Test for single Element*/
                } else {

                    //fill return object
                    returns.savePath = i;
                    returns.strCount = strCount + 1;
                    returns.returnObject = object.children[i];

                    //send return object
                    return returns;
                }
            }
        }
        return false;
    }
    return savePath;
}

function testdivs() {
    HTMLglobal.children[1].children[0].children.push(Htmlglobal__createChild('div') );
    HTMLglobal.children[1].children[0].children.push(Htmlglobal__createChild('div') );
    HTMLglobal.children[1].children[0].children.push(Htmlglobal__createChild('div') );
    HTMLglobal.children[1].children[0].children.push(Htmlglobal__createChild('div') );
    HTMLglobal.children[1].children[0].children.push(Htmlglobal__createChild('div') );
    HTMLglobal.children[1].children[0].children.push(Htmlglobal__createChild('div') );
    HTMLglobal.children[1].children[0].children.push(Htmlglobal__createChild('div') );
}

testdivs()

// console.log(HTMLglobal__search('html-body-header') );
console.log(HTMLglobal__search('html/body/header/div-4') );



console.log(HTMLglobal);
