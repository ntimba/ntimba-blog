<?php $title = $categoryData['category_name']; ?>
<?php ob_start(); ?>

<!-- Categories -->
<div class="posts mt-5 mb-5">
    <div class="container">
    <?php echo $errorHandler->displayErrors(); ?>
        <div class="row margin-top--xl">
            <div class="col-md-12">
                <h3 class="mb-2">Modifier une catégorie</h3>
                <form class="form form-floating mb-5" method="POST" action="index.php?action=update_category" >
                    <input type="hidden" name="category_id" value="<?= $categoryData['category_id'] ?>">
                    <div class="form-floating mb-3">
                        <input type="text" value="<?= $categoryData['category_name']; ?>" name="category_name" class="form-control" id="floatingCategoryName" placeholder="Nom de la catégorie" aria-labelledby="categoryNameHelpBlock">
                        <label for="floatingCategoryName">Nom de la catégorie</label>
                        <div id="" class="form-text">
                            Ce nom est utilisé un peut partout sur votre site
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" value="<?= $categoryData['category_slug']; ?>" name="category_slug" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
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
                        <textarea name="category_description" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"><?= $categoryData['category_name']; ?></textarea>
                        <label for="floatingTextarea">Description</label>

                        <div class="form-text" id="categoryParentHelpBlock">
                            Écrivez votre description
                        </div>
                    </div>

                    <button name="submit" class="mt-3 mb-4 btn">Modifier la catégorie</button>                        
                </form>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




