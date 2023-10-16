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
    echo $agePersonne;
}

function showAgeDecede($dateTimeNaissance, $dateTimeDeces) {
    $newTimeNaissance = new \DateTime($dateTimeNaissance);
    $newTimeDeces = new \DateTime($dateTimeDeces);
    $agePersonne = date_diff($newTimeDeces, $newTimeNaissance)->format("%Y ans");
    return $agePersonne;
}


while ($acteur = $detailsActeur->fetch()) {
?>
       <div class='blocDetailsActor'>
           <h3><?= $acteur["prenom"] ?> <?= $acteur["nom"] ?></h3>

            <ul>
                <li><?= $acteur["sexe"] ?></li>
                <li><span>Date de naissance</span> : <?=$acteur["formatedDateDeNaissance"]?></li>
<?php
                if($acteur["formatedDateDeDeces"]) {
?>
                    <li><span>Date de décès</span> : <?=$acteur["formatedDateDeDeces"]?> (<?=showAgeDecede($acteur["dateDeNaissance"], $acteur["dateDeDeces"])?>) </li>
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
?>
                <a href='index.php?action=detailsRole&id=<?= $casting["role_id"] ?>'><?=$casting["libelle"]?></a>(<a href="index.php?action=detailsFilm&id=<?=$casting["film_id"]?>" ><?=$casting["titre"]?></a>),
 
<?php
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
