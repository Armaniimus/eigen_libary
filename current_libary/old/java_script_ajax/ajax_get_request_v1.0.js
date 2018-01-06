let ajaxResponse = "";

/*************************************************************************************
F01 D:none; S(G)
Status: Good
Function: creates an Get request url
Variables input:
    obj: needs an js object and a number;
    mode: needs a number;
*/
function createUrl(obj, mode) {
    let url = "?";
    i = 0;
    for (let key in obj) {
        if (i > 0) {
            url += "&";
        }
        url += key + "=" + obj[key];
        i++;
    }
    url += "&mode=" + mode;
    return url;
}

/*************************************************************************************
F02 D:none; S(G) <--- This is A support function of the getAjaxController function
Status: Good
Function: Sends a get request
Variables input:
    url: url to a php file preferably
    urlSel: An url extention wich contains the data for the get request.
    mode: an selector to select if you want to return the result the the var ajaxResponse or print it to the console
*/
function getAjax(url, urlSel, mode) {
    const xhr = new XMLHttpRequest();
    const target = "?" + selector + "=" + value;
    let response = 0;
    let article;

    xhr.open('GET', url + urlSel);
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            if (mode == "console" || mode == 0) {
                console.log(response);
            } else if (mode == "return" || mode == 1) {
                ajaxResponse = response;
            }

        }
        else if (xhr.status !== 200) {
            alert('Request failed.  Returned status of ' + xhr.status);
        } else {
            return 0
        }
    };
    xhr.send();
    if (response != 0) {
        func(response);
    }

}

/*************************************************************************************
F03 D:getAjax; var ajaxResponse S(G)
Status: Good
Function: Sends a get request and waits until the request is catched
            then pass it to the function which is send to this function.
                timeout for the test is around 5 seconds.
Variables input:
    url: url to a php file preferably
    urlSel: An url extention wich contains the data for the ger request.
    mode: selects if you want to return the variable to the var ajaxResponse or print it to the console
    func: that handles the ajax request when ready
*/
function getAjaxController(url urlSel, mode, func) {
    ajaxResponse = "";
    let count = 0;

    ajax(url, urlSel, mode);

    const stop = setInterval(waitForResponse, 100);
    function waitForResponse() {

        if (count < 5000) {
            count += 100;
            console.log(count);

            if (ajaxResponse != "") {
                // console.clear();
                func(ajaxResponse);

                clearInterval(stop);
            }
        } else {
            clearInterval(stop);
        }
    }
}

/*************************************************************************************
F04 D:getAjax; var ajaxResponse S(G)
Status: Good
Function: Sends a get request and waits until the request is catched
            then pass it to the function which is send to this function.
                timeout for the test is around 5 seconds.
Variables input:
    url: url to a php file preferably
    urlSel: An url extention wich contains the data for the ger request.
    mode: an variable which get send where you can takes a string or number what ever you want to send.
    Function: that handles the ajax request when ready
*/

// example ajax passable function
function ajaxAfhandeling(ajaxResponse) {
    console.log(ajaxResponse);
}







/***********************************
example of a create artile function
*/
function createArticle() {

    let article = document.createElement("article");

        // creates the head of the article
        let articleHead = document.createElement("header");
        articleHead.classList.add("article-head");
            let articleH3 = document.createElement("h3");
                articleH3.innerHTML = "H3";
            articleHead.appendChild(articleH3);
        article.appendChild(articleHead);

        // Creates Article body
        let articleBody = document.createElement("div");
        articleBody.classList.add("article-content");
            let articleBodyWrapper = document.createElement("div");
            articleBodyWrapper.classList.add("col-12");

                // adds price to body
                let articlePrice = document.createElement("p");
                    articlePrice.innerHTML = "price";
                articleBodyWrapper.appendChild(articlePrice);

                // adds picture 1 to body
                let articlePicture1 = document.createElement("img");
                    articlePicture1.innerHTML = "picture1";
                articleBodyWrapper.appendChild(articlePicture1);

                // adds picture 2 to body
                let articlePicture2 = document.createElement("img");
                    articlePicture2.innerHTML = "picture2";
                articleBodyWrapper.appendChild(articlePicture2);

                // adds picture 3 to body
                let articlePicture3 = document.createElement("img");
                    articlePicture3.innerHTML = "picture3";
                articleBodyWrapper.appendChild(articlePicture3);

                // adds article number to body
                let articleNumber = document.createElement("p");
                    articleNumber.innerHTML = "article number";
                articleBodyWrapper.appendChild(articleNumber);

                // adds beschrijving to body
                let articleBeschrijving = document.createElement("p");
                    articleBeschrijving.innerHTML = "beschrijving";
                articleBodyWrapper.appendChild(articleBeschrijving);

        articleBody.appendChild(articleBodyWrapper);
    article.appendChild(articleBody);



    document.body.appendChild(article);
}
