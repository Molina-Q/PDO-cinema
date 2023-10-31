<?php
// permet de faire des requête sql grace aux liens avec la bdd de DAO.php
require_once "./bdd/DAO.php";

class ActorController {

    // function qui vas donner l'id et le nom de tous les actors présents dans la bdd
    public function listActors() {
        // on instancie DAO pour avoir la connection à la base de données
        $dao = new DAO();
        // la requête SQL
        $sql = 
        "SELECT 
            a.id_actor, 
            CONCAT(a.prenom ,' ', a.nom) AS full_name
        FROM 
            actor a
        ";
        // on stock le resultat de la requête dans une valeur
        $actors = $dao->executerRequete($sql);
        require "./view/actor/listActors.php";
    }

    // la function donne toutes les informations d'un acteur grace à son id qui est donné en paramètre
    public function detailsActor($idActor) {
        $dao = new DAO();
        // il y les dates initial et une formated car les function pour calculer l'age avec la date ne prennent pas la version formated des dates
        $sqlActor = 
        "SELECT 
            a.id_actor,
            a.prenom,
            a.nom, 
            a.sexe, 
            a.dateDeNaissance, 
            DATE_FORMAT(a.dateDeNaissance, '%d/%m/%Y') AS formatedDateDeNaissance,
            a.dateDeDeces,
            DATE_FORMAT(a.dateDeDeces, '%d/%m/%Y') AS formatedDateDeDeces,
            a.image
        FROM 
            actor a
        WHERE 
            a.id_actor = :idActor /* :idACtor est la valeur que je souhaite avoir mais qui n'est pas la même à chaque fois */
        GROUP BY 
            a.id_actor
        ";

        // ici je dis que idActor = le contenu de la variable idActor que j'ai en paramètre de la fonction 
        $paramsActor = [
            "idActor" => $idActor
        ];

        // je fait une requête dans casting car un acteur peut avoir des roles
        $sqlCasting =  
        "SELECT 
            ro.libelle,
            c.film_id,
            c.role_id,
            f.titre

        FROM 
            casting c
        INNER JOIN 
            role ro ON c.role_id = ro.id_role
        INNER JOIN 
            film f ON c.film_id = f.id_film
        WHERE 
            c.acteur_id = :idActor
        ";

        //plusieurs requêtes car il y a plusieurs casting possible, tout en une requête ferait des doublons
        //execute avec ma requête SQL et le params mis en place en haut, qui fera comprends a execute() que :idActor = $idActor
        $detailsActor = $dao->executerRequete($sqlActor, $paramsActor);
        $castings = $dao->executerRequete($sqlCasting, $paramsActor);
        require "./view/actor/detailsActor.php";
    }

    // function qui me permet d'afficher les infos nécessaires pour créer un acteur dans la bdd
    function addActorForm($formData = [], $globalErrorMessage = null, $formErrors = []) {
        // tous les paramètres sont des variables optionnels, elle sont présentes uniquement si il y a eu une erreur plus bas dans la function
        // les noms des colonnes que j'ai besoin d'afficher avant de pouvoir créer un acteur dans ma bdd
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces", "image"]; // il n'y a pas d'id car c'est une primary key qui est incrémenté automatiquement par la bdd

        // var qui seront utilisées dans la page pour avoir des titres et elements textes différents à chaque affichage de la page en fonction du controller appelé
        $titrePage = "Add Actor";
        $tableToFocus = "Actor";
        $actionForm = "addActor";

        // entity n'est pas nécessaire lors de l'ajout mais elle doit quand même exister donc je l'initie en NULL
        $entity = null;
        // le tout sera demandé par commonForm, "require" spécifiquement car aucune autre page ne peut l'appeler ne même temps
        require "./view/commonForm.php";
    }

    // ajoute un acteur à la base de données, cette function est appelé après avoir submit toutes les informations demandées par function précédantes
    function addActor() {

        // filtrer / nettoyer les données reçues en POST
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sexe = filter_input(INPUT_POST, "sexe", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeNaissance = filter_input(INPUT_POST, "dateDeNaissance", FILTER_SANITIZE_NUMBER_INT);
        $dateDeDeces = filter_input(INPUT_POST, "dateDeDeces", FILTER_SANITIZE_NUMBER_INT);

        // filter le nom du file upload
        $_FILES["image"]["name"] = filter_var($_FILES["image"]["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_FILES["image"]["tmp_name"] = filter_var($_FILES["image"]["tmp_name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //indique le directory ou il se sera envoyé
        $uploadDir = "./public/img/uploads/"; // l'endroit ou je veux upload l'img
        $uploadFile = $uploadDir . basename($_FILES["image"]["name"]); // le chemin final de l'img

        $newFileName = "";

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

        // faille upload - faire attention à la taille et l'extension du fichiers
        if ($_FILES["image"]["size"] > 1000000 ) {
            $formErrors["image"] = "File is too big";
        }

        if ($_FILES["image"]["type"] != "image/webp" && $_FILES["image"]["type"] != "image/jpeg" && $_FILES["image"]["type"] != "image/png") {
            $formErrors["image"] = "Wrong image format";
        }

        if(empty($formErrors["image"])) {

            // array avec les MIME types que j'accepte et leurs extensions
            $imageTypes = [
                "image/png" => ".png",
                "image/webp" => ".webp",
                "image/jpeg" => ".jpg"
            ];

            foreach($imageTypes as $mime => $ext) {

                if ($_FILES["image"]["type"] == $mime) {
                    $newFileName = uniqid().$ext;
                }
            }

            $_FILES["image"]["name"] = $newFileName;

            $uploadFile = $uploadDir . basename($_FILES["image"]["name"]); // le chemin final de l'img

            if(!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
                $formErrors["image"] = "Error";
            }
        }

        // autre règle métier / de validation du formulaire
        // if

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();
            // aucune erreur n'est detecté, je peux donc ajouter toutes mes valeurs dans la bdd
            $sql =
            "INSERT INTO 
                actor (nom, prenom, sexe, dateDeNaissance, dateDeDeces, image)
            VALUES 
                (:nom,:prenom,:sexe,:dateDeNaissance,:dateDeDeces,:image)
            ";
            
            // je reprends mes var filtrés plus haut car je sais qu'elles sont safe
            $params = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces,
                "image" => $_FILES["image"]["name"]
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

            // on redirige vers le détail du nouveau actor, grace à la ligne juste au dessus qui stock l'id 
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

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage, le formData permet d'afficher toutes les données transmises par le user
            $this->addActorForm($formData, $sqlError, $formErrors);
        }
    }

    // étant donné que cette function fait une update j'ai besoin de l'id de l'entité à update et les erreurs pour la même raison qu'au dessus
    function updateActorForm($idActor, $formData = [], $globalErrorMessage = null, $formErrors = []) {
        $fieldNames = ["nom", "prenom", "sexe", "dateDeNaissance", "dateDeDeces", "image"];
        $dao = new DAO();

        $titrePage = "Update Actor";
        $tableToFocus = "Actor";
        // cette fois j'ai un id car j'ai besoin de savoir vers quel actor redirigé la page
        $actionForm = "updateActor&id=$idActor";

        $sql = 
        "SELECT 
            id_actor,
            nom,
            prenom,
            sexe,
            dateDeNaissance,
            dateDeDeces, 
            image
        FROM 
            actor
        WHERE
            id_actor = :idActor
        ";
        
        $params = [
            "idActor" => $idActor
        ];
        
        // on utilise entity pour afficher les info déjà existante de l'elements qu'on update
        $entity = $dao->executerRequete($sql, $params);
        require "./view/commonForm.php";
    }

    // met à jour un acteur de la base de données, cette function est appelé après avoir submit toutes les informations demandées par function précédantes
    function updateActor($idActor, ) {
        
        // filtrer / nettoyer les données reçues en POST
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sexe = filter_input(INPUT_POST, "sexe", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateDeNaissance = filter_input(INPUT_POST, "dateDeNaissance", FILTER_SANITIZE_NUMBER_INT);
        $dateDeDeces = filter_input(INPUT_POST, "dateDeDeces", FILTER_SANITIZE_NUMBER_INT);

        // filter le nom du file upload
        $_FILES["image"]["name"] = filter_var($_FILES["image"]["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_FILES["image"]["tmp_name"] = filter_var($_FILES["image"]["tmp_name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //indique le directory ou il se sera envoyé
        $uploadDir = "./public/img/uploads/"; // l'endroit ou je veux upload l'img
        $uploadFile = $uploadDir . basename($_FILES["image"]["name"]); // le chemin final de l'img

        $newFileName = "";



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

        // faille upload - faire attention à la taille et l'extension du fichiers
        if ($_FILES["image"]["size"] > 1000000 ) {
            $formErrors["image"] = "File is too big";
        }

        if ($_FILES["image"]["type"] != "image/webp" && $_FILES["image"]["type"] != "image/jpeg" && $_FILES["image"]["type"] != "image/png") {
            $formErrors["image"] = "Wrong image format";
        }

        if(empty($formErrors["image"])) {

            // array avec les MIME types que j'accepte et leurs extensions
            $imageTypes = [
                "image/png" => ".png",
                "image/webp" => ".webp",
                "image/jpeg" => ".jpg"
            ];

            foreach($imageTypes as $mime => $ext) {

                if ($_FILES["image"]["type"] == $mime) {
                    $newFileName = uniqid().$ext;
                }
            }

            $_FILES["image"]["name"] = $newFileName;

            // rename("./public/img/uploads/".$_FILES["image"]["name"], "./public/img/uploads/".$newFileName);

            $uploadFile = $uploadDir . basename($_FILES["image"]["name"]); // le chemin final de l'img

            if(!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
                $formErrors["image"] = "Error";
            }
        }

        // si le formulaire est valide
        if (empty($formErrors)) {

            $dao = new DAO();

            $sql =
            "UPDATE 
                actor  
            SET 
                nom = :nom,
                prenom = :prenom,
                sexe = :sexe,
                dateDeNaissance = :dateDeNaissance,
                dateDeDeces = :dateDeDeces,
                image = :image
            WHERE 
                id_actor = :idActor
            ";

            // je recup toutes les données via le POST et les mets à jour dans la bdd
            $params = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces,
                "idActor" => $idActor,
                "image" => $_FILES["image"]["name"]
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

            // on redirige vers le détail de l'actor mis à jour
            $this->detailsActor($idActor); // contient toute la logique, jusqu'à la vue

        } else {
            // il y a eu un souci

            // préparation au renvoi des données saisies dans le formulaire
            $formData = [
                "nom" => $nom,
                "prenom" => $prenom,
                "sexe" => $sexe,
                "dateDeNaissance" => $dateDeNaissance,
                "dateDeDeces" => $dateDeDeces,
                "image" => $_FILES["image"]["name"]
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->updateActorForm($idActor, $formData, $sqlError, $formErrors);
        }    
    }

    // supprime un acteur de la bdd
    public function deleteActor($idActor) {
        $dao = new DAO ();

        $sql =
        "DELETE FROM 
            actor
        WHERE 
            id_actor = :idActor
        ";

        $params = [
            "idActor" => $idActor
        ];

        $dao->executerRequete($sql, $params);

        // une fois terminé renvoie vers la liste de l'entité
        $this->listActors();
    }
}
?>

