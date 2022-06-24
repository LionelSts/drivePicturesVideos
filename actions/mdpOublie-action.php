<?php
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;  // connexion à la bdd
$email = $_POST["email"];
$requete = "SELECT `mail`, `nom`, `prenom` FROM `utilisateurs` WHERE `mail` = ? ";
$stmt = $link->prepare($requete);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$account = mysqli_fetch_array($result);
if(!empty($account))   // si le mail existe dans la bdd...
{
    $message = file_get_contents('template.html');
    try {
        $tmpPassword = bin2hex(random_bytes(24));
    } catch (Exception $e) {
    }   // génération automatique d'un mot de passe permettant "l'unicité" du lien
    $hashedPassword = password_hash($tmpPassword, PASSWORD_BCRYPT); // hashing du mot de passe
    $requete = "UPDATE utilisateurs SET `mot_de_passe` = ? WHERE `mail` = ?"; // On met à jour la bdd avec ce mot de passe
    $stmt = $link->prepare($requete);
    $stmt->bind_param("ss", $hashedPassword,$email);
    $stmt->execute();
    $data = [                                                                                                           // On prépare les infos pour le mail
        'mailType' => 'mdpOublie',
        'mailTo' => $email,
        'tmpPsw' => $tmpPassword,
        'nom' => $account['nom'],
        'prenom' => $account['prenom']
    ];
    $curl = curl_init('http://test-mail.lesbriquesrouges.fr/mails_grp12/sendMail.php');                             // On envoie le mail
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    print_r($result);
    curl_close($curl);
    echo '<script> alert("Un mail vous a été envoyé."); window.location.replace("../index.php"); </script>'; // on informe l'utilisateur et on le redirige vers la page d'accueil
}
else    // si le mail n'existe pas dans la bdd...
{
    echo '<script> alert("Le mail saisi est incorrect."); window.location.replace("../mdpOublie.php"); </script>'; // on informe l'utilisateur et on le redirige vers la page "mdpOublie.php"
}
