<?php
require_once "./bdd/DAO.php";

class HomeController{
    // homePage affiche uniquement le compteur des films, realisateurs et acteurs présents dans la bdd
    public function homePage() {

        $dao = new DAO();
        // 3 requêtes pour ne pas faire de doublons lors de la requçtes sql (les real ont fait plusieurs films, les acteurs ont joués dans plusieurs film, etc...) 
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