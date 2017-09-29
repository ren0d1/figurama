<?php
include_once("BDD.php");
if(isset($_GET['user']) && isset($_GET['date'])){
    try{
        $req = BDD::getInstance()->requete('SELECT idx_produit, quantite, envoye FROM commandes WHERE idx_client = "'.htmlentities($_GET['user']).'" AND date_achat = "'.$_GET['date'].'"');
        while($commandes = $req->fetch()) {
            if($commandes['envoye'] != 0){
                echo 'Il est trop tard pour annuler cette commande.';
                echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
                return;
            }
            $update = BDD::getInstance()->preparation('UPDATE articles SET stock = stock + :stock WHERE articles.index = :article');
            $tab_update = array(':stock' => $commandes['quantite'], ":article" => $commandes['idx_produit']);
            BDD::getInstance()->execution($update, $tab_update);
        }

        $delete = BDD::getInstance()->preparation('DELETE FROM commandes WHERE idx_client = :user_id AND date_achat = :date_achat');
        $tab_delete = array(':user_id' => htmlentities($_GET['user']), ":date_achat" => $_GET['date']);
        BDD::getInstance()->execution($delete, $tab_delete);

        echo 'La commande a bel et bien été annulée.';
        echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
    }catch(Exception $e){
        echo 'Il est impossible d\'annuler cette commande.';
        echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
    }
}else{
    echo 'Il est impossible d\'annuler cette commande.';
    echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
}
?>