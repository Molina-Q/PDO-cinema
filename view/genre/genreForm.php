<?php
// démarre la temporisation

ob_start();
// exemple de form classique demandé par l'exo
// commentaires complets dans commonForm
?>
<h2 class="titrePage">Add a Genre</h2>
<div>
    <form action="index.php?action=addGenre" method="post">

<?php
            if ($globalErrorMessage) {
                ?>
                    <p class="error"><?= $globalErrorMessage ?></p>
                <?php
            }
?>
        <!-- libelle -->
        <label for="libelle" class="form-label">Label</label>
        <input type="text" id="libelle" name="libelle" placeholder="Aventure" value="<?= isset($formData["libelle"]) ? $formData["libelle"] : "" ?>">
<?php
            if (isset($formErrors["libelle"])) {
                ?>
                    <p class="error"><?= $formErrors["libelle"] ?></p>
                <?php
            }
?>
        <!-- submit -->
        <button type="submit">Save</button>
    </form>
</div>
<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Add a Genre";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
