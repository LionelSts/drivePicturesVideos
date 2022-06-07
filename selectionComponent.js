document.getElementById("checkActionButtons").hidden = true;
let activeContent;

function FileConvertSize(aSize){
    aSize = Math.abs(parseInt(aSize, 10));
    let def = [[1, 'octets'], [1024, 'ko'], [1024*1024, 'Mo'], [1024*1024*1024, 'Go'], [1024*1024*1024*1024, 'To']];
    for(let i=0; i<def.length; i++){
        if(aSize<def[i][0]) return (aSize/def[i-1][0]).toFixed(2)+' '+def[i-1][1];
    }
}

let buttonsAction = () => {
    activeContent = [];
    const e =  document.getElementsByTagName('input');
    let totalFilesSize = 0;
    for(let i = 0; i < e.length; i++) {
        if(e[i].checked == true){
            activeContent.push(e[i].getAttribute("name"));
            totalFilesSize += parseInt(e[i].getAttribute("value"));
        }
    }
    if(activeContent.length > 0) {
        document.getElementById("checkActionButtons").hidden = false;
    } else {
        document.getElementById("checkActionButtons").hidden = true;
    }
    if(totalFilesSize != 0){
        document.getElementById('filesSize').innerHTML = 'Fichiers selectionnés : ' + activeContent.length + ' ( ' + FileConvertSize(totalFilesSize) + ')';
    }
}

let downloadFiles = () => {
    document.getElementById("downloadZone").innerHTML = "";
    for(let i = 0; i < activeContent.length; i++) {
        document.getElementById("downloadZone").innerHTML += "<a href=\"fichiers/" + activeContent[i] + "\" download class=\"download\" hidden></a>"
    }
    let elements = document.querySelectorAll('.download');
    for(i = 0; i < elements.length; i++){
        elements[i].click();
    }
}

