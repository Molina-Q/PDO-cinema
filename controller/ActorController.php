<?php
require_once "./bdd/DAO.php";

class ActorController {

    public function listActors() {

        $dao = new DAO();
        $sql = 
        "SELECT a.id_acteur, CONCAT(a.prenom ,' ', a.nom) AS full_name
        FROM acteur a
        ";

        $acteurs = $dao->executerRequete($sql);
        require "./view/actor/listActors.php";
    }

    public function detailsActor($idActor) {

        $dao = new DAO();

        $paramsActeur = [
            "idActor" => $idActor
        ];

        $sqlActeur = 
        "SELECT 
            a.id_acteur,
            a.prenom,
            a.nom, 
            a.sexe, 
            a.dateDeNaissance, 
            DATE_FORMAT(a.dateDeNaissance, '%d/%m/%Y') AS formatedDateDeNaissance,
            a.dateDeDeces,
            DATE_FORMAT(a.dateDeDeces, '%d/%m/%Y') AS formatedDateDeDeces
        FROM acteur a
        WHERE a.id_acteur = :idActor
        GROUP BY a.id_acteur
        ";

        $sqlCasting =  
        "SELECT 
            ro.libelle,
            c.film_id,
            c.role_id,
            f.titre

        FROM casting c
        INNER JOIN role ro ON c.role_id = ro.id_role
        INNER JOIN film f ON c.film_id = f.id_film
        WHERE c.acteur_id = :idActor
        ";

        $detailsActeur = $dao->executerRequete($sqlActeur, $paramsActeur);
        $castings = $dao->executerRequete($sqlCasting, $paramsActeur);
        require "./view/actor/detailsActor.php";
    }

}
?>