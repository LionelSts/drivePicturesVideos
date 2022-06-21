<?php
ob_start();
$files = explode(",",$_POST["fichiers"]);
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_stockage`, `extension` FROM `fichiers` WHERE `id` IN (";
foreach ($files as $file){
    $file = substr($file,0,strpos($file, '.'));
    $requete .= $file.",";
}
$requete = substr($requete, 0, -1).")";
$result = mysqli_query($link, $requete);
$data = mysqli_fetch_all($result);
$zipname = '../temporary/driveLBR.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($data as $file) {
    $zip->addFile("../fichiers/".$file[0].".".$file[1], $file[0].".".$file[1]);
}
$zip->close();

if (headers_sent()) {
    echo 'HTTP header already sent';
} else {
    if (!is_file($zipname)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo 'File not found';
    } else if (!is_readable($zipname)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        echo 'File not readable';
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        header('Content-Type: application/zip');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: ' . filesize($zipname));
        header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($zipname);
        ignore_user_abort(true);
        unlink($zipname);
        exit();
    }
}
