let files =  document.getElementsByClassName('fichierContainer');

let playFile = (file) => {
    $.post( "filesPreviewBegin-action.php", { file: file }, function( data ) {
        document.getElementById('filesDisplayContainer').innerHTML += data;
    }, "html");
}

let closeFile = () => {
    let fichierTemporaire = document.getElementById('preview');

    console.log(document.getElementById('preview').className);
    $.post( "filesPreviewEnd-action.php", { file: fichierTemporaire }, function( data ) {

    }, "html");
    fichierTemporaire.remove();
}

for(let i = 0; i < files.length; i++){
    let fileInfos = files[i].children[0].children[0].children[0];
    files[i].addEventListener('dblclick', () => playFile(fileInfos.name));
}

window.addEventListener('beforeunload', () => closeFile());
