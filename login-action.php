<?php
session_start();
$mail = $_POST['email'];
$mdp = $_POST['password'];
//include("connexion.php");
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$requete = "SELECT `mail`, `mot_de_passe` FROM `utilisateurs` WHERE `mail` = '$mail' "; // Preparing the request to verify the password where the login entered is found on the database
$result = mysqli_query($link, $requete); // Saving the result
while($row = mysqli_fetch_array($result)) // Searching the right line
{
    $hashedpsw = $row['mot_de_passe']; // Saving the hashed password to verify it later
    $_SESSION['mail'] = $row['mail']; // Saving the user ID needed later
}
if(password_verify($mdp, $hashedpsw)) // If the password entered and the hashed version stored in the database are equal when password entered is hashed
{
    header('Location:????.html'); // Then you are logged in and can go further
}
else
{
    echo '<script language="JavaScript"> alert("Le compte saisi est incorrect");window.location.replace("login.html");</script>'; // If not then you are coridally invited to log in again, with the right password this time... Or to put an existing login if that wasn't the case
}
?>
