<?php
session_start();
$regex = '[@]lesbriquesrouges\.fr$';
//Include Google Configuration File
include('../gconfig.php');
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ; // connexion à la bdd
$link->query('SET NAMES utf8');
//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"]))
{
    //It will Attempt to exchange a code for an valid authentication token.
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
    //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
    if(!isset($token['error']))
    {
        //Set the access token used for requests
        $google_client->setAccessToken($token['access_token']);
        //Store "access_token" value in $_SESSION variable for future use.
        $_SESSION['access_token'] = $token['access_token'];
        //Create Object of Google Service OAuth 2 class
        $google_service = new Google_Service_Oauth2($google_client);
        //Get user profile data from google
        $data = $google_service->userinfo->get();
        //Below you can find Get profile data and store into $_SESSION variable
        $mail =  $data['email'];
        $prenom = $data['given_name'];
        $nom = $data['family_name'];
        $requete = "SELECT `role`, `etat` FROM `utilisateurs` WHERE `mail` = '$mail' "; // redirection vers le login si l'utilisateur n'est pas connecté
        $result = mysqli_query($link, $requete);
        $row = mysqli_fetch_array($result);
        if(empty($row)){
            if (!preg_match($regex,$mail)){
                header("Location:../logout-action.php");
            } else {
                try {
                    $tmpPassword = bin2hex(random_bytes(24));
                } catch (Exception $e) {
                }   // génération automatique d'un mot de passe permetant "l'unicité" du lien
                $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
                $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES ('$prenom', '$nom', '$mail', '$hashedPassword', 'lecture','Membre LBR', 'actif') "; // Insertion du compte saisi, dans la bdd avec le statut "en attente"
                $requete2 = "INSERT INTO `tableau_de_bord` (`modification`) VALUES ('Compte ".$nom." ".$prenom." (Lecture) crée avec Google')";
                mysqli_query($link,$requete);
                mysqli_query($link,$requete2);
                $data = [
                    'mailType' => 'compteGoogle',
                    'mailTo' => 'admin@lesbriquesrouges.fr',
                    'nom' => $nom,
                    'prenom' => $prenom
                ];
                $curl = curl_init('http://test-mail.lesbriquesrouges.fr/mails_grp12/sendMail.php');
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_exec($curl);
                curl_close($curl);
            }
        }else if($row['role'] != 'inactif'){
            if(!empty($data['given_name'])) {
                $_SESSION['prenom'] = $prenom;
            }
            if(!empty($data['family_name'])) {
                $_SESSION['nom'] = $nom;
            }
            if(!empty($data['email'])) {
                $_SESSION['mail'] = $mail;
            }
            $_SESSION['role'] = $row['role'];
            $_SESSION['type'] = 'google';
        } else {
            header('Location:../logout-action.php');
        }
    }
}
header('Location:../home.php');
