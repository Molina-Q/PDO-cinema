<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Liste de réalisateurs</h2>
<div class="interactAdd">
    <a href="index.php?action=addDirectorForm">
        <p>Add a director!</p>
    </a>
</div>
    
<div id=listRealisateurs>
<?php
while ($realisateur = $realisateurs->fetch()) {
?>
    <div class="listBloc">
        <a class="linkEntities" href='index.php?action=detailsDirector&id=<?= $realisateur["id_realisateur"] ?>'>
            <p class="listEntities"><?= $realisateur["full_name"] ?></p>
        </a>
        
        <div class="deleteBloc">
            <a href="index.php?action=deleteDirector&id=<?= $realisateur["id_realisateur"] ?>">
                <button class="deleteBtn"><i class="fa-solid fa-xmark"></i></button>
            </a>
        </div>

        <div class="updateBloc">
            <a href="index.php?action=updateDirectorForm&id=<?= $realisateur["id_realisateur"] ?>">
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
$title = "Les réalisateurs";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
