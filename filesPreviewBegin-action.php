<?php
$fileExtension = explode('.',$_POST['file']);
$fileExtension = $fileExtension[count($fileExtension)-1];
try {
    $bytes = random_bytes(15);
} catch (Exception $e) {
}
$randWord = bin2hex($bytes);
$filePath = './fichiers/'.$_POST['file'];
$fileName = $_POST['fileName'].'.'.$fileExtension;
$newFile = './temporary/'.$randWord.'.'.$fileExtension;
copy($filePath, $newFile);
$generateFileName = "'".$randWord.'.'.$fileExtension."'";
$htmlCode = '<div id="filePreviewContainerDiv" class="filePreviewContainer" ><div id="previewHeader"><h1>'.$fileName.'</h1>
<h1 onclick="closeFile()">X</h1></div>';
if(strstr(mime_content_type($filePath), "image/")){
    $htmlCode .= '<img id="'.$generateFileName.'" alt="preview du fichier" src="' . $newFile . '"></div>';
}else if(strstr(mime_content_type($filePath), "video/")){
    $htmlCode .= '<video id="'.$generateFileName.'" >
                    <source src="'.$newFile.'" type="'.mime_content_type($filePath).'">
                 </video></div>';
}else{
    $htmlCode="";
}
echo $htmlCode;
