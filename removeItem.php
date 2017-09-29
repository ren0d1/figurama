<?php
include("header.php");
if(!isset($_SESSION['connecte']))
{
    $_SESSION['connecte'] = 0;
}
if(!isset($_SESSION['admin']))
{
    $_SESSION['admin'] = 0;
}
if($_SESSION['connecte'] != 0 && $_SESSION['admin'] != 0) {
    include("BDD.php");
    if(isset($_GET['item'])){
        try{
            $update = BDD::getInstance()->preparation('DELETE FROM articles WHERE articles.index = :item');
            $tab_update = array(':item' => htmlentities($_GET['item']));
            BDD::getInstance()->execution($update, $tab_update);
            echo 'L\'item a bel et bien été supprimé';
            echo "<meta http-equiv='refresh' content='0;url=index.php'>";
        }catch(Exception $e){
            echo 'L\'article est référencé dans au moins une commande. Suppression annulée.';
            echo "<meta http-equiv='refresh' content='0;url=index.php'>";
        }
    }else{
        echo 'Aucun item n\'a été sélectionné.';
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    }
}else{
    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
?>
