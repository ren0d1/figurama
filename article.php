<?php

class article
{
    private $id;
    private $name;
    private $quantity;
    private $prix;

    function __construct($id)
    {
        include_once "BDD.php";

        $this->id = $id;
        $req = BDD::getInstance()->requete('SELECT nom, prix, stock FROM articles WHERE articles.index = '.$id);
        $donnees = $req->fetch();
        $this->name = $donnees['nom'];
        $this->prix = $donnees['prix'];
        if($donnees['stock'] <= 0) {
            $this->quantity = 0;
        }else{
            $this->quantity = 1;
        }
    }

    public function addItem()
    {
        include_once "BDD.php";

        $req = BDD::getInstance()->requete('SELECT stock FROM articles WHERE articles.index = '.$this->id);
        $donnees = $req->fetch();

        if($donnees['stock'] <= 0){
            $this->quantity = 0;
            return false;
        }else if($this->quantity >= $donnees['stock']) {
            $this->quantity = $donnees['stock'];
            return false;
        }else{
            $this->quantity++;
            return true;
        }
    }

    public function removeItem()
    {
        $this->quantity--;
    }

    public function getItemCount()
    {
        return $this->quantity;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function getNom(){
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }
}