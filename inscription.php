<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama Inscription</title>
    <link rel="stylesheet" type="text/css" href="css/inscription.css">
</head>
<body>
<?php
include("header.php");

if(!isset($_SESSION['connecte']))
{
    $_SESSION['connecte'] = 0;
}
if($_SESSION['connecte'] > 0)
{
    echo 'Vous etes logue';
}else{

    if(isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['mdp1']) && isset($_POST['mdp2']) && isset($_POST['ville']) && isset($_POST['CP']) && isset($_POST['adresse']) && isset($_POST['tel']) && !empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2']) && !empty($_POST['ville']) && !empty($_POST['CP']) && !empty($_POST['adresse']) && !empty($_POST['tel']))
    {
        include("BDD.php");
        try {
            if ($_POST['mdp1'] == $_POST['mdp2']) {
                $hash_pwd = \Sodium\crypto_pwhash_str(
                    $_POST['mdp1'],
                    \Sodium\CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                    \Sodium\CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
                );

                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['nom']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['prenom']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\- ]+$/", $_POST['ville']) && preg_match("/^[0-9]{4}$/", $_POST['CP']) && preg_match("/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\-,° ]+[0-9]{1,}$/", $_POST['adresse']) && preg_match("/^0[0-9]{8,9}$/", $_POST['tel'])) {
                    $req = BDD::getInstance()->preparation('INSERT INTO utilisateurs (prenom, nom, email, mdp, ville, CP, adresse, tel) VALUE (:prenom, :nom, :email, :mdp, :ville, :CP, :adresse, :tel)');
                    BDD::getInstance()->execution($req, array(':prenom' => $_POST['prenom'], ':nom' => $_POST['nom'], ':email' => $_POST['email'], ':mdp' => $hash_pwd, ':ville' => $_POST['ville'], ':CP' => $_POST['CP'], ':adresse' => $_POST['adresse'], ':tel' => $_POST['tel']));

                    include_once("mailer.php");
                    mailer::getInstance()->setSubject('Bienvenue sur figurama');
                    mailer::getInstance()->setBody('<h1>Bienvenue ' . $_POST['prenom'] . '!</h1><br><h3>Voici l\'adresse email utilisée pour le site figurama:</h3>' . $_POST['email'] . '<br><h3>A bientôt sur Figurama!</h3>');
                    mailer::getInstance()->setDestinataire($_POST['email']);

                    if (!mailer::getInstance()->send()) {
                        echo 'Le message n\'a pas pu être envoyé.';
                    } else {
                        echo 'Le message a été correctement envoyé.';
                    }
                } else {
                    echo 'Les informations fournies ne sont pas correctes';
                    echo "<meta http-equiv='refresh' content='0;url=inscription.php'>";
                }
            }
        } catch (Exception $e) {
            echo 'L\'utilisateur existe déjà.';
            echo "<meta http-equiv='refresh' content='0;url=profil.php'>";
        }
    }else{
    ?>
    <form method="POST" action="inscription.php" onsubmit="return validateForm()">
        <label for="email">
            Email:
            <input type="email" name="email" id="email" onchange="validateEmail(this)">
        </label>
        <label for="mdp1">
            Nouveau Mdp:
            <input type="password" name="mdp1" id="mdp1" onchange="validatePwd(this)">
        </label>
        <label for="mdp2">
            Confirmation Mdp:
            <input type="password" name="mdp2" id="mdp2" onchange="validatePwd(this)">
        </label>
        <label for="nom">
            Nom:
            <input type="text" name="nom" id="nom" onchange="validateText(this)">
        </label>
        <label for="prenom">
            Prénom:
            <input type="text" name="prenom" id="prenom" onchange="validateText(this)">
        </label>
        <label for="ville">
            Ville:
            <input type="text" name="ville" id="ville" onchange="validateText(this)">
        </label>
        <label for="CP">
            CP:
            <input type="text" name="CP" id="CP" onchange="validateCP(this)">
        </label>
        <label for="adresse">
            Adresse:
            <input type="text" name="adresse" id="adresse" onchange="validateAdresse(this)">
        </label>
        <label for="tel">
            Tel:
            <input type="tel" name="tel" id="tel" onchange="validateTel(this)">
        </label>
        <input type="submit" class="submit" value="Inscription">
    </form>
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
    </script>
    <?php
    }
}
?>
</body>
</html>
