<?php
    //Include Google Client Library for PHP autoload file
    require_once 'vendor/autoload.php';

    //Make object of Google API Client for call Google API
    $google_client = new Google_Client();

    //Set the OAuth 2.0 Client ID
    $google_client->setClientId('174533306896-l787cqvejjpihaee0uiagv9aqd39duao.apps.googleusercontent.com');

    //Set the OAuth 2.0 Client Secret key
    $google_client->setClientSecret('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');

    //Set the OAuth 2.0 Redirect URI
    $google_client->setRedirectUri('https://testtest12346.000webhostapp.com/home.php');

    //
    $google_client->addScope('email');

    $google_client->addScope('profile');
