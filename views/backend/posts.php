<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Posts -->
<div class="posts mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col">
                <a href="index.php?action=add_post" class="btn btn-primary col-md-3"><i class="bi bi-plus-circle-fill"></i> Créer un article</a>
            </div>

            <div class="col">
                <select class="form-select" aria-label="Default select example col-md-3">
                    <option selected disabled>Action grouper</option>
                    <option value="1">Publier</option>
                    <option value="2">Masquer </option>
                    <option value="3">Supprimer</option>
                </select>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <table class="table mt-5">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            </th>
                            <th scope="col">Titre</th>
                            <th scope="col">Date de publication</th>
                            <th scope="col">Date de mise à jour</th>
                            <th scope="col">Catégorie</th>
                            <th scope="col">Commentaires</th>
                            <th scope="col">Publier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $postsData as $postData ): ?>
                        <tr>
                            <th scope="row">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="<?= $postData['post_id']; ?>">
                            </th>
                            <td> <img src="<?= $postData['featured_image_path']; ?>" alt="">  <?= $postData['title']; ?></td>
                            <td><?= $postData['publication_date']; ?></td>
                            <td><?= $postData['update_date']; ?></td>
                            <td><?= $postData['category_name']; ?></td>
                            <td>230 Commentaires</td>
                            <td class="d-flex justify-content-start">
                                <div class="form-check form-switch">
                                    <input value="<?= $postData['post_id']; ?>" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
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
                        <button class="btn btn-primary col-md-3"><i class="bi bi-save-fill"></i> Enregistrer</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




