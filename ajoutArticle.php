<?php
    include("header.php");

    if(!isset($_SESSION['articles']))
    {
        $_SESSION['articles'] = array();
        $_SESSION['articles'][$_GET['id']] = new article($_GET['id']);
        if($_SESSION['articles'][$_GET['id']]->getItemCount() <= 0)
        {
            unset($_SESSION['articles'][$_GET['id']]);
            echo "Article non disponible actuellement.";
        }
        echo 'Panier créé.<br>';
        //print_r($_SESSION['articles']);
    }else{
        if(!isset($_SESSION['articles'][$_GET['id']]))
        {
            $_SESSION['articles'][$_GET['id']] = new article($_GET['id']);
            if($_SESSION['articles'][$_GET['id']]->getItemCount() <= 0)
            {
                unset($_SESSION['articles'][$_GET['id']]);
                echo "Article non disponible actuellement.";
            }
            echo 'Panier créé mais article nouveau.<br>';
            //print_r($_SESSION['articles']);
        }else{
            if($_SESSION['articles'][$_GET['id']]->addItem())
            {
                echo "Le total d'articles pour cet élément a bien été changé.";
            }else{
                if($_SESSION['articles'][$_GET['id']]->getItemCount() > 0)
                {
                    echo "Il n'y a plus assez de stock pour satisfaire votre demande. Nous avons donc adapté votre panier en conséquence.";
                }else{
                    unset($_SESSION['articles'][$_GET['id']]);
                    echo "Malheureusement les stocks sont épuisés. Nous avons donc enlevé cet article de votre panier.";
                }
            }
            echo 'Panier créé et article existant<br>';
            //print_r($_SESSION['articles']);
        }
    }

    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
?>