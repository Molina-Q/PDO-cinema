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

        $paramsFilm = [
            "idFilm" => $idFilm
        ];

        // film
        $sqlFilm = 
            "SELECT
                f.id_film,
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
        $film = $dao->executerRequete($sqlFilm, $paramsFilm);

        $genres = $dao->executerRequete($sqlGenres, $paramsFilm);

        $castings = $dao->executerRequete($sqlCastings, $paramsFilm);

        require "./view/movie/detailsFilm.php";
    }

    function addFilmForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        $dao = new DAO();

        $fieldNames = ["titre", "dateDeSortie", "duree", "realisateur_id"];

        $titrePage = "Add Movie";
        $tableToFocus = "Movie";
        $actionForm = "addFilm";
        $entity = null;
        
        $sqlFilms = 
        "SELECT
            r.id_realisateur,
            r.nom,
            r.prenom
        FROM 
            realisateur r
        ";

        $options = $dao->executerRequete($sqlFilms); 

        require "./view/commonForm.php";
    }

    function addFilm() {
        
        // filtrer / nettoyer les données reçues en POST
        $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeSortie = filter_input(INPUT_POST, "dateDeSortie", FILTER_SANITIZE_NUMBER_INT);
        $duree = filter_input(INPUT_POST, "duree", FILTER_SANITIZE_NUMBER_INT);
        $realisateur_id = filter_input(INPUT_POST, "realisateur_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // init vars
        $formErrors = [];
        $isSuccess = true;
        $idFilm = null;
        $sqlError = null;

        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ est obligatoire
        if (empty($titre)) {
            $formErrors["titre"]= "This field is mandatory";
        }

        if (empty($dateDeSortie)) {
            $formErrors["dateDeSortie"] = "This field is mandatory";
        }

        if (empty($duree)) {
            $formErrors["duree"] = "This field is mandatory";
        }

        if (empty($realisateur_id)) {
            $formErrors["realisateur_id"] = "This field is mandatory";
        } 

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();
    
            $sql =
            "INSERT INTO film (titre, dateDeSortie, duree, realisateur_id)
            VALUES (:titre,:dateDeSortie,:duree,:realisateur_id)
            ";


            $params = [
                "titre" => $titre,
                "dateDeSortie" => $dateDeSortie,
                "duree" => $duree,
                "realisateur_id" => $realisateur_id
            ];

    
            try {

                $isSuccess = $dao->executerRequete($sql, $params);

                $idFilm = $dao->getBDD()->lastInsertId();

            } catch (\Throwable $error) {

                $sqlError = $error;
                $isSuccess = false;
            }

        } else {
            $isSuccess = false;
        }

        // si tout s'est bien déroulé (requêtes SQL incluse)
        if ($isSuccess) {

            // on redirige vers le détail du nouveau Genre
            $this->detailsFilm($idFilm); // contient toute la logique, jusqu'à la vue

        } else {
            // il y a eu un souci

            // préparation au renvoi des données saisies dans le formulaire
            $formData = [
                "titre" => $titre,
                "dateDeSortie" => $dateDeSortie,
                "duree" => $duree,
                "realisateur_id" => $realisateur_id
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->addFilmForm($formData, $sqlError, $formErrors);
        }

    }

}

?>