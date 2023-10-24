<?php
// démarre la temporisation
ob_start();
?>

<h2 class="titrePage">Roles</h2>
<div class="interactAdd">
    <a href="index.php?action=addRoleForm">    
        <p>Add a role!</p>
    </a> 
</div>

    <div id='listRoles'>
<?php
    while ($role = $roles->fetch()) {
?>
    <div class="listBloc">
        <a class="linkEntities" href='index.php?action=detailsRole&id=<?= $role["id_role"] ?>'>
            <p class="listEntities"><?= $role["libelle"] ?></p>
        </a>
        <div class="deleteBloc">
            <a href="index.php?action=deleteRole&id=<?= $role["id_role"] ?>">
                <button class="deleteBtn"><i class="fa-solid fa-xmark"></i></button>
            </a>
        </div>

        <div class="updateBloc">
            <a href="index.php?action=updateRoleForm&id=<?= $role["id_role"] ?>">
                <button class="updateBtn"><i class="fa-solid fa-pen"></i></button>
            </a>
        </div>
    </div>
    
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
