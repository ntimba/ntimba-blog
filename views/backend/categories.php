<?php $title = "Les catégories" ?>
<?php ob_start(); ?>

<!-- Categories -->
<div class="posts mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>

        <div class="row margin-top--xl">
            <div class="col-md-6">
                <h3 class="">Ajouter une nouvelle catégorie</h3>
                <form class="form form-floating mb-5" method="POST" action="index.php?action=create_category" >

                    <div class="form-floating mb-3">
                        <input type="text" name="category_name" class="form-control" id="floatingCategoryName" placeholder="Nom de la catégorie" aria-labelledby="categoryNameHelpBlock">
                        <label for="floatingCategoryName">Nom de la catégorie</label>
                        <div id="" class="form-text">
                            Ce nom est utilisé un peut partout sur votre site
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="category_slug" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                        <label for="floatingCategoryIdentifier">Identifiant</label>
                        <div id="" class="form-text">
                            L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <select name="id_category_parent" class="form-select" id="floatingSelect" aria-labelledby="categoryParentHelpBlock" aria-label="Floating label select example">
                            <option value="aucune" selected>Aucune</option>
                            <?php foreach( $categoriesData as $categoryData ):  ?>
                                <option value="<?=  $categoryData['category_id']; ?>"><?=  $categoryData['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="floatingSelect">Parent</label>
                        
                        <div class="form-text" id="categoryParentHelpBlock">
                            Choisissez le parent d'une catégorie
                        </div>
                    </div>

                    <div class="form-floating">
                        <textarea name="category_description" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                        <label for="floatingTextarea">Description</label>

                        <div class="form-text" id="categoryParentHelpBlock">
                            Écrivez votre description
                        </div>
                    </div>

                    <button name="submit" class="mt-3 mb-4 btn">Créer une catégorie</button>                        
                </form>
            </div>

            <div class="col-md-6">
                <form action="index.php?action=modify_category" method="POST">
                    <div class="row">

                        <div class="col-md-12">
                            <select name="category_modify" class="form-select" aria-label="Default select example col-md-3">
                                <option selected>Action grouper</option>
                                <option value="update">Modifier</option>
                                <option value="delete">Supprimer</option>
                            </select>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <input class="form-check-input" type="checkbox" id="selectAll" value="option1">
                                    </th>
                                    <th scope="col">Catégorie</th>
                                    <th scope="col">Catégorie parent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $categoriesData as $categoryData ):  ?>
                                <tr>
                                    <th scope="row">
                                        <input name="category_ids[]" class="form-check-input table-item" type="checkbox" id="inlineCheckbox1" value="<?=  $categoryData['category_id']; ?>">
                                    </th>
                                    <td><a class="link--primary" href="index.php?action=read_category&id=<?= $categoryData['category_id'] ?>"><?=  $categoryData['category_name']; ?></a></td>
                                    <td><?=  $categoryData['category_parent_name']; ?></td>
                                </tr>
                                <?php endforeach;  ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                    </div> 
                    <div class="col d-flex justify-content-end">
                        <button name="submit" class="btn col-md-3"><i class="bi bi-check2-square"></i> Appliquer</button>
                    </div>
                </div>
            </form>
            </div>   
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



