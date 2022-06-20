let files =  document.getElementsByClassName('fichierContainer');
let fileGeneration;
let playFile = (file, fileName) => {
    fileGeneration ='';
    $.post( "./actions/filesPreviewBegin-action.php", { file: file, fileName: fileName }, function( data ) {
        document.getElementById('filesDisplayContainer').innerHTML += data;
        fileGeneration = document.getElementById("filePreviewContainerDiv").children[1].id;
        window.addEventListener('beforeunload', handler);
        }, "html");
}

let closeFile = () => {
    $.post( "./actions/filesPreviewEnd-action.php", { file: fileGeneration });
    document.getElementById('filePreviewContainerDiv').remove();
    window.removeEventListener('beforeunload', handler);
    setListener();
}

let handler = () =>{
    closeFile(fileGeneration);
}

let setListener = () => {
    for(let i = 0; i < files.length; i++){
        let fileInfo = files[i].children[0].children[0].children[0];
        let fileName = files[i].children[0].children[2].outerText;
        files[i].ondblclick = () => playFile(fileInfo.name, fileName);
    }
}

setListener();
