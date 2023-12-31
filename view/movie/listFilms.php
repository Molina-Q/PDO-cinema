<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Liste des films</h2>
<div class="interactAddFilm">
    <a href="index.php?action=addFilmForm">
        <p>Add Movie!</p>
    </a>

    <a href="index.php?action=addCastingFilmForm">
        <p>Add Casting to a movie!</p>
    </a>

    <a href="index.php?action=addGenreFilmForm">
        <p>Add Genre to a movie!</p>
    </a>
</div>
    
    <div id='listFilms'>
<?php
    while ($film = $films->fetch()) {
?>
        <div class='blocFilms listBloc'>

            <a href="index.php?action=detailsFilm&id=<?=$film['id_film']?>">
                <p class="listEntities"><?=$film["titre"]?></p>
                <p>En savoir plus</p>
            </a>        
            
            <div class="deleteBloc">
                <a href="index.php?action=deleteFilm&id=<?= $film["id_film"] ?>">
                    <button class="deleteBtn"><i class="fa-solid fa-xmark"></i></button>
                </a>
            </div>

            <div class="updateBloc">
                <a href="index.php?action=updateFilmForm&id=<?= $film["id_film"] ?>">
                    <button class="updateBtn"><i class="fa-solid fa-pen"></i></button>
                </a>
            </div>

        </div>       
<?php
    }
?>
    </div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Liste des films";
$content = ob_get_clean(); 
require "./view/layout.php";
?>