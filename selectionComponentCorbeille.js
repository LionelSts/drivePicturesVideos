// Clic pour la corbeille, mêm fonctionnement que dans le module précédent mais avec une actino différe te
document.getElementById("checkActionButtons").hidden = true;
let activeContent;

let file =  document.getElementsByClassName('fichierContainer');

let clicDroit = (id) => {                                                                                               // Fonction lors d'un clic droit
    if(document.getElementById('FileDataRequest')) document.getElementById('FileDataRequest').remove();// Si il y avait déjà clic on l'enlève
    let e = window.event;
    e.preventDefault();
    let x = e.pageX+10;                                                                                                  // On place la div à l'endroit de la souris (+1 pour que le double clic reste possible)
    let y = e.pageY+10;
    $.post( "actions/right_click_corbeille-action.php", { id: id }, function( data ) {                                            // On fait l'appel au fichier action qui nous donne le contenu
        document.getElementById('filesDisplayContainer').innerHTML += data;
        let div = document.getElementById('FileDataRequest');
        div.style.position = 'absolute';
        div.style.left = x+"px";
        div.style.top = y+"px";
        clicsManager();                                                                                                 // On replace tous les listener qui sont enlevé par le innerhtml+=
    }, "html");
}

let clicsManager = () => {                                                                                              // Fonction qui set tous les actions clic
    document.onclick = () => bodyClick();
    document.oncontextmenu = () => bodyClick();
    ListenersClicDroit();
    ListenersClicGauche();
}

let clicCheckBox = () => {                                                                                              // On empèche la propagation de nos events sur la checkbox
    let e = window.event;
    e.stopPropagation();
    if(document.getElementById("FileDataRequest")) document.getElementById("FileDataRequest").remove();// Si on clic sur la checkbox, on enlève les autres menus
}

let bodyClick = () => {                                                                                                 // Fonction quand on clic autre part
    let e= window.event;
    if(e.target.className !== 'customCheckBox' && e.target.className !== 'miniatureFichier'){                           // Si c'est différent de la miniature et de la checkbox
        if(document.getElementById("FileDataRequest")) document.getElementById("FileDataRequest").remove();// si il y a déjà eu un clic on enlève l'affichage
    }
}

let ListenersClicDroit = () => {
    for(let i = 0; i < file.length; i++){
        let id = file[i].children[0].children[0].children[0].id;
        file[i].oncontextmenu = () => clicDroit(id);
    }
}

let ListenersClicGauche = () => {                                                                                       // Fonction qui set les onclick du clic gauche
    for(let i = 0; i < file.length; i++){
        let name = file[i].children[0].children[0].children[0].name;
        file[i].onclick = () => clicGauche(name);
    }
}

let clicGauche = (name) => {                                                                                            // Fonction lorsqu'un clic gauche a lieu
    let e = window.event;
    if(e.target.className !== 'customCheckBox'){
        if(document.getElementById('FileDataRequest')) document.getElementById('FileDataRequest').remove();// Si il y avait déjà clic on l'enlève
    }
}

function FileConvertSize(aSize){                                // Fonction servant à afficher la taille (de façon lisible) d'un fichier (argumetn en octet)
    aSize = Math.abs(parseInt(aSize, 10));
    let def = [[1, 'octets'], [1024, 'ko'], [1024*1024, 'Mo'], [1024*1024*1024, 'Go'], [1024*1024*1024*1024, 'To']];
    for(let i=0; i<def.length; i++){
        if(aSize<def[i][0]) return (aSize/def[i-1][0]).toFixed(2)+' '+def[i-1][1];
    }
}

let buttonsAction = () => {                                                     // Lorsque l'on coche quelque chose on affiche les options et mets à jour la taille total des fichiers selectionnés
    activeContent = [];
    const e =  document.getElementsByTagName('input');
    let totalFilesSize = 0;
    for(let i = 0; i < e.length; i++) {
        if(e[i].checked === true){
            activeContent.push(e[i].getAttribute("name"));
            totalFilesSize += parseInt(e[i].getAttribute("value"));
        }
    }
    document.getElementById("checkActionButtons").hidden = activeContent.length <= 0;
    if(totalFilesSize !== 0){
        document.getElementById('filesSize').innerHTML = 'Fichiers selectionnés : ' + activeContent.length + ' ( ' + FileConvertSize(totalFilesSize) + ')';
    }
}

let confirmDelete = () => {                                                 // Popup qui demande la confirmation de la suppression
    const confirmPopUp = "<div id='confirmPopUp'>" +
        " <p>Voulez vous vraiment supprimer ces "+activeContent.length+ " éléments ?</p>" +
        "<div class='confirmButtons'><div onclick='deleteFiles()' class='confirmButton'>Oui</div><div onclick='annulDelete()' class='confirmButton'>Non</div></div>"+
        "</div>";
    document.getElementById("pageContent").innerHTML += confirmPopUp;
}

let annulDelete = () => {                                           // On ferme la popup de suppression
    document.getElementById("confirmPopUp").remove();
}

let restoreFile = () => {                                         // On fait la requête post pour restaurer les fichiers
    let newForm = "<form method=\"post\" action=\"./actions/restore-action.php\" hidden>";
    document.getElementById("downloadZone").innerHTML = "";
    newForm += "<input type=\"text\" name=\"fichiers\" value=\""+activeContent+"\">";
    newForm +=" <input type=\"submit\" name=\"page\" id=\"download\" value='"+page+"'>" +
        "</form>"
    document.getElementById('downloadZone').innerHTML += newForm;
    document.getElementById('download').click();
}

let deleteFiles = () => {                                           // On fait la requête post pour supprimer les fichiers
    let newForm = "<form method=\"post\" action=\"./actions/delete-permanently-action.php\" hidden>";
    document.getElementById("downloadZone").innerHTML = "";
    newForm += "<input type=\"text\" name=\"fichiers\" value=\""+activeContent+"\">";
    newForm +=" <input type=\"submit\" name=\"page\" id=\"download\" value='"+page+"'>" +
        "</form>"
    document.getElementById('downloadZone').innerHTML += newForm;
    document.getElementById('download').click();
}

ListenersClicDroit();
