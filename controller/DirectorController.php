<?php
require_once "./bdd/DAO.php";
class DirectorController {

    public function findAll() {

        $dao = new DAO();
        $sql = 
        "SELECT  r.id_realisateur, CONCAT(r.prenom ,' ', r.nom) AS full_name
        FROM realisateur r"
        ;

        $realisateurs = $dao->executerRequete($sql);
        require "./view/director/listDirectors.php";
    }

    public function infosRealisateur($GET_id) {

        $dao = new DAO();
        $sql = 
        "SELECT CONCAT(r.prenom, ' ', r.nom) AS full_name, r.sexe, DATE_FORMAT(r.dateDeNaissance, '%d/%m/%Y') AS dateNaissance, r.dateDeNaissance, COUNT(f.id_film) AS movieDirected
        FROM realisateur r
        INNER JOIN film f ON r.id_realisateur = f.realisateur_id
        WHERE r.id_realisateur = $GET_id";

        $detailsRealisateur = $dao->executerRequete($sql);
        require "./view/director/detailsDirector.php";
    }
}

?>