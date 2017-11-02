const openContent =  document.querySelectorAll(".dom-nav--opencontent");

// openContent[0].style.backgroundColor = "blue";

for (let i = 0; i < openContent.length; i++) {
    const arrowOpen = document.createElement("div");
    arrowOpen.innerHTML = "⮛";
    // arrowOpen.style.display = "inline";
    // arrowOpen.style.width = "20px";
    // arrowOpen.style.cursor = "pointer";
    arrowOpen.classList.add("dom-arrows");
    arrowOpen.classList.add("arrowOpen");

    const parent = openContent[i].parentNode;
    parent.insertBefore(arrowOpen ,openContent[i]);
}

const closedContent =  document.querySelectorAll(".dom-nav--closedcontent");

// openContent[0].style.backgroundColor = "blue";

for (let i = 0; i < closedContent.length; i++) {
    const arrowClosed = document.createElement("div");
    arrowClosed.innerHTML = "⮚";
    arrowClosed.classList.add("dom-arrows");
    arrowClosed.classList.add("arrowClosed");

    const parent = closedContent[i].parentNode;
    parent.insertBefore(arrowClosed ,closedContent[i]);
}


document.querySelector('.dom-navigation').addEventListener("click", function(e) {
    if (e.target.classList.contains('dom-arrows') ) {
        if (e.target.classList.contains('arrowOpen')) {
            e.target.innerHTML = "⮚";
        } else {
            e.target.innerHTML = "⮛";
        }

        e.target.nextElementSibling.nextElementSibling.classList.toggle("page-box");
        e.target.nextElementSibling.nextElementSibling.classList.toggle("page-box-hidden");

        e.target.classList.toggle("arrowOpen");
        e.target.classList.toggle("arrowClosed");
    }
});
