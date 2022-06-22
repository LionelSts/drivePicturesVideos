
// Ajout des valeurs dans le form de modification des comptes
function formReload(index){
    let mail = document.getElementById('account').value;
    document.getElementById('mailCompte').value = index.get(mail)[2];
    document.getElementById('nom').value = index.get(mail)[0];
    document.getElementById('prenom').value = index.get(mail)[1];
    document.getElementById('modifRole').value = index.get(mail)[3];
    document.getElementById('descriptif2').value = index.get(mail)[4];
}

// affichage du choux des tags ou non
let code = document.getElementById("tags1").innerHTML;
function check(index) {
    if (index === 1) {                                              // tags à la création
        if (document.getElementById("modifRoleCrea").value === "invite") {
            document.getElementById("tags2").innerHTML = code;
        } else {
            document.getElementById("tags2").innerHTML = "";
        }
    } else {                                                        // tags à la modification
        if (document.getElementById("modifRole").value === "invite") {
            document.getElementById("tags1").innerHTML = code;
        } else {
            document.getElementById("tags1").innerHTML = "";
        }
    }
    const mail = document.getElementById("account").value;
    const tabTrie = filtre(mail);
    tabTrie.forEach( element => {                                   // Coche les tags déjà attribués
        document.getElementById(element[1]).checked = true;
    });
}

