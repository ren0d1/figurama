<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama Panier</title>
    <link rel="stylesheet" type="text/css" href="css/panier.css">
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
        if(isset($_SESSION['articles'])){
            foreach($_SESSION['articles'] as $article){
                $req = BDD::getInstance()->requete('SELECT * FROM articles WHERE articles.index = '.$article->getId());
                $content = $req->fetch();

                echo '<section class="article">';
                    echo '<h2 id="product_name">'.$article->getNom().'</h2>';
                    echo '<article class="article_in">';
                        if($content['stock'] == 0){
                            unset($_SESSION['articles'][$article->getId()]);
                            echo 'Cet article n\'est plus en stock';
                        }else if($article->getItemCount() > $content['stock']){
                            while($article->getItemCount() >  $content['stock']){
                                $article->removeItem();
                            }

                            echo '<a href="retraitArticle.php?id='.$article->getId().'">-</a>';
                            echo '<span class="updated">'.$article->getItemCount().'</span>';
                            echo '<a href="ajoutArticle.php?id='.$article->getId().'">+</a>';

                        }else{
                            echo '<a href="retraitArticle.php?id='.$article->getId().'">-</a>';
                            echo '<span>'.$article->getItemCount().'</span>';
                            echo '<a href="ajoutArticle.php?id='.$article->getId().'">+</a>';
                        }
                    echo '</article>';
                echo '</section>';
            }

            echo '<a href="commande.php" onclick="return validateChoice();">Passer commande</a>';
        }
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
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            location.reload();
        }
    };

    <?php
    if($_SESSION['connecte'] == 0) {
    ?>
        function validateChoice() {
            window.alert("Vous devez être connecté pour passer commande!");
            display();
            return false;
        }
    <?php
    }else{
    ?>
        function validateChoice() {
            if (confirm("Etes-vous certain de vouloir passer commande?") == true) {
                return true;
            } else {
                return false;
            }
        }
    <?php
    }
    ?>
</script>
</body>
</html>