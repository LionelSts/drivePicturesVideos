<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$link->query('SET NAMES utf8');
$requete = "SELECT `nom_categorie` FROM `categorie`";
$result = mysqli_query($link, $requete);
while($row = mysqli_fetch_array($result)){
    $categorie[] = $row['nom_categorie'];
}
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
    <div class="pageContent">
        <h1 class="bigTitle">Gestion des tags</h1>
        <form class="profile" method="post" action="my_account-action.php">
            <label for="Categories" >Catégories : </label>
            <div class="tagsContainer">

                <?php
                foreach ($categorie as $value1) {
                    echo('<div class="tagsLine">
                            <label for="nom" >'.$value1.'</label>
                            <input class="profile" type="text" id="nomCategorie" name="nomCategorie" value='.$value1.'>
                            <input class="profile" type="submit" value="Modifier">
                            <input class="profile" type="submit" value="Supprimer">
                        </div>'
                    );
                }
                ?>
            </div>
            <div class="tagsLine">
                <label for='role'>Nouvelle catégorie :</label>
                <input class="profile" type="text" id="nomCategorie" name="nomCategorie">
                <div class="lbrSelect">
                    <select class="profile, role-select" name='role'
                        <?php if($_SESSION['role'] != "admin") echo "disabled" ?>
                    >
                        <option class="role-choices" <?php if($_SESSION['role'] == "invite") echo "selected" ?> value='invite'>invité</option>
                        <option class="role-choices-1" <?php if($_SESSION['role'] == "ecriture") echo "selected" ?> value='ecriture'>ecriture</option>
                        <option class="role-choices" <?php if($_SESSION['role'] == "lecture") echo "selected" ?> value='lecture'>lecture</option>
                        <option class="role-choices-1" <?php if($_SESSION['role'] == "admin") echo "selected" ?> value='admin'>admin</option>
                    </select>
                </div>
                <input class="profile" type="submit" value="Créer">
            </div>
        </form>
    </div>
</div>
<div id="pageContent-bottom" class="pageContent">
    <form class="profile" method="post" action="my_account-action.php">
        <label for="Categories" >Tags : </label>
        <div class="tagsContainer">

            <?php
            foreach ($categorie as $value1) {
                echo('<div class="tagsLine">
                            <label for="nom" >'.$value1.'</label>
                            <input class="profile" type="text" id="nomCategorie" name="nomCategorie" value='.$value1.'>
                            <input class="profile" type="submit" value="Modifier">
                            <input class="profile" type="submit" value="Supprimer">
                        </div>'
                );
            }
            ?>
        </div>
        <div class="tagsLine">
            <label for='role'>Nouvelle catégorie :</label>
            <input class="profile" type="text" id="nomCategorie" name="nomCategorie">
            <div class="lbrSelect">
                <select class="profile, role-select" name='role'
                    <?php if($_SESSION['role'] != "admin") echo "disabled" ?>
                >
                    <option class="role-choices" <?php if($_SESSION['role'] == "invite") echo "selected" ?> value='invite'>invité</option>
                    <option class="role-choices-1" <?php if($_SESSION['role'] == "ecriture") echo "selected" ?> value='ecriture'>ecriture</option>
                    <option class="role-choices" <?php if($_SESSION['role'] == "lecture") echo "selected" ?> value='lecture'>lecture</option>
                    <option class="role-choices-1" <?php if($_SESSION['role'] == "admin") echo "selected" ?> value='admin'>admin</option>
                </select>
            </div>
            <input class="profile" type="submit" value="Créer">
        </div>
    </form>
</div>
</div>
</body>


