<?php
    $mail = $_SESSION['mail'];
    $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$link->query('SET NAMES utf8');
    $requete = "SELECT * FROM `fichiers` WHERE `auteur` = '$mail' ORDER BY `date`"; // Preparing the request to verify the password where the login entered is found on the database
    $result = mysqli_query($link, $requete); // Saving the result
    $files = mysqli_fetch_all($result);

    $requete = "SELECT * FROM `caracteriser`"; // Preparing the request to verify the password where the login entered is found on the database
    $result = mysqli_query($link, $requete); // Saving the result
    $filesTag = mysqli_fetch_all($result);
?>
<div id="filesDisplayContainer">
    <?php
        foreach ($files as $fichier){
            echo '<div class="fichierContainer">
                    <div class="fichierSubContainer">
                    <label class="checkboxContainer checkboxFiles">
                             <input type="checkbox" id="' . $fichier[0] . '" name="'. $fichier[0] . '" value="Yes">
                             <span class="customCheckBox"></span>
                        </label>
                        <img class="migniatureFichier" src='.".\mignatures\\" . $fichier[0] . ".png" .' >
                        <p>
                            '.$fichier[1].'
                        </p>
                    </div>
                    <p>
                            Tags : '.$filesTag[$fichier[0]-1][1].'
                        </p>
                  </div>';
        }
    ?>
</div>
