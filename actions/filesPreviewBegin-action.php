<?php
$fileExtension = explode('.',$_POST['file']);
$fileId = $fileExtension[0];
$fileExtension = $fileExtension[count($fileExtension)-1];
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_stockage`, `extension` FROM `fichiers` WHERE `id` = $fileId";
$result = mysqli_query($link, $requete);
$data = mysqli_fetch_array($result);
$fileCodedName = './fichiers/'.$data[0] .".". $fileExtension;
$filePath = '.'.$fileCodedName;
$fileName = $_POST['fileName'].'.'.$fileExtension;
$htmlCode = '<div id="filePreviewContainerDiv" class="filePreviewContainer" ><div id="previewHeader"><h1>'.$_POST['fileName'].'</h1>
<h1 style="cursor: pointer" onclick="clicsManager(1)">X</h1></div>';
if(str_contains(mime_content_type($filePath), "image/")){
    $htmlCode .= '<img alt="preview du fichier" src="' . $fileCodedName .'"></div>';
}else if(strstr(mime_content_type($filePath), "video/") || strstr(mime_content_type($filePath), "audio/") ){
    $htmlCode .= '<video  controls autoplay>
                    <source src="'. $fileCodedName .'" type="'.mime_content_type($filePath).'">
                 </video></div>';
}else{
    $htmlCode.='</div>';
}
echo $htmlCode;
