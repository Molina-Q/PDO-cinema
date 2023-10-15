<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Informations base de données</h2>

<?php
echo "<div id='homePage'>";
while ($count = $countBDD->fetch()) {
    echo "<div>",
            "<p>Le site contient ".$count["nb_films"]." films</p>",
            "<p>".$count["nb_realisateurs"]." réalisateurs</p>",
            "<p>".$count["nb_acteurs"]." acteurs</p>",
        "</div>";
}
echo "</div>";
?>
<?php 
// termine la temporisation, et initie la var title et content, content qui aura tout le contenu de cette page
$title = "Allocine";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
<!-- 
    temporisation de sortie PHP :
    débute avec ob_start() et se termine avec ob_get_clean()
    ob_get_clean() return le tampon de sortie et le supprime, donc il faut stocker le tampon dans une var pour ne pas le perdre
    met en tampon les functions comme echo et le code PHP entre le début et la fin de la temporisation
    peut également être copié dans une string avec ob_get_contents()
    la temporisation n'affecte pas header() et setcookie()
-->

