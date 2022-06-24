<?php
session_start();    // démarage de la session
if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';  // redirection vers le login si l'utilisateur n'est pas connecté
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");    // connexion à la base de données
$link->query('SET NAMES utf8');
$nom = $_POST['nom']; $prenom = $_POST['prenom']; $mail = $_POST['mail']; $role = $_POST['role']; $descriptif = $_POST['descriptif']; $mdp = $_POST['password']; // récupération des données du formulaire de Création de compte
$name = $_SESSION["prenom"]; $lastname = $_SESSION["nom"]; $role2 = $_SESSION["role"];
$requete = "SELECT `mail` FROM `utilisateurs` Where `mail`= ?"; // on vérifie dans la bdd que le mail saisi est bien disponible
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();
$exist = mysqli_num_rows($result); // on associe la variable '$exists' au nombre d'apparition de l'email saisi dans la bdd
$regex = "/^(?=.*\w)(?=.*\W)[\w\W]{8,}$/";  // on vérifie le respect des régles du mot de passe (1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et 8 caractères au minimum)
$chaine = urldecode(file_get_contents('php://input'));  // récupération de la liste des tags sélectionné en enlevant les données inutiles
$chaine = str_replace("nom=".$nom."&prenom=".$prenom."&mail=".$mail."&role=".$role, '', $chaine);
$chaine = str_replace("&listeTag=", ' ', $chaine);
$chaine = str_replace("&descriptif=".$descriptif."&password=".$mdp, '', $chaine);
$chaine=str_replace("&randomPassword=randomPassword","",$chaine);
if($exist==1)   // si le mail existe déjà dans la bdd...
{
    echo '<script> alert("Le compte saisi existe déjà.");window.location.replace("../accounts.php");</script>'; // on informe l'utilisateur et on le redirige vers la page "accounts.php"
}
else    // si le mail n'existe pas dans la bdd...
{
    $tab = explode(" ",$chaine);    // on récupère la chaine d'informations contenant les tags et on la convertit en tableau
    $requete = "SELECT `nom_tag` FROM `tags`";
    $result = mysqli_query($link,$requete);
    $data = mysqli_fetch_all($result);
    foreach($data as $value){
        $liste_tag[]=$value[0];
    }
    $tag="";
    foreach($tab as $value){
        if($value!==""){
            $tag.=" ".$value;
            $tag=trim($tag);
        }
        if(in_array($tag,$liste_tag)){
            $requete = "INSERT INTO `attribuer` (`email`,`nom_tag`) VALUES (?, ?)";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("ss", $mail,$tag);
            $stmt->execute();
            $tag="";
        }
    }
    if (isset($_POST['randomPassword'])) { // si la person
        try {
            $tmpPassword = bin2hex(random_bytes(24));
        } catch (Exception $e) {
        }   // génération automatique d'un mot de passe permetant "l'unicité" du lien
        $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
        $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES (?,?,?,?,?,?, 'en attente') "; // Insertion du compte saisi, dans la bdd avec le statut "en attente"
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ssssss", $prenom,$nom,$mail,$hashedPassword,$role,$descriptif);
        $stmt->execute();
        $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' (',?,') crée par ',?,' ',?,' (',?,') - choix du mot de passe par l\'utilisateur'))";
        $stmt = $link->prepare($requete);
        $stmt->bind_param("ssssss", $nom,$prenom,$role,$lastname,$name,$role2);
        $stmt->execute();
        $data = [                                                                                                       // On prépare les infos pour le mail
            'mailType ' => 'mdpRandom',
            'mailTo' => $mail,
            'tmpPsw' => $hashedPassword,
            'nom' => $nom,
            'prenom' => $prenom
        ];
    }
    else if($_POST["password"] != ""){  // si l'utilisateur rentre un mot de passe ...
        if (preg_match($regex, $_POST['password'])) {   // si le mot de passe répond aux critères de sécurité
            $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT); // hashing du mot de passe
            for ($i = 1; $i < count($tab); $i++){ // puis on insère la liste des tags dans la bdd en parcourant le tableau obtenu précédement
                $requete = "INSERT INTO `attribuer` (`email`,`nom_tag`) VALUES (?,?)";
                $stmt = $link->prepare($requete);
                $stmt->bind_param("ss", $mail,$tab[$i]);
                $stmt->execute();
            }
            $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES (?,?,?,?,?,?, 'en attente') "; // Insertion du compte saisi, dans la bdd avec le statut "en attente"
            $stmt = $link->prepare($requete);
            $stmt->bind_param("ssssss", $prenom,$nom,$mail,$mdp,$role,$descriptif);
            $stmt->execute();
            $requete = "INSERT INTO `tableau_de_bord` (`modification`) VALUES (CONCAT('Compte ',?,' ',?,' (',?,') crée par ',?,' ',?,' (',?,') - choix du mot de passe par l`admin'))";
            $stmt = $link->prepare($requete);
            $stmt->bind_param("ssssss", $nom,$prenom,$role,$lastname,$name,$role2);
            $stmt->execute();
            $data = [                                                                                                   // On prépare les infos pour le mail
                'mailType ' => 'mdpChoisis',
                'mailTo' => $mail,
                'nom' => $nom,
                'prenom' => $prenom
            ];
        }
        else echo '<script> alert("Veuillez saisir un mot de passe contenant au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial."); window.location.replace("../accounts.php");</script>';   //si le mot de passe ne respecte pas les régles, on affiche un message d'erreur et on réactualise la page
    }
    else echo '<script> alert("Erreur"); window.location.replace("../accounts.php");</script>';

    $curl = curl_init('http://test-mail.lesbriquesrouges.fr/mails_grp12/sendMail.php');                             // On envoie le mail
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_exec($curl);
    curl_close($curl);
    echo '<script> alert("Compte crée avec succés."); window.location.replace("../accounts.php");</script>'; // redirection vers la page de gestions des comptes
}
