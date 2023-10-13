<?php
// démarre la temporisation
ob_start();
?>

<h2>Liste des films</h2>

<!-- <?=$films->rowCount() ?> -->

<?php
while ($film = $films->fetch()) {
    echo "<div>",
            "<p>".$film["id_film"].") ".$film["titre"]."</p>",
        "</div>";
?>
    <a href="index.php?action=detailsFilm&id=<?=$film['id_film']?>">Detail film</a>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Liste des films";
$content = ob_get_clean(); 
require "./view/layout.php";
?>