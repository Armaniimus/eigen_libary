const selectbox1 = document.getElementById("avrCustomSelect1");
const selectbox1Span = selectbox1.getElementsByTagName('span')[0];
const selectbox1Ul = selectbox1.getElementsByTagName('ul')[0];
const selectbox1Options = document.querySelectorAll("#avrCustomSelect1 .list .option");
const secretinput = document.getElementById("additioneleDiensten");
let wasOpen;

window.addEventListener("click", function() {
    if ( selectbox1.classList.contains("open") ) {
        selectbox1.classList.remove("open");
        wasOpen = true;
    } else {
        wasOpen = false;
    }
});

selectbox1.addEventListener("click", function() {
    setTimeout(function () {
        if (wasOpen === false) {
            selectbox1.classList.add("open");
        }
    }, 1);
});

for (var i = 0; i < selectbox1Options.length; i++) {
    selectbox1Options[i].addEventListener("click", function(e) {
        if ( e.target.classList.contains("disabled") == false ) {
            const focus = document.querySelector("#avrCustomSelect1 .list .focus")
            focus.classList.remove("focus");

            const selected = document.querySelector("#avrCustomSelect1 .list .selected")
            selected.classList.remove("selected");

            e.target.classList.add("focus");
            e.target.classList.add("selected");

            const content = e.target.innerHTML;
            selectbox1Span.innerHTML = content;
            secretinput.value = content;
        }
    });
}

selectbox1.addEventListener("keydown", function(e) {
    // console.log(e.keyCode);

    if (27 == e.keyCode) {// escape
        selectbox1.classList.remove("open");
    }

    else if (13 == e.keyCode) { //enter
        if ( selectbox1.classList.contains("open") ) {
            selectbox1.classList.remove("open");

            const focus = document.querySelector("#avrCustomSelect1 .list .focus")
            const selected = document.querySelector("#avrCustomSelect1 .list .selected")

            selected.classList.remove("selected");
            focus.classList.add("selected");

            const content = focus.innerHTML;
            selectbox1Span.innerHTML = content;
            secretinput.value = content;

        } else {
            selectbox1.classList.add("open");
        }
    }

    else if (38 == e.keyCode) { //arrow up
        e.preventDefault();
        if ( selectbox1.classList.contains("open") ) {
            const focus = document.querySelector("#avrCustomSelect1 .list .focus")
            const previousFocus = focus.previousElementSibling;

            if (previousFocus !== undefined && previousFocus !== null) {
                if ( previousFocus.classList.contains("disabled") == false ) {
                    previousFocus.classList.add("focus");
                    focus.classList.remove("focus");
                }
            }
        }
    }

    else if (40 == e.keyCode) {// arrow down
        e.preventDefault();
        if ( selectbox1.classList.contains("open") ) {
            const focus = document.querySelector("#avrCustomSelect1 .list .focus")
            const nextFocus = focus.nextElementSibling;

            if (nextFocus !== undefined && nextFocus !== null) {
                if ( nextFocus.classList.contains("disabled") == false ) {
                    nextFocus.classList.add("focus");
                    focus.classList.remove("focus");
                }
            }

        } else {
            selectbox1.classList.add("open");
        }
    }

    else if (9 == e.keyCode) { // tab
        if ( selectbox1.classList.contains("open") ) {
            e.preventDefault();
        }
    }
});

function controlDienst() {
    const dienst = document.getElementById("dienst");
    const dienstValue = document.getElementById("dienstValue");

    dienstValue.value = dienst.value;
    if (dienst.value == "Additionele diensten") {
        selectbox1.setAttribute("style", "");
        secretinput.setAttribute("style", "color:white; border-width:0px; font-size: 1px; font-weight: 100; heigth:0px width:1px;");
    } else {
        selectbox1.setAttribute("style", "display: none;");
        secretinput.setAttribute("style", "display: none;");

        secretinput.value = 0;
        selectbox1Span.innerHTML = "Selecteer uw additionele dienst";

        // remove focus
        const focus = document.querySelector("#avrCustomSelect1 .list .focus")
        const selected = document.querySelector("#avrCustomSelect1 .list .selected")
        focus.classList.remove("focus");
        selected.classList.remove("selected");

        // add focus
        selectbox1Ul.children[0].classList.add("focus");
        selectbox1Ul.children[0].classList.add("selected");
    }
}
