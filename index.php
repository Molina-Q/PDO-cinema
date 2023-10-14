<!-- porte d'entrée application -->


<!-- 
On fait appel aux fichiers physiques  -->
<?php
    require_once "./controller/ActorController.php"; 
    require_once "./controller/MovieController.php"; 
    require_once "./controller/DirectorController.php"; 
    require_once "./controller/RoleController.php"; 
    require_once "./controller/GenreController.php"; 
    require_once "./controller/HomeController.php"; 
   
    // instancier tous nos controller
    
    $actorController = new ActorController();
    $movieController = new MovieController();
    $directorController = new DirectorController();
    $roleController = new RoleController();
    $genreController = new GenreController();
    $homeController = new HomeController();

    // si j'ai une "action "dans l'URL , cette action donnera accès à un controlleur et à la fonction demandée (si elle existe)
    if(isset($_GET['action'])){
    
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS ); // car possible d'injecter du code malveillant dans l'URL
        
        switch($_GET['action']){
            case "homePage" : $homeController->homePage(); break;
            // les cases en lien avec movie
            case "listFilms" : $movieController->findAll(); break;
            case "detailsFilm" : $movieController->infosFilm($_GET["id"]); break;

            // les cases en lien avec actor
            case "listActors" : $actorController->findAll(); break;
            case "detailsActor" : $actorController->infosActeur($_GET["id"]); break;

            case "listDirectors" : $directorController->findAll(); break;

        }

    } else {
        // ma page par défault si l'action demandée n'est pas trouvée
        $homeController->homePage();
    }

?>
