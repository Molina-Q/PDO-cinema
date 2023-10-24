<?php
// require_once "./bdd/DAO.php";

class SearchBarController{

    // function utilisé par la search bar
    public function searchBar($srch) {

        $dao = new DAO();

        $sqlFilms =
        "SELECT 
            id_film AS id_search,
            titre AS label
        FROM 
            film
        ";

        $sqlActors =
        "SELECT 
            id_acteur AS id_search,
            CONCAT(prenom, ' ', nom) AS  label
        FROM 
            acteur 
        ";

        $sqlDirectors =
        "SELECT 
            id_realisateur AS id_search,
            CONCAT(prenom, ' ', nom) AS label
        FROM 
            realisateur
        ";

        $sqlRoles =
        "SELECT 
            id_role AS id_search,
            libelle AS label
        FROM 
            role
        ";

        $sqlGenres =
        "SELECT 
            id_genre AS id_search,
            libelle AS label
        FROM 
            genre
        ";

        $films = $dao->executerRequete($sqlFilms);  
        $actors = $dao->executerRequete($sqlActors);  
        $directors = $dao->executerRequete($sqlDirectors);  
        $roles = $dao->executerRequete($sqlRoles);  
        $genres = $dao->executerRequete($sqlGenres);  

        $searchArray = [];

        // remplis un array associatif avec les id, label et categorie des films, acteurs et realisateurs
        while($film = $films->fetch()) {
            $searchArray["articles"][] = [ 
                "label" => $film["label"],
                "id" => "index.php?action=detailsFilm&id=".$film["id_search"],
                "category" => "Film"
            ];
        }

        while($actor = $actors->fetch()) {
            $searchArray["articles"][] = [
                "label" => $actor["label"], 
                "id" => "index.php?action=detailsActor&id=".$actor["id_search"],
                "category" => "Actor" 
            ];
        }   

        while($director = $directors->fetch()) {
            $searchArray["articles"][] = [
                "label" => $director["label"],
                "id" => "index.php?action=detailsDirector&id=".$director["id_search"],
                "category" => "Director"
            ];
        }

        while($role = $roles->fetch()) {
            $searchArray["articles"][] = [
                "label" => $role["label"],
                "id" => "index.php?action=detailsDirector&id=".$role["id_search"],
                "category" => "Role"
            ];
        }

        while($genre = $genres->fetch()) {
            $searchArray["articles"][] = [
                "label" => $genre["label"],
                "id" => "index.php?action=detailsDirector&id=".$genre["id_search"],
                "category" => "Genre"
            ];
        }
        
        $hint = "";

        // si la search bar n'est pas vide
        if ($srch !== "") {

            $srch = strtolower($srch); // stock le texte écris dans la bar de recherche et le met en lowercase

            $srchLen = strlen($srch); // stock la longueur du string écris dans la bar

            foreach($searchArray["articles"] as $element) { // boucle sur l'array associatif searchArray

                if ($srch == strtolower($element["category"])) {
                    $idSearch = $element["id"];
                    if ($hint === "") {
                        $hint =
                        "<a href='$idSearch'>
                            <p><span>".$element["category"]." -> </span>".$element['label']."</p>
                        </a>";

                    } else {

                        $hint .=
                        "<a href='$idSearch'>
                            <p><span>".$element["category"]." -> </span>".$element['label']."</p>
                       </a>";

                    }

                    /* j'hésite entre deux système de recherche : 
                    1 => cherche un resultat qui CONTIENT le string écrit dans la search bar
                    2 => cherche un resultat qui COMMENCE par le string écrit dans la search bar 
                    potentiel solution : mettre en place une radio ou un btn qui permet au user de choisir le système voulu
                    */
                } else if (str_contains(strtolower($element["label"]), $srch) || str_contains($element["label"], $srch)) { 
                // } else if (stristr($srch, substr($element["label"], 0, $srchLen))) {  

                    // cherche si il existe des valeurs dont le label commence par le string écris dans la bar
                    // substr([mot de l'array], [index string(0 est la première lettre)], [nb de caractère à return]) return un string 
                    // stristr([string dans la search bar], substr()) va return tous les label qui commence par le string écris dans la search bar

                    $idSearch = $element["id"];
                    if ($hint === "") {
                        $hint =
                        "<a href='$idSearch'>
                            <p><span>".$element["category"]." -> </span>".$element['label']."</p>
                        </a>";

                    } else {

                        $hint .=
                        "<a href='$idSearch'>
                            <p><span>".$element["category"]." -> </span>".$element['label']."</p>
                       </a>";
                       
                    }
                }
            }
            
        }
        
        // si il n'y a aucun resultat 
        echo $hint === "" ? "no suggestion" : $hint;
        
    }

}

?>