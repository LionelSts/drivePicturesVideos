let files =  document.getElementsByClassName('fichierContainer');
let fileGeneration;
let playFile = (file, fileName) => {
    fileGeneration ='';
    $.post( "filesPreviewBegin-action.php", { file: file, fileName: fileName }, function( data ) {
        document.getElementById('filesDisplayContainer').innerHTML += data;
        fileGeneration = document.getElementById("filePreviewContainerDiv").children[1].id;
        window.addEventListener('beforeunload', () => closeFile(fileGeneration));
    }, "html");

}

let closeFile = () => {
    console.log(fileGeneration);
    $.post( "filesPreviewEnd-action.php", { file: fileGeneration }, function( data ) {

    }, "html");
    document.getElementById('filePreviewContainerDiv').remove();
}

for(let i = 0; i < files.length; i++){
    let fileInfo = files[i].children[0].children[0].children[0];
    let fileName = files[i].children[0].children[2].outerText;
    files[i].addEventListener('dblclick', () => playFile(fileInfo.name, fileName));
}
