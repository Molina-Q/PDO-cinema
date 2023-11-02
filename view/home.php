<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Informations base de données</h2>

<div id='homePage'>
<?php
    while ($film = $films->fetch()) {
?>
    <div>
        <p>Le site contient <?= $film["nb_films"] ?> films</p>
<?php
    }
    while ($director = $directors->fetch()) {
?>
        <p><?= $director["nb_realisateurs"] ?> réalisateurs</p>
<?php
    }
    while ($actor = $actors->fetch()) {
?>
        <p><?= $actor["nb_acteurs"] ?> actors</p>
    </div>
<?php
    }
?>
</div>
<?php
$title = "Allocine";
$content = ob_get_clean(); 
require "./view/layout.php";
?>


