<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Liste d'actors</h2>
<div class="interactAdd">
    <a  href="index.php?action=addActorForm">
        <p>Add an actor!</p>
    </a>
</div>
    
<div id='listActors'>
<?php
while ($actor = $actors->fetch()) {
    $acteurId = $actor["id_actor"];
?>
    <div class="listBloc">
        <a class="linkEntities" href='index.php?action=detailsActor&id=<?= $actor["id_actor"] ?>'>
            <p class="listEntities"><?= $actor["full_name"] ?></p>
        </a>
        
        <div class="deleteBloc">
            <a href="index.php?action=deleteActor&id=<?= $actor["id_actor"] ?>">
                <button class="deleteBtn"><i class="fa-solid fa-xmark"></i></button>
            </a>
        </div>

        <div class="updateBloc">
            <a href="index.php?action=updateActorForm&id=<?= $actor["id_actor"] ?>">
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
$title = "Les actors";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
