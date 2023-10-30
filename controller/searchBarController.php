<?php
require_once "./bdd/DAO.php";

class SearchBarController{

    // function utilisé par la search bar
    public function searchBar() {
        $dao = new DAO();

        // $srch est la variable qui contient ce qu'il y a dans la search bar 
        $srch = filter_input(INPUT_GET, "srch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $searchResults = "" ;

        // un array avec les paramètres que j'utilise dans le foreach en dessous pour me permettre de factoriser mes requêtes SQL
        $sqlBuilderCategoryField = [
            "Film" => "titre", // $category => $field, $cat est la table visé et $field le nom de la colonne voulu
            "Actor" => "CONCAT(prenom, ' ', nom)",
            "Director" => "CONCAT(prenom, ' ', nom)",
            "Role" => "libelle",
            "Genre" => "libelle"
        ];

        $sql = "";
        $isFirstEntity = true;

        foreach ($sqlBuilderCategoryField as $category => $field) {

            // ajoute "UNION" à la requête uniquement à partir de la deuxieme boucle du foreach
            if ($isFirstEntity) {
                $isFirstEntity = false;
            } else {
                $sql .= " UNION ";
            }

            // ..details".$category."&id.. me permet de donner au resultat de la requête un lien vers son détails
            $sql .= "SELECT ";
            $sql .= "CONCAT('index.php?action=details".$category."&id=', id_$category) AS link, ";
            $sql .= "$field AS label, ";
            $sql .= "'$category' AS category ";
            $sql .= "FROM ";
            $sql .= "$category ";
            $sql .= "WHERE ";
            $sql .= "$field LIKE '%$srch%' ";
        }

        // try pour catch une potentielle erreur
        try {
            // execute la requête faite plus haut 
            $searchResults = $dao->executerRequete($sql);
            // stock le contenu de toutes la requête avec un fetchAll
            $result = $searchResults->fetchAll();
            
            // l'encode en JSON pour pouvoir le récupérer sur js ensuite
            $results = json_encode($result);
            echo $results;

        } catch(\Throwable $error) {
            //si il y a une erreur le contenu sera affiché pour trouver le problème
            echo  json_encode([
                "srch" => $srch,
                "sql" => $sql,
                "request" => $dao->executerRequete($sql)
            ]);
          
        }
        
        // if ($searchResults) {
            // $result = json_encode($searchResults);
            // echo $result;
        // } else {
        //     $result = json_encode("no suggestion");
        //     echo $result;
        // }
        
    }

}

?>