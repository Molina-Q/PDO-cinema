<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Genres</h2>


    <div id='listGenres'>
<?php
    while ($genre = $genres->fetch()) {
?>
        <p><a href='index.php?action=detailsGenre&id=<?=$genre["id_genre"]?>'><?=$genre["libelle"]?></a></p>
<?php
    }
?>
</div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Genres";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
