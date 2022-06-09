<?php
session_start();

$files = explode(",",$_POST["fichiers"]);


header('Location:index.php');
