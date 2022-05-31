<?php
session_start();
$link = mysqli_connect("127.0.0.1", "root", "", "drivelbr");
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$mail = $_POST['mail'];
$role = $_POST['role'];
$psw = $_POST['password'];
$descriptif = $_POST['descriptif'];
$password = password_hash($psw, PASSWORD_BCRYPT); // Hashing the password for more safety
$requete = "SELECT `mail` FROM `utilisateurs` Where `mail`= '$mail'"; // The request to see if this login doesn't exist already
$result = mysqli_query($link,$requete); // Applying the request
$exist = mysqli_num_rows($result); // If the login doesn't exist already then $exist=0 if it exists already then exist= 1
if($exist==1)
{
    echo '<script language="JavaScript"> alert("Le compte saisi existe déjà.");window.location.replace("../home.php");</script>'; // Please chose another login
}
else // If that's not the case then all's good you can use it, and enjoy doing Sudoku! (after you log in of course)
{
    if (!isset($_POST['mdp'])) {
        $requete = "INSERT INTO utilisateurs(`prenom`, `nom`, `mail`, `mot_de_passe`,`role`,`descriptif`, `etat`) VALUES ('$prenom', '$nom', '$mail', '$password', '$role','$descriptif', 'actif') "; // So we create your account in our database
        $result = mysqli_query($link,$requete); // the request itself
        $message = "Bonjour ".$_POST['prenom']. "" .$_POST['nom'].",\r\n Pour rappel votre mot de passe est ".$_POST["password"]."";
        mail($mail, "Confirmation d'inscription", $message);
    }
    header("location:../home.php"); // Now you are to login...
}

?>