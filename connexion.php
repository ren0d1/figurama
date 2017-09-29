<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Figurama Connexion</title>
    <link rel="stylesheet" type="text/css" href="css/connexion.css">
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
    if(isset($_POST['email']) && isset($_POST['mdp']))
    {
        include("BDD.php");
        try{
            $req = BDD::getInstance()->preparation('SELECT mdp, ban, admin, reset_mdp FROM utilisateurs WHERE email = :email');
            BDD::getInstance()->execution($req, array(':email' => htmlentities($_POST['email'])));
            $donnees = $req->fetch();
            if(!empty($donnees))
            {
                if (\Sodium\crypto_pwhash_str_verify($donnees['mdp'], $_POST['mdp'])) {
                    // recommended: wipe the plaintext password from memory
                    unset($_POST['mdp']);
                    if($donnees['ban'] == 0){
                        if($donnees['reset_mdp'] != 0){
                            BDD::getInstance()->requete('UPDATE utilisateurs SET reset_mdp = 0 WHERE email = "'.htmlentities($_POST["email"]).'"');
                        }
                        if($donnees['admin'] != 0){
                            $_SESSION['admin'] = 1;
                        }
                        $_SESSION['connecte'] = 1;
                        $_SESSION['id_user'] = $_POST['email'];
                        echo 'connecte';
                    }else{
                        echo 'Impossible de vous connecter. Vous avez été banni de figurama.';
                    }
                } else {
                    // recommended: wipe the plaintext password from memory
                    \Sodium\memzero($_POST['mdp']);
                    $_SESSION['connecte'] = 0;
                    ?>
                    <div class="top">
                        <a href="#">
                            <img src="img/logo.png" class="logo" alt="figurama logo">
                        </a>
                        <form method="POST" action="connexion.php">
                            <label for="email">
                                Email:
                                <input type="email" name="email" id="email" value="<?php echo $_POST['email'] ?>">
                            </label>
                            <label for="mdp">
                                Mot de passe:
                                <input type="password" name="mdp" id="mdp" style="border: 2px solid red;">
                            </label>
                            <button type="submit" class="submit">
                                Connexion
                            </button>
                        </form>
                    </div>
                    <div class="bottom">
                        <a href="inscription.php">Pas encore de compte? Enregistre-toi!</a>
                        <a href="resetMdp.php">Mot de passe oublié?</a>
                    </div>
                    <?php
                }
            }else{
                $_SESSION['connecte'] = 0;
                ?>
                <div class="top">
                    <a href="#">
                        <img src="img/logo.png" class="logo" alt="figurama logo">
                    </a>
                    <form method="POST" action="connexion.php">
                        <label for="email">
                            Email:
                            <input type="email" name="email" id="email" style="border: 2px solid red;">
                        </label>
                        <label for="mdp">
                            Mot de passe:
                            <input type="password" name="mdp" id="mdp" style="border: 2px solid red;">
                        </label>
                        <button type="submit" class="submit">
                            Connexion
                        </button>
                    </form>
                </div>
                <div class="bottom">
                    <a href="inscription.php">Pas encore de compte? Enregistre-toi!</a>
                    <a href="resetMdp.php">Mot de passe oublié?</a>
                </div>
                <?php
            }
        }catch(Exception $e){
            echo 'L\'utilisateur n\'existe pas.';
            echo "<meta http-equiv='refresh' content='0;url=connexion.php'>";
        }
    }else{
        ?>
        <div class="top">
            <a href="#">
                <img src="img/logo.png" class="logo" alt="figurama logo">
            </a>
            <form method="POST" action="connexion.php">
                <label for="email">
                    Email:
                    <input type="email" name="email" id="email">
                </label>
                <label for="mdp">
                    Mot de passe:
                    <input type="password" name="mdp" id="mdp">
                </label>
                <button type="submit" class="submit">
                    Connexion
                </button>
            </form>
        </div>
        <div class="bottom">
            <a href="inscription.php">Pas encore de compte? Enregistre-toi!</a>
            <a href="resetMdp.php">Mot de passe oublié?</a>
        </div>
        <?php
    }
}
?>
</body>
</html>