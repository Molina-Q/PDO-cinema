<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Details d'un genre</h2>
<?php

while ($genre = $detailsGenre->fetch()) {
?>
       <div class='blocDetailsGenre'>
           <h3><?= $genre["libelle"] ?> : </h3>

<?php
}
?>
            <p>
<?php
            while ($film = $films->fetch()) {
                // if($film["film_id"] == null) {
                    ?>
                    Aucun film dans ce genre.
                    <?php
                // } else {
?>
                    <a href="index.php?action=detailsFilm&id=<?=$film["film_id"]?>"><?= $film["titre"] ?></a>, 
<?php
                // }
?>
                
<?php
            }
?>          </p>
          
        </div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Informations role";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
