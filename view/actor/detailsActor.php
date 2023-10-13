<?php
// démarre la temporisation
ob_start();
?>

<h2>Details de l'acteur</h2>


<?php
while ($acteur = $detailsActeur->fetch()) {
    echo "<div>",
            "<h3>".$acteur["full_name"]."</h3>",
            "<ul>",
                "<li>".$film["duree"]."/".$film["dateDeSortie"]."</li>",
                "<li>Directed by : ".$film["full_name"]."</li>",
            "</ul>",
        "</div>";
        // afficher tous les attributs et voire pour mettre le nb de film ou ils ont joués et l'age
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
