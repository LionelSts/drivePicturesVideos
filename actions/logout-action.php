<?php
session_start();    // démarage de la session
include('../gconfig.php');
if(isset($_SESSION['type'])){
    //Reset OAuth access token
    $google_client->revokeToken();
}
session_destroy();  // destruction de la session
header('Location:../index.php');   // redirection vers le login
