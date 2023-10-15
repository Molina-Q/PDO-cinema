<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Genres</h2>


<?php
while ($genre = $genres->fetch()) {
    $genreId = $genre["id_genre"];
    echo "<h3><a href='index.php?action=detailsGenre&id=$genreId'>".$genre["libelle"]."</a></h3>";
?>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Genres";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
