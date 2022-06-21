document.getElementById("checkActionButtons").hidden = true;
let activeContent;

let file =  document.getElementsByClassName('fichierContainer');

function ListenersClicDroit(){
    for(let i = 0; i < file.length; i++){
        let id = file[i].children[0].children[0].children[0].id;
        file[i].addEventListener('contextmenu', function (e){
            e.preventDefault();
            $.post( "actions/right_click_corbeille-action.php", { id: id }, function( data ) {
                document.getElementById('filesDisplayContainer').innerHTML += data;
                let div = document.getElementById('FileDataRequest');
                div.style.position = 'absolute';
                let posX = e.pageX;
                let posY = e.pageY;
                div.style.left = posX+"px";
                div.style.top = posY+"px";
                document.addEventListener('click', RemoveClickListener);
            }, "html");
        });
    }
}

function RemoveContextMenu(){
    document.getElementById('FileDataRequest').remove();
}

function RemoveClickListener(){
    RemoveContextMenu();
    ListenersClicDroit();
    document.removeEventListener('click', RemoveClickListener);


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
