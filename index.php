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

    // si j'ai une "action "dans l'URL, cette action donnera accès à un controlleur et à la fonction demandée (si elle existe)
    if(isset($_GET['action'])){
    
        // $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS ); // car possible d'injecter du code malveillant dans l'URL
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT); // car possible d'injecter du code malveillant dans l'URL
        
        switch($_GET['action']){
            // cases en lien avec homePage
            case "homePage" : $homeController->homePage(); break;

            // cases en lien avec movie
            case "listFilms" : $movieController->listFilms(); break;
            case "detailsFilm": $movieController->detailsFilm($id); break;

            // cases en lien avec actor
            case "listActors" : $actorController->listActors(); break;
            case "detailsActor" : $actorController->detailsActor($id); break;

            // cases en lien avec director
            case "listDirectors" : $directorController->listDirectors(); break;
            case "detailsDirector" : $directorController->detailsDirector($id); break;

            // cases role
            case "listRoles" : $roleController->listRoles(); break;
            case "detailsRole" : $roleController->detailsRole($id); break;

            // cases en lien avec genre
            case "listGenres" : $genreController->listGenres(); break;
            case "detailsGenre" : $genreController->detailsGenre($id); break;
            

            // si l'action n'est pas reconnu redirige vers homePage
            default : $homeController->homePage(); break;

        }

    } else {
        // ma page par défault si l'action demandée n'est pas trouvée
        $homeController->homePage();
    }

?>
