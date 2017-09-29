<?php
include("header.php");
include("BDD.php");
if(isset($_SESSION['connecte'])) {
    if($_SESSION['connecte'] != 0 && isset($_SESSION['admin']) && $_SESSION['admin'] == 0){
        echo 'Veuillez vous déconnecter avant de venir sur cette page si vous souhaitez reinitialiser votre mot de passe.';
        echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
    }
}
if(isset($_GET['user'])){
    if(BDD::getInstance()->requete('SELECT count(*) FROM utilisateurs WHERE utilisateurs.`index` = "'.htmlentities($_GET["user"]).'"')->fetch()[0] > 0){
        if(isset($_SESSION['admin'])){
            if($_SESSION['admin'] != 0){
                $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $mdp = '';
                $max = mb_strlen($keyspace, '8bit') - 1;
                for ($i = 0; $i < 16; ++$i) {
                    $mdp .= $keyspace[rand(0, $max)];
                }

                $hash_pwd = \Sodium\crypto_pwhash_str(
                    $mdp,
                    \Sodium\CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                    \Sodium\CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
                );

                $update = BDD::getInstance()->preparation('UPDATE utilisateurs SET mdp = :mdp WHERE utilisateurs.`index` = :user_id');
                $tab = array(':mdp' => $hash_pwd, ":user_id" => htmlentities($_GET["user"]));
                BDD::getInstance()->execution($update, $tab);

                echo 'Le nouveau mot de passe est: '.$mdp;
                echo "<meta http-equiv='refresh' content='5;url=admin.php'>";
            }
        }else{
            $req = BDD::getInstance()->requete('SELECT reset_mdp, email FROM utilisateurs WHERE utilisateurs.`index` = "'.htmlentities($_GET["user"]).'"');
            $user_infos = $req->fetch();
            if(!empty($user_infos)){
                if($user_infos['reset_mdp'] != 0){
                    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $mdp = '';
                    $max = mb_strlen($keyspace, '8bit') - 1;
                    for ($i = 0; $i < 16; ++$i) {
                        $mdp .= $keyspace[rand(0, $max)];
                    }

                    $hash_pwd = \Sodium\crypto_pwhash_str(
                        $mdp,
                        \Sodium\CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                        \Sodium\CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
                    );

                    $update = BDD::getInstance()->preparation('UPDATE utilisateurs SET mdp = :mdp WHERE utilisateurs.`index` = :user_id');
                    $tab = array(':mdp' => $hash_pwd, ":user_id" => htmlentities($_GET["user"]));
                    BDD::getInstance()->execution($update, $tab);

                    include_once("mailer.php");
                    mailer::getInstance()->setSubject('Figurama: Réinitialisation de mot de passe');
                    mailer::getInstance()->setBody('Voici votre mot de passe temporaire: '.$mdp);
                    mailer::getInstance()->setDestinataire($user_infos['email']);

                    if(!mailer::getInstance()->send()) {
                        echo 'L\'email n\'a pas pu être envoyé.';
                    } else {
                        echo 'Un mail contenant votre mot de passe temporaire a bien été envoyé';
                    }
                }else{
                    echo 'Vous n\'avez actuellement aucune demande réinitialisation de mot de passe en cours ou celle-ci a expiré.<br>Si vous le souhaitez, vous pourrez en effectuer une nouvelle après la redirection.';
                    echo "<meta http-equiv='refresh' content='5;url=index.php'>";
                }
            }
        }
    }
}else{
    echo 'Un utilisateur doit être spécifié si vous souhaitez faire une requête de reinitialisation de mot de passe';
    echo "<meta http-equiv='refresh' content='5;url=index.php'>";
}
?>