<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Roles</h2>
<div class="interactAdd">
    <a href="index.php?action=addRoleForm">    
        <p>Create a role!</p>
    </a> 
</div>

    <div id='listRoles'>
<?php
    while ($role = $roles->fetch()) {
?>
    <a class="linkEntities" href='index.php?action=detailsRole&id=<?= $role["id_role"] ?>'>
        <p class="listEntities"><?= $role["libelle"] ?></p>
    </a>
<?php
    }
?>
    </div>

<?php 
// termine la temporisation, et initie les variables title et content, content qui aura tous le contenu de cette page
$title = "Roles";
$content = ob_get_clean(); 
require "./view/layout.php";
?>
