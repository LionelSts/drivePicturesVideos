
<?php
    function getMenu(){
        $role = $_SESSION['role'];
        $html = "<div id='leftmenu'>
        <ul id='menurubrique'>
            <li id='title'>Mon compte</li>";
        if($role != 'lecture') $html .= "<ul><li class='menuLine'><img class='iconMenu' src='images/icons/folder.png'><a href='#' id='page'>Mes fichiers</a></li>
            <ul id='menufichiers'>
                <li><a href='#' id='page'>Fichier 1</a></li>
                <li><a href='#' id='page'>Fichier 2</a></li>
            </ul>";
        if($role != 'lecture') $html.= "<li class='menuLine'><img class='iconMenu' src='images/icons/trash_red.png'><a href='#' id='page'>Corbeille</a></li>";
        $html.="<li class='menuLine'><img class='iconMenu' src='images/icons/engrenages.png'><a>Gestion</a></li><ul>
            <li class='menuLine'><img class='iconMenu' src='images/icons/user.png'><a href='my_account.php' id='page'>Gérer mon compte</a></li>";
        if($role == 'admin') $html.="<li class='menuLine'><img class='iconMenu' src='images/icons/multiple-users-silhouette.png'><a href='#' id='page'>Gérer les comptes</a></li>";
        if($role == 'ecriture' || $role == 'admin') $html.= "<li class='menuLine'><img class='iconMenu' src='images/icons/tag.png'><a href='#' id='page'>Gérer les tags</a></li>";
        if($role == 'admin') $html.= "<li class='menuLine'><img class='iconMenu' src='images/icons/book-of-black-cover-closed.png'><a href='#' id='page'>Journal de bord</a></li>";
        $html .= "</ul>
            </ul>
            <li id='disconnect'><img id='iconMenuDisconnect' src='images/icons/exit.png'><a id='page' href='logout-action.php'>Déconnexion</a></li>
        </ul>
        </div>";
    return $html;
    }
?>
