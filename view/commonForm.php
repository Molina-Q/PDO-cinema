<?php
// démarre la temporisation
ob_start();


//function qui crée les balises html du form: labelName = nom du label, $inputType = type d'input demandé, columnName est le nom de la colonne voulu dans la bdd
function makeHTMLGroup($labelName, $inputType, $columnName, $placeholder, $options = null) {
    $inputs = ["text", "number", "date", "time"]; 
?>
    <label for="<?= $columnName ?>" class="form-label"><?= $labelName ?></label>
<?php
    if(in_array($inputType, $inputs)) {
?>
        <input type="<?= $inputType ?>" id="<?= $columnName ?>" name="<?= $columnName ?>" step="2" placeholder="<?= $placeholder ?>" value="<?= isset($formData[$columnName]) ? $formData[$columnName] : "" ?>"> 
<?php
    } else {
        ?>
        <select name="<?= $columnName ?>" id="<?= $columnName ?>">
            <option value="<?=$placeholder?>"><?= $inputType ?></option>
<?php 
            while($option = $options->fetch()) {
?>
                <option value="<?= $option["id_realisateur"] ?>"> <?= $option["prenom"]?> <?=$option["nom"]?></option>
                <?php
            }
            ?>
        </select>
<?php
    }
}

?>
<!-- <p>Want to <a href="index.php?action=commonForm&id=<?= $idOtherPage ?>"><?= $otherPage ?> </a>instead ?</p> -->


<h2 class="titrePage"><?=$titrePage?></h2>

<div>
    <form action="index.php?action=<?= $actionForm ?>" method="post">
        <?php


            if (in_array("libelle", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("Label", "text", "libelle", $placeholder); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }            
            
            if (in_array("nom", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("lastName", "text", "nom", "Dupont"); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }

            if (in_array("prenom", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("firstName", "text", "prenom", "Benoit"); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }

            if (in_array("sexe", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("gender", "text", "sexe", "Homme"); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }

            if (in_array("dateDeNaissance", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("Birth date", "date", "dateDeNaissance", "2023-01-20"); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }

            if (in_array("titre", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("titre du film", "text", "titre", "Star Wars : episode X"); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }

            if (in_array("dateDeSortie", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("Release date", "date", "dateDeSortie", "2020-12-25"); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }     

            if (in_array("duree", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("Duration", "time", "duree", "2020-12-25"); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }         

            if (in_array("realisateur_id", $fieldNames)) { // mon truc a un fields libelle
                makeHtmlGroup("Director", "select", "realisateur_id", "--Choose a director--", $options); // ce fields libelle a besoin d'un label "Label", d'un "input", et d'autres truc utilise le terme "libelle"
                //je peux encore ajouter un placeholder en ensuite 
            }            
            
        ?>
            <button type="submit">Save</button>
        
        
    </form>
</div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = $titrePage;
$content = ob_get_clean(); 
require "./view/layout.php";
?>
