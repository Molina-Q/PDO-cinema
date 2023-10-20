<?php
// démarre la temporisation
// commentaires dans detailsActor.php
ob_start();
$count = 1;
$nbRow = $films->rowCount();
$separator = ", ";
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


if ($director = $detailsDirector->fetch()) {
?>
    <div class="interactUpdate">
        <a href="index.php?action=updateDirectorForm&id=<?= $director["id_realisateur"] ?>">
            <p>Update</p>
        </a>
    </div>
   <div class='blocDetailsDirector'>
            <h3><?= $director["prenom"] ?> <?= $director["nom"] ?></h3>
            <ul>
              <li><?= $director["sexe"] ?></li>
              <li><span>Date de naissance</span> : <?= $director["formatedDateDeNaissance"] ?></li>
<?php
                if($director["formatedDateDeDeces"]) {
?>
                    <li><span>Date de décès</span> : <?= $director["formatedDateDeDeces"] ?> (<?= showAge($director["dateDeNaissance"], $director["dateDeDeces"]) ?>) </li>
<?php
                } else {
?>
                    <li><span>Age</span> : <?= showAge($director["dateDeNaissance"]) ?></li>   
<?php
                }
?>
                <li><span>film(s) réalisé(s) : </span> 
<?php
                while ($film = $films->fetch()) {
                    if ($count == $nbRow) {
                        $separator = "";
                    }
?>
                    <a href="index.php?action=detailsFilm&id=<?= $film["id_film"] ?>"><?= $film["titre"] ?><a><?=  $separator ?>
<?php
                    $count++;
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
