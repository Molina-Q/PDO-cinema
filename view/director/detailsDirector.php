<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Details du réalisateur</h2>


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


while ($realisateur = $detailsDirector->fetch()) {
?>
    <div class="interactUpdate">
        <a href="index.php?action=updateDirectorForm&id=<?= $realisateur["id_realisateur"] ?>">
            <p>Update</p>
        </a>
    </div>
   <div class='blocDetailsDirector'>
            <h3><?=$realisateur["prenom"]?> <?=$realisateur["nom"]?></h3>
            <ul>
              <li><?=$realisateur["sexe"]?></li>
              <li><span>Date de naissance</span> : <?=$realisateur["formatedDateDeNaissance"]?></li>
<?php
                if($realisateur["formatedDateDeDeces"]) {
?>
                    <li><span>Date de décès</span> : <?=$realisateur["formatedDateDeDeces"]?> (<?=showAge($realisateur["dateDeNaissance"], $realisateur["dateDeDeces"])?>) </li>
<?php
                } else {
?>
                    <li><span>Age</span> : <?=showAge($realisateur["dateDeNaissance"])?></li>   
<?php
                }
?>
                <li>film(s) réalisé(s) : 
<?php
                while ($film = $films->fetch()) {
?>
                    <a href="index.php?action=detailsFilm&id=<?=$film["id_film"]?>"><?=$film["titre"]?><a>, 
<?php
                }
?>
                </li>
                    
          </ul>
      </div>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Informations réalisateur";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
