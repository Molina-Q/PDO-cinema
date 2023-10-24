<?php
// permet de faire des requête sql grace aux liens avec la bdd de DAO.php
require_once "./bdd/DAO.php";

class ActorController {
    // requête qui donne l'id et le nom de tous les acteurs présents dans bdd
    public function listActors() {
        // on instancie DAO pour avoir la connection à la base de données
        $dao = new DAO();
        // la requête SQL
        $sql = 
        "SELECT 
            a.id_acteur, 
            CONCAT(a.prenom ,' ', a.nom) AS full_name
        FROM 
            acteur a
        ";
        // on stock le resultat de la requête dans une valeur
        $acteurs = $dao->executerRequete($sql);
        require "./view/actor/listActors.php";
    }
    // la function doit forcément avoir un id en paramètre
    public function detailsActor($idActor) {
        $dao = new DAO();
        // il y les dates inital et une formated car les function pour calculer l'age avec la date ne prennent pas la version formated des dates
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
            a.id_acteur = :idActor /* :idACtor est la valeur que je souhaite avoir mais qui n'est pas la même à chaque fois */
        GROUP BY 
            a.id_acteur
        ";

        // ici je dis que idActeur = au contenu de l'idActeur que j'ai en paramètre de la fonction 
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

        //plusieurs requêtes car il y a plusieurs casting possible, tout en une requête ferait des doublons
        //execute avec ma requête SQL et le params mis en place en haut, qui fera comprends a execute() que :idActeur = $idActeur
        $detailsActeur = $dao->executerRequete($sqlActeur, $paramsActeur);
        $castings = $dao->executerRequete($sqlCasting, $paramsActeur);
        require "./view/actor/detailsActor.php";
    }

    //tous les paramètres sont des variables optionnels, elle sont présentes uniquement si il y a eu une erreur plus bas dans la function
    function addActorForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        // tous les noms de colones que j'ai besoin d'afficher quand je crée mon form pour ajouter un acteur a ma bdd
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces"];

        // var qui seront utilsés dans la page pour avoir des titres et elements textes différents à chaque affichage de la page en fonction du controller appelé
        $titrePage = "Add Actor";
        $tableToFocus = "Actor";
        $submitInput = "submit";
        $actionForm = "addActor";

        // entity n'est pas nécessaire lors de l'ajout mais elle doit quand même exister donc je l'initie en NULL
        $entity = null;
        // le tout sera demandé par commonForm, "require" spécifiquement car aucune autre page ne peut l'appeler ne même temps
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
        // différent car cette var est optionnel, et donc NULL si elle n'est pas entré
        if (empty($dateDeDeces)) {
            $dateDeDeces = null;
        }  

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();
            // aucune erreur n'est detecté, je peux donc ajouter toutes mes valeurs dans la bdd
            $sql =
            "INSERT INTO 
                acteur (nom, prenom, sexe, dateDeNaissance, dateDeDeces)
            VALUES 
                (:nom,:prenom,:sexe,:dateDeNaissance,:dateDeDeces)
            ";
            
            // je reprends mes var filtrés plus haut pour être sur qu'elle soit safe
            $params = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces
            ];
    
            // try catch me permet d'attraper les erreurs et de ne pas complètement bloquer le site si le user reçoit une erreur
            try {
                $isSuccess = $dao->executerRequete($sql, $params);

                //cette function n'as pas d'ID en paramètre donc je récupère la dernière id ajouté a ma bdd dans la catéguorie actuelle et je la stock
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

            // on redirige vers le détail du nouveau acteur, grace à la ligne juste au dessus qui stock l'id 
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

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage, le formData permet d'afficher toutes les données ecrit par le user
            $this->addActorForm($formData, $sqlError, $formErrors);
        }
    }

    // etant donné que cette function update j'ai besoin de l'id de l'element a updaten et leurs erreurs pour la même raison qu'au dessus
    function updateActorForm($idActor, $formData = [], $globalErrorMessage = null, $formErrors = []) {
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces"];
        $dao = new DAO();

        $titrePage = "Update Actor";
        $tableToFocus = "Actor";
        // cette fois j'ai un id car j'ai besoin de savoir vers quel acteur redirigé la page
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
        
        // on utilise entity pour afficher les info déjà existante de l'elements qu'on update
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

            // je recup toutes les données via le POST et les mets à jour dans la bdd
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

            // on redirige vers le détail de l'acteur mis à jour
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

    public function deleteActor($idActor) {
        $dao = new DAO ();

        $sql =
        "DELETE FROM 
            acteur
        WHERE 
            id_acteur = :idActor
        ";

        $params = [
            "idActor" => $idActor
        ];

        $dao->executerRequete($sql, $params);

        $this->listActors();
    }


}
?>