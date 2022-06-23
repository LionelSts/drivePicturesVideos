document.getElementById("checkActionButtons").hidden = true;                                                   // On cache les bouttons actions au chargement
let activeContent;

let file =  document.getElementsByClassName('fichierContainer');                                              // On récupère tous le container de fichier (tous l'affichage

let ListenersClicDroit = () => {
    for(let i = 0; i < file.length; i++){
        let id = file[i].children[0].children[0].children[0].id;
        file[i].oncontextmenu = () => clicDroit(id);
    }
}

let clicDroit = (id) => {                                                                                               // Fonction lors d'un clic droit
    if(document.getElementById('FileDataRequest')) document.getElementById('FileDataRequest').remove();// Si il y avait déjà clic on l'enlève
    let e = window.event;
    e.preventDefault();
    let x = e.pageX+10;                                                                                                  // On place la div à l'endroit de la souris (+1 pour que le double clic reste possible)
    let y = e.pageY+10;
    $.post( "actions/right_click-action.php", { id: id }, function( data ) {                                            // On fait l'appel au fichier action qui nous donne le contenu
        document.getElementById('filesDisplayContainer').innerHTML += data;
        let div = document.getElementById('FileDataRequest');
        div.style.position = 'absolute';
        div.style.left = x+"px";
        div.style.top = y+"px";
        clicsManager();                                                                                                 // On replace tous les listener qui sont enlevé par le innerhtml+=
    }, "html");
}

let ListenersClicGauche = () => {                                                                                       // Fonction qui set les onclick du clic gauche
    for(let i = 0; i < file.length; i++){
        let name = file[i].children[0].children[0].children[0].name;
        file[i].onclick = () => clicGauche(name);
    }
}

let clicGauche = (name) => {                                                                                            // Fonction lorsqu'un clic gauche a lieu
    let e = window.event;
    let x = e.pageX+10;
    let y = e.pageY+10;                                                                                                  // On place la div à l'endroit de la souris (+1 pour que le double clic reste possible)
    if(e.target.className !== 'customCheckBox'){
        if(document.getElementById('FileDataRequest')) document.getElementById('FileDataRequest').remove();// Si il y avait déjà clic on l'enlève
        $.post( "actions/left_click-action.php", { name: name }, function( data ) {                                     // On fait l'appel au fichier action qui nous donne le contenu
            document.getElementById('filesDisplayContainer').innerHTML += data;
            let div = document.getElementById('FileDataRequest');
            div.style.position = 'absolute';
            div.style.left = x+"px";
            div.style.top = y+"px";
            clicsManager();                                                                                             // On replace tous les listener qui sont enlevé par le innerhtml+=
        }, "html");
    }
}

let clicCheckBox = () => {                                                                                              // On empèche la propagation de nos events sur la checkbox
    let e = window.event;
    e.stopPropagation();
    if(document.getElementById("FileDataRequest")) document.getElementById("FileDataRequest").remove();// Si on clic sur la checkbox, on enlève les autres menus
}

let clicsManager = () => {                                                                                              // Fonction qui set tous les actions clic
    document.onclick = () => bodyClick();
    document.oncontextmenu = () => bodyClick();
    setListener();
    ListenersClicDroit();
    ListenersClicGauche();
}

let bodyClick = () => {                                                                                                 // Fonction quand on clic autre part
    let e= window.event;
    if(e.target.className !== 'customCheckBox' && e.target.className !== 'miniatureFichier'){                           // Si c'est différent de la miniature et de la checkbox
        if(document.getElementById("FileDataRequest")) document.getElementById("FileDataRequest").remove();// si il y a déjà eu un clic on enlève l'affichage
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
    for(let i = 0; i < e.length; i++) {                                                                                 // Lorsque l'on coche (ou décoche) on met à jour la variable des éléments cochés
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

let tagSelection = (name = activeContent) => {    // Popup affichant les différents tags pour modifier les tags des fichiers selectionnés
    if(typeof name == 'string') name=name.split();
    let nbTags = " <p>Vous modifiez les tags de "+name.length+ " éléments</p>";
    if(name.length===1) nbTags = " <p>Vous modifiez les tags de 1 élément</p>";
    let window = " <form class=\"tagsFichiers\" method=\"post\" action=\"actions/contentTags-action.php\"> <div id='confirmPopUp'>" +
        nbTags + "<div class=\"autreTagsContainer\">";
    let counter = 0;
    let allFiles="";
    name.forEach(file => allFiles+= file +",");
    for (let i =0; i < listTag.length; i++) {
        if(counter%2){
            window += "<div class='tag-choices'>" +
                "          <label class='redCheckboxContainer'>" + listTag[i] +
                "               <input type='checkbox' id='"+ listTag[i] +"' name='listeTag[]' value ='"+ listTag[i] +"' \>" +
                "               <span class='tagChoiceCheckbox tagCheckbox redCheckbox'></span>\n" +
                "           </label>\n" +
                "     </div>";
        }else{
            window += "<div class='tag-choices-1'>" +
                "          <label class='redCheckboxContainer'>" + listTag[i] +
                "               <input type='checkbox' id='"+ listTag[i] +"' name='listeTag[]' value ='"+ listTag[i] +"' \>" +
                "               <span class='tagChoiceCheckbox tagCheckbox redCheckbox'></span>\n" +
                "           </label>\n" +
                "     </div>";
        }
        counter++;
    }
    window += "</div>" +
        "<input type='checkbox' name=\"page\" value=\"" + page + "\" checked hidden>"+
        "<div class='confirmButtons'>" +
        "<input type=\"submit\" name=\""+allFiles+"\" value=\"Oui\">" +
        "<div onclick='annulTags()' class='confirmButton'>Non</div>" +
        "</div>"+
        "</div></form>";
    document.getElementById("pageContent").innerHTML += window;
}

let annulTags = () => {                                             // On ferme la popup des tags
    document.getElementById("confirmPopUp").remove();
}

let annulDelete = () => {                                           // On ferme la popup de suppression
    document.getElementById("confirmPopUp").remove();
}

let downloadFiles = (name = activeContent) => {                                         // On fait la requête post pour télécharger les fichiers
    let newForm = "<form method=\"post\" action=\"./actions/download-action.php\" hidden>";
    document.getElementById("downloadZone").innerHTML = "";
    newForm += "<input type=\"text\" name=\"fichiers\" value=\""+name+"\">";
    newForm +=" <input type=\"submit\" id=\"download\" >" +
        "</form>"
    document.getElementById('downloadZone').innerHTML += newForm;
    document.getElementById('download').click();
}

let deleteFiles = (name = activeContent) => {                                           // On fait la requête post pour supprimer les fichiers
    let newForm = "<form method=\"post\" action=\"./actions/delete-action.php\" hidden>";
    document.getElementById("downloadZone").innerHTML = "";
    newForm += "<input type=\"text\" name=\"fichiers\" value=\""+name+"\">";
    newForm +=" <input type=\"submit\" name=\"page\" id=\"download\" value='"+page+"'>" +
        "</form>"
    document.getElementById('downloadZone').innerHTML += newForm;
    document.getElementById('download').click();
}

ListenersClicDroit();                                                                                                   // On set tous les listener
ListenersClicGauche();
