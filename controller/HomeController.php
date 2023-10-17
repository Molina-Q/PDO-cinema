<?php
require_once "./bdd/DAO.php";

class HomeController{

    public function homePage() {

        $dao = new DAO();
        $sqlFilms = 
        "SELECT 
            COUNT(f.id_film) AS nb_films
        FROM 
            film f
        ";

        $sqlDirectors = 
        "SELECT 
            COUNT(r.id_realisateur) AS nb_realisateurs
        FROM 
            realisateur r
        ";

        $sqlActors = 
        "SELECT 
            COUNT(a.id_acteur) AS nb_acteurs
        FROM 
            acteur a
        ";

        $films = $dao->executerRequete($sqlFilms);  
        $directors = $dao->executerRequete($sqlDirectors);  
        $actors = $dao->executerRequete($sqlActors);  
        require "./view/home.php";
    }



}

?>