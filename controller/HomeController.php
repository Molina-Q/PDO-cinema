<?php
require_once "./bdd/DAO.php";

class HomeController{

    public function homePage() {

        $dao = new DAO();
        $sql = 
        "SELECT COUNT(DISTINCT f.id_film) AS nb_films, COUNT(DISTINCT a.id_acteur) AS nb_acteurs, COUNT(DISTINCT r.id_realisateur) AS nb_realisateurs
        FROM casting c
        INNER JOIN acteur a ON c.acteur_id = a.id_acteur
        INNER JOIN film f  ON c.film_id = f.id_film
        INNER JOIN realisateur r ON f.realisateur_id = r.id_realisateur
        ";

        $countBDD = $dao->executerRequete($sql);  
        require "./view/home.php";
    }

}

?>