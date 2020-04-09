document.querySelector("#bereidingswijze").addEventListener("click", controlRecept, event);
// document.querySelector("#ingredienten").addEventListener("click", controlRecept, event);
function controlRecept(event) {
    if (event.target.tagName == "LI") {

        if (event.target.className == "active") {
            event.target.className = "";
        } else {
            event.target.className = "active";
        }
    }
}
