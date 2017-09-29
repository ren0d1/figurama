<?php
    /* A implémenter dans les pages puis rename DB.php */
    class BDD
    {
        //----------------------------------------
        //SINGLETON
        //----------------------------------------
        private static $connect = null;
        private $access_db;

        private function __construct()
        {
            $Host = "localhost";
            $DB_user = "root";
            $DB_pwd = "root";
            $DB = "php_projet";


            //Création d'un lien à la base de données de type PDO
            try{
                $this->access_db = new PDO('mysql:host='.$Host.';dbname='.$DB,$DB_user,$DB_pwd,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $this->access_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(Exception $e){
                die('Erreur : '.$e->getMessage());
            }
        }

        public static function getInstance() {
            if(is_null(self::$connect)) {
                self::$connect = new BDD();
            }
            return self::$connect;
        }

        //----------------------------------------
        //FONCTIONS
        //----------------------------------------

        // Permet d'effectuer une requête SQL. Retourne le résultat (s'il y en a un) de la requête sous forme d'objet
        public function requete($req){
            $query = $this->access_db->query($req);
            return $query;
        }

        // Permet de préparer une requête SQL. Retourne la requête préparée sous forme d'objet
        public function preparation($req){
            $query = $this->access_db->prepare($req);
            return $query;
        }

        // Permet d'exécuter une requête SQL préparée. Retourne le résultat (s'il y en a un) de la requête sous forme d'objet
        public function execution($query, $tab){
            $req = $query->execute($tab);
            return $req;
        }

        // Retourne l'id de la dernière insertion par auto-incrément dans la base de données
        public function dernierIndex(){
            return $this->access_db->lastInsertId();
        }
    }