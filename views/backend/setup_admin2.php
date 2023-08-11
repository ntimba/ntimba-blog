<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>




<!-- Categories -->
<div class="posts mt-5 mb-5">
    <div class="container">
        <div class="row">
            <h3 class="mb-5">Configuration du blog</h3>
            <form class="form-floating mb-5 row" method="post" enctype="multipart/form-data">
                <div class="col-md-6">
                    <fieldset>
                        <legend>Information du site</legend>
                        <!-- Nom du blog -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingBlogName" placeholder="Nom du blog" aria-labelledby="blogNameHelpBlock">
                            <label for="floatingBlogName">Nom du blog</label>
                            <div id="blogNameHelpBlock" class="form-text">
                                Le nom du blog est affiché à plusieurs endroits clé du site. par exemple : pied de page.
                            </div>
                        </div>
                        <!-- Description du blog -->
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Description du blog" id="floatingBlogDescription" aria-labelledby="blogDescriptionHelpBlock"></textarea>
                            <label for="floatingBlogDescription">Description du blog</label>
                                
                            <div class="form-text" id="blogDescriptionHelpBlock">
                                La description du blog peut aider à 
                            </div>
                        </div>
                        <!-- logo -->
                        <div class="mb-3">
                            <input class="form-control" type="file" id="" aria-labelledby="logoHelpBlock">
        
                            <div class="form-text" id="logoHelpBlock">
                                Le logo sera un peut utiliser partout dans le site
                            </div>
                        </div>
        
                        <!-- Adresse de contact -->
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="mail@site.tld" id="floatingTextarea" aria-labelledby="blogDescriptionHelpBlock"></textarea>
                            <label for="floatingTextarea">Adresse e-mail de contact</label>
                                
                            <div class="form-text" id="blogDescriptionHelpBlock">
                                La description du blog peut aider à 
                            </div>
                        </div>
                        <!-- Langue -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" aria-labelledby="categoryParentHelpBlock" aria-label="Floating label select example">
                                <option value="0">Francais</option>
                                <option value="1">Deutsch</option>
                                <option value="2">English</option>
                            </select>
        
                            <label for="floatingSelect">Langue du site</label>
                            
                            <div class="form-text" id="categoryParentHelpBlock">
                                Le site sera afficher à la langue choisi
                            </div>
                        </div>
                        <!-- Fuseau horaire -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" aria-labelledby="categoryParentHelpBlock" aria-label="Floating label select example">
                                <optgroup label="Les plus utilisé">
                                    <option value="Europe/Paris">Europe/Paris</option>
                                    <option value="Europe/London">Europe/London</option>
                                    <option value="Europe/Zurich">Europe/Zurich</option>
                                </optgroup>
                                
                                <optgroup label="Liste complet des fuseau horaire">
                                    <?php foreach($timezones as $timezone): ?>
                                        <option value="<?= $timezone ?>"><?= $timezone ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                          
                            <label for="floatingSelect">Fuseau horaire</label>
                            
                            <div class="form-text" id="categoryParentHelpBlock">
                                le fuseau horaire va permetre d'afficher corretement la date en fonction de localisation du visiteur
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-md-6">
                    <fieldset>
                        <legend>Informations de l'administrateur</legend>
    
                        <!-- Nom -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                            <label for="floatingCategoryIdentifier">Nom</label>
                            <div id="" class="form-text">
                                L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                            </div>
                        </div>
                        <!-- Prénom -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                            <label for="floatingCategoryIdentifier">Prénom</label>
                            <div id="" class="form-text">
                                L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                            </div>
                        </div>
                        <!-- Nom d'utilisateur -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                            <label for="floatingCategoryIdentifier">Nom d'utilisateur</label>
                            <div id="" class="form-text">
                                L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                            </div>
                        </div>
                        <!-- Adresse e-mail -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                            <label for="floatingCategoryIdentifier">Adresse E-mail</label>
                            <div id="" class="form-text">
                                L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                            </div>
                        </div>
                        <!-- Mot de passe -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                            <label for="floatingCategoryIdentifier">Mot de passe</label>
                            <div id="" class="form-text">
                                L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                            </div>
                        </div>
                        <!-- Confirmer le mot de passe -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingCategoryIdentifier" placeholder="Identifiant" aria-labelledby="categoryNameHelpBlock">
                            <label for="floatingCategoryIdentifier">Confirmer le mot de passe</label>
                            <div id="" class="form-text">
                                L'identifiant est la version normalisée du nom. Il ne contient généralement que des lettres minuscules non accentuées, des chiffres et des traits d'union.
                            </div>
                        </div>
                        
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                            <label for="floatingTextarea">Description</label>
        
                            <div class="form-text" id="categoryParentHelpBlock">
                                Écrivez votre description
                            </div>
                        </div>
    
                    </fieldset>                 
                    <button class="mt-5 mb-4 btn btn-primary">Enregistrer</button>                        
                </div>

            </form>
                        
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>