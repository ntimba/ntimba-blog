<?php $title = "Les articles" ?>
<?php ob_start(); ?>

<!-- Posts -->
<div class="posts mt-5 mb-5">
    <div class="container">
    <?php echo $errorHandler->displayErrors(); ?>
        <form class="" action="index.php?action=post_modify" method="POST">
            <div class="row">
                <div class="col">
                    <a href="index.php?action=add_post" class="btn col-md-3">
                        <i class="bi bi-plus-circle-fill"></i> 
                        Article
                    </a>
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
                    <table class="table p-3 mt-5">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th scope="col">Titre</th>

                                <th scope="col" class="d-none d-sm-table-cell">Date de mise à jour</th>
                                <th scope="col" class="d-none d-sm-table-cell">Catégorie</th>

                                <th scope="col" class="d-none d-sm-table-cell">Publier</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $postsData as $postData ): ?>
                            <tr>
                                <th scope="row">
                                    <input name="post_ids[]" class="form-check-input table-item" type="checkbox" id="inlineCheckbox1" value="<?= $postData['post_id']; ?>">
                                </th>
                                <td class="d-flex flex-row">
                                    <div class="thumbnail-container">
                                        <?php if($postData['featured_image_path'] != NULL): ?>
                                        <img class="thumbnail-image rounded-3 object-cover thumbnail-s" src="<?= $postData['featured_image_path']; ?>" alt="">
                                        <?php endif; ?>
                                    </div>
                                    <div class="ps-2">
                                        <div><?= $postData['title']; ?></div>
                                        <div><?= $postData['publication_date']; ?></div>
                                    </div>
                                </td>

                                <td class="d-none d-sm-table-cell"><?= $postData['update_date']; ?></td>
                                <td class="d-none d-sm-table-cell"><?= $postData['category_name']; ?></td>
                                <td class="d-none d-sm-table-cell d-flex justify-content-start">
                                    <div class="form-check form-switch">
                                        <input value="<?= $postData['post_id']; ?>" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?= $postData['status'] ? 'checked' : ''; ?> disabled>
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




