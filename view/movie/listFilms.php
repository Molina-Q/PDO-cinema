<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Liste des films</h2>

<!-- <?=$films->rowCount() ?> -->

<?php
echo "<div id='listFilms'>";
while ($film = $films->fetch()) {
    echo "<div class='blocFilms'>",
            "<p>".$film["id_film"].". ".$film["titre"]."</p>";
?>
    <a href="index.php?action=detailsFilm&id=<?=$film['id_film']?>">En savoir plus</a>
<?php
    echo "</div>";
}

echo "</div>";
?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Liste des films";
$content = ob_get_clean(); 
require "./view/layout.php";
?>