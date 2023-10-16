<?php

require_once "./bdd/DAO.php";

class MovieController {

    public function listFilms() {

        $dao = new DAO();
        $sql = "SELECT f.id_film, f.titre FROM film f";

        $films = $dao->executerRequete($sql);
        require "./view/movie/listFilms.php";
    }

    public function detailsFilm($idFilm) {

        $dao = new DAO();

        // $sql = 
        // "SELECT DISTINCT f.titre, f.duree, DATE_FORMAT(f.dateDeSortie, '%d/%m/%Y') AS  release_date, r.id_realisateur,
        // CONCAT( r.prenom, ' ', r.nom) AS full_name,  
        // GROUP_CONCAT(DISTINCT a.id_acteur) AS idActeurs, -- acteur_ids
        // GROUP_CONCAT(DISTINCT a.prenom,' ', a.nom ORDER BY a.id_acteur) AS acteurs,
        // GROUP_CONCAT(DISTINCT ' ',g.libelle) AS genres,
        // f.affiche 
        // FROM film f
        // INNER JOIN casting c  ON f.id_film = c.film_id
        // INNER JOIN acteur a ON c.acteur_id = a.id_acteur
        // INNER JOIN role ro ON c.role_id = ro.id_role
        // INNER JOIN realisateur r ON f.realisateur_id = r.id_realisateur
        // INNER JOIN genre_film gf ON f.id_film = gf.film_id
        // INNER JOIN genre g ON gf.genre_id = g.id_genre
        // WHERE f.id_film = $idFilm
        // GROUP BY f.id_film
        // ";

        // $detailsFilm = $dao->executerRequete($sql);

        $paramsFilm = [
            "idFilm" => $idFilm
        ];

        // film
        $sqlFilm = 
            "SELECT
                f.titre,
                f.duree,
                DATE_FORMAT(f.dateDeSortie, '%d/%m/%Y') AS release_date,
                f.affiche,
                r.id_realisateur,
                r.prenom, 
                r.nom
            FROM
                film f
                INNER JOIN realisateur r ON f.realisateur_id = r.id_realisateur
            WHERE f.id_film = :idFilm
            GROUP BY f.id_film
        ";


        // genres
        $sqlGenres = 
            "SELECT
                g.libelle,
                g.id_genre
            FROM
                genre_film gf
                INNER JOIN genre g ON gf.genre_id = g.id_genre
            WHERE
                gf.film_id = :idFilm
        ";

        // castings
        $sqlCastings = 
            "SELECT
                a.id_acteur,
                a.prenom,
                a.nom,
                ro.libelle,
                ro.id_role
            FROM
                casting c
                INNER JOIN acteur a ON c.acteur_id = a.id_acteur
                INNER JOIN role ro ON c.role_id = ro.id_role
            WHERE
                c.film_id = :idFilm
        ";

        // exécution
        $detailsFilm = $dao->executerRequete($sqlFilm, $paramsFilm);

        $genres = $dao->executerRequete($sqlGenres, $paramsFilm);

        $castings = $dao->executerRequete($sqlCastings, $paramsFilm);

        require_once "./view/movie/detailsFilm.php";
    }

}

?>