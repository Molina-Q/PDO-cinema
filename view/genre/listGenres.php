<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Genres</h2>
<div class="interactAdd">
    <a href="index.php?action=addGenreForm">
        <p>Add a genre!</p>
    </a>
</div>

    <div id='listGenres'>
<?php
    while ($genre = $genres->fetch()) {
?>
    <div class="listBloc">
        <a class="linkEntities" href='index.php?action=detailsGenre&id=<?=$genre["id_genre"]?>'>
            <p class="listEntities"><?= $genre["libelle"] ?></p>
        </a> 

        <div class="deleteBloc">
            <a href="index.php?action=deleteGenre&id=<?= $genre["id_genre"] ?>">
                <button class="deleteBtn"><i class="fa-solid fa-xmark"></i></button>
            </a>
        </div>

        <div class="updateBloc">
            <a href="index.php?action=updateGenreForm&id=<?= $genre["id_genre"] ?>">
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
$title = "Genres";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
