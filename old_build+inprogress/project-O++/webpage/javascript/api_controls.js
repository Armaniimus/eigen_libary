//createhtml
let HTMLApi = HTMLApi__construct();


function HTMLApi__construct() {
    object = HTMLApi__addChild('html');
    object.children.push(HTMLApi__addChild('head'));
    object.children.push(HTMLApi__addChild('body'));

    object.children[1].children.push(HTMLApi__addChild('header'));
    object.children[1].children.push(HTMLApi__addChild('main'));
    object.children[1].children.push(HTMLApi__addChild('footer'));

    return object
}


function HTMLApi__addChild(objectName) {
    let object = {elementTagName: objectName, children: [], classList: [], elementId: '', attributeList: []};
    return object;
}


function HTMLApi__addClass(className) {

}

function HTMLApi__addId(idName) {

}

function HTMLApi__addAttribute(attributeName, value) {

}

function HTMLApi__removeElement(object) {
    console.log(object);
}

function HTMLApi__search(searchStr, func, name, attributeValue) {

    // console.log(' ')
    // console.log(' ')
    // console.log(' ')
    // console.log(' ')
    // console.log('searchStr = ' + searchStr)
    // console.log('func = ' + func)
    // console.log('name = ' + name)
    // console.log('attributeValue = ' + attributeValue)


    searchStr = searchStr.split("/");
    let strCount = 0;
    let savePath = [];
    let object = HTMLApi;

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
                return('invalid dom traverse');
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
            searchStr[strCount] = strSplit[0];
            multibleElement.active = 1;
            multibleElement.countTo = strSplit[1];
        }

        // loop trough the objects to see if one matches.
        let counti = 0;
        while(counti < object.children.length) {
            if (object.children[counti].elementTagName == searchStr[strCount]) {

                /**************************
                Test for multible Element*/
                if (multibleElement.active == 1) {

                    //Return element if this is the requested element
                    if (multibleElement.countTo == multibleElement.count) {

                        //fill return object
                        returns.savePath = counti;
                        returns.strCount = strCount + 1;
                        returns.returnObject = object.children[i];

                        //send return object
                        // return returns;


                    // Increment the counter
                    } else {
                        multibleElement.count ++;
                    }

                /************************
                Test for single Element*/
                } else {

                    //fill return object
                    returns.savePath = counti;
                    returns.strCount = strCount + 1;
                    returns.returnObject = object.children[i];

                    //send return object
                    // return returns;
                }
            }

            // console.log('logCount: ' + counti)
            // console.log(object);
            // console.log(object.children.length)
            counti++
        }



        return returns;
        return false;
    }
    // console.log('hello');
    return subFunc__traveseGlobal();

    function subFunc__traveseGlobal() {
        if (savePath.length == 2) {
            return subFunc__mutate(HTMLApi.children[savePath[1]]);

        } else if (savePath.length == 3) {
            return subFunc__mutate(HTMLApi.children[savePath[1]].children[savePath[2]]);

        } else if (savePath.length == 4) {
            HTMLApi.children[savePath[1]].children[savePath[2]].children[savePath[3]];
            return subFunc__mutate(HTMLApi.children[savePath[1]].children[savePath[2]].children[savePath[3]]);

        } else if (savePath.length == 5) {
            HTMLApi.children[savePath[1]].children[savePath[2]].children[savePath[3]].children[savePath[4]];

            return subFunc__mutate(HTMLApi.children[savePath[1]].children[savePath[2]].children[savePath[3]].children[savePath[4]]);
        }
        return 'Api pathname length not supported';
    }

    function subFunc__mutate(object) {
        if (func == 'addchild' || func == 0) {
            obj = HTMLApi__addChild(name);
            object.children.push(obj);
            return true;

        } else if (func == 'addclass' || func == 1) {
            obj = HTMLApi__addClass(name);
            object.classList.push(obj);
            return true;

        } else if (func == 'addattribute' || func == 2) {
            obj = HTMLApi__addAttribute(name, attributeValue);
            object.attributeList.push(obj);
            return true;

        } else if (func == 'addid' || func == 3) {
            obj = HTMLApi__addId(name);
            object.elementId = obj;
            return true;

        } else if (func == 'removeElement' || func == 4) {
            // HTMLApi__removeElement(object);
            // object.parentNode.removeChild(object);
            object.indexOf;
            return true;
        }

        return 'invalidFunctionName';
    }
}


function HTMLDOM__search() {
    const selectedElement = document.querySelector('.dom-selected');
    let currentEl = selectedElement;
    let parent;
    let navigationArray = [];
    let tagname;
    let tagcount;

    tagname = currentEl.innerHTML.toLowerCase();
    let i= 0;

    while (i < 200) {
        let ii = 0;
        tagcount = 0;

        while (ii < 400) {

            // Test for existence
            if (currentEl == undefined || currentEl == null) {
                break;

            // Test for existence of previousElementSibling
            } else if (currentEl.previousElementSibling == undefined || currentEl.previousElementSibling == null) {
                navigationArray.unshift(tagname);
                break;

            } else {

                // Test if current test sibling is the same as element inside the dompath
                if (currentEl.tagName.toLowerCase == tagname) {
                    tagcount ++
                }

                // set the new currentEl
                currentEl = currentEl.previousElementSibling;

                //increment safety variable
                ii++;
            }
        }

        // set parent var
        parent = currentEl.parentNode;

        // test the parent for exitence
        if (parent == undefined || parent == null) {
            break;

        // test the parent for special class
        } else if (parent.classList.contains('dom-navigation') ) {
            break;

        } else {
            // set tagname
            tagname[0] = tagname[0].toUpperCase();
            tagname = parent.previousElementSibling.innerHTML.toLowerCase();

            // set currentEl
            currentEl = parent;
            i++;
        }
    }
    let navigationUrl = navigationArray[0];

    for (let iii = 1; iii < navigationArray.length; iii++) {
        navigationUrl += '/';
        navigationUrl += navigationArray[iii];
        if (tagcount > 0) {
            navigationUrl += '-' + tagname;
        }
    }
    return navigationUrl;
}

console.log(HTMLApi);
