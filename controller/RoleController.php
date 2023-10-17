<?php
require_once "./bdd/DAO.php";
class RoleController {
    public function listRoles() {

        $dao = new DAO();
        $sql = 
        "SELECT 
            ro.id_role, 
            ro.libelle
        FROM 
            role ro
        ";

        $roles = $dao->executerRequete($sql);
        require "./view/role/listRoles.php";
    }

    public function detailsRole($idRole) {

        $dao = new DAO();

        $paramsRole = [
            "idRole" => $idRole
        ];

        $sqlRole = 
        "SELECT
            ro.id_role,
            ro.libelle
        FROM 
            role ro
        WHERE 
            ro.id_role = :idRole
        GROUP BY 
            ro.id_role
        ";

        $sqlCasting =  
        "SELECT 
            c.acteur_id,
            c.film_id,
            a.prenom,
            a.nom,
            f.titre
        FROM 
            casting c
        INNER JOIN 
            acteur a ON c.acteur_id = a.id_acteur
        INNER JOIN
            film f ON c.film_id = f.id_film
        WHERE 
            c.role_id = :idRole
        ";
        $detailsRole = $dao->executerRequete($sqlRole, $paramsRole);
        $castings = $dao->executerRequete($sqlCasting, $paramsRole);
        require "./view/role/detailsRole.php";
    }

    function addRoleForm($formData = [], $globalErrorMessage = null, $formErrors = []) {

        $fieldNames = ["libelle"];

        $titrePage = "Add Role";
        $tableToFocus = "Role";
        $submitInput = "submit";
        $actionForm = "addRole";
        $placeholder = "Darth Vader";


        require "./view/commonForm.php";
    }

    function addRole() {
        
        // filtrer / nettoyer les données reçues en POST
        $libelle = filter_input(INPUT_POST, "libelle", FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        // init vars
        $formErrors = [];
        $isSuccess = true;
        $idRole = null;
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
            "INSERT INTO role (libelle)
            VALUES (:libelle)
            ";
    
            $params = [
                "libelle" => $libelle
            ];
    
            try {

                $isSuccess = $dao->executerRequete($sql, $params);

                $idRole = $dao->getBDD()->lastInsertId();

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
            $this->detailsRole($idRole); // contient toute la logique, jusqu'à la vue

        } else {
            // il y a eu un souci

            // préparation au renvoi des données saisies dans le formulaire
            $formData = [
                "libelle" => $libelle
            ];

            // on renvoie vers le même formulaire, en donnant les infos nécessaires à l'affichage
            $this->addRoleForm($formData, $sqlError, $formErrors);
        }

    }
}
?>