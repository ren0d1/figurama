<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama</title>
    <link rel="stylesheet" type="text/css" href="css/editItem.css">
</head>
<body>
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
    if(isset($_GET['item'])){
        if(isset($_POST['nom']) && isset($_POST['desc']) && isset($_POST['genre']) && isset($_POST['type']) && isset($_POST['serie']) && isset($_POST['prix']) && isset($_POST['numero']) && isset($_POST['stock'])){
            $update = BDD::getInstance()->preparation('UPDATE articles SET nom = :nom, articles.desc = :desc, genre = :genre, type = :type, serie = :serie, prix = :prix, numero = :numero, stock = :stock WHERE articles.index = :article_id');
            $tab_update = array(':nom' => $_POST['nom'], ':desc' => $_POST['desc'], ':genre' => $_POST['genre'], ':type' => $_POST['type'], ':serie' => $_POST['serie'], ':prix' => $_POST['prix'], ':numero' => $_POST['numero'], ':stock' => $_POST['stock'], ':article_id' => $_GET['item']);
            BDD::getInstance()->execution($update, $tab_update);
            echo 'Modificaion effectuée.';
            echo "<meta http-equiv='refresh' content='0;url=index.php'>";
        }else{
            $req = BDD::getInstance()->requete('SELECT * FROM articles WHERE articles.index = '.$_GET['item']);
            $donnees = $req->fetch();
            if (!empty($donnees)) {
                ?>
                <form id="item_edit" action="editItem.php?item=<?=$_GET['item']?>" method="POST">
                    <label for="nom">
                        Nom:
                        <input type="text" name="nom" id="nom" value="<?= $donnees['nom'] ?>">
                    </label>
                    <label for="desc">
                        Description:
                        <textarea name="desc" id="desc" form="item_edit">
                            <?= $donnees['desc'] ?>
                        </textarea>
                    </label>
                    <label for="genre">
                        Genre:
                        <select name="genre" id="genre" form="item_edit">
                            <?php
                            switch ($donnees['genre']) {
                                case 'Jeu vidéo':
                                    echo '<option value="Jeu vidéo">Jeu vidéo</option>
                                <option value="Anime/Manga">Anime/Manga</option>
                                <option value="Vocaloid">Vocaloid</option>
                                <option value="Licence populaire">Licence populaire</option>';
                                    break;

                                case 'Anime/Manga':
                                    echo '<option value="Jeu vidéo">Jeu vidéo</option>
                                <option value="Anime/Manga" selected>Anime/Manga</option>
                                <option value="Vocaloid">Vocaloid</option>
                                <option value="Licence populaire">Licence populaire</option>';
                                    break;

                                case 'Vocaloid':
                                    echo '<option value="Jeu vidéo">Jeu vidéo</option>
                                <option value="Anime/Manga">Anime/Manga</option>
                                <option value="Vocaloid" selected>Vocaloid</option>
                                <option value="Licence populaire">Licence populaire</option>';
                                    break;

                                case 'Licence populaire':
                                    echo '<option value="Jeu vidéo">Jeu vidéo</option>
                                <option value="Anime/Manga">Anime/Manga</option>
                                <option value="Vocaloid">Vocaloid</option>
                                <option value="Licence populaire" selected>Licence populaire</option>';
                                    break;
                            }
                            ?>
                        </select>
                    </label>
                    <label for="type">
                        Type:
                        <select name="type" id="type" form="item_edit">
                            <?php
                            switch ($donnees['type']) {
                                case 'Nendoroid':
                                    echo '<option value="Nendoroid">Nendoroid</option>
                                <option value="Nendoroid Co-de">Nendoroid Co-de</option>
                                <option value="Nendoroid Petite">Nendoroid Petite</option>
                                <option value="Nendoroid Plus">Nendoroid Plus</option>';
                                    break;

                                case 'Nendoroid Co-de':
                                    echo '<option value="Nendoroid">Nendoroid</option>
                                <option value="Nendoroid Co-de" selected>Nendoroid Co-de</option>
                                <option value="Nendoroid Petite">Nendoroid Petite</option>
                                <option value="Nendoroid Plus">Nendoroid Plus</option>';
                                    break;

                                case 'Nendoroid Petite':
                                    echo '<option value="Nendoroid">Nendoroid</option>
                                <option value="Nendoroid Co-de">Nendoroid Co-de</option>
                                <option value="Nendoroid Petite" selected>Nendoroid Petite</option>
                                <option value="Nendoroid Plus">Nendoroid Plus</option>';
                                    break;

                                case 'Nendoroid Plus':
                                    echo '<option value="Nendoroid">Nendoroid</option>
                                <option value="Nendoroid Co-de">Nendoroid Co-de</option>
                                <option value="Nendoroid Petite">Nendoroid Petite</option>
                                <option value="Nendoroid Plus" selected>Nendoroid Plus</option>';
                                    break;
                            }
                            ?>
                        </select>
                    </label>
                    <label for="serie">
                        Série:
                        <input type="text" name="serie" id="serie" value="<?= $donnees['serie'] ?>">
                    </label>
                    <label for="prix">
                        Prix:
                        <input type="number" name="prix" id="prix" value="<?= $donnees['prix'] ?>" min="0">
                    </label>
                    <label for="numero">
                        Numéro:
                        <input type="text" name="numero" id="numero" value="<?= $donnees['numero'] ?>">
                    </label>
                    <label for="stock">
                        Stock:
                        <input type="number" name="stock" id="stock" value="<?= $donnees['stock'] ?>" min="0">
                    </label>
                    <input type="submit" class="submit" value="Modifier">
                </form>
                <?php
            }
        }
    }else{
        echo 'aucun item n\'a été sélectionné.';
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    }
}else{
    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
?>
</body>
</html>
