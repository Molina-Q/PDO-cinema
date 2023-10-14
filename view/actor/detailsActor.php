<?php
// démarre la temporisation
ob_start();
?>

<h2>Details de l'acteur</h2>


<?php
function showAge($dateTimeActeur) {
    $dateTimeNow = new \DateTime("now");
    $dateTimeNaissance = new \DateTime($dateTimeActeur);
    $ageActeur = date_diff($dateTimeNaissance, $dateTimeNow)->format("%Y ans");
    return $ageActeur;
}

while ($acteur = $detailsActeur->fetch()) {
    echo "<div>",
            "<h3>".$acteur["full_name"]."</h3>",
            "<ul>",
                "<li>".$acteur["sexe"]."</li>",
                "<li>".$acteur["dateNaissance"]."</li>",
                "<li>".showAge($acteur["dateDeNaissance"])."</li>",
            "</ul>",
        "</div>";
        // afficher tous les attributs et voire pour mettre le nb de film ou ils ont joués et l'age
?>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "info acteur";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
