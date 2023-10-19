<?php
// démarre la temporisation
ob_start();
$count = 1;
$nbRow = $castings->rowCount();
$separator = ", ";
?>

<h2 class="titrePage">Details des roles</h2>
<?php

if ($role = $detailsRole->fetch()) {
?>
    <div class="interactUpdate">
        <a href="index.php?action=updateRoleForm&id=<?= $role["id_role"] ?>">
            <p>Update</p>
        </a>
    </div>

    <div class='blocDetailsRole'>
        <h3><?= $role["libelle"] ?></h3>
<?php
}
?>
        <p><span>Joué par</span> : 
<?php
        while ($casting = $castings->fetch()) {
            if ($count == $nbRow) {
                $separator = "";
            }
?>
            <a href="index.php?action=detailsActor&id=<?=$casting["acteur_id"]?>"><?=$casting["prenom"]?> <?=$casting["nom"]?></a>(<a href="index.php?action=detailsFilm&id=<?=$casting["film_id"]?>"><?=$casting["titre"]?></a>)<?=  $separator ?>
<?php
            $count++;
        }
?>      </p>
    </div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Informations role";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
