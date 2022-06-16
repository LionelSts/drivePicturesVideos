<?php
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
    function loadFiles($myFiles,$search = ""): void
    {
        if(isset($_GET["page"])){
            $page = $_GET["page"]*20;
        }else{
            $page = 0;
        }
        $mail = $_SESSION['mail'];
        $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
        $link->query('SET NAMES utf8');
        if($myFiles){
            $requete = "SELECT * FROM `fichiers` WHERE `auteur` = '$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request to verify
            $result = mysqli_query($link, $requete); // Saving the result
            $files = mysqli_fetch_all($result);
        }else if($search === ""){
            if($_SESSION['role'] == "invite"){
                $requete = "SELECT * FROM `fichiers` WHERE `id`  IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (SELECT `nom_tag` FROM attribuer WHERE `email`='$mail')) OR auteur='$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request
            }else{
                $requete = "SELECT * FROM `fichiers` ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request to verify
            }
            $result = mysqli_query($link, $requete); // Saving the result
            $files = mysqli_fetch_all($result);
        }else{

        }
        echo'
    <div class="filesNavigation">
        <h2 class="mediumTitle">Récents</h2>
        <div>
            <div id="checkActionButtons">
                <p id="filesSize"></p>
                <div class="actionButtonsContainer">
                    <div id="downloadZone"></div>
                    <p id="editFilesTags" onclick="tagSelection()">Modifier les tags</p>
                    <img alt="télécharger" src="./images/icons/download.png" onclick="downloadFiles()">
                    <img alt="supprimer" src="./images/icons/trash.png" onclick="deleteFiles()">
                </div>
            </div>
            <a href=';
        if($page <= 0){
            echo './my_files.php?page=0';
        }else{
            echo './my_files.php?page='.$page/20 -1;
        }
        echo'">< Page précédente</a>';
        echo '<a href="';
        if(count($files) < 20){
            echo './my_files.php?page='.$page/20;
        }else{
            echo './my_files.php?page='.$page/20 +1;
        }
        echo'">Page suivante ></a>
        </div>
    </div>
    <div id="filesDisplayContainer">';
        foreach ($files as $fichier){
            $search=$fichier[0];
            $requete = "SELECT `nom_tag` FROM `caracteriser` WHERE `id_fichier` = '$fichier[0]'";
            $result = mysqli_query($link, $requete); // Saving the result
            $fileTags = mysqli_fetch_all($result);
            $taglist="";
            foreach ($fileTags as $key=>$value){
                $taglist .= $value[0] ." ";
            }
            echo '<div class="fichierContainer">
                        <div class="fichierSubContainer">
                        <label class="checkboxContainer checkboxFiles">
                                 <input type="checkbox" id="' . $fichier[0] . '" name="'. $fichier[0] . '.' . $fichier[2] . '" value="'.$fichier[6].'" onclick="buttonsAction()">
                                 <span class="customCheckBox"></span>
                            </label>';

            $migature= ".\mignatures\\" . $fichier[0] . ".png";
            $imageData = base64_encode(file_get_contents($migature));

            // Format the image SRC:  data:{mime};base64,{data};
            $src = 'data: '.mime_content_type($migature).';base64,'.$imageData;

            // Echo out a sample image
            echo '<img alt="mignature du fichier '. $fichier[0] . '.' . $fichier[2] .'" class="migniatureFichier"  src="' . $src . '">';

            echo '      <p>
                                <span class="fileNameContainer">'.$fichier[1].'.</span>
                                <span class="fileExtensionContainer">'.$fichier[2].'</span>
                        </p>
                        </div>
                        <p>
                            Tags : '.$taglist.'
                        </p>
                   </div>';
        }
        echo'
    </div>
    <script src="selectionComponent.js"></script>';
        $requete = "SELECT `nom_tag` FROM `tags`";
        $result = mysqli_query($link, $requete); // Saving the result
        $tags = mysqli_fetch_all($result);
        $tagsString = "[";
        foreach ($tags as $tag){
            $tagsString .= "'".$tag[0]."'".",";
        }
        $tagsString = rtrim($tagsString, ',');
        $tagsString.=']';
        echo'
    <script>
        const listTag = '.$tagsString;
        echo'
    </script>
    
    <script src="filesPreview.js"></script>';
    }

