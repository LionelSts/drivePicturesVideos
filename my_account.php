<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
?>
<body>
    <div id="header">
        <a href="home.php"> <img  id="logo-header" src="images/graphiqueLBR/logoLONGUEURClassic.png"></a>
    </div>


    <div id="main">
        <?php
        include './menu.php';
        echo getMenu();
        ?>
        <div id="pageContent">
            <h1 class="bigTitle">Gestion de compte</h1>
            <form class="profile" method="post" action="my_account-action.php">
                <div class="formLine">
                    <label for="nom" >Nom : </label>
                    <input class="profile" type="text" id="nom" name="nom" value='<?php echo $_SESSION["nom"]?>' <?php if($_SESSION['role'] != "admin") echo "disabled" ?> >
                </div>
                <div class="formLine">
                    <label for="prenom">Prénom : </label>
                    <input class="profile" type="text" id="prenom" name="prenom" value='<?php echo $_SESSION["prenom"]?>' <?php if($_SESSION['role'] != "admin") echo "disabled" ?> >
                </div>
                <div class="formLine">
                    <label for="mail">Adresse mail :</label>
                    <input class="profile" type="email" id="mail" name="email" value='<?php echo $_SESSION["mail"]?>' <?php if($_SESSION['role'] != "admin") echo "disabled" ?> >
                </div>
                <div class="formLine">
                    <label for="motdepasse">Mot de passe :</label>
                    <input class="profile" type="password" id="motdepasse" name="password"><br>
                </div>
                <div class="formLine">
                    <label for='role'>Rôle :</label>
                    <select class="profile" name='role' id='role-select'
                        <?php if($_SESSION['role'] != "admin") echo "disabled" ?>
                    >
                        <option <?php if($_SESSION['role'] == "invite") echo "selected" ?> value='invite'>invité</option>
                        <option <?php if($_SESSION['role'] == "ecriture") echo "selected" ?> value='ecriture'>ecriture</option>
                        <option <?php if($_SESSION['role'] == "lecture") echo "selected" ?> value='lecture'>lecture</option>
                        <option <?php if($_SESSION['role'] == "admin") echo "selected" ?> value='admin'>admin</option>
                    </select><br>
                </div>

                    <input class="profile" type="submit" style="cursor: pointer;" value="Appliquer les modifications">
            </form>
        </div>
    </div>
</body>


