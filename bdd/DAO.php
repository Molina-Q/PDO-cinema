<!--
DAO : Objet d'accès aux données (data access object)
> est un design pattern
la communication entre php et la database se fait en classe DAO

PDO : PHP Data Objects
interface permettant d'accéder a une BDD depuis PHP
est une couche abstraite qui intervient entre PHP et les SGBD
-->

<?php

class DAO{
    private $bdd;

    public function __construct(){
        $this->bdd = new PDO('mysql:host=localhost;dbname=cinema;charset=utf8', 'root', ''); //dbname = le nom de ma database
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