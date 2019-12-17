document.getElementById('remove').addEventListener("click", function(){
    controlRemoveChild();
    console.log(HTMLApi);
});

document.getElementById('addChild').addEventListener("click", function() {
    controlAddChild();
    console.log(HTMLApi);
});

document.querySelector('.dom-navigation').addEventListener("click", controlDomTree, 1);
addDomIcons();
