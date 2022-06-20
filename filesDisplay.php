<?php
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
    function loadFiles($myPage, $search = []): void
    {
        if(isset($_GET["page"])){
            $page = $_GET["page"]*20;
        }else{
            $page = 0;
        }
        $mail = $_SESSION['mail'];
        $link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
        $link->query('SET NAMES utf8');
        if($myPage == 'my_files'){
            $requete = "SELECT * FROM `fichiers` WHERE `auteur` = '$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request to verify
            $result = mysqli_query($link, $requete); // Saving the result
            $files = mysqli_fetch_all($result);
        }else if($myPage == 'home'){
            if(empty($search)){
                if($_SESSION['role'] == "invite"){
                    $requete = "SELECT * FROM `fichiers` WHERE `id`  IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (SELECT `nom_tag` FROM attribuer WHERE `email`='$mail')) OR auteur='$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request
                }else{
                    $requete = "SELECT * FROM `fichiers` ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request to verify
                }
                $result = mysqli_query($link, $requete); // Saving the result
                $files = mysqli_fetch_all($result);
            }else{
                $requete = "SELECT * FROM `fichiers`";
                if($_SESSION['role'] == "invite"){
                    $requete = "SELECT * FROM `fichiers` WHERE `id`  IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (SELECT `nom_tag` FROM attribuer WHERE `email`='$mail')) OR auteur='$mail' ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request
                }else{
                    if(!empty($search['tags']) && !empty($search['extensions'])){
                        $tags = $search['tags'];
                        $extensions = $search['extensions'];
                        $requete = "SELECT * FROM `fichiers` WHERE `extension` IN (";
                        for ($i = 0; $i < count($extensions); $i++) {
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"' . $extensions[$i] . '"';
                        }
                        $requete .= ") AND `id` IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (";
                        for($i = 0; $i < count($tags); $i++) {
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"'.$tags[$i].'"';
                        }
                        $requete .= ")) ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET " . intval($page); // Preparing the request to verify

                    }else if(!empty($search['tags'])){
                        $tags = $search['tags'];
                        $requete = "SELECT * FROM `fichiers` WHERE `id` IN (SELECT DISTINCT `id_fichier` FROM `caracteriser` WHERE `nom_tag` IN (";
                        for($i = 0; $i < count($tags); $i++) {
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"'.$tags[$i].'"';
                        }
                        $requete .=")) ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET ".intval($page); // Preparing the request to verify
                    }else if(!empty($search['extensions'])) {
                        $requete = "SELECT * FROM `fichiers` WHERE `extension` IN (";
                        $extensions = $search['extensions'];
                        for ($i = 0; $i < count($extensions); $i++) {
                            if ($i !== 0) $requete .= ' , ';
                            $requete .= '"' . $extensions[$i] . '"';
                        }
                        $requete .= ") ORDER BY `date` DESC, `id`  LIMIT 20 OFFSET " . intval($page); // Preparing the request to verify
                    }
                }
                $result = mysqli_query($link, $requete); // Saving the result
                $files = mysqli_fetch_all($result);
            }
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
            <a href="';
        if($page <= 0){
            echo './'.$myPage.'.php?page=0';
        }else{
            echo './'.$myPage.'.php?page='.$page/20 -1;
        }
        echo'">< Page précédente</a>';
        echo '<a href="';
        if(!isset($files)){
            echo './'.$myPage.'.php?page=0';
            $files = [];
        }else{
            if(count($files) < 20){
                echo './'.$myPage.'.php?page='.$page/20;
            }else{
                echo './'.$myPage.'.php?page='.$page/20 +1;
            }
        }
        echo'">Page suivante ></a>
        </div>
    </div>
    <div id="filesDisplayContainer">';
        foreach ($files as $fichier){
            $requete = "SELECT `nom_tag` FROM `caracteriser` WHERE `id_fichier` = '$fichier[0]'";
            $result = mysqli_query($link, $requete); // Saving the result
            $fileTags = mysqli_fetch_all($result);
            $taglist="";
            foreach ($fileTags as $value){
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
    </div>';
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
        let listTag = '.$tagsString.';'.
        'let page = "'.$myPage.'";';
        echo'
    </script>
    <script src="selectionComponent.js"></script>
    <script src="filesPreview.js"></script>';
    }

