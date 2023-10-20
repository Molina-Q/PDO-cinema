<?php

require_once "./bdd/DAO.php";

class MovieController {

    public function listFilms() {

        $dao = new DAO();
        $sql = 

        "SELECT 
            f.id_film, f.titre 
        FROM 
            film f";

        $films = $dao->executerRequete($sql);
        require "./view/movie/listFilms.php";
    }

    public function detailsFilm($idFilm) {

        $dao = new DAO();


        // tous les elements à afficher pour detailsFilm
        // un film, n'a qu'un realisateur donc il n'y qu'une requête pour ceci
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

        // mais un film peut avoir plusieurs genres...
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

        // et généralement plusieurs castings
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

        $paramsFilm = [
            "idFilm" => $idFilm // film_id et id_film utilisent le même idFilm car ils sont tous liés au même film
        ];

        // exécution - toujours une executions par requête
        $film = $dao->executerRequete($sqlFilm, $paramsFilm);

        $genres = $dao->executerRequete($sqlGenres, $paramsFilm);

        $castings = $dao->executerRequete($sqlCastings, $paramsFilm);

        require "./view/movie/detailsFilm.php";
    }

    // var opt pour les erreurs
    function addFilmForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        $dao = new DAO();

        //fields nécessaire pour le form
        $fieldNames = ["titre", "dateDeSortie", "duree", "realisateur_id"];

        $titrePage = "Add Movie";
        $tableToFocus = "Movie";
        $actionForm = "addFilm";
        $entity = null;

        // id_option et complete_label sont des alias peu précis car ils sont utilisés pour afficher des données déjà existante dans les select > options des form
        $sqlFilms = 
        "SELECT
            id_realisateur AS id_option, 
            CONCAT(prenom, ' ',nom) AS complete_label
        FROM 
            realisateur
        ";

        //options sera utilise pour la liste d'option dans le select du form qui sera crée
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
        // si le select > option du form n'est pas touché sa valeur sera select, dans ce cas il est considéré comme vide 
        if ($realisateur_id == "select") {
            $formErrors["realisateur_id"] = "This field is mandatory";
        } 

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();

            $sql =
            "INSERT INTO 
                film (titre, dateDeSortie, duree, realisateur_id)
            VALUES 
                (:titre,:dateDeSortie,:duree,:realisateur_id)
            ";

            $params = [
                "titre" => $titre,
                "dateDeSortie" => $dateDeSortie,
                "duree" => $duree,
                "realisateur_id" => $realisateur_id
            ];

            //si des erreurs arrivent après mes règles métiers
            try {
                $isSuccess = $dao->executerRequete($sql, $params);

                //me permettra de passer sur le detailsFilm modifié avec son id
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
                "realisateur_id" => $realisateur_id // l'id permet de renvoyer dans le bon option du select
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->addFilmForm($formData, $sqlError, $formErrors);
        }

    }

    function updateFilmForm($idFilm, $formData = [], $globalErrorMessage = null, $formErrors = []) {
        $fieldNames = ["titre", "dateDeSortie", "duree", "realisateur_id"];
        $dao = new DAO();

        $titrePage = "Update Film";
        $tableToFocus = "Film";
        $actionForm = "updateFilm&id=$idFilm";


        $sql = 
        "SELECT 
            titre,
            dateDeSortie,
            duree,
            realisateur_id
        FROM 
            film
        WHERE
            id_film = :idFilm
        ";

        $sqlFilms = 
        "SELECT
            id_realisateur AS id_option,
            CONCAT(prenom, ' ',nom) AS complete_label

        FROM 
            realisateur
        ";

        $params = [
            "idFilm" => $idFilm
        ];

        $entity = $dao->executerRequete($sql, $params);
        $options = $dao->executerRequete($sqlFilms); 
        require "./view/commonForm.php";
    }

    function updateFilm($idFilm) {
        
        // filtrer / nettoyer les données reçues en POST
        $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeSortie = filter_input(INPUT_POST, "dateDeSortie", FILTER_SANITIZE_NUMBER_INT);
        $duree = filter_input(INPUT_POST, "duree", FILTER_SANITIZE_NUMBER_INT);
        $realisateur_id = filter_input(INPUT_POST, "realisateur_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // init vars
        $formErrors = [];
        $isSuccess = true;
        $sqlError = null;    
           
        
        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ libelle est obligatoire
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
            "UPDATE 
                film  
            SET 
                titre = :titre,
                dateDeSortie = :dateDeSortie,
                duree = :duree,
                realisateur_id = :realisateur_id

            WHERE 
                id_film= :idFilm
            ";

            $params = [
                "titre" => $titre,
                "dateDeSortie" => $dateDeSortie,
                "duree" => $duree,
                "realisateur_id" => $realisateur_id,
                "idFilm" => $idFilm
            ];

            try {

                $isSuccess = $dao->executerRequete($sql, $params);

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
            $this->updateFilmForm($idFilm, $formData, $sqlError, $formErrors);
        }    
    }
    
    function addGenreFilmForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        $dao = new DAO();

        // genre_film est composé de l'id d'un film et de l'id d'un genre
        $fieldNames = ["film_id", "genre_id"];

        $titrePage = "Add Genre to a Movie ";
        $tableToFocus = "Movie";
        $actionForm = "addGenreFilm";
        $entity = null;
        
        //id_option et complete_label pour select > option
        $sqlGenres = 
        "SELECT
            id_genre AS id_option,
            libelle AS complete_label
        FROM 
            genre
        ";

        $sqlFilms = 
         "SELECT
            id_film AS id_option,
            titre AS complete_label
        FROM 
            film
        ";

        $optionsGenre = $dao->executerRequete($sqlGenres); 
        $optionsFilm = $dao->executerRequete($sqlFilms); 

        require "./view/commonForm.php";
    }

    function addGenreFilm() {

        // filtrer / nettoyer les données reçues en POST
        $film_id = filter_input(INPUT_POST, "film_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $genre_id = filter_input(INPUT_POST, "genre_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // init vars
        $formErrors = [];
        $isSuccess = true;
        $idFilm = null;
        $sqlError = null;

        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ est obligatoire
        if ($film_id == "select") {
            $formErrors["film_id"] = "This field is mandatory";
        } 

        if ($genre_id == "select") {
            $formErrors["genre_id"] = "This field is mandatory";
        } 

        // à faire !! attraper l'erreur qui apparait lorsque que le genre_film crée existe déjà


        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();
    
            $sql =
            "INSERT INTO 
                genre_film (film_id, genre_id)
            VALUES 
                (:film_id,:genre_id)
            ";

            $params = [
                "film_id" => $film_id,
                "genre_id" => $genre_id
            ];

    
            try {

                $isSuccess = $dao->executerRequete($sql, $params);

                $idFilm = $film_id;

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
                "film_id" => $film_id,
                "genre_id" => $genre_id
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->addGenreFilmForm($formData, $sqlError, $formErrors);
        }

    }

    function addCastingFilmForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        $dao = new DAO();
        // casting est composé de ces trois id
        $fieldNames = ["film_id", "acteur_id", "role_id"];

        $titrePage = "Add a Casting to a Movie";
        $tableToFocus = "Movie";
        $actionForm = "addCastingFilm";
        $entity = null;
        
        // 3 requête pour qu'il n'y ai aucun doublons
        $sqlFilms = 
        "SELECT
            id_film AS id_option,
            titre AS complete_label
        FROM 
            film
        ";

        $sqlActors = 
         "SELECT
            id_acteur AS id_option,
            CONCAT(prenom, ' ', nom) AS complete_label
        FROM 
            acteur
        ";

        $sqlRoles = 
        "SELECT
            id_role AS id_option,
            libelle AS complete_label
        FROM 
            role
        ";

        $optionsFilm = $dao->executerRequete($sqlFilms); 
        $optionsActor = $dao->executerRequete($sqlActors); 
        $optionsRole = $dao->executerRequete($sqlRoles); 

        require "./view/commonForm.php";
    }

    function addCastingFilm() {

        // filtrer / nettoyer les données reçues en POST
        $film_id = filter_input(INPUT_POST, "film_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $acteur_id = filter_input(INPUT_POST, "acteur_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $role_id = filter_input(INPUT_POST, "role_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // init vars
        $formErrors = [];
        $isSuccess = true;
        $idFilm = null;
        $sqlError = null;

        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ est obligatoire, ils n'utilisent pas empty() car ce sont des select>optino
        if ($film_id == "select") {
            $formErrors["film_id"] = "This field is mandatory";
        } 

        if ($acteur_id == "select") {
            $formErrors["acteur_id"] = "This field is mandatory";
        } 

        if ($role_id == "select") {
            $formErrors["role_id"] = "This field is mandatory";
        } 

        // à faire !! attraper l'erreur qui apparait lorsque que le genre_film crée existe déjà


        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();

            $sql =
            "INSERT INTO 
                casting (film_id, acteur_id, role_id)
            VALUES 
                (:film_id,:acteur_id,:role_id)
            ";

            $params = [
                "film_id" => $film_id,
                "acteur_id" => $acteur_id,
                "role_id" => $role_id
            ];

            try {

                $isSuccess = $dao->executerRequete($sql, $params);

                $idFilm = $film_id;

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
                "film_id" => $film_id,
                "acteur_id" => $acteur_id,
                "role_id" => $role_id
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->addCastingFilmForm($formData, $sqlError, $formErrors);
        }

    }


    //WIP fonctionnalités qui seront peut être ajoutés dans le futur

    // function updateGenreFilmForm($idFilm, $formData = [], $globalErrorMessage = null, $formErrors = []) {
    //     $dao = new DAO();
    //     $fieldNames = ["film_id", "genre_id"];

    //     $titrePage = "Udate a Genre from a Movie ";
    //     $tableToFocus = "Movie";
    //     $actionForm = "updateGenreFilm&id=$idFilm";
    //     $entity = null;

    //     $sqlGenres = 
    //     "SELECT
    //         id_genre AS id_option,
    //         libelle AS complete_label
    //     FROM 
    //         genre
    //     ";

    //     $sqlFilms = 
    //      "SELECT
    //         id_film AS id_option,
    //         titre AS complete_label
    //     FROM 
    //         film
    //     ";

    //     $sqlFilms = 
    //     "SELECT
    //         id_realisateur AS id_option,
    //         CONCAT(prenom, ' ',nom) AS complete_label

    //     FROM 
    //         film
    //     ";

    //     $params = [
    //         "idFilm" => $idFilm
    //     ];

    //     $entity = $dao->executerRequete($sql, $params);
    //     $options = $dao->executerRequete($sqlFilms); 
    //     require "./view/commonForm.php";
    // }

    // function updateGenreFilm($idFilm) {
        
    //     // filtrer / nettoyer les données reçues en POST
    //     $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //     $dateDeSortie = filter_input(INPUT_POST, "dateDeSortie", FILTER_SANITIZE_NUMBER_INT);
    //     $duree = filter_input(INPUT_POST, "duree", FILTER_SANITIZE_NUMBER_INT);
    //     $realisateur_id = filter_input(INPUT_POST, "realisateur_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //     // init vars
    //     $formErrors = [];
    //     $isSuccess = true;
    //     $sqlError = null;    
           
        
    //     // validation des règles métier (valider les données saisies dans le formulaire soumis)

    //     // le champ libelle est obligatoire
    //     if (empty($titre)) {
    //         $formErrors["titre"]= "This field is mandatory";
    //     }

    //     if (empty($dateDeSortie)) {
    //         $formErrors["dateDeSortie"] = "This field is mandatory";
    //     }

    //     if (empty($duree)) {
    //         $formErrors["duree"] = "This field is mandatory";
    //     }

    //     if (empty($realisateur_id)) {
    //         $formErrors["realisateur_id"] = "This field is mandatory";
    //     } 

    //     // autre règle métier / de validation du formulaire
    //     // if

    //     // si le formulaire est valide
    //     if (empty($formErrors)) {

    //         $dao = new DAO();

    //         $sql =
    //         "UPDATE 
    //             film  
    //         SET 
    //             titre = :titre,
    //             dateDeSortie = :dateDeSortie,
    //             duree = :duree,
    //             realisateur_id = :realisateur_id

    //         WHERE 
    //             id_film= :idFilm
    //         ";

    //         $params = [
    //             "titre" => $titre,
    //             "dateDeSortie" => $dateDeSortie,
    //             "duree" => $duree,
    //             "realisateur_id" => $realisateur_id,
    //             "idFilm" => $idFilm
    //         ];

    //         try {

    //             $isSuccess = $dao->executerRequete($sql, $params);

    //         } catch (\Throwable $error) {

    //             $sqlError = $error;
    //             $isSuccess = false;
    //         }

    //     } else {
    //         $isSuccess = false;
    //     }

    //     // si tout s'est bien déroulé (requêtes SQL incluse)
    //     if ($isSuccess) {

    //         // on redirige vers le détail du nouveau Genre
    //         $this->detailsFilm($idFilm); // contient toute la logique, jusqu'à la vue

    //     } else {
    //         // il y a eu un souci

    //         // préparation au renvoi des données saisies dans le formulaire
    //         $formData = [
    //             "titre" => $titre,
    //             "dateDeSortie" => $dateDeSortie,
    //             "duree" => $duree,
    //             "realisateur_id" => $realisateur_id
    //         ];

    //         // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
    //         $this->updateFilmForm($idFilm, $formData, $sqlError, $formErrors);
    //     }    
    // }

}

?>