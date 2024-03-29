<?php
$fileExtension = explode('.',$_POST['file']);
$fileId = $fileExtension[0];                            //On récupère l'ID du fichier
$fileExtension = $fileExtension[count($fileExtension)-1];//On récupère l'extension
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_stockage`, `extension` FROM `fichiers` WHERE `id` = ?";   // On récupère le nom de stockage
$stmt = $link->prepare($requete);
$stmt->bind_param("i", $fileId);
$stmt->execute();
$result = $stmt->get_result();
$data = mysqli_fetch_array($result);
$fileCodedName = './fichiers/'.$data[0] .".". $fileExtension;                           // chemin d'accès du fichier
$filePath = '.'.$fileCodedName;
$fileName = $_POST['fileName'].'.'.$fileExtension;
$htmlCode = '<div id="filePreviewContainerDiv" class="filePreviewContainer" ><div id="previewHeader"><h1>'.htmlspecialchars($_POST['fileName'], ENT_QUOTES, 'UTF-8').'</h1>
<h1 style="cursor: pointer" onclick="clicsManager()">X</h1></div>';                                                     // On commence pas déclarer le container html
if(str_contains(mime_content_type($filePath), "image/")){
    $htmlCode .= '<img alt="preview du fichier" src="' . $fileCodedName .'"></div>';                                    // Si c'est une image on le met dans une balise img
}else if(strstr(mime_content_type($filePath), "video/") || strstr(mime_content_type($filePath), "audio/") ){
    $htmlCode .= '<video  controls autoplay>
                    <source src="'. $fileCodedName .'" type="'.mime_content_type($filePath).'">
                 </video></div>';                                                                                       // Si c'est une vidéo on le met dans une balise video
}else{
    $htmlCode.='</div>';
}
echo $htmlCode;
