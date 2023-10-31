<?php
// démarre la temporisation
ob_start();
$count = 1;
$nbRow = $castings->rowCount();
$separator = ", ";

?>
<h2 class="titrePage">Details de l'actor</h2>
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


if ($actor = $detailsActor->fetch()) {
?>          
    <div class="interactUpdate">
        <a href="index.php?action=updateActorForm&id=<?= $actor["id_actor"] ?>">
            <p>Update</p>
        </a>
    </div>

    <div class='blocDetailsActor'>
            
        <figure class="portraitPerson">
            <img src="./public/img/uploads/<?= $actor["image"] ?>" alt="<?= $actor["prenom"].$actor["nom"] ?>">
        </figure>

        <h3><?= $actor["prenom"] ?> <?= $actor["nom"] ?></h3>

        <ul>
            <li><?= $actor["sexe"] ?></li>
            <li><span>Date de naissance</span> : <?=$actor["formatedDateDeNaissance"]?></li>
<?php
            if($actor["formatedDateDeDeces"]) { /* check si la personne à une date de decès et ensuite afficher le nécessaire */
?>
                <li><span>Date de décès</span> : <?=$actor["formatedDateDeDeces"]?> (<?=showAge($actor["dateDeNaissance"], $actor["dateDeDeces"])?>) </li>
<?php
            } else {
?>
                <li><span>Age</span> : <?=showAge($actor["dateDeNaissance"])?></li>   
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
$title = "Informations actor";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
