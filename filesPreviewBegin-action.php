<?php
$fileExtension = explode('.',$_POST['file']);
$fileExtension = $fileExtension[count($fileExtension)-1];
try {
    $bytes = random_bytes(15);
} catch (Exception $e) {
}
$randWord = bin2hex($bytes);
$filePath = './fichiers/'.$_POST['file'];
$newFile = './temporary/'.$randWord.'.'.$fileExtension;
copy($filePath, $newFile);
$htmlCode = "<div onclick='closeFile()'>X</div>";
if(strstr(mime_content_type($filePath), "image/")){
    $htmlCode .= '<img id="preview" class="'.$newFile.'" alt="preview du fichier" src="' . $newFile . '">';
}else if(strstr(mime_content_type($filePath), "video/")){
    $htmlCode .= '<video id="preview" class="'.$newFile.'">
                    <source src="'.$newFile.'" type="'.mime_content_type($filePath).'">
                 </video>';
}else{
    $htmlCode="";
}
echo $htmlCode;
