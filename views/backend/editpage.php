<?php $title = $page->getTitle(); ?>
<?php ob_start(); ?>

<!-- Formpost -->
<div class="formpost mt-5 mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>
        <div class="row margin-top--xl">
            <div class="col-md-12">
                <h3 class="mb-5">Modifier la page</h3>

                <form class="form form-floating mb-5" action="index.php?action=update_page&id=<?= $page->getId() ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">   
                            <div class="form-floating mb-3">
                                <input name="title" value="<?= $page->getTitle() ?>" type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                                <label for="floatingCategoryIdentifier">Titre de l'article</label>
                                <div id="" class="form-text">
                                    L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                                </div>
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input name="slug" value="<?= $page->getSlug() ?>" type="text" class="form-control" id="floatingCategoryName" placeholder="Nom de la catégorie" aria-labelledby="categoryNameHelpBlock">
                                <label for="floatingCategoryName">Slug</label>
                                <div id="" class="form-text">
                                    Ce nom est utilisé un peut partout sur votre site
                                </div>
                            </div>
                            
                            <div class="form-floating">
                                <textarea name="content" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"><?= $page->getContent() ?></textarea>
                                <label for="floatingTextarea">Contenu de l'article</label>

                                <div class="form-text" id="categoryParentHelpBlock">
                                    Écrivez le contenu de l'article
                                </div>
                            </div>

                            <div class="col">   
                                <button name="action" value="publish" class="mt-3 mb-4 btn d-inline-block">Publier</button>                        
                                <button name="action" value="draft" class="mt-3 mb-4 btn">Enregistrer le brouillon</button> 
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h3>Image mise en avant</h3>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Choisissez une image</label>
                                <input name="featured_image" class="form-control" type="file" id="formFile">
                            </div>
                            
                            <div class="mt-5 mb-3">
                                <img id="imagePreview" src="#" class="img-fluid" alt="Image Preview" style="display: none;">
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="mb-5">
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.getElementById('formFile').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            document.getElementById('imagePreview').style.display = "block";
            document.getElementById('imagePreview').src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').src = "";
        }
    });

</script>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




