<?php
// démarre la temporisation
ob_start();
// ce if est nécessaire pour filter les add qui n'ont pas besoin de entity et donc pas besoin d'afficher des valeurs existantes
if ($entity) {
    $entity = $entity ->fetch();
}

//function qui crée les balises html du form: labelName = nom du label, $inputType = type d'input demandé, columnName est le nom de la colonne voulu dans la bdd
function makeHTMLGroup($labelName, $inputType, $columnName, $placeholder, $entity, $options = null, ) {
    $inputs = ["text", "number", "date", "time"]; 
    $stepValue = 0; // step = 1 permet d'afficher "time" en h:m:s sans affecter le reste des "date", il est initié sur zéro et switch sur 1 lors du tour de time
    $formData = [];   

    // aussi pour empecher le add d'interagir avec entity et de creer une erreur
    if($entity) {
        // stock la valeur de la colonne actuelle
        $entityName = $entity[$columnName];
        // l'utilise pour la placer dans le formData (et afficher les données dans les input)
        $formData = [$columnName => $entityName];
    }

?>
    <!--  -->
    <div class="div-label-input">
        <label for="<?= $columnName ?>" class="form-label"><?= $labelName ?></label>
<?php
    if(in_array($inputType, $inputs)) {
        if($inputType == "time") {
            $stepValue = 1;
        }
?>
        <input type="<?= $inputType ?>" id="<?= $columnName ?>" name="<?= $columnName ?>" step="<?=$stepValue?>" placeholder="<?= $placeholder ?>" value="<?= isset($formData[$columnName]) ? $formData[$columnName] : ''?>">
    </div>
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
    </div>
<?php
    }
}

function messageErrors($formErrors, $columnName) {
    if (isset($formErrors[$columnName])) {
        ?>
            <p class="error"><?= $formErrors[$columnName] ?></p>
        <?php
    }
}

?>
<h2 class="titrePage"><?=$titrePage?></h2>
<div>
    <form id="bloc-form" action="index.php?action=<?= $actionForm ?>" method="post">
        <?php
            if ($globalErrorMessage) {
?>
                <p class="error"><?= $globalErrorMessage ?></p>
<?php
            }   

            /***** genre, role *****/
            if (in_array("libelle", $fieldNames)) { // mon entité a un fields libelle
                makeHtmlGroup("Label", "text", "libelle", $placeholder, $entity); // le field "libelle" : a un label "Label"(c'est un nom pour décrire l'input), un input de type text, le nom de la colonne de la bdd que je veux modifier/créer, name et dataForm l'utilise, placeholder, entity contient l'id et le nom de la colonne de l'entity que je veux modifier(entity est utilisé uniquement durant une update) 
                messageErrors($formErrors, "libelle");  
            }            
            
            /***** acteur, realisateur *****/
            if (in_array("nom", $fieldNames)) { // mon entité a un fields nom      
                makeHtmlGroup("last Name", "text", "nom", "Dupont...", $entity);   
                messageErrors($formErrors, "nom");  
            }

            if (in_array("prenom", $fieldNames)) { // mon entité a un fields prenom
                makeHtmlGroup("first Name", "text", "prenom", "Benoit...", $entity); 
                messageErrors($formErrors, "prenom");  

            }

            if (in_array("sexe", $fieldNames)) { // mon entité a un fields sexe
                makeHtmlGroup("gender", "text", "sexe", "Homme...", $entity); 
                messageErrors($formErrors, "sexe");  

            }

            if (in_array("dateDeNaissance", $fieldNames)) { // mon entité a un fields date de naissance
                makeHtmlGroup("Birth date", "date", "dateDeNaissance", "20/01/2020", $entity); 
                messageErrors($formErrors, "dateDeNaissance");  

            }
            
            if (in_array("dateDeDeces", $fieldNames)) { // mon entité a un fields date de naissance
                makeHtmlGroup("Date of death", "date", "dateDeDeces", "2023-01-20", $entity); 
                messageErrors($formErrors, "dateDeDeces");  
            }

            /***** film *****/
            if (in_array("titre", $fieldNames)) { // mon entité a un fields titre
                makeHtmlGroup("Movie's title", "text", "titre", "Star Wars : episode X...", $entity); 
                messageErrors($formErrors, "titre");  

            }

            if (in_array("dateDeSortie", $fieldNames)) { // mon entité a un fields date de sortie 
                makeHtmlGroup("Release date", "date", "dateDeSortie", "2020-12-25", $entity); 
                messageErrors($formErrors, "dateDeSortie");  

            }     

            if (in_array("duree", $fieldNames)) { // mon entité a un fields durée
                makeHtmlGroup("Duration", "time", "duree", "2020-12-25", $entity); 
                messageErrors($formErrors, "duree");  

            }         

            if (in_array("realisateur_id", $fieldNames)) { // mon entité a un fields id realisateur (foreign key)
                makeHtmlGroup("Director", "select", "realisateur_id", "--Choose a director--", $entity, $options);  
                messageErrors($formErrors, "realisateur_id");  
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
