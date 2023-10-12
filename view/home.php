

<?php


ob_start();
?>

<div id="listCurrentlyShowing">
    <h2>Currently showing</h2>
    <div>
        <figure><img src="" alt=""></figure>
        <h3><a href="">$movieName</a></h3>
        <ul>
            <li><p>$releaseDate / $duration / <a href="">$genres</a></p></li>
            <li><p>Made by : <a href="">$director</a></p></li>
            <li><p>With : <a href="">$actors</a></p></li>
        </ul>
        <li><p>$synopsis</p></li>
    </div>

</div>

<div id="bestMovies">
    <h2>Best movies</h2>

    <div id="movieFilters">
        <div id="filterByGenres">

        </div>
        <div id="filterByReleaseDate">

        </div>
    </div>

    <div>
        <figure><img src="" alt=""></figure>
        <h3><a href="">$movieName</a></h3>
        <ul>
        <li><p>$releaseDate / $duration / <a href="">$genres</a></p></li>
            <li><p>Made by : <a href="">$director</a></p></li>
            <li><p>With : <a href="">$actors</a></p></li>
        </ul>
        <li><p>$synopsis</p></li>
    </div>
</div>

<?php 

$content = ob_get_clean(); 
require "./view/layout.php";?>
<!-- 
    temporisation de sortie PHP :
    débute avec ob_start() et se termine avec ob_get_clean()
    met en tampon les functions comme echo et le code PHP entre le début et la fin de la temporisation
    peut également être copié dans une string avec ob_get_contents()
    la temporisation n'affecte pas header() et setcookie()
-->

