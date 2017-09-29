<?php
include_once("header.php");
include_once("BDD.php");
if(isset($_GET['user'])){
    $req = BDD::getInstance()->requete('SELECT livre FROM commandes WHERE idx_client = "'.htmlentities($_GET['user']).'"');
    while($commandes = $req->fetch()) {
        if($commandes['livre'] == 0){
            echo 'Vous avez des commandes en cours, vous ne pouvez donc pas supprimer votre compte.';
            echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
            return;
        }
    }
    $update = BDD::getInstance()->preparation('UPDATE utilisateurs SET ban = 1 WHERE utilisateurs.index = :user_id');
    $tab_update = array(':user_id' => htmlentities($_GET['user']));
    BDD::getInstance()->execution($update, $tab_update);

    echo 'Votre compte a bel et bien été supprimé, mais sachez que nous sommes triste de vous voir partir.. :(';
    if(isset($_SESSION['admin']) && isset($_SESSION['id_user'])){
        if(BDD::getInstance()->requete('SELECT email FROM utilisateurs WHERE utilisateurs.index = '.$_GET['user'])->fetch()[0] == $_SESSION['id_user']){
            session_destroy();
        }
    }
    echo "<meta http-equiv='refresh' content='5;url=index.php'>";
}
?>