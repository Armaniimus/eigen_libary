function SetEventListeners() {
    let nr = 0;
    while (true) {
        nr ++;
        let currentID = "row" + nr;
        let row = document.getElementById(currentID);
        if (row) {
            row.addEventListener('click', function(e, er) {
                if (e.target.tagName == "BUTTON") {

                    const sendObj = {
                        crudFunc: "",
                        id: row.querySelector("td input[name='id']").value,
                        naam: row.querySelector("td input[name='naam']").value,
                        leeftijd: row.querySelector("td input[name='leeftijd']").value
                    }

                    if (e.target.className == "upd-button") {
                        sendObj.crudFunc = "update";
                        SubmitFunction(sendObj);
                    }
                    else if (e.target.className == "del-button") {
                        sendObj.crudFunc = "delete";
                        SubmitFunction(sendObj);
                    }
                }
            }, 1);
        } else {
            break;
        }
    }
}

document.getElementById('read').addEventListener('click', function(){
    let payload = {
        dbcrud: "read",
        id: document.getElementById('id').value
    }
    ajax_module.post('php_includes/main.php', {}, SubmitCallback, payload, 0);
});

document.getElementById('readAll').addEventListener('click', function(){
    let payload = { dbcrud: read}
    ajax_module.post('php_includes/main.php', {}, SubmitCallback, payload, 0);
});


document.getElementById('create1').addEventListener('click', HandleCreateToggle);
