let ajaxResponse = "";

function ajax(selector, value, mode) {
    const xhr = new XMLHttpRequest();
    const url = "../php/get_articles.php";
    const target = "?" + selector + "=" + value;
    let response = 0;
    let article;

    xhr.open('GET', url + target);
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


function loadArticles(selector, value, mode, func) {
    ajaxResponse = "";
    let count = 0;
    console.log("Load articles para" + selector + "," + mode + "," + value + "," + func);

    ajax(selector, value, mode);

    var stop = setInterval(waitForResponse, 100);
    function waitForResponse() {

        if (count < 10000) {
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










function ajaxAfhandeling() {
    console.log(ajaxResponse);
}

loadArticles("1", "2", 1, ajaxAfhandeling);

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




    // document.write("hello");
    // document.write(article);

    //
    //
    //
    // <article class="">
    //     <header class="article-head">
    //         <h3>myhead</h3>
    //     </header>

    //     <div class="article-content">
    //         <div class="col-12">
    //             <p class="col-12">
    //
    //             </p>
    //             <div class="float col-6">
    //
    //             </div>
    //             <div class="float col-6">
    //
    //             </div>
    //         </div>
    //     </div>
    // </article>
