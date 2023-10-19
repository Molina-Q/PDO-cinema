<?php
// démarre la temporisation
ob_start();
$count = 1;
$nbRow = $castings->rowCount();
$separator = ", ";

?>
<h2 class="titrePage">Details de l'acteur</h2>
<?php

function showAge($dateTimeNaissance,  $dateTimeDeces = null) {
    if(isset($dateTimeDeces)) {
        $dateTimeInterval = new \DateTime($dateTimeDeces);
    } else {
        $dateTimeInterval = new \DateTime("now");
    }

    $newTimeNaissance = new \DateTime($dateTimeNaissance);
    $agePersonne = date_diff($newTimeNaissance, $dateTimeInterval)->format("%Y ans");
    return $agePersonne;
}


if ($acteur = $detailsActeur->fetch()) {
    ?>          
    <div class="interactUpdate">
        <a href="index.php?action=updateActorForm&id=<?= $acteur["id_acteur"] ?>">
            <p>Update</p>
        </a>
    </div>

    <div class='blocDetailsActor'>
        <h3><?= $acteur["prenom"] ?> <?= $acteur["nom"] ?></h3>

        <ul>
            <li><?= $acteur["sexe"] ?></li>
            <li><span>Date de naissance</span> : <?=$acteur["formatedDateDeNaissance"]?></li>
<?php
            if($acteur["formatedDateDeDeces"]) { /* check si la personne à une date de decès et ensuite afficher le nécessaire */
?>
                <li><span>Date de décès</span> : <?=$acteur["formatedDateDeDeces"]?> (<?=showAge($acteur["dateDeNaissance"], $acteur["dateDeDeces"])?>) </li>
<?php
            } else {
?>
                <li><span>Age</span> : <?=showAge($acteur["dateDeNaissance"])?></li>   
<?php
            }
?>
            <li><span>Rôle(s)</span> : 
<?php
}
        while ($casting = $castings->fetch()) {
            if ($count == $nbRow) {
                $separator = "";
            }
?>
            <a href='index.php?action=detailsRole&id=<?= $casting["role_id"] ?>'><?=$casting["libelle"]?></a>(<a href="index.php?action=detailsFilm&id=<?=$casting["film_id"]?>" ><?=$casting["titre"]?></a>)<?= $separator ?>
<?php
            $count++;
        }
?>
            </li>
        </ul>
    </div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Informations acteur";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
