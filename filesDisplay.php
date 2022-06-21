<?php
    if(!isset($_SESSION["mail"])) echo '<script> alert("Vous n`êtes pas connecté.");window.location.replace("./index.php");</script>';
    function loadFiles($myPage, $search = []): void
    {
        if(isset($_GET["page"])){
            $page = $_GET["page"]*20;
        }else{
            $page = 0;
        }
        $currentPage = "./".$myPage.".php";
        if(isset($_GET)){
            $currentPage .= "?";
            foreach ($_GET as $key => $parameter){
                if($key != 'page'){
                    $currentPage .= $key.'='.$parameter.'&';
                }
            }
            $currentPage = str_replace(' ', '+',$currentPage);
            $currentPage .= 'page=';
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
                $requete = "SELECT * FROM `fichiers` LIMIT 20 OFFSET ".intval($page);
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
        }else if($myPage == "corbeille"){
            if($_SESSION['role'] == 'admin'){
                $requete = "SELECT * FROM `corbeille` ORDER BY `supprime_date` DESC  LIMIT 20 OFFSET ".intval($page); // Preparing the request to verify
                $result = mysqli_query($link, $requete); // Saving the result
                $files = mysqli_fetch_all($result);
            }else{
                $requete = "SELECT * FROM `corbeille` WHERE `auteur` = '$mail' ORDER BY `supprime_date` DESC  LIMIT 20 OFFSET ".intval($page); // Preparing the request to verify
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
                    <div id="downloadZone"></div>';
                    if($myPage == "corbeille"){
                        echo'
                    <img alt="supprimer" src="./images/icons/trash.png" onclick="deleteFiles()">
                    <img alt="restaurer" src="./images/icons/recycle.png" onclick="restoreFile()">';
                    }else{
                        echo'<img alt="supprimer" src="./images/icons/trash.png" onclick="deleteFiles()">
                            <img alt="télécharger" src="./images/icons/download.png" onclick="downloadFiles()">
                            <p id="editFilesTags" onclick="tagSelection()">Modifier les tags</p>';
                    }

                echo '</div>
            </div>
            <a href="';
        if($page <= 0){
            echo $currentPage.'0';
        }else{
            echo $currentPage.$page/20 -1;
        }
        echo'">< Page précédente</a>';
        echo '<a href="';
        if(!isset($files)){
            echo $currentPage.'0';
            $files = [];
        }else{
            if(count($files) < 20){
                echo $currentPage.$page/20;
            }else{
                echo $currentPage.$page/20 +1;
            }
        }
        echo'">Page suivante ></a>
        </div>
    </div>
    <div id="filesDisplayContainer">';
        foreach ($files as $fichier){
            if($myPage == "corbeille"){
                $taglist ="Sans tag";
                $miniature= ".\corbeille\\"."miniature-" . $fichier[7] . ".png";
            }else{
                $requete = "SELECT `nom_tag` FROM `caracteriser` WHERE `id_fichier` = '$fichier[0]'";
                $result = mysqli_query($link, $requete); // Saving the result
                $fileTags = mysqli_fetch_all($result);
                $taglist="";
                foreach ($fileTags as $value){
                    $taglist .= $value[0] ." ";
                }
                $miniature= ".\miniatures\\" . $fichier[7] . ".png";
            }

                echo '<div class="fichierContainer">
                            <div class="fichierSubContainer">
                            <label class="checkboxContainer checkboxFiles">
                                     <input type="checkbox" id="' . $fichier[0] . '" name="'. $fichier[0] . '.' . $fichier[2] . '" value="'.$fichier[6].'" onclick="buttonsAction()">
                                     <span class="customCheckBox"></span>
                                </label>';

            // We return the thumbnail
            echo '<img alt="miniature du fichier '. $fichier[0] . '.' . $fichier[2] .'" class="miniatureFichier"  src="' . $miniature . '">';

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
        echo'<script>
        let listTag = '.$tagsString.';'.
        'let page = "'.$myPage.'";';
        echo'</script>';
        if($myPage == 'corbeille'){
            echo'<script src="selectionComponentCorbeille.js"></script>';
        }else{
            echo'<script src="selectionComponent.js"></script>';
        }
        if($myPage != "corbeille"){
            echo '<script src="filesPreview.js"></script>';
        }
    }

