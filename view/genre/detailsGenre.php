<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Details d'un genre</h2>
<?php

if ($genre = $genre->fetch()) {
    ?>
        <a href="index.php?action=updateGenreForm&id=<?= $genre["id_genre"] ?>">
            <p>Update</p>
        </a>
        <div class='blocDetailsGenre'>
           <h3><?= $genre["libelle"] ?> : </h3>
           
<?php
}
?>
            <p>
<?php
                if($films->rowCount() == 0) {
                    ?>
                        <p>Aucun film dans ce genre.</p>
                    <?php
                } else {

                    while ($film = $films->fetch()) {
?>
                        <a href="index.php?action=detailsFilm&id=<?=$film["film_id"]?>"><?= $film["titre"] ?></a> 
                    
                    <?php
                    }
                }
?>
                
<?php
?>          </p>
          
        </div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Informations role";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
