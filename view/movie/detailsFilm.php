<?php
// démarre la temporisation
ob_start();
?>

<h2>Details du film</h2>


<?php
function splitString($idActeurs, $acteurs) {
    $expId = explode(',', $idActeurs);
    $expActeurs = explode(',', $acteurs);
    $returnValue = "";
    for($i = 0; $i < count($expId); $i++) {
        $openIdActeurs = $expId[$i];
        $openActeurs = $expActeurs[$i];
        $returnValue .= "<a href='index.php?action=detailsActor&id=$openIdActeurs'>".$openActeurs.", </a>";
    }
    return $returnValue;
}
while ($film = $detailsFilm->fetch()) {
    $afficheFilm = $film["affiche"];
        echo "<div class='blocDetailsFilm'>",
            "<figure class='afficheFilm'>
                <img src='./public/img/$afficheFilm.jpg' alt='$afficheFilm'>
            </figure>",
            "<div class='textDetailsFilm'>",
                "<h3>".$film["titre"]."</h3>",
                "<ul>",
                    "<li><span>Sortie le</span> ".$film["release_date"]."</li>",
                    "<li><span>Durée</span> : ".$film["duree"]."</li>",
                    "<li><span>Réalisé par</span>  ".$film["full_name"]."</li>",
                    "<li><span>Avec</span> :".splitString($film["idActeurs"], $film["acteurs"])."</li>",
                "</ul>",
            "</div>",
        "</div>";
?>

<?php

}

?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Details du film";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
