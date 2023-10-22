<?php
/*
DAO : Objet d'accès aux données (data access object)
> est un design pattern
la communication entre php et la database se fait en classe DAO

PDO : PHP Data Objects
extension pour me connecter à une BDD
interface permettant d'accéder a une BDD depuis PHP
est une couche abstraite qui intervient entre PHP et les SGBD

query() : exécute la requête présente en paramètre
et return un object PDOStatement

prepare() prépare la requête à exécuter
execute() return le resultat de la requête preparé en incluant les valeurs données pour les :qqch
*/
//permet la comm entre php et la bdd
class DAO{
    private $bdd;

    public function __construct(){
        //n'a qu'une seule variable, qui fait référence à la base données et permet de s'y connecter
        $this->bdd = new \PDO('mysql:host=localhost;dbname=cinema;charset=utf8', 'root', ''); //dbname = le nom de ma database
    }
    //getter
    function getBDD(){
        return $this->bdd;
    }
    //method - permet d'exectuter une requête SQL, params(qui est optionnel) permet de de donner un/des paramètres (des valeurs) en plus à la requête (un id par ex)
    public function executerRequete($sql, $params = NULL){
        if ($params == NULL){
            // si null fait un simple query() qui execute la requête
            $resultat = $this->bdd->query($sql);
        }else{
            // si non null, la requête sql sera préparé, bindValue()* et bindParam()** peuvent être placé après prepare(et avant execute())
            $resultat = $this->bdd->prepare($sql);
            // puis quand il sera executé il prendra les valeurs de params et return le resultat
            $resultat->execute($params);
        }
        return $resultat;

    }
}
/*
bindValue() permet de donner la valeur d'un :qqch ou ?, grace à la valeur de la variable utilisé
bindParam() idem, sauf que bindParam() peut changer de valeur après le prepare() et le nouvel valeur sera prise en compte (avec Bvalue() la valeur reste la même après le prepare()) 
*/

?>