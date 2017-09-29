<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama Profil</title>
    <link rel="stylesheet" type="text/css" href="css/profil.css">
</head>
<?php
include("header.php");
if(!isset($_SESSION['connecte']))
{
    $_SESSION['connecte'] = 0;
}
if($_SESSION['connecte'] != 0) {
include("BDD.php");
if (isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['old_mdp']) && isset($_POST['mdp1']) && isset($_POST['mdp2']) && isset($_POST['ville']) && isset($_POST['CP']) && isset($_POST['adresse']) && isset($_POST['tel']))
{
    $req = BDD::getInstance()->preparation('SELECT mdp FROM utilisateurs WHERE email = :email');
    BDD::getInstance()->execution($req, array('email' => $_SESSION['id_user']));
    $donnees = $req->fetch();
    if (!empty($donnees)) {
        if (\Sodium\crypto_pwhash_str_verify($donnees[0], $_POST['old_mdp'])) {
            if ($_POST['mdp1'] == $_POST['mdp2']) {
                $hash_pwd = \Sodium\crypto_pwhash_str(
                    $_POST['mdp1'],
                    \Sodium\CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                    \Sodium\CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
                );
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['nom']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['prenom']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['ville']) && preg_match("/^[0-9]{4}$/", $_POST['CP']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\-,° ]+[0-9]{1,}$/", $_POST['adresse']) && preg_match("/^0[0-9]{8,9}$/", $_POST['tel'])) {
                    if(strcmp($_POST['email'], $_SESSION['id_user']) != 0 && BDD::getInstance()->requete('SELECT count(*) FROM utilisateurs WHERE email = "'.$_POST['email'].'"')->fetch()[0] == 0){
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :newEmail, mdp = :newMDP, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':newEmail' => $_POST['email'], ':newMDP' => $hash_pwd, ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':email' => $_SESSION['id_user']));
                    }else if(strcmp($_POST['email'], $_SESSION['id_user']) == 0){
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, mdp = :newMDP, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':newMDP' => $hash_pwd, ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':email' => $_SESSION['id_user']));
                    }else{
                        echo 'La nouvelle adresse email est déjà existante.';
                        echo "<meta http-equiv='refresh' content='0;url=profil.php'>";
                    }
                }else{
                    echo 'Les informations fournies ne sont pas correctes';
                    echo "<meta http-equiv='refresh' content='0;url=profil.php'>";
                }
            }
        }
    }
}else{
$req = BDD::getInstance()->preparation('SELECT utilisateurs.`index`, prenom, nom, email, mdp, ville, CP, adresse, tel FROM utilisateurs WHERE email = :email');
BDD::getInstance()->execution($req, array(':email' => $_SESSION['id_user']));
$donnees = $req->fetch();
if(!empty($donnees))
{
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
        <a href="profil.php" class="menu_item active">
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
<main>
    <section>
        <form method="POST" action="profil.php" onsubmit="return validateChoiceModif()">
            <label for="email">
                Email:
                <input type="email" name="email" id="email" value="<?= $donnees['email'] ?>" onchange="validateEmail(this)">
            </label>
            <label for="old_mdp">
                Ancien mdp:
                <input type="password" name="old_mdp" id="old_mdp">
            </label>
            <label for="mdp1">
                Nouveau mdp:
                <input type="password" name="mdp1" id="mdp1" onchange="validatePwd(this)">
            </label>
            <label for="mdp2">
                Confirmation nouveau mdp:
                <input type="password" name="mdp2" id="mdp2" onchange="validatePwd(this)">
            </label>
            <label for="nom">
                Nom:
                <input type="text" name="nom" id="nom" value="<?= $donnees['nom'] ?>" onchange="validateText(this)">
            </label>
            <label for="prenom">
                Prénom:
                <input type="text" name="prenom" id="prenom" value="<?= $donnees['prenom'] ?>" onchange="validateText(this)">
            </label>
            <label for="ville">
                Ville:
                <input type="text" name="ville" id="ville" value="<?= $donnees['ville'] ?>" onchange="validateText(this)">
            </label>
            <label for="CP">
                CP:
                <input type="text" name="CP" id="CP" value="<?= $donnees['CP'] ?>" onchange="validateCP(this)">
            </label>
            <label for="adresse">
                Adresse:
                <input type="text" name="adresse" id="adresse" value="<?= $donnees['adresse'] ?>" onchange="validateAdresse(this)">
            </label>
            <label for="tel">
                Téléphone:
                <input type="tel" name="tel" id="tel" value="<?= $donnees['tel'] ?>" onchange="validateTel(this)">
            </label>
            <input type="submit" class="submit" value="Modifier">
        </form>
        <a href="accountDelete.php?user=<?=$donnees['index']?>" onclick="return validateChoiceDelete();"><img src="img/account-remove.png" alt="remove account"></a>
    </section>
    <section>
        <?php
        $req = BDD::getInstance()->requete('SELECT * FROM commandes WHERE idx_client = ' . $donnees['index'] . ' ORDER BY date_achat DESC');
        $date_commande = null;
        while ($commandes = $req->fetch()) {
            if ($date_commande != $commandes['date_achat']) {
                if ($date_commande != null) {
                    echo '</ul>';
                }
                echo '<ul><h2>Commande du '.substr($commandes['date_achat'], 0, strrpos($commandes['date_achat'], "."));

                if($commandes['envoye'] == 0){
                    echo ' : en préparation<a href="annulerCommande.php?user='.$commandes['idx_client'].'&date='.$commandes['date_achat'].'" class="cancel"><img src="img/cancel-order.png" alt ="cancel order"></a></h2>';
                }else if($commandes['envoye'] == 1 && $commandes['livre'] == 0){
                    echo ' : en cours de livraison</h2>';
                }else{
                    echo ' : livré</h2>';
                }

                $date_commande = $commandes['date_achat'];
            }
            echo '<br><li><a href="product.php?id='.$commandes['idx_produit'].'">Article '.$commandes['idx_produit'].': '.$commandes['quantite'].' fois</a></li>';
        }
        ?>
    </section>
</main>
<script>
    function validateEmail(email){
        var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(regex.test(email.value)){
            email.style.color = "blue";
            return true;
        }else{
            email.style.color = "red";
        }
        return false;
    }

    function validatePwd(mdp){
        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[;<>!@#\$%\^&\*])(?=.{8,})/;
        if(mdp.value.length < 8){
            alert("Le mot de passe doit au moins contenir 8 caractères.");
        }else{
            if(regex.test(mdp.value)){
                mdp.style.color = "blue";
                return true;
            }else{
                mdp.style.color = "red";
                alert("Le mot de passe doit au moins contenir 1 lettre minuscule, 1 lettre majuscule, 1 chiffre et 1 caractère spécial.");
            }
        }
        return false;
    }

    function validateText(text){
        var regex = /^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/;
        if(regex.test(text.value)){
            text.style.color = "blue";
            return true;
        }else{
            text.style.color = "red";
        }
        return false;
    }

    function validateCP(CP){
        var regex = /^[0-9]+$/;
        if(CP.value.length == 4){
            if(regex.test(CP.value)){
                CP.style.color = "blue";
                return true;
            }else{
                CP.style.color = "red";
            }
        }else{
            CP.style.color = "red";
            alert("Un code postal belge ne peut être composé que de 4 chiffres");
        }
        return false;
    }

    function validateAdresse(adresse){
        var regex = /^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\-,° ]+[0-9]{1,}$/;
        if(regex.test(adresse.value)){
            adresse.style.color = "blue";
            return true;
        }else{
            adresse.style.color = "red";
        }
        return false;
    }

    function validateTel(tel){
        var regex = /^0[0-9]+$/;
        if(tel.value.length > 8 && tel.value.length < 11){
            if(regex.test(tel.value)){
                tel.style.color = "blue";
                return true;
            }else{
                tel.style.color = "red";
            }
        }else{
            tel.style.color = "red";
            alert('Un numéro belge est composé de 9 ou 10 chiffres.');
        }
        return false;
    }

    function validateForm(){
        if(validateEmail(document.getElementById('email')) && validatePwd(document.getElementById('mdp1')) && validatePwd(document.getElementById('mdp2')) && validateText(document.getElementById('nom')) && validateText(document.getElementById('prenom')) && validateText(document.getElementById('ville'))  && validateCP(document.getElementById('CP')) && validateAdresse(document.getElementById('adresse')) && validateTel(document.getElementById('tel'))){
            return true;
        }else{
            alert('Le formulaire n\'est pas correctement complété. L\'inscription n\'a donc pas été effectuée');
            return false;
        }
    }

    function validateChoiceModif() {
        if(confirm("Etes-vous certain de vouloir mettre a jour vos informations?") == true){
            return validateForm();
        }else{
            return false;
        }
    }

    function validateChoiceDelete() {
        if(confirm("Etes-vous certain de vouloir supprimer votre compte?") == true){
            return true;
        }else{
            return false;
        }
    }
</script>
</body>
</html>
<?php
        }
    }
}else{
?>
<body onload="display()">
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
                <a href="#" class="menu_item">
                    Admin
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
    </script>
</body>
</html>
<?php
}
?>