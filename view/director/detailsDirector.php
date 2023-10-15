<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Details du réalisateur</h2>


<?php
function showAge($dateTimeReal) {
    $dateTimeNow = new \DateTime("now");
    $dateTimeNaissance = new \DateTime($dateTimeReal);
    $agePersonne = date_diff($dateTimeNaissance, $dateTimeNow)->format("%Y ans");
    return $agePersonne;
}

while ($realisateur = $detailsRealisateur->fetch()) {
    echo "<div class='blocDetailsDirector'>",
            "<h3>".$realisateur["full_name"]."</h3>",
            "<ul>",
                "<li>".$realisateur["sexe"]."</li>",
                "<li><span>Date de naissance</span> : ".$realisateur["dateNaissance"]."</li>",
                "<li><span>Age</span> :".showAge($realisateur["dateDeNaissance"])."</li>",
                "<li>Nombres de film réalisé : ".$realisateur["movieDirected"]."</li>",
            "</ul>",
        "</div>";
        // afficher tous les attributs et voire pour mettre le nb de film ou ils ont joués et l'age
?>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Informations réalisateur";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
