<?php $title = "Les pages" ?>
<?php ob_start(); ?>

<!-- Posts -->
<div class="posts mt-5 mb-5">
    <div class="container">
    <?php echo $errorHandler->displayErrors(); ?>
        <form action="index.php?action=page_modify" method="POST">
            <div class="row">
                <div class="col">
                    <a href="index.php?action=add_page" class="btn col-md-3"><i class="bi bi-plus-circle-fill"></i> Créer une page</a>
                </div>
                
                <div class="col">
                    <select name="action" class="form-select" aria-label="Default select example col-md-3">
                        <option selected disabled>Action grouper</option>
                        <option value="publish">Publier</option>
                        <option value="unpublish">Dépublier</option>
                        <option value="update">Modifier</option>
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
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th scope="col">Titre</th>
                                <th scope="col">Date de publication</th>
                                <th scope="col">Date de mise à jour</th>

                                <th scope="col">Publier</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $pagesData as $pageData ): ?>
                            <tr>
                                <th scope="row">
                                    <input name="page_ids[]" class="form-check-input table-item" type="checkbox" id="inlineCheckbox1" value="<?= $pageData['page_id']; ?>">
                                </th>
                                <td><img src="<?= $pageData['featured_image_path']; ?>" alt=""> <?= $pageData['title']; ?> </td>
                                <td><?= $pageData['publication_date']; ?></td>
                                <td><?= $pageData['update_date']; ?></td>

                                <td class="d-flex justify-content-start">
                                    <div class="form-check form-switch">
                                        <input value="<?= $pageData['page_id']; ?>" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?= $pageData['status'] ? 'checked' : ''; ?> disabled>
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
                            <button name="submit" class="btn col-md-3"><i class="bi bi-save-fill"></i> Enregistrer</button>
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




