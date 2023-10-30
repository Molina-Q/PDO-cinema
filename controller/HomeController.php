<?php
require_once "./bdd/DAO.php";

class HomeController{
    // homePage affiche uniquement le compteur des films, directors et actors présents dans la bdd
    public function homePage() {

        $dao = new DAO();
        // 3 requêtes pour ne pas faire de doublons lors de la requçtes sql (les real ont fait plusieurs films, les actors ont joués dans plusieurs film, etc...) 
        $sqlFilms = 
        "SELECT 
            COUNT(f.id_film) AS nb_films
        FROM 
            film f
        ";

        $sqlDirectors = 
        "SELECT 
            COUNT(r.id_director) AS nb_realisateurs
        FROM 
            director r
        ";

        $sqlActors = 
        "SELECT 
            COUNT(a.id_actor) AS nb_acteurs
        FROM 
            actor a
        ";

        $films = $dao->executerRequete($sqlFilms);  
        $directors = $dao->executerRequete($sqlDirectors);  
        $actors = $dao->executerRequete($sqlActors);  
        require "./view/home.php";
    }




}

?>