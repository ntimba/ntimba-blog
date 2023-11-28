<?php $title = "Les utilisateurs" ?>
<?php ob_start(); ?>

<!-- Users -->
<div class="users mt-5 mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>
        <form action="index.php?action=user_modify" method="POST">
            <div class="row">
                <div class="col">
                    <!-- <a href="index.php?action=register" class="btn btn-primary col"><i class="bi bi-plus-circle-fill"></i> Créer un utilisateur</a> -->
                </div>

                <div class="col">
                    <select name="action" class="form-select" aria-label="Default select example col-md-3">
                        <option selected>Action grouper</option>
                        <option value="activate">Activer</option>
                        <option value="deactivate">Desactiver </option>
                        <option value="delete">Supprimer</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table mt-5">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input class="form-check-input" type="checkbox" id="selectAll" value="option1">
                                </th>
                                <th scope="col">Pseudo</th>
                                <th scope="col">Membre depuis</th>
                                <th scope="col">Mail</th>
                                <th scope="col">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($usersData as $userData): ?>
                            <tr>
                                <th scope="row">
                                    <input name="user_ids[]" class="form-check-input table-item" type="checkbox" id="inlineCheckbox1" value="<?= $userData['user_id'] ?>">
                                </th>
                                <td><img src="<?php //$userData['user_profile'] ?>" alt=""> <span><?= $userData['username'] ?></span> </td>
                                <td><?= $userData['register_datum'] ?></td>
                                <td><?= $userData['email'] ?></td>
                                <td class="d-flex justify-content-start">
                                    <div class="form-check form-switch">
                                        <input value="<?= $userData['user_id']; ?>" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?= $userData['status'] ? 'checked' : ''; ?> disabled>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col">
                        </div>
                        
                        <div class="col d-flex justify-content-end">
                            <button class="btn col-md-3"><i class="bi bi-save-fill"></i> Enregistrer</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-center">
    <!-- La pagination -->
    <?= $paginationLinks ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



