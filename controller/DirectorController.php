<?php
require_once "./bdd/DAO.php";

class DirectorController {

    // function qui vas donner l'id et le nom de tous les realisateurs présents dans la bdd
    public function listDirectors() {

        $dao = new DAO();
        $sql = 
        "SELECT 
            r.id_director, CONCAT(r.prenom ,' ', r.nom) AS full_name
        FROM 
            director r
        ";

        $directors = $dao->executerRequete($sql);
        require "./view/director/listDirectors.php";
    }

    // la function donne toutes les informations d'un realisateur grace à son id qui est donné en paramètre
    public function detailsDirector($idDirector) {

        $dao = new DAO();

        $paramsDirector = [
            "idDirector" => $idDirector
        ];

        $sqlDirector = 
        "SELECT 
            r.id_director,
            r.prenom,
            r.nom,
            r.sexe, 
            r.dateDeNaissance, 
            DATE_FORMAT(r.dateDeNaissance, '%d/%m/%Y') AS formatedDateDeNaissance,
            r.dateDeDeces,
            DATE_FORMAT(r.dateDeDeces, '%d/%m/%Y') AS formatedDateDeDeces
        FROM
            director r
        WHERE 
            r.id_director = :idDirector

        ";

        $sqlFilm = 
        "SELECT
            f.id_film,
            f.titre
        FROM
            film f
        WHERE 
            f.realisateur_id = :idDirector
        ";

        $detailsDirector = $dao->executerRequete($sqlDirector, $paramsDirector);
        $films = $dao->executerRequete($sqlFilm, $paramsDirector);
        require "./view/director/detailsDirector.php";
    }

    // function qui me permet d'afficher les infos nécessaires pour créer un realisateur dans la bdd
    function addDirectorForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces"];

        $titrePage = "Add Director";
        $tableToFocus = "Director";
        $actionForm = "addDirector";
        $entity = null;

        require "./view/commonForm.php";
    }

    // ajoute un realisateur à la base de données, cette function est appelé après avoir submit toutes les informations demandées par function précédantes
    function addDirector() {
        
        // filtrer / nettoyer les données reçues en POST
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sexe = filter_input(INPUT_POST, "sexe", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeNaissance = filter_input(INPUT_POST, "dateDeNaissance", FILTER_SANITIZE_NUMBER_INT);
        $dateDeDeces = filter_input(INPUT_POST, "dateDeDeces", FILTER_SANITIZE_NUMBER_INT);


        // init vars
        $formErrors = [];
        $isSuccess = true;
        $idDirector = null;
        $sqlError = null;

        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ libelle est obligatoire
        if (empty($nom)) {
            $formErrors["error"][] = "This field is mandatory";
        }

        if (empty($prenom)) {
            $formErrors["error"][] = "This field is mandatory";
        }

        if (empty($sexe)) {
            $formErrors["error"][] = "This field is mandatory";
        }

        if (empty($dateDeNaissance)) {
            $formErrors["error"][] = "This field is mandatory";
        } 
        
        if (empty($dateDeDeces)) {
            $dateDeDeces = null;
        }  

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors["errors"])) {

            $dao = new DAO();
    
            $sql =
            "INSERT INTO director (nom, prenom, sexe, dateDeNaissance, dateDeDeces)
            VALUES (:nom,:prenom,:sexe,:dateDeNaissance,:dateDeDeces)
            ";
    
            $params = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces
            ];
    
            try {

                $isSuccess = $dao->executerRequete($sql, $params);

                $idDirector = $dao->getBDD()->lastInsertId();

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
            $this->detailsDirector($idDirector); // contient toute la logique, jusqu'à la vue

        } else {
            // il y a eu un souci

            // préparation au renvoi des données saisies dans le formulaire
            $formData = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->addDirectorForm($formData, $sqlError, $formErrors);
        }

    }

    function updateDirectorForm($idDirector, $formData = [], $globalErrorMessage = null, $formErrors = []) {
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces"];
        $dao = new DAO();

        $titrePage = "Update Director";
        $tableToFocus = "Director";
        $actionForm = "updateDirector&id=$idDirector";


        $sql = 
        "SELECT 
            id_director,
            nom,
            prenom,
            sexe,
            dateDeNaissance,
            dateDeDeces
        FROM 
            director
        WHERE
            id_director = :idDirector
        ";
        
        $params = [
            "idDirector" => $idDirector
        ];
        
        
        $entity = $dao->executerRequete($sql, $params);
        require "./view/commonForm.php";
    }

    function updateDirector($idDirector) {
        
        // filtrer / nettoyer les données reçues en POST
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sexe = filter_input(INPUT_POST, "sexe", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeNaissance = filter_input(INPUT_POST, "dateDeNaissance", FILTER_SANITIZE_NUMBER_INT);
        $dateDeDeces = filter_input(INPUT_POST, "dateDeDeces", FILTER_SANITIZE_NUMBER_INT);


        // init vars
        $formErrors = [];
        $isSuccess = true;
        $sqlError = null;       
        
        // validation des règles métier (valider les données saisies dans le formulaire soumis)

        // le champ libelle est obligatoire
        if (empty($nom)) {
            $formErrors["nom"] = "This field is mandatory";
        }

        if (empty($prenom)) {
            $formErrors["prenom"] = "This field is mandatory";
        }

        if (empty($sexe)) {
            $formErrors["sexe"] = "This field is mandatory";
        }

        if (empty($dateDeNaissance)) {
            $formErrors["dateDeNaissance"] = "This field is mandatory";
        } 
        
        if (empty($dateDeDeces)) {
            $dateDeDeces = null;
        }  

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();

            $sql =
            "UPDATE 
                director  
            SET 
                nom = :nom,
                prenom = :prenom,
                sexe = :sexe,
                dateDeNaissance = :dateDeNaissance,
                dateDeDeces = :dateDeDeces
            WHERE 
                id_director = :idDirector
            ";

            $params = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces,
                "idDirector" => $idDirector
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
            $this->detailsDirector($idDirector); // contient toute la logique, jusqu'à la vue

        } else {
            // il y a eu un souci

            // préparation au renvoi des données saisies dans le formulaire
            $formData = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces
            ];
            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->updateDirectorForm($idDirector, $formData, $sqlError, $formErrors);
        }    
    } 

    public function deleteDirector($idDirector) {
        $dao = new DAO ();

        $sql =
        "DELETE FROM 
            director
        WHERE 
            id_director = :idDirector
        ";

        $params = [
            "idDirector" => $idDirector
        ];

        $dao->executerRequete($sql, $params);

        $this->listDirectors();
    }
}

?>