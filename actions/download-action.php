<?php
ob_start();
$files = explode(",",$_POST["fichiers"]);
try {
    $bytes = random_bytes(5);
} catch (Exception $e) {
}
$randWord = bin2hex($bytes);
$zip = new ZipArchive;
$fileName = $randWord.'.zip';
$path = '../temporary/'.$fileName;
if ($zip->open($_SERVER['DOCUMENT_ROOT']."/driveBriquesRouges/temporary/".$fileName, ZipArchive::CREATE) === TRUE)
{
    foreach ($files as $file){
        $filePath = "../fichiers/".$file;
        $zip->addFile($filePath, $file);
    }
    // All files are added, so close the zip file.
    $zip->close();
}else{
    exit("Impossible d'ouvrir le fichier <$randWord>\n");
}

$fileToSend = '../temporary/'.$fileName;

if (headers_sent()) {
    echo 'HTTP header already sent';
} else {
    if (!is_file($fileToSend)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo 'File not found';
    } else if (!is_readable($fileToSend)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        echo 'File not readable';
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        header('Content-Type: application/zip');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: ' . filesize($fileToSend));
        header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"");
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($_SERVER['DOCUMENT_ROOT']."/driveBriquesRouges/temporary/".$fileName);
        ignore_user_abort(true);
        unlink($fileToSend);
        exit();
    }
}
