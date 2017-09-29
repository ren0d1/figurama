<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama Admin</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
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
?>
<body>
<section class="header">
    <h1>
        <a href="index.php">
            <img src="img/logo.png" class="logo" alt="figurama logo">
        </a>
    </h1>
    <nav class="menu">
        <a href="index.php" class="menu_item">
            Accueil
        </a>
        <a href="profil.php" class="menu_item">
            Profil
        </a>
        <a href="admin.php" class="menu_item active">
            Admin
        </a>
        <a href="logout.php" class="menu_item">
            Deconnexion
        </a>
    </nav>
    <div class="cart">
        <a href="createItem.php" class="cart_link">
            <img src="img/create-item.png" height="74" alt="create item">
        </a>
    </div>
</section>
<section>
    <form id="filtres" action="admin.php" method="POST">
        <label for="afficher">
            Afficher:
            <select name="afficher" id="afficher" form="filtres" onchange="sendChange();">
                <?php
                if(isset($_POST['afficher'])){
                    if($_POST['afficher'] == 2){
                        $_SESSION['filtres_admin']['afficher'] = 2;
                    }else{
                        $_SESSION['filtres_admin']['afficher'] = 1;
                    }
                }

                if(isset($_SESSION['filtres_admin']['afficher']))
                {
                    switch ($_SESSION['filtres_admin']['afficher']) {
                        case 1:
                            echo '<option value="1">Utilisateurs</option>
                                  <option value="2">Commandes</option>';
                            break;

                        case 2:
                            echo '<option value="1">Utilisateurs</option>
                                  <option value="2" selected>Commandes</option>';
                            break;

                        default:
                            echo '<option value="1">Utilisateurs</option>
                                  <option value="2">Commandes</option>';
                    }
                }else{
                    echo '<option value="1">Utilisateurs</option>
                          <option value="2">Commandes</option>';
                }
                ?>
            </select>
        </label>
        <?php
        if(isset($_SESSION['filtres_admin']['afficher'])) {
            if($_SESSION['filtres_admin']['afficher'] == 2){
                echo '<label for="users_filter">Utilisateurs: <select name="users_filter" id="users_filter" onchange="editDisplay()">';
                echo '<option id="Tout">Tout</option>';
                $users_select = BDD::getInstance()->requete('SELECT nom, prenom FROM utilisateurs');
                while($users = $users_select->fetch()){
                    echo '<option id="'.$users['nom'].' '.$users['prenom'].'">'.$users['nom'].' '.$users['prenom'].'</option>';
                }
                echo'</select></label>';
                echo '<label for="envoi_filter">Afficher les commandes envoyées: <input type="checkbox" name="envoi_filter" id="envoi_filter" onchange="editDisplay()"></label>';
                echo '<label for="livre_filter">Afficher les commandes livrées: <input type="checkbox" name="livre_filter" id="livre_filter" onchange="editDisplay()"></label>';
            }else{
                echo '<label for="ban_filter">Afficher les comptes bannis: <input type="checkbox" name="ban_filter" id="ban_filter" onchange="editDisplay()"></label>';
            }
        }else{
            echo '<label for="ban_filter">Afficher les comptes bannis: <input type="checkbox" name="ban_filter" id="ban_filter" onchange="editDisplay()"></label>';
        }
        ?>
    </form>
</section>
<section>
    <?php
    if(isset($_SESSION['filtres_admin']['afficher'])) {
        if($_SESSION['filtres_admin']['afficher'] == 2){
            $req = BDD::getInstance()->requete('SELECT * FROM commandes ORDER BY date_achat');
            $date_commande = null;
            while ($commandes = $req->fetch()) {
                if($date_commande != $commandes['date_achat']){
                    if ($date_commande != null) {
                        echo '</figure>';
                    }
                    $nom_user = BDD::getInstance()->requete('SELECT nom, prenom FROM utilisateurs WHERE utilisateurs.index = '.$commandes['idx_client'])->fetch();
                    echo '<figure class="commandes_container" data-envoi="'.$commandes['envoye'].'" data-livre="'.$commandes['livre'].'" data-user="'.$nom_user['nom'].' '.$nom_user['prenom'].'"><div class="commande"><h2>'.substr($commandes['date_achat'], 0, strrpos($commandes['date_achat'], ".")).'</h2><h2>'.$nom_user['nom'].' '.$nom_user['prenom'].'</h2></div>';
                }
                $nom_article = BDD::getInstance()->requete('SELECT nom FROM articles WHERE articles.index = '.$commandes['idx_produit'])->fetch()[0];
                echo '<div class="commande"><h3>'.$nom_article.'</h3><h3>'.$commandes['quantite'].'</h3></div>';
                $date_commande = $commandes['date_achat'];
            }
        }else{
            $req = BDD::getInstance()->requete('SELECT * FROM utilisateurs');
            while ($content = $req->fetch()) {
                echo '<figure class="user_container" data-ban="'.$content['ban'].'"><h2>' . $content['nom'] . ' ' . $content['prenom'] . ' habitant ' . $content['adresse'] . ' à ' . $content['CP'] . ' ' . $content['ville'] . '</h2><div class="edit"><a href="accountEdit.php?user='.$content['email'].'"><img src="img/account-edit.png" alt="account edit" height="30"></a><a href="accountDelete.php?user='.$content['index'].'"><img src="img/account-remove.png" alt="account remove" height="30"></a></div></figure>';
            }
        }
    }else{
        $req = BDD::getInstance()->requete('SELECT * FROM utilisateurs');
        while ($content = $req->fetch()) {
            echo '<figure class="user_container" data-ban="'.$content['ban'].'"><h2>' . $content['nom'] . ' ' . $content['prenom'] . ' habitant ' . $content['adresse'] . ' à ' . $content['CP'] . ' ' . $content['ville'] . '</h2><div class="edit"><a href="accountEdit.php?user='.$content['email'].'"><img src="img/account-edit.png" alt="account edit" height="30"></a><a href="accountDelete.php?user='.$content['index'].'"><img src="img/account-remove.png" alt="account remove" height="30"></a></div></figure>';
        }
    }
    ?>
</section>
<script>
    function sendChange()
    {
        document.getElementById('filtres').submit();
    }

    <?php
    if(isset($_SESSION['filtres_admin']['afficher'])) {
        if($_SESSION['filtres_admin']['afficher'] == 2){
        ?>
        function editDisplay(){
            var checkbox_envoi = document.getElementById("envoi_filter");
            var checkbox_livre = document.getElementById("livre_filter");

            if(document.getElementById("Tout").selected){
                document.querySelectorAll(".commandes_container").forEach(function(current){
                    current.style.display = "flex";
                });

                if(checkbox_livre.checked){
                    document.querySelectorAll('[data-livre="1"]').forEach(function(current){
                        current.style.display = "flex";
                    });
                    if(checkbox_envoi.checked){
                        document.querySelectorAll('[data-envoi="1"]').forEach(function(current){
                            current.style.display = "flex";
                        });
                        document.querySelectorAll('[data-envoi="0"]').forEach(function(current){
                            current.style.display = "none";
                        });
                    }else{
                        document.querySelectorAll('[data-livre="0"]').forEach(function(current){
                            current.style.display = "none";
                        });
                    }
                }else{
                    document.querySelectorAll('[data-livre="1"]').forEach(function(current){
                        current.style.display = "none";
                    });
                    if(checkbox_envoi.checked){
                        document.querySelectorAll('[data-envoi="1"]').forEach(function(current){
                            current.style.display = "flex";
                        });
                        document.querySelectorAll('[data-envoi="0"]').forEach(function(current){
                            current.style.display = "none";
                        });
                    }else{
                        document.querySelectorAll('[data-envoi="1"]').forEach(function(current){
                            current.style.display = "none";
                        });
                        document.querySelectorAll('[data-envoi="0"]').forEach(function(current){
                            current.style.display = "flex";
                        });
                    }
                }
            }else{
                document.getElementById("users_filter").childNodes.forEach(function(current){
                    var queriedUser = document.querySelector("[data-user=" + CSS.escape(current.id) + "]");
                    if(queriedUser != null){
                        if(current.selected){
                            document.querySelectorAll("[data-user=" + CSS.escape(current.id) + "]").forEach(function(commande){
                                commande.style.display = "flex";
                            });

                            if(checkbox_livre.checked){
                                document.querySelectorAll('[data-livre="1"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                    commande.style.display = "flex";
                                });
                                if(checkbox_envoi.checked){
                                    document.querySelectorAll('[data-envoi="1"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "flex";
                                    });
                                    document.querySelectorAll('[data-envoi="0"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "none";
                                    });
                                }else{
                                    document.querySelectorAll('[data-livre="0"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "none";
                                    });
                                    document.querySelectorAll('[data-envoi="0"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "none";
                                    });
                                }
                            }else{
                                document.querySelectorAll('[data-livre="0"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                    commande.style.display = "none";
                                });
                                if(checkbox_envoi.checked){
                                    document.querySelectorAll('[data-envoi="1"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "flex";
                                    });
                                    document.querySelectorAll('[data-envoi="0"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "none";
                                    });
                                }else{
                                    document.querySelectorAll('[data-envoi="1"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "none";
                                    });
                                    document.querySelectorAll('[data-envoi="0"][data-user=' + CSS.escape(current.id) + ']').forEach(function(commande) {
                                        commande.style.display = "flex";
                                    });
                                }
                            }
                        }else{
                            document.querySelectorAll("[data-user=" + CSS.escape(current.id) + "]").forEach(function(commande){
                                commande.style.display = "none";
                            });
                        }
                    }
                });
            }
        }

        document.body.onload = editDisplay();
        <?php
        }else{
        ?>
        function editDisplay(){
            var checkbox = document.getElementById("ban_filter");
            if(checkbox.checked){
                document.querySelectorAll('[data-ban="1"]').forEach(function(current){
                    current.style.display = "flex";
                });
            }else{
                document.querySelectorAll('[data-ban="1"]').forEach(function(current){
                    current.style.display = "none";
                });
            }
        }

        document.body.onload = editDisplay();
        <?php
        }
    }else{
        ?>
    function editDisplay() {
        var checkbox = document.getElementById("ban_filter");
        if (checkbox.checked) {
            document.querySelectorAll('[data-ban="1"]').forEach(function (current) {
                current.style.display = "flex";
            });
        } else {
            document.querySelectorAll('[data-ban="1"]').forEach(function (current) {
                current.style.display = "none";
            });
        }
    }

    document.body.onload = editDisplay();
    <?php
    }
    ?>
</script>
</body>
</html>
<?php
}else{
?>
<body>
</body>
</html>
<?php
}
?>