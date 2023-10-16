<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Details du film</h2>

<?php
?>

<div id='detailsFilm'>
<?php
    //fetch le resultat de la requête SQL contenu dans $film
    while ($film = $detailsFilm->fetch()) {
?>
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
?>
                         <a href="index.php?action=detailsGenre&id=<?= $genre["id_genre"] ?>"><?=$genre["libelle"]?></a>,
<?php
                    }
?>
                    <li><span>Réalisé par </span><a href='index.php?action=detailsDirector&id=<?=$film["id_realisateur"]?>'><?= $film["prenom"]?> <?=$film["nom"]?></a></li>
                    
                    </li>
                    
                    <li><span>Avec</span> :
<?php
                    while ($casting = $castings->fetch()) {

?>
                        <a href='index.php?action=detailsActor&id=<?=$casting["id_acteur"]?>'><?= $casting["prenom"] ?> <?= $casting["nom"] ?></a>(<a href="index.php?action=detailsRole&id=<?= $casting["id_role"] ?>"><?= $casting["libelle"] ?></a>),
<?php
                    }
?>
                    </li>
                </ul>
            </div>
        </div>

</div>

<?php 
}
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Details du film";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
