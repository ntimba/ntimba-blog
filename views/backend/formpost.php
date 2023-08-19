<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Formpost -->
<div class="formpost mt-5 mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-5">Ajouter un nouveau billet</h3>

                <form class="form-floating mb-5" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">                            
                            <div class="form-floating mb-3">
                                <input name="title" type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                                <label for="floatingCategoryIdentifier">Titre de l'article</label>
                                <div id="" class="form-text">
                                    L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                                </div>
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input name="slug" type="text" class="form-control" id="floatingCategoryName" placeholder="Nom de la catégorie" aria-labelledby="categoryNameHelpBlock">
                                <label for="floatingCategoryName">Slug</label>
                                <div id="" class="form-text">
                                    Ce nom est utilisé un peut partout sur votre site
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <select name="id_category" class="form-select" id="floatingSelect" aria-labelledby="categoryParentHelpBlock" aria-label="Floating label select example">
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
                                <textarea name="content" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                <label for="floatingTextarea">Contenu de l'article</label>

                                <div class="form-text" id="categoryParentHelpBlock">
                                    Écrivez le contenu de l'article
                                </div>
                            </div>


                            <div class="col">   
                                <button name="action" value="publish_post" class="mt-3 mb-4 btn btn-primary d-inline-block">Publier</button>                        
                                <button name="action" value="draft_post" class="mt-3 mb-4 btn btn-primary">Enregistrer le brouillon</button> 
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h3>Image mise en avant</h3>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Définir l'image mise en avant
                            </button>

                            <div class="mt-5 mb-3">
                                <!-- <img src="assets/uploads/moi.jpg" class="img-fluid" alt="..."> -->
                                <img id="imagePreview" src="#" class="img-fluid" alt="Image Preview" style="display: none;">
                            </div>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter une image</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Choisissez une image</label>
                                                <input name="featured_image" class="form-control" type="file" id="formFile">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
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