<?php
/* porte d'entrée application


On fait appel aux fichiers physiques */

    require_once "./controller/ActorController.php"; 
    require_once "./controller/MovieController.php"; 
    require_once "./controller/DirectorController.php"; 
    require_once "./controller/RoleController.php"; 
    require_once "./controller/GenreController.php"; 
    require_once "./controller/HomeController.php"; 
    require_once "./controller/searchBarController.php"; 
   
    // instancier tous nos controller
    $actorController = new ActorController();
    $movieController = new MovieController();
    $directorController = new DirectorController();
    $roleController = new RoleController();
    $genreController = new GenreController();
    $homeController = new HomeController();
    $searchController = new SearchBarController();

    // si j'ai une "action "dans l'URL, cette action donnera accès à un controlleur et à la fonction demandée (si elle existe)
    if(isset($_GET['action'])){
    
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT); // car possible d'injecter du code malveillant dans l'URL
        $idSec = filter_input(INPUT_GET, "idSec", FILTER_SANITIZE_NUMBER_INT);
        
        switch($_GET['action']){
            // cases en lien avec homePage
            case "homePage" : $homeController->homePage(); break;

            // cases en lien avec movie
            case "listFilms" : $movieController->listFilms(); break;
            case "detailsFilm": $movieController->detailsFilm($id); break;
            case "addFilmForm" : $movieController->addFilmForm(); break;
            case "addFilm" : $movieController->addFilm(); break;
            case "updateFilmForm" : $movieController->updateFilmForm($id); break;
            case "updateFilm" : $movieController->updateFilm($id); break;
            case "addGenreFilmForm" : $movieController->addGenreFilmForm(); break;
            case "addGenreFilm" : $movieController->addGenreFilm(); break;
            case "addCastingFilmForm" : $movieController->addCastingFilmForm(); break;
            case "addCastingFilm" : $movieController->addCastingFilm(); break;
            case "deleteFilm" : $movieController->deleteFilm($id); break;
            case "deleteGenreFilm" : $movieController->deleteGenreFilm($id, $idSec); break;
            case "updateGenreFilmForm" : $movieController->updateGenreFilmForm($id, $idSec); break;
            case "updateGenreFilm" : $movieController->updateGenreFilm($id, $idSec); break;
            // case "updateCastingFilmForm" : $movieController->updateCastingFilmForm($id); break;
            // case "updateCastingFilm" : $movieController->updateCastingFilm($id); break;

            // cases en lien avec actor
            case "listActors" : $actorController->listActors(); break;
            case "detailsActor" : $actorController->detailsActor($id); break;
            case "addActorForm" : $actorController->addActorForm(); break;
            case "addActor" : $actorController->addActor(); break;
            case "updateActorForm" : $actorController->updateActorForm($id); break;
            case "updateActor" : $actorController->updateActor($id); break;
            case "deleteActor" : $actorController->deleteActor($id); break;

            // cases en lien avec director
            case "listDirectors" : $directorController->listDirectors(); break;
            case "detailsDirector" : $directorController->detailsDirector($id); break;
            case "addDirectorForm" : $directorController->addDirectorForm(); break;
            case "addDirector" : $directorController->addDirector(); break;
            case "updateDirectorForm" : $directorController->updateDirectorForm($id); break;
            case "updateDirector" : $directorController->updateDirector($id); break;
            case "deleteDirector" : $directorController->deleteDirector($id); break;


            // cases role
            case "listRoles" : $roleController->listRoles(); break;
            case "detailsRole" : $roleController->detailsRole($id); break;
            case "addRoleForm" : $roleController->addRoleForm(); break;
            case "addRole" : $roleController->addRole(); break;
            case "updateRoleForm" : $roleController->updateRoleForm($id); break;
            case "updateRole" : $roleController->updateRole($id); break;
            case "deleteRole" : $roleController->deleteRole($id); break;
            
            // cases en lien avec genre
            case "listGenres" : $genreController->listGenres(); break;
            case "detailsGenre" : $genreController->detailsGenre($id); break;
            case "addGenreForm" : $genreController->addGenreForm(); break;
            case "addGenre" : $genreController->addGenre(); break;
            case "updateGenreForm" : $genreController->updateGenreForm($id); break;
            case "updateGenre" : $genreController->updateGenre($id); break;
            case "deleteGenre" : $genreController->deleteGenre($id); break;

            // case search
            case "search" : $searchController->searchBar(); break;
            
            // si action n'est pas reconnu redirige vers homePage
            default : $homeController->homePage(); break;
        } 
        
    } else {
        // ma page par défault si l'action demandée n'est pas trouvée
        $homeController->homePage();
    }


?>
