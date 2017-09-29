<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama</title>
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
    if (isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['ville']) && isset($_POST['CP']) && isset($_POST['adresse']) && isset($_POST['tel'])) {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['nom']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['prenom']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['ville']) && preg_match("/^[0-9]{4}$/", $_POST['CP']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\-,° ]+[0-9]{1,}$/", $_POST['adresse']) && preg_match("/^0[0-9]{8,9}$/", $_POST['tel'])) {
            if(isset($_POST['ban']) && $_POST['ban']== "on"){
                if(isset($_POST['admin']) && $_POST['admin']== "on"){
                    if (strcmp($_POST['email'], $_GET['user']) != 0 && BDD::getInstance()->requete('SELECT count(*) FROM utilisateurs WHERE email = "' . $_POST['email'] . '"')->fetch()[0] == 0) {
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :newEmail, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':newEmail' => $_POST['email'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => $_POST['ban'], ':admin' => $_POST['admin'], ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else if(strcmp($_POST['email'], $_GET['user']) == 0){
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => $_POST['ban'], ':admin' => $_POST['admin'], ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else{
                        echo 'La nouvelle adresse email est déjà existante.';
                        echo '<meta http-equiv="refresh" content="0;url=accountEdit.php?user='.$_GET['user'].'"">';
                    }
                }else{
                    if (strcmp($_POST['email'], $_GET['user']) != 0 && BDD::getInstance()->requete('SELECT count(*) FROM utilisateurs WHERE email = "' . $_POST['email'] . '"')->fetch()[0] == 0) {
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :newEmail, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':newEmail' => $_POST['email'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => $_POST['ban'], ':admin' => false, ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else if(strcmp($_POST['email'], $_GET['user']) == 0){
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => $_POST['ban'], ':admin' => false, ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else{
                        echo 'La nouvelle adresse email est déjà existante.';
                        echo '<meta http-equiv="refresh" content="0;url=accountEdit.php?user='.$_GET['user'].'"">';
                    }
                }
            }else{
                if(isset($_POST['admin']) && $_POST['admin']== "on"){
                    if (strcmp($_POST['email'], $_GET['user']) != 0 && BDD::getInstance()->requete('SELECT count(*) FROM utilisateurs WHERE email = "' . $_POST['email'] . '"')->fetch()[0] == 0) {
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :newEmail, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':newEmail' => $_POST['email'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => false, ':admin' => $_POST['admin'], ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else if(strcmp($_POST['email'], $_GET['user']) == 0){
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => false, ':admin' => $_POST['admin'], ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else{
                        echo 'La nouvelle adresse email est déjà existante.';
                        echo '<meta http-equiv="refresh" content="0;url=accountEdit.php?user='.$_GET['user'].'"">';
                    }
                }else{
                    if (strcmp($_POST['email'], $_GET['user']) != 0 && BDD::getInstance()->requete('SELECT count(*) FROM utilisateurs WHERE email = "' . $_POST['email'] . '"')->fetch()[0] == 0) {
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :newEmail, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':newEmail' => $_POST['email'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => false, ':admin' => false, ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else if(strcmp($_POST['email'], $_GET['user']) == 0){
                        $req = BDD::getInstance()->preparation('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, ville = :ville, CP = :CP, adresse = :adresse, tel = :tel, ban = :ban, admin =:admin WHERE email = :email');
                        BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel'], ':ban' => false, ':admin' => false, ':email' => $_GET['user']));
                        echo 'Les changements on été correctement effectués.';
                        echo '<meta http-equiv="refresh" content="0;url=admin.php">';
                    }else{
                        echo 'La nouvelle adresse email est déjà existante.';
                        echo '<meta http-equiv="refresh" content="0;url=accountEdit.php?user='.$_GET['user'].'"">';
                    }
                }
            }
        } else {
            echo 'Les informations fournies ne sont pas correctes';
            echo '<meta http-equiv="refresh" content="0;url=accountEdit.php?user='.$_GET['user'].'"">';
        }
    } else {
        if (isset($_GET['user']) && isset($_SESSION['admin'])) {
            if ($_SESSION['admin'] != 0) {
                $req = BDD::getInstance()->preparation('SELECT utilisateurs.`index`, prenom, nom, email, ville, CP, adresse, tel, ban, admin FROM utilisateurs WHERE email = :email');
                BDD::getInstance()->execution($req, array(':email' => $_GET['user']));
                $donnees = $req->fetch();
                if (!empty($donnees)) {
                    ?>
                    <body style="background-color: #DB7093">
                    <form method="POST" action="accountEdit.php?user=<?=$_GET['user']?>" onsubmit="return validateChoiceModif()">
                        <label for="email">
                            Email:
                            <input type="email" name="email" id="email" value="<?= $donnees['email'] ?>" onchange="validateEmail(this)">
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
                        <?php
                            if($donnees['ban']){
                                echo '<label for="ban">
                                        Banni:
                                        <input type="checkbox" name="ban" id="ban" value="on" checked>
                                      </label>';
                            }else{
                                echo '<label for="ban">
                                        Banni:
                                        <input type="checkbox" name="ban" id="ban" value="on">
                                      </label>';
                            }

                            if($donnees['admin']){
                                echo '<label for="admin">
                                            Admin:
                                            <input type="checkbox" name="admin" id="admin" value="on" checked>
                                          </label>';
                            }else{
                                echo '<label for="admin">
                                            Admin:
                                            <input type="checkbox" name="admin" value="on" id="admin">
                                          </label>';
                            }
                        ?>
                        <input type="submit" class="submit" value="Modifier">
                    </form>
                    <hr>
                    <a href="changeMdp.php?user=<?=$donnees['index']?>"><h2 style="text-align: center">Réinitialiser son mot de passe.</h2></a>
                    <script>
                        function validateEmail(email) {
                            var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            if (regex.test(email.value)) {
                                email.style.color = "blue";
                                return true;
                            } else {
                                email.style.color = "red";
                            }
                            return false;
                        }

                        function validateText(text) {
                            var regex = /^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/;
                            if (regex.test(text.value)) {
                                text.style.color = "blue";
                                return true;
                            } else {
                                text.style.color = "red";
                            }
                            return false;
                        }

                        function validateCP(CP) {
                            var regex = /^[0-9]+$/;
                            if (CP.value.length == 4) {
                                if (regex.test(CP.value)) {
                                    CP.style.color = "blue";
                                    return true;
                                } else {
                                    CP.style.color = "red";
                                }
                            } else {
                                CP.style.color = "red";
                                alert("Un code postal belge ne peut être composé que de 4 chiffres");
                            }
                            return false;
                        }

                        function validateAdresse(adresse) {
                            var regex = /^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\-,° ]+[0-9]{1,}$/;
                            if (regex.test(adresse.value)) {
                                adresse.style.color = "blue";
                                return true;
                            } else {
                                adresse.style.color = "red";
                            }
                            return false;
                        }

                        function validateTel(tel) {
                            var regex = /^0[0-9]+$/;
                            if (tel.value.length > 8 && tel.value.length < 11) {
                                if (regex.test(tel.value)) {
                                    tel.style.color = "blue";
                                    return true;
                                } else {
                                    tel.style.color = "red";
                                }
                            } else {
                                tel.style.color = "red";
                                alert('Un numéro belge est composé de 9 ou 10 chiffres.');
                            }
                            return false;
                        }

                        function validateForm() {
                            if (validateEmail(document.getElementById('email')) && validateText(document.getElementById('nom')) && validateText(document.getElementById('prenom')) && validateText(document.getElementById('ville')) && validateCP(document.getElementById('CP')) && validateAdresse(document.getElementById('adresse')) && validateTel(document.getElementById('tel'))) {
                                return true;
                            } else {
                                alert('Le formulaire n\'est pas correctement complété. L\'inscription n\'a donc pas été effectuée');
                                return false;
                            }
                        }

                        function validateChoiceModif() {
                            if (confirm("Etes-vous certain de vouloir mettre a jour les informations?") == true) {
                                return validateForm();
                            } else {
                                return false;
                            }
                        }
                    </script>
                    </body>
                    <?php
                }
            }else{
                echo 'Vous n\'avez pas accès à cette page';
                //echo "<meta http-equiv='refresh' content='3;url=index.php'>";
            }
        }else{
            echo 'Paramètres incorrects';
            //echo "<meta http-equiv='refresh' content='3;url=index.php'>";
        }
    }
}
?>
</html>