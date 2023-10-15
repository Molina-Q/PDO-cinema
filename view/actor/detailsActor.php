<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Details de l'acteur</h2>


<?php
function showAge($dateTimeActeur) {
    $dateTimeNow = new \DateTime("now");
    $dateTimeNaissance = new \DateTime($dateTimeActeur);
    $agePersonne = date_diff($dateTimeNaissance, $dateTimeNow)->format("%Y ans");
    return $agePersonne;
}

while ($acteur = $detailsActeur->fetch()) {
    echo "<div class='blocDetailsActor'>",
            "<h3>".$acteur["full_name"]."</h3>",
            "<ul>",
                "<li>".$acteur["sexe"]."</li>",
                "<li><span>Date de naissance</span> : ".$acteur["dateNaissance"]."</li>",
                "<li><span>Age</span> : ".showAge($acteur["dateDeNaissance"])."</li>",
                "<li><span>Rôle(s)</span> : ".$acteur["roles"]."</li>",
            "</ul>",
        "</div>";
?>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Informations acteur";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
