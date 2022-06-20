<?php
$link = mysqli_connect("127.0.0.1", "root", "" , "drivelbr") ;
$link->query('SET NAMES utf8');
$final_tab = [];
$requete = "SELECT `nom_tag`, `nom_categorie` FROM `tags`";
$result = mysqli_query($link, $requete);
$data = mysqli_fetch_all($result);
$requete = "SELECT `nom_categorie` FROM `categorie`";
$result = mysqli_query($link, $requete);
$categorie = [];
while($row = mysqli_fetch_array($result)){
    $categorie[] = $row['nom_categorie'];
}

foreach($categorie as $value) {
    foreach ($data as list($value1, $value2)) {
        if($value == $value2) {
            $tab[] = $value1;
        }
    }
    if(!empty($tab)){
        $final_tab[] = $tab;
    }else{
        $final_tab[] = [];
    }
    unset($tab);
}

$requete = "SELECT DISTINCT `extension` FROM `fichiers`";
$result = mysqli_query($link, $requete);
$extensions = mysqli_fetch_all($result);
?>
<div id="searchPopUpContainer">
    <div class="closeButton" onclick="closeSearchPopUp()">
        <h1>X</h1>
    </div>
    <h1 id="searchTitle">Rechercher des fichiers</h1>
    <form id="uploadForm" method='get' action='./home.php' enctype="multipart/form-data">
        <div id="uploadsTags">
            <div>
                <p class="bold">Tags :</p>
            </div>
            <?php
            $i = 0;
            foreach ($final_tab as $value1) {
                if($value1){
                    echo('
                            <div class="uploadCategories">
                                <p>' . $categorie[$i] . '</p>
                                <div class="uploadsTagList" >
                                <div id="list' . $categorie[$i] . '">'
                    );
                    foreach ($value1 as $value2) {
                        echo('
                                    <label class="checkboxContainer">' . $value2 . '
                                        <input type="checkbox" id="' . $value2 . '" name="'. $categorie[$i] .'-' .  $value2 . '" value="Yes">
                                        <span class="customCheckBox"></span>
                                    </label>'
                        );
                    }


                echo '          </div>
                                </div>
                            </div>';
                }
                $i++;
            }
            ?>
        </div>
        <div id="uploadsTags">
            <div>
                <p class="bold">Extensions :</p>
            </div>
            <div id="extensionsContainer">
            <?php
            foreach ($extensions as $value1) {
                echo('<div class="extensionsContainer" >
                            <label class="checkboxContainer">' . $value1[0] . '
                                <input type="checkbox" id="' . $value1[0] . '" name="extension-' .  $value1[0] . '" value="Yes">
                                <span class="customCheckBox"></span>
                            </label>
                            </div>'
                );
            }
            ?>
            </div>
        </div>
        <input id="uploadButton" type='submit' name='submit' value='Rechercher'>
    </form>
</div>



