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
while ($acteur = $acteurs->fetch()) {
    $acteurId = $acteur["id_acteur"];
?>
    <div class="listBloc">
        <a class="linkEntities" href='index.php?action=detailsActor&id=<?= $acteur["id_acteur"] ?>'>
            <p class="listEntities"><?= $acteur["full_name"] ?></p>
        </a>
        
        <div class="deleteBloc">
            <a href="index.php?action=deleteActor&id=<?= $acteur["id_acteur"] ?>">
                <button class="deleteBtn"><i class="fa-solid fa-xmark"></i></button>
            </a>
        </div>

        <div class="updateBloc">
            <a href="index.php?action=updateActorForm&id=<?= $acteur["id_acteur"] ?>">
                <button class="updateBtn"><i class="fa-solid fa-pen"></i></button>
            </a>
        </div>
    </div>
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
