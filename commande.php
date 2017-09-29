<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama Commande</title>
    <link rel="stylesheet" type="text/css" href="css/panier.css">
</head>
<?php
    include("header.php");
    include_once("BDD.php");
?>
<body>
<?php
    if(!isset($_SESSION['connecte']))
    {
        $_SESSION['connecte'] = 0;
    }
    if($_SESSION['connecte'] == 0) {
        echo 'Vous devez être connecté pour passer commande. Vous allez être redirigé.';
        echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
    }else{
        foreach ($_SESSION['articles'] as $article){
            $req = BDD::getInstance()->requete('SELECT * FROM articles WHERE articles.index = '.$article->getId());
            $content = $req->fetch();

            if($article->getItemCount() > $content['stock']){
                echo 'Il est malheureusement impossible de commander les articles souhaités.<br>Vous allez être redirigés vers votre panier. Ce dernier a été mis à jour!';
                echo "<meta http-equiv='refresh' content='5;url=panier.php'>";
                return;
            }
        }

        try{
            $date_now = date("Y-m-d H:i:s");

            include_once("mailer.php");
            mailer::getInstance()->setSubject('Figurama: Commande du '.$date_now);
            $body = '<a href="http://www.figurama.com/index.php">Connectez-voud pour accéder à l\'historique de vos commandes</a>';
            $total = 0;

            foreach($_SESSION['articles'] as $article){
                $req_user = BDD::getInstance()->requete('SELECT utilisateurs.index FROM utilisateurs WHERE utilisateurs.email = \''.$_SESSION['id_user'].'\'');
                $db_get_user = $req_user->fetch();
                $user_id = $db_get_user['index'];

                $req = BDD::getInstance()->preparation('INSERT INTO commandes (idx_client, idx_produit, quantite, date_achat) VALUES (:id_client, :id_produit, :quantite, :date_achat)');
                $tab = array(':id_client' => $user_id, ':id_produit' => $article->getId(), ':quantite' => $article->getItemCount(), ':date_achat' => $date_now);
                BDD::getInstance()->execution($req, $tab);

                $req = BDD::getInstance()->preparation('UPDATE articles SET stock = stock - :achat WHERE articles.index = :index');
                $tab = array(':achat' => $article->getItemCount(), ':index' => $article->getId());
                BDD::getInstance()->execution($req, $tab);

                $body .= '<br>'.$article->getItemCount().' '.$article->getNom().' pour '.($article->getItemCount()*$article->getPrix()).'€';
                $total += $article->getItemCount()*$article->getPrix();

                $event_name = "Envoi: ".$_SESSION['id_user']." ON ".$date_now;
                BDD::getInstance()->requete("DROP EVENT IF EXISTS `php_projet`.`$event_name`;");
                BDD::getInstance()->requete('CREATE EVENT IF NOT EXISTS `php_projet`.`'.$event_name.'` ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 HOUR ON COMPLETION NOT PRESERVE ENABLE DO UPDATE commandes SET envoye = 1 WHERE commandes.idx_client = "'.$user_id.'" AND commandes.date_achat = "'.$date_now.'";');
                $event_name = "Livraison: ".$_SESSION['id_user']." ON ".$date_now;
                BDD::getInstance()->requete("DROP EVENT IF EXISTS `php_projet`.`$event_name`;");
                BDD::getInstance()->requete('CREATE EVENT IF NOT EXISTS `php_projet`.`'.$event_name.'` ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 2 DAY ON COMPLETION NOT PRESERVE ENABLE DO UPDATE commandes SET livre = 1 WHERE commandes.idx_client = "'.$user_id.'" AND commandes.date_achat = "'.$date_now.'";');
            }

            echo 'Votre commande a bien été enregistrée.<br>';

            $body .= '<br>Total : '.$total;
            mailer::getInstance()->setBody($body);
            mailer::getInstance()->setDestinataire($_SESSION['id_user']);

            if(!mailer::getInstance()->send()) {
                echo 'L\'email n\'a pas pu être envoyé.';
            } else {
                echo 'Un mail contenant le résumé de votre commande a bien été envoyé.';
            }

            unset($_SESSION['articles']);
            echo "<meta http-equiv='refresh' content='5;url=profil.php'>";
        }catch(Exception $e){
            echo 'Impossible d\'effectuer la commande. Votre panier est probablement vide.<br>Vous allez être redirigé afin de pouvoir vérifier.';
            echo "<meta http-equiv='refresh' content='5;url=panier.php'>";
        }
    }
?>
</body>
</html>
