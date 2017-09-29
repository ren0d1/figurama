<?php
    include("header.php");

    if(!isset($_SESSION['articles']))
    {
        echo 'Votre panier est actuellement vide.';
    }else{
        if(!isset($_SESSION['articles'][$_GET['id']]))
        {
            echo "Cet article n'est pas dans votre panier.";
        }else{
            $_SESSION['articles'][$_GET['id']]->removeItem();

            if($_SESSION['articles'][$_GET['id']]->getItemCount() > 0)
            {
                echo 'Article correctement enlevé. Le nouveau nombre de cet article est: '.$_SESSION['articles'][$_GET['id']]->getItemCount().'.';
            }else{
                unset($_SESSION['articles'][$_GET['id']]);
                echo "Article enlevé de votre panier.";
            }
        }
    }

    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
?>