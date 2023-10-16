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
}
?>