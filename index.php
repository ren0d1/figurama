<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Figurama Accueil</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="css/my-slider.css"/>
    <script src="js/ism-2.2.min.js"></script>
</head>
<?php
include("header.php");
?>
<body>
    <section class="header">
        <h1>
            <a href="index.php">
                <img src="img/logo.png" class="logo" alt="figurama logo">
            </a>
        </h1>
        <nav class="menu">
            <a href="index.php" class="menu_item active">
                Accueil
            </a>
            <?php
            if(!isset($_SESSION['connecte']))
            {
                $_SESSION['connecte'] = 0;
            }
            if($_SESSION['connecte'] == 0) {
            ?>
            <a onclick="display()" class="menu_item">
                Connexion
            </a>
            <?php
            }else{
            ?>
            <a href="profil.php" class="menu_item">
                Profil
            </a>
            <?php
            if(isset($_SESSION['admin'])) {
                if ($_SESSION['admin'] != 0) {
                    ?>
                    <a href="admin.php" class="menu_item">
                        Admin
                    </a>
                    <?php
                }
            }else{
                ?>
                <a href="mailto:support@figurama.com" class="menu_item">
                    Contact
                </a>
                <?php
            }
            ?>
            <a href="logout.php" class="menu_item">
                Deconnexion
            </a>
            <?php
            }
            ?>
        </nav>
        <?php
        if(isset($_SESSION['admin'])) {
            if ($_SESSION['admin'] != 0) {
                ?>
                <div class="cart">
                    <a href="createItem.php" class="cart_link">
                        <img src="img/create-item.png" height="74" alt="create item">
                    </a>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="cart">
                <a href="panier.php" class="cart_link">
                    <img src="img/count-cart.png" height="74" alt="cart logo">
                    <?php
                    $articles_quantity = 0;
                    if (isset($_SESSION['articles'])) {
                        foreach ($_SESSION['articles'] as $temp) {
                            $articles_quantity += $temp->getItemCount();
                        }
                    }
                    ?>
                    <span class="<?php if ($articles_quantity < 10) {
                        echo 'cart_count';
                    } else {
                        echo 'cart_count_big';
                    } ?>"><?php if (!isset($_SESSION['articles'])) {
                            echo '0';
                        } else {
                            echo $articles_quantity;
                        } ?></span>
                    <aside class="cart_price">
                        <?php
                        if (!isset($_SESSION['articles'])) {
                            echo '0';
                        } else {
                            $prix = 0;
                            foreach ($_SESSION['articles'] as $temp) {
                                $prix += $temp->getItemCount() * $temp->getPrix();
                            }
                            echo $prix;
                        } ?>€
                    </aside>
                </a>
            </div>
            <?php
        }
        ?>
    </section>
    <div id="connexion" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <object data="connexion.php" type="text/html">
                <embed src="connexion.php" type="text/html">
            </object>
        </div>
    </div>
    <main>
        <section class="content">
            <div class="display_flex">
                <?php
                    include("BDD.php");

                    if(!isset($_SESSION['filtres'])){
                        $_SESSION['filtres'] = array();
                    }

                    //Tri par genre
                    if(isset($_POST['genre'])){
                        $_SESSION['filtres']['genre'] = $_POST['genre'];
                    }

                    if(isset($_SESSION['filtres']['genre']))
                    {
                        switch ($_SESSION['filtres']['genre']) {
                            case 1:
                                $genre = 'WHERE genre != "" ';
                                break;

                            case 2:
                                $genre = 'WHERE genre = "Jeu vidéo" ';
                                break;

                            case 3:
                                $genre = 'WHERE genre = "Anime/Manga" ';
                                break;

                            case 4:
                                $genre = 'WHERE genre = "Vocaloid" ';
                                break;

                            case 5:
                                $genre = 'WHERE genre = "Licence populaire" ';
                                break;

                            default:
                                $genre = 'WHERE genre != "" ';
                        }
                    }else{
                        $genre = 'WHERE genre != "" ';
                    }

                    //Tri par ordre
                    if(isset($_POST['ordre'])){
                        $_SESSION['filtres']['ordre'] = $_POST['ordre'];
                    }

                    if(isset($_SESSION['filtres']['ordre']))
                    {
                        switch ($_SESSION['filtres']['ordre']) {
                            case 1:
                                $ordre = "ORDER BY nom";
                                break;

                            case 2:
                                $ordre = "ORDER BY nom DESC";
                                break;

                            case 3:
                                $ordre = "ORDER BY prix";
                                break;

                            case 4:
                                $ordre = "ORDER BY prix DESC";
                                break;

                            default:
                                $ordre = "ORDER BY nom";
                        }
                    }else{
                        $ordre = "ORDER BY nom";
                    }

                    //Tri par type
                    if(isset($_POST['type'])){
                        $_SESSION['filtres']['type'] = $_POST['type'];
                    }

                    if(isset($_SESSION['filtres']['type']))
                    {
                        switch ($_SESSION['filtres']['type']) {
                            case 1:
                                $type = "";
                                break;

                            case 2:
                                $type = 'AND type = "Nendoroid" ';

                                //Si nendo alors tri ordre special
                                if(isset($_POST['ordre_nendo'])){
                                    $_SESSION['filtres']['ordre'] = $_POST['ordre_nendo'];
                                }

                                if(isset($_SESSION['filtres']['ordre']))
                                {
                                    switch ($_SESSION['filtres']['ordre']) {
                                        case 1:
                                            $ordre = "ORDER BY nom";
                                            break;

                                        case 2:
                                            $ordre = "ORDER BY nom DESC";
                                            break;

                                        case 3:
                                            $ordre = "ORDER BY prix";
                                            break;

                                        case 4:
                                            $ordre = "ORDER BY prix DESC";
                                            break;

                                        case 5:
                                            $ordre = "ORDER BY numero";
                                            break;

                                        case 6:
                                            $ordre = "ORDER BY numero DESC";
                                            break;

                                        default:
                                            $ordre = "ORDER BY numero";
                                    }
                                }else{
                                    $ordre = "ORDER BY numero";
                                }
                                break;

                            case 3:
                                $type = 'AND type = "Nendoroid Co-de" ';
                                break;

                            case 4:
                                $type = 'AND type = "Nendoroid Petite" ';
                                break;

                            case 5:
                                $type = 'AND type = "Nendoroid Plus" ';
                                break;

                            default:
                                $type = "";
                        }
                    }else{
                        $type = "";
                    }

                    //Tri par serie
                    if(isset($_POST['serie'])){
                        $_SESSION['filtres']['serie'] = $_POST['serie'];
                    }

                    if(isset($_SESSION['filtres']['serie'])) {
                        $req = BDD::getInstance()->requete('SELECT serie FROM articles GROUP BY serie');
                        $i = 2;
                        $serie = "";
                        while ($groupe = $req->fetch()) {
                            if($_SESSION['filtres']['serie'] == $i){
                                $serie = 'AND serie = "'.$groupe['serie'].'" ';
                            }
                            $i++;
                        }
                    }else{
                        $serie = "";
                    }
                    //Tri par disponibilité
                    if(isset($_POST['disponible'])){
                        $_SESSION['filtres']['dispo'] = $_POST['disponible'];
                    }

                    if(isset($_SESSION['filtres']['dispo']))
                    {
                        switch ($_SESSION['filtres']['dispo']) {
                            case 1:
                                $dispo = "";
                                break;

                            case 2:
                                $dispo = "AND stock > 0 ";
                                break;

                            case 3:
                                $dispo = "AND stock <= 0 ";
                                break;

                            default:
                                $dispo = "";
                        }
                    }else{
                        $dispo = "";
                    }

                    $req = BDD::getInstance()->requete('SELECT count(*) FROM articles '.$genre.$type.$serie.$dispo.$ordre);
                    $donnees = $req->fetch();
                    if(!empty($donnees)){

                        $pages = ceil($donnees[0]/16.0);
                        if(isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pages)
                        {
                            $page = $_GET['page'];
                        }else{
                            $page = 1;
                        }

                        //$req = $bd->query('SELECT * FROM articles WHERE type = "Nendoroid" ORDER BY nom LIMIT '.($page*16-16).', 16');
                        $req = BDD::getInstance()->requete('SELECT * FROM articles '.$genre.$type.$serie.$dispo.$ordre.' LIMIT '.($page*16-16).', 16');
                        while($content = $req->fetch())
                        {
                            ?>
                            <article class="figurine_display_content">
                                <div class="figurine_display">
                                    <h2 class="figurine_display_name">
                                        <?php
                                        if (stripos($content['nom'], '(')) {
                                            echo substr($content['nom'], 0, stripos($content['nom'], '('));
                                        } else if (stripos($content['nom'], '[')) {
                                            echo substr($content['nom'], 0, stripos($content['nom'], '['));
                                        } else if (stripos($content['nom'], ':')) {
                                            echo substr($content['nom'], 0, stripos($content['nom'], ':'));
                                        } else {
                                            echo $content['nom'];
                                        }

                                        ?>
                                    </h2>
                                    <?php
                                    if($content['index']%8 == 0){
                                        $img_product = "img/products/08.png";
                                    }else if($content['index']%7 == 0){
                                        $img_product = "img/products/07.png";
                                    }else if($content['index']%6 == 0){
                                        $img_product = "img/products/06.png";
                                    }else if($content['index']%5 == 0){
                                        $img_product = "img/products/05.png";
                                    }else if($content['index']%4 == 0){
                                        $img_product = "img/products/04.png";
                                    }else if($content['index']%3 == 0){
                                        $img_product = "img/products/03.png";
                                    }else if($content['index']%2 == 0){
                                        $img_product = "img/products/02.png";
                                    }else if($content['index']%1 == 0){
                                        $img_product = "img/products/01.png";
                                    }
                                    ?>
                                    <img src="<?=$img_product?>" class="figurine_display_img" alt="image article">
                                    <footer class="figurine_display_infos">
                                        <h3 class="categorie">
                                            <?php echo $content['genre'] ?>
                                        </h3>
                                        <h3 class="price">
                                            <?php echo $content['prix'] ?>€
                                        </h3>
                                    </footer>
                                </div>
                                <div class="figurine_display_hover">
                                <?php
                                if(isset($_SESSION['admin'])){
                                    if($_SESSION['admin'] != 0){
                                        ?>
                                        <figure class="figurine_display_options">
                                            <a href="editItem.php?item=<?= $content['index'] ?>" class="figurine_display_options_links">
                                                <img src="img/edit-item.png" alt="edit item" class="figurine_display_options_img">
                                            </a>
                                            <a href="removeItem.php?item=<?= $content['index'] ?>" class="figurine_display_options_links">
                                                <img src="img/delete-item.png" alt="delete item" class="figurine_display_options_img">
                                            </a>
                                        </figure>
                                        <?php
                                    }else{
                                        ?>
                                        <figure class="figurine_display_options">
                                            <a href="product.php?id=<?= $content['index'] ?>" class="figurine_display_options_links">
                                                <img src="img/info.png" class="figurine_display_options_img" alt="image infos article">
                                            </a>
                                            <a href="ajoutArticle.php?id=<?= $content['index'] ?>" class="figurine_display_options_links">
                                                <img src="img/add-cart.png" class="figurine_display_options_img" alt="image ajouter article panier">
                                            </a>
                                        </figure>
                                        <?php
                                    }
                                }else {
                                ?>
                                    <figure class="figurine_display_options">
                                        <a href="product.php?id=<?= $content['index'] ?>" class="figurine_display_options_links">
                                            <img src="img/info.png" class="figurine_display_options_img" alt="image infos article">
                                        </a>
                                        <a href="ajoutArticle.php?id=<?= $content['index'] ?>" class="figurine_display_options_links">
                                            <img src="img/add-cart.png" class="figurine_display_options_img" alt="image ajouter article panier">
                                        </a>
                                    </figure>
                                <?php
                                }
                                ?>
                                </div>
                            </article>
                            <?php
                        }
                    }
                ?>
                <nav class="pages_links">
                <?php
                //Définit min and max
                if($page-2 > 0){
                    $min = $page - 2;
                }else{
                    $min = 1;
                }
                if($page+2 < $pages){
                    $max = $page+2;
                }else{
                    $max = $pages;
                }

                //Précédent
                if($page > 1){
                    echo '<a href="index.php?page='.($page-1).'" class="page_move_left">-</a>';
                }else{
                    echo '<span class="page_move_left disabled">-</span>';
                }

                //Affichage pages
                if($min == 1){
                    for($i = $min; $i < $page; $i++){
                        echo '<a href="index.php?page='.$i.'">'.$i.'</a>';
                    }
                }else{
                    echo '<a href="index.php?page=1">1</a>';
                    echo '<span>...</span>';
                    for($i = $min; $i < $page; $i++){
                        echo '<a href="index.php?page='.$i.'">'.$i.'</a>';
                    }
                }

                echo '<a href="index.php?page='.$page.'" class="active_page">'.$page.'</a>';

                if($max == $pages){
                    for($i = $page + 1; $i <= $max; $i++){
                        echo '<a href="index.php?page='.$i.'">'.$i.'</a>';
                    }
                }else{
                    for($i = $page + 1; $i <= $max; $i++){
                        echo '<a href="index.php?page='.$i.'">'.$i.'</a>';
                    }
                    echo '<span>...</span>';
                    echo '<a href="index.php?page='.$pages.'">'.$pages.'</a>';
                }

                //Suivant
                if($page < $pages){
                    echo '<a href="index.php?page='.($page+1).'" class="page_move_right">+</a>';
                }else{
                    echo '<span class="page_move_right disabled">+</span>';
                }
                ?>
                </nav>
            </div>
        </section>
        <section class="search">
            <form id="filtres" action="index.php" method="POST">
                <!-- filtres de recherche -->
                <fieldset class="filtres_box">
                    <legend>
                        Filtres généraux:
                    </legend>
                    <ul class="filtres">
                        <li class="filtre">
                            <label for="genre">
                                Genre:
                            </label>
                            <select name="genre" id="genre" form="filtres" onchange="sendChange();">
                            <?php
                            if(isset($_SESSION['filtres']['genre']))
                            {
                                switch ($_SESSION['filtres']['genre']) {
                                    case 1:
                                        echo '<option value="1">Tout</option>
                                              <option value="2">Jeu vidéo</option>
                                              <option value="3">Anime/Manga</option>
                                              <option value="4">Vocaloid</option>
                                              <option value="5">Licence populaire</option>';
                                        break;

                                    case 2:
                                        echo '<option value="1">Tout</option>
                                              <option value="2" selected>Jeu vidéo</option>
                                              <option value="3">Anime/Manga</option>
                                              <option value="4">Vocaloid</option>
                                              <option value="5">Licence populaire</option>';
                                        break;

                                    case 3:

                                        echo '<option value="1">Tout</option>
                                              <option value="2">Jeu vidéo</option>
                                              <option value="3" selected>Anime/Manga</option>
                                              <option value="4">Vocaloid</option>
                                              <option value="5">Licence populaire</option>';
                                        break;

                                    case 4:
                                        echo '<option value="1">Tout</option>
                                              <option value="2">Jeu vidéo</option>
                                              <option value="3">Anime/Manga</option>
                                              <option value="4" selected>Vocaloid</option>
                                              <option value="5">Licence populaire</option>';
                                        break;

                                    case 5:
                                        echo '<option value="1">Tout</option>
                                              <option value="2">Jeu vidéo</option>
                                              <option value="3">Anime/Manga</option>
                                              <option value="4">Vocaloid</option>
                                              <option value="5" selected>Licence populaire</option>';
                                        break;

                                    default:
                                        echo '<option value="1">Tout</option>
                                              <option value="2">Jeu vidéo</option>
                                              <option value="3">Anime/Manga</option>
                                              <option value="4">Vocaloid</option>
                                              <option value="5">Licence populaire</option>';
                                }
                            }else{
                                echo '<option value="1">Tout</option>
                                      <option value="2">Jeu vidéo</option>
                                      <option value="3">Anime/Manga</option>
                                      <option value="4">Vocaloid</option>
                                      <option value="5">Licence populaire</option>';
                            }
                            ?>
                            </select>
                        </li>
                        <li class="filtre">
                            <label for="type">
                                Type:
                            </label>
                            <select name="type" id="type" form="filtres" onchange="displaySpec(this);sendChange();">
                                <?php
                                if(isset($_SESSION['filtres']['type']))
                                {
                                    switch ($_SESSION['filtres']['type']) {
                                        case 1:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">Nendoroid</option>
                                                  <option value="3">Nendoroid Co-de</option>
                                                  <option value="4">Nendoroid Petite</option>
                                                  <option value="5">Nendoroid Plus</option>';
                                            break;

                                        case 2:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2" selected>Nendoroid</option>
                                                  <option value="3">Nendoroid Co-de</option>
                                                  <option value="4">Nendoroid Petite</option>
                                                  <option value="5">Nendoroid Plus</option>';
                                            break;

                                        case 3:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">Nendoroid</option>
                                                  <option value="3" selected>Nendoroid Co-de</option>
                                                  <option value="4">Nendoroid Petite</option>
                                                  <option value="5">Nendoroid Plus</option>';
                                            break;

                                        case 4:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">Nendoroid</option>
                                                  <option value="3">Nendoroid Co-de</option>
                                                  <option value="4" selected>Nendoroid Petite</option>
                                                  <option value="5">Nendoroid Plus</option>';
                                            break;

                                        case 5:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">Nendoroid</option>
                                                  <option value="3">Nendoroid Co-de</option>
                                                  <option value="4">Nendoroid Petite</option>
                                                  <option value="5" selected>Nendoroid Plus</option>';
                                            break;

                                        default:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">Nendoroid</option>
                                                  <option value="3">Nendoroid Co-de</option>
                                                  <option value="4">Nendoroid Petite</option>
                                                  <option value="5">Nendoroid Plus</option>';
                                    }
                                }else{
                                    echo '<option value="1">Tout</option>
                                          <option value="2">Nendoroid</option>
                                          <option value="3">Nendoroid Co-de</option>
                                          <option value="4">Nendoroid Petite</option>
                                          <option value="5">Nendoroid Plus</option>';
                                }
                                ?>
                            </select>
                        </li>
                        <li class="filtre" id="filtre_serie">
                            <label for="serie">
                                Série:
                            </label>
                            <select name="serie" id="serie" form="filtres" onchange="sendChange();">
                                <option value="1" class="serie-opt">Tout</option>
                                <?php
                                $req = BDD::getInstance()->requete('SELECT serie FROM articles GROUP BY serie');
                                $i = 2;
                                while($groupe = $req->fetch())
                                {
                                    if(isset($_SESSION['filtres']['serie'])){
                                        if($_SESSION['filtres']['serie'] == $i){
                                            echo '<option value="'.$i.'" class="serie-opt" selected>'.$groupe['serie'].'</option>';
                                        }else{
                                            echo '<option value="'.$i.'" class="serie-opt">'.$groupe['serie'].'</option>';
                                        }
                                    }else{
                                        echo '<option value="'.$i.'" class="serie-opt">'.$groupe['serie'].'</option>';
                                    }
                                    $i++;
                                }
                                ?>
                            </select>
                        </li>
                        <li class="filtre">
                            <label for="disponible">
                                Disponibilité:
                            </label>
                            <select name="disponible" id="disponible" form="filtres" onchange="sendChange();">
                                <?php
                                if(isset($_SESSION['filtres']['dispo']))
                                {
                                    switch ($_SESSION['filtres']['dispo']) {
                                        case 1:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">En stock</option>
                                                  <option value="3">Epuisé</option>';
                                            break;

                                        case 2:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2" selected>En stock</option>
                                                  <option value="3">Epuisé</option>';
                                            break;

                                        case 3:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">En stock</option>
                                                  <option value="3" selected>Epuisé</option>';
                                            break;

                                        default:
                                            echo '<option value="1">Tout</option>
                                                  <option value="2">En stock</option>
                                                  <option value="3">Epuisé</option>';
                                    }
                                }else{
                                    echo '<option value="1">Tout</option>
                                          <option value="2">En stock</option>
                                          <option value="3">Epuisé</option>';
                                }
                                ?>
                            </select>
                        </li>
                        <li id="general_nendo" class="filtre">
                            <label for="ordre">
                                Ordre:
                            </label>
                            <select name="ordre" id="ordre" form="filtres" onchange="sendChange();">
                                <?php
                                if(isset($_SESSION['filtres']['ordre']))
                                {
                                    switch ($_SESSION['filtres']['ordre']) {
                                        case 1:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>';
                                            break;

                                        case 2:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2" selected>Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>';
                                            break;

                                        case 3:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3" selected>Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>';
                                            break;

                                        case 4:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4" selected>Prix décroissants</option>';
                                            break;

                                        default:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>';
                                    }
                                }else{
                                    echo '<option value="1">A->Z</option>
                                          <option value="2">Z->A</option>
                                          <option value="3">Prix croissants</option>
                                          <option value="4">Prix décroissants</option>';
                                }
                                ?>
                            </select>
                        </li>
                    </ul>
                </fieldset>
                <!-- faire apparaitre quand nendo -->
                <fieldset id="special_nendo" class="filtres_box">
                    <legend>
                        Filtre spécial Nendoroid:
                    </legend>
                    <ul class="filtres">
                        <li class="filtre">
                            <label for="ordre_nendo">
                                Ordre:
                            </label>
                            <select name="ordre_nendo" id="ordre_nendo" form="filtres" onchange="sendChange();">
                                <?php
                                if(isset($_SESSION['filtres']['ordre']))
                                {
                                    switch ($_SESSION['filtres']['ordre']) {
                                        case 1:
                                            echo '<option value="1" selected>A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>
                                                  <option value="5">Serials croissants</option>
                                                  <option value="6">Serials décroissants</option>';
                                            break;

                                        case 2:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2" selected>Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>
                                                  <option value="5">Serials croissants</option>
                                                  <option value="6">Serials décroissants</option>';
                                            break;

                                        case 3:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3" selected>Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>
                                                  <option value="5">Serials croissants</option>
                                                  <option value="6">Serials décroissants</option>';
                                            break;

                                        case 4:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4" selected>Prix décroissants</option>
                                                  <option value="5">Serials croissants</option>
                                                  <option value="6">Serials décroissants</option>';
                                            break;

                                        case 5:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>
                                                  <option value="5" selected>Serials croissants</option>
                                                  <option value="6">Serials décroissants</option>';
                                            break;

                                        case 6:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>
                                                  <option value="5">Serials croissants</option>
                                                  <option value="6" selected>Serials décroissants</option>';
                                            break;

                                        default:
                                            echo '<option value="1">A->Z</option>
                                                  <option value="2">Z->A</option>
                                                  <option value="3">Prix croissants</option>
                                                  <option value="4">Prix décroissants</option>
                                                  <option value="5" selected>Serials croissants</option>
                                                  <option value="6">Serials décroissants</option>';
                                    }
                                }else{
                                    echo '<option value="1">A->Z</option>
                                          <option value="2">Z->A</option>
                                          <option value="3">Prix croissants</option>
                                          <option value="4">Prix décroissants</option>
                                          <option value="5" selected>Serials croissants</option>
                                          <option value="6">Serials décroissants</option>';
                                }
                                ?>
                            </select>
                        </li>
                    </ul>
                </fieldset>
            </form>
            <article>
                <div class="new_frame">
                    <?php
                    $req = BDD::getInstance()->requete('SELECT * FROM articles ORDER BY articles.index DESC LIMIT 1');
                    $donnees = $req->fetch();
                    if(!empty($donnees)){
                        echo '<img src="img/animated.png" class="frame">
                        <h2 class="latest_nendo">';
                        if(stripos($donnees['nom'],'(')){
                            echo substr($donnees['nom'], 0, stripos($donnees['nom'],'('));
                        }else if(stripos($donnees['nom'],'[')){
                            echo substr($donnees['nom'], 0, stripos($donnees['nom'],'['));
                        }else if(stripos($donnees['nom'],':')){
                            echo substr($donnees['nom'], 0, stripos($donnees['nom'],':'));
                        }else{
                            echo $donnees['nom'];
                        }
                        echo'</h2>';
                        if($donnees['index']%8 == 0){
                            $img_product = "img/products/08.png";
                        }else if($donnees['index']%7 == 0){
                            $img_product = "img/products/07.png";
                        }else if($donnees['index']%6 == 0){
                            $img_product = "img/products/06.png";
                        }else if($donnees['index']%5 == 0){
                            $img_product = "img/products/05.png";
                        }else if($donnees['index']%4 == 0){
                            $img_product = "img/products/04.png";
                        }else if($donnees['index']%3 == 0){
                            $img_product = "img/products/03.png";
                        }else if($donnees['index']%2 == 0){
                            $img_product = "img/products/02.png";
                        }else if($donnees['index']%1 == 0){
                            $img_product = "img/products/01.png";
                        }
                        echo '<img src="'.$img_product.'" class="framed">';
                    }
                    ?>
                </div>
            </article>
            <article class="top5">
                <div class="ism-slider" data-radio_type="thumbnail" data-play_type="loop" id="my-slider">
                    <ol>
                        <?php
                        $req = BDD::getInstance()->requete('SELECT * FROM commandes GROUP BY idx_produit ORDER BY count(quantite), date_achat DESC');
                        $count = 0;
                        while ($commandes = $req->fetch()) {
                            if($count < 5){
                                if($commandes['idx_produit']%8 == 0){
                                    $img_product = "img/products/08.png";
                                }else if($commandes['idx_produit']%7 == 0){
                                    $img_product = "img/products/07.png";
                                }else if($commandes['idx_produit']%6 == 0){
                                    $img_product = "img/products/06.png";
                                }else if($commandes['idx_produit']%5 == 0){
                                    $img_product = "img/products/05.png";
                                }else if($commandes['idx_produit']%4 == 0){
                                    $img_product = "img/products/04.png";
                                }else if($commandes['idx_produit']%3 == 0){
                                    $img_product = "img/products/03.png";
                                }else if($commandes['idx_produit']%2 == 0){
                                    $img_product = "img/products/02.png";
                                }else if($commandes['idx_produit']%1 == 0){
                                    $img_product = "img/products/01.png";
                                }

                                $article_req = BDD::getInstance()->requete('SELECT nom FROM articles WHERE articles.`index` = '.$commandes['idx_produit']);
                                $article = $article_req->fetch();

                                if(!empty($article)) {
                                    echo '<li><img src="' . $img_product . '" alt="' . $article['nom'] . '"><h2 class="ism-caption ism-caption-0">' . $article['nom'] . '</h2></li>';
                                }
                                $count++;
                            }
                        }
                        ?>
                    </ol>
                </div>
            </article>
        </section>
    </main>
    <script>

        document.querySelector("body").addEventListener("load", displaySpecOnRefresh(), false);

        // Get the modal
        var modal = document.getElementById('connexion');


        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        var testVar = document.getElementsByClassName("figurine_display");

        // When the user clicks on the button, open the modal
        function display() {
            modal.style.display = "block";
            for (var i = 0; i < testVar.length; i++) {
                testVar[i].style.zIndex = 0;
            }
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
            location.reload();
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                location.reload();
            }
        }

        function displaySpec(filtre){
            if(filtre.selectedIndex == 1){
                document.getElementById('special_nendo').style.display = 'inherit';
                document.getElementById('general_nendo').style.display = 'none';
            }else{
                document.getElementById('special_nendo').style.display = 'none';
                document.getElementById('general_nendo').style.display = 'flex';
            }
        }

        function displaySpecOnRefresh(){
            if(document.getElementById('type').selectedIndex == 1){
                document.getElementById('special_nendo').style.display = 'inherit';
                document.getElementById('general_nendo').style.display = 'none';
            }else{
                document.getElementById('special_nendo').style.display = 'none';
                document.getElementById('general_nendo').style.display = 'flex';
            }
        }

        function sendChange()
        {
            document.getElementById('filtres').submit();
        }
    </script>
</body>
</html>