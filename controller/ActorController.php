<?php
require_once "./bdd/DAO.php";

class ActorController {

    public function findAll() {

        $dao = new DAO();
        $sql = 
        "SELECT  a.id_acteur, CONCAT(a.prenom ,' ', a.nom) AS full_name, a.sexe, a.dateDeNaissance 
        FROM acteur a"
        ;

        $acteurs = $dao->executerRequete($sql);
        require "./view/actor/listActors.php";
    }

    public function infosActeur($GET_id) {

        $dao = new DAO();
        $sql = 
        "SELECT f.titre, f.duree, f.dateDeSortie, CONCAT(r.prenom, ' ', r.nom) AS full_name
        FROM casting c
        INNER JOIN acteur a ON c.acteur_id = a.id_acteur
        INNER JOIN role ro ON c.role_id = ro.id_role
        INNER JOIN film f  ON c.film_id = f.id_film
        INNER JOIN realisateur r ON f.realisateur_id = r.id_realisateur
        WHERE f.id_film = $GET_id
        GROUP BY f.id_film";

        $detailsActeur = $dao->executerRequete($sql);
        require "./view/actor/detailsActor.php";
    }

}
?>