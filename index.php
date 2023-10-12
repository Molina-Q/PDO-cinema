<!-- porte d'entrée application -->


<!-- 
On fait appel aux fichiers physiques  -->
<?php
    require_once "./controller/ActorController.php"; 
    require_once "./controller/MovieController.php"; 
    require_once "./controller/DirectorController.php"; 
    require_once "./controller/RoleController.php"; 
    require_once "./controller/GenreController.php"; 
    require_once "./controller/AccueilController.php"; 
   
        // instancier tous nos controller
  
    $actorController = new ActorController();

// je switch entre difféents case
// si j'ai une "action "dans l'URL , cette action donnera accès à un controlleur et à la fonction demandée (si elle existe)
if(isset($_GET['action'])){
    
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING); // car possible d'injecter du code malveillant dans l'URL
    
        switch($_GET['action']){
            case "listFilms" : $ctrlFilm->findAll(); break;
            case "detailFilm" : $ctrlFilm->findOneById($id); break;

            
        }
    }else{
        $ctrlAccueil->pageAccueil();
        // ma page par défault si l'action demandée n'est pas trouvée
    }


?>
