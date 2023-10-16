<?php
require_once "./bdd/DAO.php";
class DirectorController {

    public function listDirectors() {

        $dao = new DAO();
        $sql = 
        "SELECT r.id_realisateur, CONCAT(r.prenom ,' ', r.nom) AS full_name
        FROM realisateur r
        ";

        $realisateurs = $dao->executerRequete($sql);
        require "./view/director/listDirectors.php";
    }



    public function detailsDirector($idDirector) {

        $dao = new DAO();

        $paramsDirector = [
            "idDirector" => $idDirector
        ];

        $sqlDirector = 
        "SELECT 
            r.id_realisateur,
            r.prenom,
            r.nom,
            r.sexe, 
            r.dateDeNaissance, 
            DATE_FORMAT(r.dateDeNaissance, '%d/%m/%Y') AS formatedDateDeNaissance,
            r.dateDeDeces,
            DATE_FORMAT(r.dateDeDeces, '%d/%m/%Y') AS formatedDateDeDeces
        FROM
            realisateur r
        WHERE 
            r.id_realisateur = :idDirector

        ";

        $sqlFilm = 
        "SELECT
            f.id_film,
            f.titre
        FROM
            film f
        WHERE 
            f.realisateur_id = :idDirector
        ";

        $detailsDirector = $dao->executerRequete($sqlDirector, $paramsDirector);

        $films = $dao->executerRequete($sqlFilm, $paramsDirector);

        require "./view/director/detailsDirector.php";
    }
}

?>