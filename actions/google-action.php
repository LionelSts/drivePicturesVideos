<?php
session_start();
$regex = '#[@]lesbriquesrouges\.fr$#';
//Include Google Configuration File
include('../gconfig.php');
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ; // connexion à la bdd
$link->query('SET NAMES utf8');
if(isset($_GET["code"]))
{
    // On récupère le token de connexion
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
    // on vérifie qu'il n'y a aucune erreur
    if(!isset($token['error']))
    {
        $google_client->setAccessToken($token['access_token']);
        // On stock le access token pour une utilisation future
        $_SESSION['access_token'] = $token['access_token'];
        $google_service = new Google_Service_Oauth2($google_client);
        //On récupère le informations utilisateur depuis google
        $data = $google_service->userinfo->get();
        //on stock toutes ces informations
        $mail =  $data['email'];
        $prenom = $data['given_name'];
        $nom = $data['family_name'];
        $requete = "SELECT `role`, `etat` FROM `utilisateurs` WHERE `mail` = ? "; // On vérifie que l'utilisateur peut se connecter (l'etat de son compte) et on récupère son role pour initialiser sa session
        $stmt = $link->prepare($requete);
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_array($result);
        if(empty($row)){                                                                // Si le compte n'existe pas
            if (!preg_match($regex,$mail)){                                             // Si son adresse mail ne match pas celle des briques rouges on détruit sa session
                header("Location:../logout-action.php");
            } else {                                                                     // Si son adresse mail match celle des briques rouges on fait tout le necessaire pour lui créer un compte
                try {
                    $tmpPassword = bin2hex(random_bytes(24));
                } catch (Exception $e) {
                }   // génération automatique d'un mot de passe permetant "l'unicité" du lien
                $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
                $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES (?, ?, ?, ?, 'lecture','Membre LBR', 'actif') "; // Insertion du compte saisi, dans la bdd avec le statut "en attente"
                $stmt = $link->prepare($requete);
                $stmt->bind_param("ssss", $prenom, $nom,$mail,$hashedPassword);
                $stmt->execute();
                $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?, ' ', ?, ' (Lecture) crée avec Google'))";
                $stmt = $link->prepare($requete);
                $stmt->bind_param("ss", $nom, $prenom);
                $stmt->execute();
                $data = [                                                                                               // On prépare les informations pour envoyer le mail à l'administrateur
                    'mailType' => 'compteGoogle',
                    'mailTo' => 'admin@lesbriquesrouges.fr',
                    'nom' => $nom,
                    'prenom' => $prenom
                ];
                $curl = curl_init('http://test-mail.lesbriquesrouges.fr/mails_grp12/sendMail.php');                 // On envoie le mail à l'administrateur
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_exec($curl);
                curl_close($curl);
            }
        } else {
            header('Location:../logout-action.php');
        }
        if($row['etat'] != 'inactif' || !empty($data)){                                                                            // On initialise la session
            if($row['etat'] == 'en attente'){
                $requete = "UPDATE `utilisateurs` SET `etat` = 'actif' WHERE `mail` = ? ";
                $stmt = $link->prepare($requete);
                $stmt->bind_param("s", $mail);
                $stmt->execute();
            }
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
        }
    }
}
header('Location:../home.php');
