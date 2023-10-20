<?php
require_once "./bdd/DAO.php";
// pour plus d'infos la seule différence avec ActorController est le nom des var, les commentaires sont dans ActorController.php
class DirectorController {

    public function listDirectors() {

        $dao = new DAO();
        $sql = 
        "SELECT 
            r.id_realisateur, CONCAT(r.prenom ,' ', r.nom) AS full_name
        FROM 
            realisateur r
        ";

        $realisateurs = $dao->executerRequete($sql);
        require "./view/director/listDirectors.php";
    }

    public function detailsDirector($idDirector) {

        $dao = new DAO();

        $paramsDirector = [
            "idDirector" => $idDirector
        ];

        $sqlDirector = 
        "SELECT 
            r.id_realisateur,
            r.prenom,
            r.nom,
            r.sexe, 
            r.dateDeNaissance, 
            DATE_FORMAT(r.dateDeNaissance, '%d/%m/%Y') AS formatedDateDeNaissance,
            r.dateDeDeces,
            DATE_FORMAT(r.dateDeDeces, '%d/%m/%Y') AS formatedDateDeDeces
        FROM
            realisateur r
        WHERE 
            r.id_realisateur = :idDirector

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

    function addDirectorForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces"];

        $titrePage = "Add Director";
        $tableToFocus = "Director";
        $actionForm = "addDirector";
        $entity = null;

        require "./view/commonForm.php";
    }

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
            "INSERT INTO realisateur (nom, prenom, sexe, dateDeNaissance, dateDeDeces)
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
            id_realisateur,
            nom,
            prenom,
            sexe,
            dateDeNaissance,
            dateDeDeces
        FROM 
            realisateur
        WHERE
            id_realisateur = :idDirector
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
                realisateur  
            SET 
                nom = :nom,
                prenom = :prenom,
                sexe = :sexe,
                dateDeNaissance = :dateDeNaissance,
                dateDeDeces = :dateDeDeces
            WHERE 
                id_realisateur = :idDirector
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
}

?>