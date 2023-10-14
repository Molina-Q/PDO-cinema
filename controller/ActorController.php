<?php
require_once "./bdd/DAO.php";

class ActorController {

    public function findAll() {

        $dao = new DAO();
        $sql = 
        "SELECT  a.id_acteur, CONCAT(a.prenom ,' ', a.nom) AS full_name, a.sexe
        FROM acteur a"
        ;

        $acteurs = $dao->executerRequete($sql);
        require "./view/actor/listActors.php";
    }

    public function infosActeur($GET_id) {

        $dao = new DAO();
        $sql = 
        "SELECT CONCAT(a.prenom, ' ', a.nom) AS full_name, a.sexe, DATE_FORMAT(a.dateDeNaissance, '%d/%m/%Y') AS dateNaissance, a.dateDeNaissance
        FROM acteur a
        WHERE a.id_acteur = $GET_id";

        $detailsActeur = $dao->executerRequete($sql);
        require "./view/actor/detailsActor.php";
    }

}
?>