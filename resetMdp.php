<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama</title>
</head>
<body>
<?php
    include_once('BDD.php');
    if(isset($_POST['email']) && BDD::getInstance()->requete('SELECT count(*) FROM utilisateurs WHERE email = "'.htmlentities($_POST["email"]).'"')->fetch()[0] > 0 && $_POST['email'] != null){
        $event_name = "USER:".str_replace("@", "AT", $_POST['email']);
        try{
            BDD::getInstance()->requete('UPDATE utilisateurs SET reset_mdp = 1 WHERE email = "'.htmlentities($_POST["email"]).'"');
            BDD::getInstance()->requete("DROP EVENT IF EXISTS `php_projet`.`$event_name`;");
            BDD::getInstance()->requete('CREATE EVENT IF NOT EXISTS `php_projet`.`'.$event_name.'` ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 HOUR ON COMPLETION NOT PRESERVE ENABLE DO UPDATE utilisateurs SET reset_mdp = 0 WHERE utilisateurs.email = "'.htmlentities($_POST['email']).'";');

            $req = BDD::getInstance()->requete('SELECT utilisateurs.index FROM utilisateurs WHERE email = "'.htmlentities($_POST["email"]).'"');
            $temp = $req->fetch();

            include_once("mailer.php");
            mailer::getInstance()->setSubject('Figurama: Réinitialisation de mot de passe');
            mailer::getInstance()->setBody('<a href="http://www.figurama.com/changeMdp.php?user='.$temp['index'].'">Choisir un nouveau mot de passe</a>');
            mailer::getInstance()->setDestinataire($_POST['email']);

            if(!mailer::getInstance()->send()) {
                echo 'L\'email n\'a pas pu être envoyé.';
            } else {
                echo 'Un mail pour réinitialiser votre mot de passe a bien été envoyé. Vous avez une heure pour ce faire.';
            }
        } catch(Exception $e){
            echo 'Impossible de trouver un compte associé à cette adresse email';
        }
    }else{
        ?>
    <form method="POST" action="resetMdp.php">
        <label for="email">
        Email:
            <input type="email" name="email" id="email">
        </label>
        <input type="submit" class="submit" value="resetMDP">
    </form>
    <?php
    }
?>
</body>
</html>
