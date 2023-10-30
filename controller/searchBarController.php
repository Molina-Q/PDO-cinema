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

        try {
          
            $searchResults = $dao->executerRequete($sql);
            $result = $searchResults->fetchAll();
            
            $results = json_encode($result);
            echo $results;

        } catch(\Throwable $error) {

            echo  json_encode([
                "srch" => $srch,
                "sql" => $sql,
                "request" => $dao->executerRequete($sql)
            ]);
          
        }
        
        // $searchResults = $searchResults->fetchAll();
        
        // $hint = "";

        // // si la search bar n'est pas vide
        // if ($srch !== "") {

        //     $srch = strtolower($srch); // stock le texte écris dans la bar de recherche et le met en lowercase

        //     $srchLen = strlen($srch); // stock la longueur du string écris dans la bar

        //     foreach($searchArray["articles"] as $element) { // boucle sur l'array associatif searchArray

        //         if ($srch == strtolower($element["category"])) {
        //             $idSearch = $element["id"];
        //             if ($hint === "") {
        //                 $hint =
        //                 "<a href='$idSearch'>
        //                     <p><span>".$element["category"]." -> </span>".$element['label']."</p>
        //                 </a>";

        //             } else {

        //                 $hint .=
        //                 "<a href='$idSearch'>
        //                     <p><span>".$element["category"]." -> </span>".$element['label']."</p>
        //                </a>";

        //             }

        //             /* j'hésite entre deux système de recherche : 
        //             1 => cherche un resultat qui CONTIENT le string écrit dans la search bar
        //             2 => cherche un resultat qui COMMENCE par le string écrit dans la search bar 
        //             potentiel solution : mettre en place une radio ou un btn qui permet au user de choisir le système voulu
        //             */
        //         } else if (str_contains(strtolower($element["label"]), $srch) || str_contains($element["label"], $srch)) { 
        //         // } else if (stristr($srch, substr($element["label"], 0, $srchLen))) {  

        //             // cherche si il existe des valeurs dont le label commence par le string écris dans la bar
        //             // substr([mot de l'array], [index string(0 est la première lettre)], [nb de caractère à return]) return un string 
        //             // stristr([string dans la search bar], substr()) va return tous les label qui commence par le string écris dans la search bar

        //             $idSearch = $element["id"];
        //             if ($hint === "") {
        //                 $hint =
        //                 "<a href='$idSearch'>
        //                     <p><span>".$element["category"]." -> </span>".$element['label']."</p>
        //                 </a>";

        //             } else {

        //                 $hint .=
        //                 "<a href='$idSearch'>
        //                     <p><span>".$element["category"]." -> </span>".$element['label']."</p>
        //                </a>";
                       
        //             }
        //         }
        //     }
            
        // }
        
        // // si il n'y a aucun resultat 
        // echo $hint === "" ? "no suggestion" : $hint;
        
        // echo json_encode($searchResults);
        // echo json_encode($searchResults ? $searchResults : "no suggestion");

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