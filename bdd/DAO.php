<!--
DAO : Objet d'accès aux données (data access object)
> est un design pattern
la communication entre php et la database se fait en classe DAO

PDO : PHP Data Objects
extension pour me connecter à une BDD
interface permettant d'accéder a une BDD depuis PHP
est une couche abstraite qui intervient entre PHP et les SGBD

query() : exécute la requête présente en paramètre (fonctionne pour les single cell results)
et return un object PDOStatement

prepare() prépare la requête à exécuter (fonctionne pour plusieurs cell results)
execute() return le resultat de la requête chaque ligne à besoin d'un paramètre unique (primary key) 
-->

<?php

class DAO{
    private $bdd;

    public function __construct(){
        $this->bdd = new \PDO('mysql:host=localhost;dbname=cinema;charset=utf8', 'root', ''); //dbname = le nom de ma database
    }

    function getBDD(){
        return $this->bdd;
    }

    public function executerRequete($sql, $params = NULL){
        if ($params == NULL){
            $resultat = $this->bdd->query($sql);
        }else{
            $resultat = $this->bdd->prepare($sql);
            $resultat->execute($params);
        }
        return $resultat;

    }
}

//2:35