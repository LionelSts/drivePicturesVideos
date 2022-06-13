document.getElementById("checkActionButtons").hidden = true;
let activeContent;

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
    let window = " <form class=\"tagsFichiers\" method=\"post\" action=\"./contentTags-action.php\"> <div id='confirmPopUp'>" +
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
                "               <span class='tagCheckbox redCheckbox'></span>\n" +
                "           </label>\n" +
                "     </div>";
        }else{
            window += "<div class='tag-choices-1'>" +
                "          <label class='redCheckboxContainer'>" + listTag[i] +
                "               <input type='checkbox' id='"+ listTag[i] +"' name='listeTag[]' value ='"+ listTag[i] +"' \>" +
                "               <span class='tagCheckbox redCheckbox'></span>\n" +
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

let downloadFiles = () => {                                         // On fait la requête post pour télécharger les fichiers
    let newForm = "<form method=\"post\" action=\"download-action.php\" hidden>";
    document.getElementById("downloadZone").innerHTML = "";
    newForm += "<input type=\"text\" name=\"fichiers\" value=\""+activeContent+"\">";
    newForm +=" <input type=\"submit\" id=\"download\">" +
        "</form>"

    document.getElementById('downloadZone').innerHTML += newForm;
    document.getElementById('download').click();
}

let deleteFiles = () => {                                           // On fait la requête post pour supprimer les fichiers
    let newForm = "<form method=\"post\" action=\"delete-action.php\" hidden>";
    document.getElementById("downloadZone").innerHTML = "";
    newForm += "<input type=\"text\" name=\"fichiers\" value=\""+activeContent+"\">";
    newForm +=" <input type=\"submit\" id=\"download\">" +
        "</form>"
    document.getElementById('downloadZone').innerHTML += newForm;
    document.getElementById('download').click();
}

