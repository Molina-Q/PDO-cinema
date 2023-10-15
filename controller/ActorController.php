<?php
require_once "./bdd/DAO.php";

class ActorController {

    public function findAll() {

        $dao = new DAO();
        $sql = 
        "SELECT  a.id_acteur, CONCAT(a.prenom ,' ', a.nom) AS full_name, a.sexe
        FROM acteur a
        ";

        $acteurs = $dao->executerRequete($sql);
        require "./view/actor/listActors.php";
    }

    public function infosActeur($GET_id) {

        $dao = new DAO();
        $sql = 
        "SELECT CONCAT(a.prenom, ' ', a.nom) AS full_name, a.sexe, DATE_FORMAT(a.dateDeNaissance, '%d/%m/%Y') AS dateNaissance, a.dateDeNaissance, group_CONCAT(' ',ro.libelle,'(',f.titre,')') AS roles, GROUP_CONCAT(f.titre) AS titreFilm
        FROM acteur a
        INNER JOIN casting c ON a.id_acteur = c.acteur_id
        INNER JOIN role ro ON c.role_id = ro.id_role
        INNER JOIN film f ON c.film_id = f.id_film
        WHERE a.id_acteur = $GET_id
        GROUP BY a.id_acteur
        ";
        

        $detailsActeur = $dao->executerRequete($sql);
        require "./view/actor/detailsActor.php";
    }

}
?>