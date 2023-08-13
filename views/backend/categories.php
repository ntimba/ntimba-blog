<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Categories -->
<div class="posts mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-5">Ajouter une nouvelle catégorie</h3>
                <form class="form-floating mb-5" >

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingCategoryName" placeholder="Nom de la catégorie" aria-labelledby="categoryNameHelpBlock">
                        <label for="floatingCategoryName">Nom de la catégorie</label>
                        <div id="" class="form-text">
                            Ce nom est utilisé un peut partout sur votre site
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                        <label for="floatingCategoryIdentifier">Identifiant</label>
                        <div id="" class="form-text">
                            L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <select class="form-select" id="floatingSelect" aria-labelledby="categoryParentHelpBlock" aria-label="Floating label select example">
                            <option value="aucune" selected>Aucune</option>
                            <option value="0">Défaut</option>
                            <option value="1">Crypto</option>
                            <option value="2">Voyage</option>
                        </select>

                        <label for="floatingSelect">Parent</label>
                        
                        <div class="form-text" id="categoryParentHelpBlock">
                            Choisissez le parent d'une catégorie
                        </div>
                    </div>


                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                        <label for="floatingTextarea">Description</label>

                        <div class="form-text" id="categoryParentHelpBlock">
                            Écrivez votre description
                        </div>
                    </div>

                    <button class="mt-3 mb-4 btn btn-primary">Créer une catégorie</button>                        
                </form>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <select class="form-select" aria-label="Default select example col-md-3">
                            <option selected>Action grouper</option>
                            <option value="2">Masquer</option>
                            <option value="3">Supprimer</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </th>
                                    <th scope="col">Catégorie</th>
                                    <th scope="col">Catégorie parent</th>
                                    <th scope="col">Articles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </th>
                                    <td>Crypto</td>
                                    <td>Technologies</td>
                                    <td>230 Articles</td>
                                </tr>
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
                        <button class="btn btn-primary col-md-3"><i class="bi bi-check2-square"></i> Appliquer</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>