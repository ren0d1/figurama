<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama Fiche Produit</title>
    <link rel="stylesheet" type="text/css" href="css/product.css">
</head>
<?php
include("header.php");
include("BDD.php");
?>
<body>
    <section class="header">
        <h1>
            <a href="index.php">
                <img src="img/logo.png" class="logo" alt="figurama logo">
            </a>
        </h1>
        <nav class="menu">
            <a href="index.php" class="menu_item">
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
                <a href="mailto:support@figurama.com" class="menu_item">
                    Contact
                </a>
                <a href="logout.php" class="menu_item">
                    Deconnexion
                </a>
                <?php
            }
            ?>
        </nav>
        <div class="cart">
            <a href="panier.php" class="cart_link">
                <img src="img/count-cart.png" height="74" alt="cart logo">
                <?php
                    $articles_quantity = 0;
                    if(isset($_SESSION['articles'])){
                        foreach($_SESSION['articles'] as $temp){
                            $articles_quantity += $temp->getItemCount();
                        }
                    }
                ?>
                <span class="<?php if($articles_quantity<10){echo 'cart_count';}else{echo 'cart_count_big';}?>"><?php if(!isset($_SESSION['articles'])){echo '0';}else{echo $articles_quantity;}?></span>
                <aside class="cart_price">
                    <?php
                    if(!isset($_SESSION['articles']))
                    {
                        echo '0';
                    }else{
                        $prix = 0;
                        foreach($_SESSION['articles'] as $temp){
                            $prix += $temp->getItemCount() * $temp->getPrix();
                        }
                        echo $prix;
                    }?>€
                </aside>
            </a>
        </div>
    </section>
    <main>
    <?php
        $req = BDD::getInstance()->requete('SELECT count(*) FROM articles');
        $donnees = $req->fetch();
        if(!empty($donnees)){
            if(isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] <= $donnees[0])
            {
                $id = $_GET['id'];
            }else{
                $id = 1;
            }

            $req = BDD::getInstance()->requete('SELECT * FROM articles WHERE articles.index = '.$id);
            $content = $req->fetch();

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
            <div class="product">
                <h2 id="product_name"><?=$content['nom']?></h2>
                <img src="<?=$img_product?>" alt="<?=$content['nom']?>">
            </div>
            <p><?=$content['desc']?></p>
            <a href="ajoutArticle.php?id=<?= $content['index'] ?>" class="figurine_display_options_links">
                <img src="img/add-cart.png" class="figurine_display_options_img" alt="image ajouter article panier">
            </a>
            <?php
        }
    ?>
    </main>
    <div id="connexion" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <object data="connexion.php" type="text/html">
                <embed src="connexion.php" type="text/html">
            </object>
        </div>
    </div>
    <script>
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
    </script>
</body>
</html>