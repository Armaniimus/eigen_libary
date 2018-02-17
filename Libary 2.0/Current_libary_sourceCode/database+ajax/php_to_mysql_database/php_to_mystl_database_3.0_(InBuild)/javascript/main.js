// document.getElementById('myForm').addEventListener("submit", SubmitFunction);

function SubmitCallback(result) {
    document.getElementById('myTable').innerHTML = result;
    SetEventListeners();
}

function SubmitFunction(sendObj) {

    let payload = {
        dbcrud: "",
        id: "",
        naam: "",
        leeftijd: 0
    }

    if (sendObj.crudFunc == "create") {
        payload.dbcrud = sendObj.crudFunc,
        payload.naam = sendObj.naam,
        payload.leeftijd = sendObj.leeftijd
    }

    else if (sendObj.crudFunc == "read" || sendObj.crudFunc == "delete") {
        payload.dbcrud = sendObj.crudFunc,
        payload['id'] = sendObj['id']
    }

    else if (sendObj.crudFunc == "update") {
        payload.dbcrud = sendObj.crudFunc,
        payload['id'] = sendObj['id'],
        payload.naam = sendObj.naam,
        payload.leeftijd = sendObj.leeftijd
    }

    console.log(payload);
    ajax_module.post('php_includes/main.php', {}, SubmitCallback, payload, 0);
}

const Onload = (function() {
    let payload = { dbcrud: read}
    ajax_module.post('php_includes/main.php', {}, SubmitCallback, payload, 0);
})();

function HandleCreateToggle() {
    if (document.getElementById('createViewOuter') ) {
        HandleCreate2()
    } else {
        HandleCreate1()
    }
}
function HandleCreate2() {
    let createViewOuter = document.getElementById('createViewOuter')
    let createView = createViewOuter.querySelector('.createView');

    const sendObj = {
        dbcrud: "create",
        naam: createView.querySelector("input[name='naam']").value,
        leeftijd: createView.querySelector("input[name='leeftijd']").value
    }

    createViewOuter.remove();
    ajax_module.post('php_includes/main.php', {}, SubmitCallback, sendObj, 0);
};

function HandleCreate1() {
    let createViewOuter = document.createElement('div')
    createViewOuter.classList.add('createViewOuter')
    createViewOuter.id = 'createViewOuter';

    let createView = document.createElement('div')
    createView.classList.add('createView')

    let labels = [];
    let inputs = [];

    for (let i = 0; i < 2; i++) {
        let name = ""
        if (i == 0) {
            name = 'naam';
        }
        else {
            name = 'leeftijd';
        }

        labels[i] = document.createElement('label')
        labels[i].classList.add("createViewInner")
        labels[i].classList.add("createViewInnerLabel")
        labels[i].setAttribute("for", name + "Create")
        labels[i].innerHTML = name;

        inputs[i] = document.createElement('input')
        inputs[i].classList.add("createViewInner")
        inputs[i].setAttribute("name", name)
        inputs[i].id = name + 'Create'

        createView.append(labels[i])
        createView.append(inputs[i])
    }
    let button = document.createElement('button')
    button.innerHTML = "create";
    button.classList.add("createViewInner")
    button.addEventListener('click', HandleCreateToggle);

    createView.append(button)
    createViewOuter.append(createView)
    document.body.append(createViewOuter)
};
