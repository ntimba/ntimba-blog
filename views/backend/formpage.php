<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Formpage -->
<div class="posts mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-5">Ajouter une nouvelle page</h3>
                <form class="form-floating mb-5" >

                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                        <label for="floatingCategoryIdentifier">Titre de la page</label>
                        <div id="" class="form-text">
                            L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingCategoryName" placeholder="Nom de la catégorie" aria-labelledby="categoryNameHelpBlock">
                        <label for="floatingCategoryName">Slug</label>
                        <div id="" class="form-text">
                            Ce nom est utilisé un peut partout sur votre site
                        </div>
                    </div>

                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                        <label for="floatingTextarea">Contenu de la page</label>

                        <div class="form-text" id="categoryParentHelpBlock">
                            Écrivez le contenu de l'article
                        </div>
                    </div>

                    <button class="mt-3 mb-4 btn btn-primary">Publier</button>                        
                    <button class="mt-3 mb-4 btn btn-primary">brouillon</button>                        
                </form>
            </div>

            <div class="col-md-4">
                <div class="mb-5">
                    <div class="mt-5 mb-3">
                        <img src="assets/uploads/moi.jpg" class="img-fluid" alt="...">
                    </div>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Ajouter une image
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter une image</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Choisissez une image</label>
                                        <input class="form-control" type="file" id="formFile">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Ajouter</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>

                    
                </div>

            </div>
            
            
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




