<?php
    // on inclus le module google installé avec composer
    require_once 'vendor/autoload.php';

    // On créé l'objet google client
    $google_client = new Google_Client();

    //On set l'id du client
    $google_client->setClientId('174533306896-l787cqvejjpihaee0uiagv9aqd39duao.apps.googleusercontent.com');

    //On set le secret du client
    $google_client->setClientSecret('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');

    //On set l'url de redirection
    $google_client->setRedirectUri('https://testtest12346.000webhostapp.com/home.php');


    $google_client->addScope('email');
    $google_client->addScope('profile');
