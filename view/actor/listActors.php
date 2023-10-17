<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Liste d'acteurs</h2>
<p><a href="index.php?action=addActorForm"><span class="link-within-text">Add</span></a> an actor!</p>

<?php
echo "<div id='listActeurs'>";

while ($acteur = $acteurs->fetch()) {
    $acteurId = $acteur["id_acteur"];
    echo "<h3><a href='index.php?action=detailsActor&id=$acteurId'>".$acteur["full_name"]."</a></h3>";
}

echo "</div>";
?>


<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Les acteurs";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
