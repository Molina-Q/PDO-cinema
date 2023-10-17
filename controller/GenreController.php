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

    public function detailsGenre($idGenre) {

        $dao = new DAO();
        $sqlGenre =
        "SELECT 
            g.id_genre,
            g.libelle
        FROM 
            genre g
        WHERE
            g.id_genre = :idGenre
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

        $genre = $dao->executerRequete($sqlGenre, $paramsGenre);
        $films = $dao->executerRequete($sqlFilms, $paramsGenre);
        require "./view/genre/detailsGenre.php";
    }

    // function addGenreForm($formData = [], $globalErrorMessage = null, $formErrors = []) {

    //     require "./view/genre/genreForm.php";
    // }

    function addGenreForm($formData = [], $globalErrorMessage = null, $formErrors = []) {

        $fieldNames = ["libelle"];

        $titrePage = "Add Genre";
        $tableToFocus = "Genre";
        $submitInput = "submit";
        $actionForm = "addGenre";
        $placeholder = "Aventure";

        require "./view/commonForm.php";
    }

    // function addGenreForm() {
    //     // $dao = new DAO();
    //     // $sql =
    //     // "SELECT 
    //     //     g.id_genre,
    //     //     g.libelle
    //     // FROM 
    //     //     genre g
    //     // GROUP BY 
    //     //     g.id_genre
    //     // ";
    //     $titrePage = "Create genre";
    //     $tableToFocus = "Genre";
    //     $submitInput = "submitCreate";

    //     // $form = $dao->executerRequete($sql);
    //     require_once "./view/commonForm.php";
    // }

    
    function addGenre() {
        
        // filtrer / nettoyer les données reçues en POST
        $libelle = filter_input(INPUT_POST, "libelle", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // init vars
        $formErrors = [];
        $isSuccess = true;
        $idGenre = null;
        $sqlError = null;

        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ libelle est obligatoire
        if (empty($libelle)) {
            $formErrors["error"][] = "This field is mandatory";
        }

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();
    
            $sql =
            "INSERT INTO genre (libelle)
            VALUES (:libelle)
            ";
    
            $params = [
                "libelle" => $libelle
            ];
    
            try {

                $isSuccess = $dao->executerRequete($sql, $params);

                $idGenre = $dao->getBDD()->lastInsertId();

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
            $this->detailsGenre($idGenre); // contient toute la logique, jusqu'à la vue

        } else {
            // il y a eu un souci

            // préparation au renvoi des données saisies dans le formulaire
            $formData = [
                "libelle" => $libelle
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->addGenreForm($formData, $sqlError, $formErrors);
        }

    }

    function updateGenre($idGenre) {
        $titrePage = "Update genre";
        $tableToFocus = "Genre";
        
        $dao = new DAO();
        $sql =
        "SELECT 
            g.id_genre,
            g.libelle
        FROM 
            genre g
        GROUP BY 
            g.id_genre
        WHERE 
            g.id_genre = :idGenre
        ";

        $params = [
            "idGenre" => $idGenre
        ];

        $form = $dao->executerRequete($sql, $params);
        require_once "./view/commonForm.php";
    }    
    
    function updateGenreForm($idGenre) {
        $fieldNames = ["libelle"];

        $titrePage = "Update genre";
        $tableToFocus = "Genre";
        $submitInput = "submitUpdate";

        require "./view/commonForm.php";
    }
    
    
}




?>