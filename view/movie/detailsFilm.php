<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Details du film</h2>

<?php
// quand il y a plusieurs acteurs, leurs nom fait partie d'un string unique, cette fonction sépare leurs noms et leurs id pour les mettre dans un <a> et ensuite appliquer un GET grace à une une boucle
function splitString($idActeurs, $acteurs) {
    //sépare le string et les store dans un array en utilisant la virgule entre chaque id (1,2,3,etc...)
    $expId = explode(',', $idActeurs);
    //sépare le string et les store dans un array en utilisant la virgule entre chaque nom (jean, benoit, etc...)
    $expActeurs = explode(',', $acteurs);
    // permet de savoir combien de loop sont nécessaire (il y a deux arrays donc je peux pas utiliser forEach qui serait plus efficace)
    $nbLoop = count($expId);
    // permet de return toutes les execution de la boucle après qu'elle soit terminé sans l'arrêter comme le ferait un return;
    $returnValue = "";

    for($i = 0; $i < $nbLoop; $i++) {
        $separator = ", ";
        $openIdActeurs = $expId[$i];
        $openActeurs = $expActeurs[$i];
    
        if($i == (count($expId) - 1)) {
            $separator = " ";
        }
        $returnValue .= "<a href='index.php?action=detailsActor&id=$openIdActeurs'>".$openActeurs."</a>".$separator;
    }
    return $returnValue;
}
echo "<div id='detailsFilm'>";
//fetch le resultat de la requête SQL contenu dans $detailsFilm
while ($film = $detailsFilm->fetch()) {
    $idRealisateur = $film["id_realisateur"];
    $afficheFilm = $film["affiche"];
    echo "<div class='blocDetailsFilm'>",
            "<figure class='afficheFilm'>",
               "<img src='./public/img/$afficheFilm.jpg' alt='$afficheFilm'>",
            "</figure>",
                "<div class='textDetailsFilm'>",
                "<h3>".$film["titre"]."</h3>",
                    "<ul>",
                        "<li><span>Sortie le</span> ".$film["release_date"]."</li>",
                        "<li><span>Durée</span> : ".$film["duree"]."</li>",
                        "<li><span>Réalisé par</span> <a href='index.php?action=detailsDirector&id=$idRealisateur'>".$film["full_name"]."</a></li>",
                        "<li><span>Avec</span> : ".splitString($film["idActeurs"], $film["acteurs"])."</li>",
                    "</ul>",
                "</div>",
            "</div>";
}
echo "</div>";
?>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Details du film";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
