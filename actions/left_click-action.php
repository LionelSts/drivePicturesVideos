<?php

session_start();
$role = $_SESSION['role'];
$name = $_POST['name'];
$downloadFile = "<div id='Telecharger'><p onclick='downloadFiles(`".$name."`)'>Télécharger</p></div>";
if($role == 'ecriture' || $role == 'admin'){
    $deleteFile = "<div id='Supprimer'><p onclick='deleteFiles(`".$name."`)'>Supprimer</p></div>";
    $modifTags = "<div id='modifTags'><p onclick='tagSelection(`".$name."`)'>Modifier les tags</p></div>";    
}
else{
    $deleteFile="";
    $modifTags="";
}

echo("
    <div id='FileDataRequest' class='clickMenu'>"
    .$downloadFile
    .$deleteFile
    .$modifTags.
    "</div>"
);