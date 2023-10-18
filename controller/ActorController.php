<?php
require_once "./bdd/DAO.php";

class ActorController {

    public function listActors() {

        $dao = new DAO();
        $sql = 
        "SELECT a.id_acteur, CONCAT(a.prenom ,' ', a.nom) AS full_name
        FROM acteur a
        ";

        $acteurs = $dao->executerRequete($sql);
        require "./view/actor/listActors.php";
    }

    public function detailsActor($idActor) {

        $dao = new DAO();

        
        $sqlActeur = 
        "SELECT 
            a.id_acteur,
            a.prenom,
            a.nom, 
            a.sexe, 
            a.dateDeNaissance, 
            DATE_FORMAT(a.dateDeNaissance, '%d/%m/%Y') AS formatedDateDeNaissance,
            a.dateDeDeces,
            DATE_FORMAT(a.dateDeDeces, '%d/%m/%Y') AS formatedDateDeDeces
        FROM 
            acteur a
        WHERE 
            a.id_acteur = :idActor
        GROUP BY 
            a.id_acteur
        ";

        $paramsActeur = [
            "idActor" => $idActor
        ];

        $sqlCasting =  
        "SELECT 
            ro.libelle,
            c.film_id,
            c.role_id,
            f.titre

        FROM casting c
        INNER JOIN role ro ON c.role_id = ro.id_role
        INNER JOIN film f ON c.film_id = f.id_film
        WHERE c.acteur_id = :idActor
        ";

        $detailsActeur = $dao->executerRequete($sqlActeur, $paramsActeur);
        $castings = $dao->executerRequete($sqlCasting, $paramsActeur);
        require "./view/actor/detailsActor.php";
    }

    function addActorForm($formData = [], $globalErrorMessage = null, $formErrors = []) {

        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces"];

        $titrePage = "Add Actor";
        $tableToFocus = "Actor";
        $submitInput = "submit";
        $actionForm = "addActor";
        $entity = null;


        require "./view/commonForm.php";
    }

    function addActor() {
        
        // filtrer / nettoyer les données reçues en POST
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sexe = filter_input(INPUT_POST, "sexe", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeNaissance = filter_input(INPUT_POST, "dateDeNaissance", FILTER_SANITIZE_NUMBER_INT);
        $dateDeDeces = filter_input(INPUT_POST, "dateDeDeces", FILTER_SANITIZE_NUMBER_INT);


        // init vars
        $formErrors = [];
        $isSuccess = true;
        $idActor = null;
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
            "INSERT INTO acteur (nom, prenom, sexe, dateDeNaissance, dateDeDeces)
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

                $idActor = $dao->getBDD()->lastInsertId();

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
            $this->detailsActor($idActor); // contient toute la logique, jusqu'à la vue

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
            $this->addActorForm($formData, $sqlError, $formErrors);
        }

    }

    function updateActorForm($idActor, $formData = [], $globalErrorMessage = null, $formErrors = []) {
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces"];
        $dao = new DAO();

        $titrePage = "Update Actor";
        $tableToFocus = "Actor";
        $actionForm = "updateActor&id=$idActor";


        $sql = 
        "SELECT 
            id_acteur,
            nom,
            prenom,
            sexe,
            dateDeNaissance,
            dateDeDeces
        FROM 
            acteur
        WHERE
            id_acteur = :idActor
        ";
        
        $params = [
            "idActor" => $idActor
        ];
        
        
        $entity = $dao->executerRequete($sql, $params);
        require "./view/commonForm.php";
    }

    function updateActor($idActor, ) {
        
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
                acteur  
            SET 
                nom = :nom,
                prenom = :prenom,
                sexe = :sexe,
                dateDeNaissance = :dateDeNaissance,
                dateDeDeces = :dateDeDeces
            WHERE 
                id_acteur = :idActor
            ";

            $params = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces,
                "idActor" => $idActor
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
            $this->detailsActor($idActor); // contient toute la logique, jusqu'à la vue

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
            $this->updateActorForm($idActor, $formData, $sqlError, $formErrors);
        }    
    }

}
?>