<?php
require_once "./bdd/DAO.php";
class GenreController {
   
    public function listGenres() {

        $dao = new DAO();
        $sql =
        "SELECT g.id_genre, g.libelle 
        FROM genre g";

        $genres = $dao->executerRequete($sql);
        require "./view/genre/listGenres.php";
    }

    public function DetailsGenre($idGenre) {

        $dao = new DAO();
        $sqlGenre =
        "SELECT 

            g.libelle
        FROM 
            genre g

        WHERE g.id_genre = :idGenre
        ";

        $paramsGenre = [
            "idGenre" => $idGenre
        ];

        $sqlFilms =
        "SELECT 
            gf.genre_id,
            gf.film_id,
            f.titre 
        FROM 
            genre_film gf
        INNER JOIN 
            film f ON gf.film_id = f.id_film
        WHERE 
            gf.genre_id = :idGenre
        ";

        $detailsGenre = $dao->executerRequete($sqlGenre, $paramsGenre);
        $films = $dao->executerRequete($sqlFilms, $paramsGenre);
        require "./view/genre/detailsGenre.php";
    }
    
}

?>