// On récupère les fichiers
let files =  document.getElementsByClassName('fichierContainer');
let fileGeneration;
                                                                                                                        // Fonction qui fait la requête en jquery au fichier action.php pour récupérer le lecteur
let playFile = (file, fileName) => {
    fileGeneration ='';
    if(document.getElementById('FileDataRequest')) document.getElementById('FileDataRequest').remove();// Si l'on veut jouer un fichier, on enlève les menus déjà ouverts
    $.post( "./actions/filesPreviewBegin-action.php", { file: file, fileName: fileName }, function( data ) {
        document.getElementById('filesDisplayContainer').innerHTML += data;
        fileGeneration = document.getElementById("filePreviewContainerDiv").children[1].id;
    }, "html");
}

// Fonction qui set tous les events onclick pour le doubleclick
let setListener = () => {
    if(document.getElementById('filePreviewContainerDiv')) document.getElementById('filePreviewContainerDiv').remove();
    for(let i = 0; i < files.length; i++){
        let fileInfo = files[i].children[0].children[0].children[0];
        let fileName = files[i].children[0].children[2].outerText;
        files[i].ondblclick = () => playFile(fileInfo.name, fileName);
    }
}

setListener();
