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
}

?>