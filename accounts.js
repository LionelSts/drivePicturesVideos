function formReload(index){
    let mail = document.getElementById('account').value;
    document.getElementById('mail').value = index.get(mail)[2];
    document.getElementById('nom').value = index.get(mail)[0];
    document.getElementById('prenom').value = index.get(mail)[1];
}