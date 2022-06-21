document.getElementById("checkActionButtons").hidden = true;
let activeContent;

let file =  document.getElementsByClassName('fichierContainer');

let ListenersClicDroit = () => {
    for(let i = 0; i < file.length; i++){
        let id = file[i].children[0].children[0].children[0].id;
        file[i].oncontextmenu = () => clicDroit(id);
    }
}

let clicDroit = (id) => {
    if(document.getElementById('FileDataRequest')) document.getElementById('FileDataRequest').remove();
    let e = window.event;
    e.preventDefault();
    //e.stopPropagation();
    let x = e.pageX;
    let y = e.pageY;
    $.post( "actions/right_click-action.php", { id: id }, function( data ) {
        document.getElementById('filesDisplayContainer').innerHTML += data;
        let div = document.getElementById('FileDataRequest');
        div.style.position = 'absolute';
        div.style.left = x+"px";
        div.style.top = y+"px";
        clicsManager(1);
    }, "html");
}

let ListenersClicGauche = () => {
    for(let i = 0; i < file.length; i++){
        let name = file[i].children[0].children[0].children[0].name;
        file[i].onclick = () => clicGauche(name);
    }
}

let clicGauche = (name) => {
    let e = window.event;
    let x = e.pageX+1;
    let y = e.pageY+1;
    //e.preventDefault();
    //e.stopPropagation();
    if(e.target.className !== 'customCheckBox'){
        if(document.getElementById('FileDataRequest')) document.getElementById('FileDataRequest').remove();
        let dataRequest = document.createElement('div');
        dataRequest.setAttribute('id', 'FileDataRequest');
        let downloadFile = "<div id='Telecharger'><p onclick='downloadFiles(`"+name+"`)'>Télécharger</p></div>";
        let deleteFile = "<div id='Supprimer'><p onclick='deleteFiles(`"+name+"`)'>Supprimer</p></div>";
        let modifTags = "<div id='modifTags'><p onclick='tagSelection()'>Modifier les tags</p></div>";
        dataRequest.style.left = x+"px";
        dataRequest.style.top = y+"px";
        dataRequest.style.position = "absolute";
        dataRequest.className = "clickMenu";
        dataRequest.innerHTML=downloadFile+deleteFile+modifTags;
        let div = document.getElementById('filesDisplayContainer');
        div.append(dataRequest);
        clicsManager(1);
    }
}

let clicsManager = (param) => {

    if(param == 0){
        setListener();
        ListenersClicDroit();
        ListenersClicGauche();
    }
    else if(param == 1){
        setListener();
        ListenersClicDroit();
        ListenersClicGauche();
    }
    else {

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

let tagSelection = () => {                                                  // Popup affichant les différents tags pour modifier les tags des fichiers selectionnés
    let window = " <form class=\"tagsFichiers\" method=\"post\" action=\"actions/contentTags-action.php\"> <div id='confirmPopUp'>" +
    " <p>Vous modifiez les tags de "+activeContent.length+ " élément(s)</p>" +
        "<div class=\"autreTagsContainer\">";
    let counter = 0;
    let allFiles="";
    activeContent.forEach(file => allFiles+= file +",");
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

ListenersClicDroit();
ListenersClicGauche();
