<?php

require_once "./bdd/DAO.php";

class MovieController {
    // donne tous les films
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

    // donne tous les details d'un
    public function detailsFilm($idFilm) {
        $dao = new DAO();

        // tous les elements à afficher pour detailsFilm
        // un film, n'a qu'un director donc il n'y qu'une requête pour ceci
        $sqlFilm = 
            "SELECT
                f.id_film,
                f.titre,
                f.duree,
                DATE_FORMAT(f.dateDeSortie, '%d/%m/%Y') AS release_date,
                f.affiche,
                r.id_director,
                r.prenom, 
                r.nom
            FROM
                film f
                INNER JOIN director r ON f.realisateur_id = r.id_director
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
                a.id_actor,
                a.prenom,
                a.nom,
                ro.libelle,
                ro.id_role
            FROM
                casting c
                INNER JOIN actor a ON c.acteur_id = a.id_actor
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

    // affiche le visuelle du Form et les envoie via POST - var opt pour les erreurs
    function addFilmForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        $dao = new DAO();

        //fields nécessaire pour le form
        $fieldNames = ["titre", "dateDeSortie", "duree", "realisateur_id", "role_id", "acteur_id", "genre_id"];

        $titrePage = "Add Movie";
        $tableToFocus = "Movie";
        $actionForm = "addFilm";
        $entity = null;

        // id_option et complete_label sont des alias peu précis car ils sont utilisés pour afficher des données déjà existante dans les select > options des form
        $sqlFilms = 
        "SELECT
            id_director AS id_option, 
            CONCAT(prenom, ' ',nom) AS complete_label
        FROM 
            director
        ";

        $sqlGenres = 
        "SELECT
            id_genre AS id_option, 
            libelle AS complete_label
        FROM 
            genre
        ";

        $sqlRoles = 
        "SELECT
            id_role AS id_option, 
            libelle AS complete_label
        FROM 
            role
        ";

        $sqlActors = 
        "SELECT
            id_actor AS id_option, 
            CONCAT(prenom, ' ',nom) AS complete_label
        FROM 
            actor
        ";

        //options sera utilise pour la liste d'option dans le select du form qui sera crée
        $options = $dao->executerRequete($sqlFilms); 
        $optionsGenre = $dao->executerRequete($sqlGenres); 
        $optionsRole = $dao->executerRequete($sqlRoles); 
        $optionsActor = $dao->executerRequete($sqlActors); 

        require "./view/commonForm.php";
    }

    // receptionne le post de la fonction plus haut
    function addFilm() {
        
        // filtrer / nettoyer les données reçues en POST
        $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeSortie = filter_input(INPUT_POST, "dateDeSortie", FILTER_SANITIZE_NUMBER_INT);
        $duree = filter_input(INPUT_POST, "duree", FILTER_SANITIZE_NUMBER_INT);
        $realisateur_id = filter_input(INPUT_POST, "realisateur_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $genre_id = filter_input(INPUT_POST, "genre_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $acteur_id = filter_input(INPUT_POST, "acteur_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $role_id = filter_input(INPUT_POST, "role_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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

        if ($genre_id == "select") {
            $formErrors["genre_id"] = "This field is mandatory";
        } 
        
        if ($acteur_id == "select") {
            $formErrors["acteur_id"] = "This field is mandatory";
        } 
        
        if ($role_id == "select") {
            $formErrors["role_id"] = "This field is mandatory";
        } 

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();

            $sqlFilm =
            "INSERT INTO 
                film (titre, dateDeSortie, duree, realisateur_id)
            VALUES 
                (:titre,:dateDeSortie,:duree,:realisateur_id)
            ";

            $sqlGenre = 
            "INSERT INTO
                genre_film (genre_id, film_id)
            VALUES 
                (:genre_id, :film_id)
            ";

            $sqlCasting = 
            "INSERT INTO
                casting (film_id, acteur_id, role_id)
            VALUES 
                (:film_id, :acteur_id, :role_id)
            ";

            $params = [
                "titre" => $titre,
                "dateDeSortie" => $dateDeSortie,
                "duree" => $duree,
                "realisateur_id" => $realisateur_id
            ];

            //si des erreurs arrivent après mes règles métiers
            try {
                $isSuccess = $dao->executerRequete($sqlFilm, $params);

                //me permettra de passer sur le detailsFilm modifié avec son id
                $idFilm = $dao->getBDD()->lastInsertId();

                $params = [
                    "genre_id" => $genre_id,
                    "film_id" => $idFilm
                ];

                $dao->executerRequete($sqlGenre, $params);

                $params = [
                    "film_id" => $idFilm,
                    "acteur_id" => $acteur_id,
                    "role_id" => $role_id
                ];

                $dao->executerRequete($sqlCasting, $params);

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
        $fieldNames = ["titre", "dateDeSortie", "duree", "realisateur_id","genre_id", "acteur_id", "role_id"];
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

        $sqlDirector = 
        "SELECT
            id_director AS id_option,
            CONCAT(prenom, ' ',nom) AS complete_label

        FROM 
            director
        ";

        $sqlGenres = 
        "SELECT
            id_genre AS id_option,
            libelle AS complete_label
        FROM 
            genre
        INNER JOIN 
            genre_film gf ON genre.id_genre = gf.genre_id
        WHERE
            film_id = :idFilm 
        ";

        $sqlActors = 
        "SELECT
            id_actor AS id_option,
            CONCAT(prenom, ' ',nom) AS complete_label
        FROM 
            actor
        INNER JOIN 
            casting c ON actor.id_actor = c.acteur_id
        WHERE
            film_id = :idFilm 
        ";

        $sqlRoles = 
        "SELECT
            id_role AS id_option,
            libelle AS complete_label
        FROM 
            role  
        INNER JOIN 
            casting c ON role.id_role = c.role_id
        WHERE
            film_id = :idFilm         
        ";

        $params = [
            "idFilm" => $idFilm
        ];

        $entity = $dao->executerRequete($sql, $params);
        $options = $dao->executerRequete($sqlDirector); 
        $optionsGenre = $dao->executerRequete($sqlGenres, $params); 
        $optionsActor = $dao->executerRequete($sqlActors, $params); 
        $optionsRole = $dao->executerRequete($sqlRoles, $params); 
        // $radios = $dao->executerRequete($sqlGenres); 
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
                var_dump($error);
                die();
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
            id_actor AS id_option,
            CONCAT(prenom, ' ', nom) AS complete_label
        FROM 
            actor
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

        // filtrer / nettoyer les données reçues en POST / le INPUT_POST permet de récuperer les infos du $_POST
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

    public function deleteFilm($idFilm) {
        $dao = new DAO ();

        $sql =
        "DELETE FROM 
            film
        WHERE 
            id_film = :idFilm
        ";
        
        $sqlGenre = 
        "DELETE FROM 
            genre_film
        WHERE 
            film_id = :idFilm
        ";

        $sqlCast = 
        "DELETE FROM 
            casting
        WHERE 
            film_id = :idFilm
        ";

        $params = [
            "idFilm" => $idFilm
        ];

        $dao->executerRequete($sqlGenre, $params);
        $dao->executerRequete($sqlCast, $params);
        $dao->executerRequete($sql, $params);

        $this->listFilms();
    }

    public function deleteGenreFilm($idGenre, $idFilm) {
        $dao = new DAO ();

        $sql =
        "DELETE FROM 
            genre_film
        WHERE 
            film_id = :idFilm AND genre_id = :idGenre
        ";
        $params = [
            "idFilm" => $idFilm,
            "idGenre" => $idGenre
        ];
        
        $dao->executerRequete($sql, $params);

        $this->detailsFilm($idFilm);
    }

    //WIP fonctionnalités qui seront peut être ajoutés dans le futur
    function updateGenreFilmForm($idGenre, $idFilm, $formData = [], $globalErrorMessage = null, $formErrors = []) {
        $dao = new DAO();
        $fieldNames = ["film_id", "genre_id"];

        $titrePage = "Update a Genre from a Movie ";
        $tableToFocus = "Movie";
        $actionForm = "updateGenreFilm&id=$idGenre&idSec=$idFilm";
        $entity = null;

        $sqlGenreFilm = 
        "SELECT 
            film_id,
            genre_id
            
        FROM
            genre_film
        WHERE
            genre_id = :idGenre AND film_id = :idFilm
        ";

        $sqlGenres = 
        "SELECT
            id_genre AS id_option,
            libelle AS complete_label
        FROM 
            genre
        HAVING id_genre NOT IN (
                SELECT genre_id
                FROM genre_film
                WHERE film_id = :idFilm
            )
        ";

        $sqlFilms = 
        "SELECT
            id_film AS id_option,
            titre AS complete_label
        FROM 
            film
        WHERE id_film = :idFilm
        ";

        $params = [
            "idGenre" => $idGenre,
            "idFilm" => $idFilm
        ];

        $param = [
            "idFilm" => $idFilm
        ];

        $entity = $dao->executerRequete($sqlGenreFilm, $params);
        $optionsGenre = $dao->executerRequete($sqlGenres, $param); 
        $optionsFilm = $dao->executerRequete($sqlFilms, $param); 
        require "./view/commonForm.php";
    }

    function updateGenreFilm($idGenre, $idFilm) {
        
        // filtrer / nettoyer les données reçues en POST
        $genre_id = filter_input(INPUT_POST, "genre_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $film_id = filter_input(INPUT_POST, "film_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        // init vars
        $formErrors = [];
        $isSuccess = true;
        $sqlError = null;    
           
        
        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ libelle est obligatoire
        if (empty($genre_id)) {
            $formErrors["genre_id"]= "This field is mandatory";
        }

        if (empty($film_id)) {
            $formErrors["film_id"] = "This field is mandatory";
        } 

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();

            $sql =
            "UPDATE 
                genre_film
            SET 
                film_id = :film_id,
                genre_id = :genre_id

            WHERE 
                film_id = :idFilm AND genre_id = :idGenre 
            ";

            $params = [
                "film_id" => $film_id,
                "genre_id" => $genre_id,
                "idGenre" => $idGenre,
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
                "film_id" => $film_id,
                "genre_id" => $genre_id
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->updateGenreFilm($idGenre, $idFilm, $formData, $sqlError, $formErrors);
        }    
    }

}

?>