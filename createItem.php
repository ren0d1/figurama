<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama</title>
    <link rel="stylesheet" type="text/css" href="css/editItem.css">
</head>
<?php
include("header.php");
if(!isset($_SESSION['connecte']))
{
    $_SESSION['connecte'] = 0;
}
if(!isset($_SESSION['admin']))
{
    $_SESSION['admin'] = 0;
}
if($_SESSION['connecte'] != 0 && $_SESSION['admin'] != 0) {
include("BDD.php");
    if(isset($_POST['nom']) && isset($_POST['desc']) && isset($_POST['genre']) && isset($_POST['type']) && isset($_POST['serie']) && isset($_POST['prix']) && isset($_POST['numero']) && isset($_POST['stock'])){
        $insert = BDD::getInstance()->preparation('INSERT INTO articles(nom, articles.desc, genre, type, serie, prix, numero, stock) VALUES (:nom, :desc, :genre, :type, :serie, :prix, :numero, :stock)');
        $tab_insert = array(':nom' => $_POST['nom'], ':desc' => $_POST['desc'], ':genre' => $_POST['genre'], ':type' => $_POST['type'], ':serie' => $_POST['serie'], ':prix' => $_POST['prix'], ':numero' => $_POST['numero'], ':stock' => $_POST['stock']);
        BDD::getInstance()->execution($insert, $tab_insert);
        echo 'Nouvel article enregistré.';
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    }else {
    ?>
    <form id="item_create" action="createItem.php" method="POST">
        <label for="nom">
            Nom:
            <input type="text" name="nom" id="nom" value="Nendoroid name">
        </label>
        <label for="desc">
            Description:
            <textarea name="desc" id="desc" form="item_create">
            Aucune description n'est disponible pour cet article.
        </textarea>
        </label>
        <label for="genre">
            Genre:
            <select name="genre" id="genre" form="item_create">
                <option value="Jeu vidéo">Jeu vidéo</option>
                <option value="Anime/Manga">Anime/Manga</option>
                <option value="Vocaloid">Vocaloid</option>
                <option value="Licence populaire">Licence populaire</option>
            </select>
        </label>
        <label for="type">
            Type:
            <select name="type" id="type" form="item_create">
                <option value="Nendoroid">Nendoroid</option>
                <option value="Nendoroid Co-de">Nendoroid Co-de</option>
                <option value="Nendoroid Petite">Nendoroid Petite</option>
                <option value="Nendoroid Plus">Nendoroid Plus</option>
            </select>
        </label>
        <label for="serie">
            Série:
            <input type="text" name="serie" id="serie">
        </label>
        <label for="prix">
            Prix:
            <input type="number" name="prix" id="prix" min="0" value="30">
        </label>
        <label for="numero">
            Numéro:
            <input type="text" name="numero" id="numero" value="none">
        </label>
        <label for="stock">
            Stock:
            <input type="number" name="stock" id="stock" min="0" value="1">
        </label>
        <input type="submit" class="submit" value="Ajouter">
    </form>
    <?php
    }
}
?>
</html>
