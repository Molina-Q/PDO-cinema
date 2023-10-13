<?php
// démarre la temporisation
ob_start();
?>

<h2>Details du film</h2>


<?php
while ($film = $detailsFilm->fetch()) {
    echo "<div>",
            "<h3>".$film["titre"]."</h3>",
            "<ul>",
                "<li>".$film["duree"]."/".$film["dateDeSortie"]."</li>",
                "<li>Directed by : ".$film["full_name"]."</li>",
            "</ul>",
        "</div>";
?>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Details du film";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
