<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Roles</h2>
<p><a href="index.php?action=addRoleForm"><span class="link-within-text">Create</span></a> a role!</p>

    <div id='listRoles'>
<?php
    while ($role = $roles->fetch()) {
?>
        <p><a href='index.php?action=detailsRole&id=<?= $role["id_role"] ?>'><?= $role["libelle"] ?></a></p>
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
