<?php
// démarre la temporisation
ob_start();
// count le nb de boucle effectué dans genre et casting pour ne pas avoir de virgule aux dernier mot
$countG = 1;
$countC = 1;
$nbRowC = $castings->rowCount();
$nbRowG = $genres->rowCount();
$separator = ", ";
?>

<h2 class="titrePage">Details du film</h2>

<?php
    //fetch le resultat de la requête SQL contenu dans $film
    if ($film = $film->fetch()) {
?>
        <!-- div update  -->
        <div class="interactUpdateFilm">
            <a href="index.php?action=updateFilmForm&id=<?= $film["id_film"] ?>">
                <p>Update Movie</p>
            </a>

            <a href="index.php?action=updateGenreForm&id=<?= $film["id_film"] ?>">
                <p><s>Update Genre</s></p>
            </a>

            <a href="index.php?action=updateCastingForm&id=<?= $film["id_film"] ?>">
                <p><s>Update Casting</s></p>
            </a>
        </div>

        <!-- bloc details  -->
        <div class='blocDetailsFilm'>
            <figure class='afficheFilm'>
               <img src='./public/img/<?=$film["affiche"]?>.jpg' alt='<?=$film["affiche"]?>'>
            </figure>

            <div class='textDetailsFilm'>
                <h3><?= $film["titre"] ?></h3>
                <ul>
                    <li><span>Sortie le</span> <?= $film["release_date"] ?></li>
                    <li><span>Durée</span> : <?=$film["duree"]?></li>
                    <li><span>Genre(s)</span> :
<?php
    }
                    while ($genre = $genres->fetch()) {
                        if ($countG == $nbRowG) {
                            $separator = "";
                        }
?>
                        <a href="index.php?action=detailsGenre&id=<?= $genre["id_genre"] ?>"><?=$genre["libelle"]?></a><?= $separator ?>
<?php
                        $countG++;
                    }
?>
                    </li> <!-- fin du li avec les genres -->

                    <li><span>Réalisé par </span><a href='index.php?action=detailsDirector&id=<?=$film["id_realisateur"]?>'><?= $film["prenom"]?> <?=$film["nom"]?></a></li>
                    
                    <li><span>Avec</span> :
<?php
                    $separator = ", "; /* redéclare $separator avec une virgule car il y a deux instance ou il est utilisé */ 
                    while ($casting = $castings->fetch()) {
                        if ($countC == $nbRowC) {
                            $separator = "";
                        }
?>
                        <a href='index.php?action=detailsActor&id=<?=$casting["id_acteur"]?>'><?= $casting["prenom"] ?> <?= $casting["nom"] ?></a>(<a href="index.php?action=detailsRole&id=<?= $casting["id_role"] ?>"><?= $casting["libelle"] ?></a>)<?= $separator ?>
<?php
                        $countC++;
                    }
?>
                    </li>
                </ul>
            </div>
        </div>
</div>

<?php 

// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Details du film";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
