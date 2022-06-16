<?php
session_start();    // démarage de la session
session_destroy();  // destruction de la session
header('Location:../index.php');   // redirection vers le login
