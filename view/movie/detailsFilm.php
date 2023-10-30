<?php
// démarre la temporisation
ob_start();
// count le nb de boucle effectué dans genre et casting pour ne pas avoir de virgule aux derniers mots
$countG = 1;
$countC = 1;
$nbRowC = $castings->rowCount();
$nbRowG = $genres->rowCount();
$separator = ", ";
?>

<h2 class="titrePage">Details du film</h2>

<!-- href="index.php?action=updateGenreForm&id=$film["id_film"] ?>" -->
<?php
?>

<?php
    //fetch le resultat de la requête SQL contenu dans $film
    if ($film = $film->fetch()) {
?>
        <div class="interactUpdateFilm">
            <a href="index.php?action=updateFilmForm&id=<?= $film["id_film"] ?>">
                <p>Update Movie</p>
            </a>

            <a href="#" id="updateGenre">
                <p>Delete Genre</p>
            </a>

            <a href="index.php?action=DeleteCastingForm&id=<?= $film["id_film"] ?>">
                <p><s>Update Casting</s></p>
            </a>
        </div>

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
                    while ($genre = $genres->fetch()) {
                        if ($countG == $nbRowG) {
                            $separator = "";
                        }
?>
                        <a class="genreFilm" href="index.php?action=detailsGenre&id=<?= $genre["id_genre"] ?>&idSec=<?= $film["id_film"] ?>"><?=$genre["libelle"]?></a><?= $separator ?>
<?php
                        $countG++;
                    }
    }
?>
                    <li><span>Réalisé par </span><a href='index.php?action=detailsDirector&id=<?=$film["id_director"]?>'><?= $film["prenom"]?> <?=$film["nom"]?></a></li>
                    
                    </li>
                    
                    <li><span>Avec</span> :
<?php
                    $separator = ", ";
                    while ($casting = $castings->fetch()) {
                        if ($countC == $nbRowC) {
                            $separator = "";
                        }
?>
                        <a href='index.php?action=detailsActor&id=<?=$casting["id_actor"]?>'><?= $casting["prenom"] ?> <?= $casting["nom"] ?></a>(<a href="index.php?action=detailsRole&id=<?= $casting["id_role"] ?>"><?= $casting["libelle"] ?></a>)<?= $separator ?>
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
