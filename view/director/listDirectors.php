<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Liste de réalisateurs</h2>

<?php
echo "<div id=listRealisateurs>"; 

while ($realisateur = $realisateurs->fetch()) {
    $realisateurId = $realisateur["id_realisateur"];
    echo "<h3><a href='index.php?action=detailsDirector&id=$realisateurId'>".$realisateur["full_name"]."</a></h3>";
}

echo "</div>";
?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Les réalisateurs";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
