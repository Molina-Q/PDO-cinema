<?php

require_once "./bdd/DAO.php";

class MovieController {

    public function findAll() {

        $dao = new DAO();
        $sql = "SELECT f.id_film, f.titre FROM film f";

        $films = $dao->executerRequete($sql);
        require "./view/movie/listFilms.php";
    }

    public function genresFilm($GET_id) {
        $dao = new DAO();
        $sql = 
        "SELECT group_CONCAT(g.libelle) AS genres
        FROM film f
        INNER JOIN genre_film gf ON f.id_film = gf.film_id
        INNER JOIN genre g ON gf.genre_id = id_genre
        WHERE f.id_film = $GET_id
        ";

        $genresFilm = $dao->executerRequete($sql);
        require_once "./view/movie/detailsFilm.php";
    }

    public function infosFilm($GET_id) {

        $dao = new DAO();
        $sql = 
        "SELECT f.titre, f.duree, DATE_FORMAT(f.dateDeSortie, '%d/%m/%Y') AS  release_date, r.id_realisateur,
            CONCAT(r.prenom, ' ', r.nom) AS full_name,  GROUP_CONCAT(a.id_acteur) as idActeurs, GROUP_CONCAT(a.prenom,' ', a.nom) AS acteurs, f.affiche
        FROM casting c
        INNER JOIN acteur a ON c.acteur_id = a.id_acteur
        INNER JOIN role ro ON c.role_id = ro.id_role
        INNER JOIN film f  ON c.film_id = f.id_film
        INNER JOIN realisateur r ON f.realisateur_id = r.id_realisateur
        WHERE f.id_film = $GET_id
        GROUP BY f.id_film
        ";

        $detailsFilm = $dao->executerRequete($sql);
        require_once "./view/movie/detailsFilm.php";
    }

    public function castingFilm($GET_id) {
        $dao = new DAO();
        $sql = 
        "SELECT f.titre, group_CONCAT(a.prenom,' ',a.nom) AS acteurs, group_CONCAT(ro.libelle) AS roles
        FROM casting c
        INNER JOIN role ro ON c.role_id = ro.id_role
        INNER JOIN acteur a ON c.acteur_id = a.id_acteur
        INNER JOIN film f ON c.film_id = f.id_film
        WHERE f.id_film = $GET_id 
        GROUP BY f.id_film
        ";

        $castingFilm = $dao->executerRequete($sql);
    }


}

?>