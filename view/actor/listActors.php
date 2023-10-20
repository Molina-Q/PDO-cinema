<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Liste d'acteurs</h2>

<div class="interactAdd">
    <a  href="index.php?action=addActorForm">
        <p>Add an actor!</p>
    </a>
</div>
    
<div id='listActeurs'>
<?php
// while car il y a plusieurs acteurs
while ($acteur = $acteurs->fetch()) {
?>
    <a class="linkEntities" href='index.php?action=detailsActor&id=<?= $acteur["id_acteur"] ?>'>
        <p class="listEntities"><?= $acteur["full_name"] ?></p>
    </a>
<?php
}
?>
</div>
<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Les acteurs";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
