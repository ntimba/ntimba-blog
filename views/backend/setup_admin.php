<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Categories -->
<div class="posts mt-5 mb-5">
    <div class="container">
    <?php echo $errorHandler->displayErrors(); ?>
        <div class="row">
            <h3 class="mb-5">Configuration du blog</h3>
            <form class="form-floating mb-5 row" method="post" enctype="multipart/form-data">
                <div class="col-md-6">
                    <fieldset>
                        <legend>Information du site</legend>
                        <!-- Nom du blog -->
                        <div class="form-floating mb-3">
                            <input name="blog_name" value="<?php echo isset($_POST['blog_name']) ? htmlspecialchars($_POST['blog_name']) : ''; ?>" type="text" class="form-control" id="blogName" placeholder="Nom du blog" aria-describedby="blogNameHelpBlock">
                            <label for="blogName">Nom du blog</label>
                            <div id="blogNameHelpBlock" class="form-text">
                                Le nom du blog est affiché à plusieurs endroits clé du site, par exemple : pied de page.
                            </div>
                        </div>
                        <!-- Description du blog -->
                        <div class="form-floating mb-3">
                            <textarea name="blog_description" value="<?php echo isset($_POST['blog_description']) ? htmlspecialchars($_POST['blog_description']) : ''; ?>" class="form-control" placeholder="Description du blog" id="blogDescription" aria-describedby="blogDescriptionHelpBlock"></textarea>
                            <label for="blogDescription">Description du blog</label>
                            <div id="blogDescriptionHelpBlock" class="form-text">
                                La description du blog peut aider à attirer et informer les visiteurs.
                            </div>
                        </div>
                        <!-- logo -->
                        <div class="mb-3">
                            <input name="logo_path" class="form-control" type="file" id="blogLogo" aria-describedby="logoHelpBlock">
                            <div id="logoHelpBlock" class="form-text">
                                Le logo sera utilisé à divers endroits sur le site.
                            </div>
                        </div>
                        <!-- Adresse de contact -->
                        <div class="form-floating mb-3">
                            <input name="contact_email" value="<?php echo isset($_POST['contact_email']) ? htmlspecialchars($_POST['contact_email']) : ''; ?>" type="email" class="form-control" id="contactEmail" placeholder="mail@site.tld" aria-describedby="contactEmailHelpBlock">
                            <label for="contactEmail">Adresse e-mail de contact</label>
                            <div id="contactEmailHelpBlock" class="form-text">
                                Cette adresse e-mail sera utilisée pour les communications et les demandes des visiteurs.
                            </div>
                        </div>
                        <!-- Langue -->
                        <div class="form-floating mb-3">
                            <select name="default_language" class="form-select" id="siteLanguage" aria-describedby="languageHelpBlock">
                                <option value="fr" <?php echo (isset($_POST['default_language']) && $_POST['default_language'] == 'fr') ? 'selected' : ''; ?>>Francais</option>
                                <option value="de" <?php echo (isset($_POST['default_language']) && $_POST['default_language'] == 'de') ? 'selected' : ''; ?>>Deutsch</option>
                                <option value="en" <?php echo (isset($_POST['default_language']) && $_POST['default_language'] == 'en') ? 'selected' : ''; ?>>English</option>
                            </select>

                            <label for="siteLanguage">Langue du site</label>
                            <div id="languageHelpBlock" class="form-text">
                                Le site sera affiché dans la langue choisie.
                            </div>
                        </div>
                        <!-- Fuseau horaire -->
                        <div class="form-floating mb-3">

                            <select name="timezone" class="form-select" id="timeZone" aria-describedby="timeZoneHelpBlock">
                                <optgroup label="Les plus utilisé">
                                    <option value="Europe/Paris" <?php echo (isset($_POST['timezone']) && $_POST['timezone'] == 'Europe/Paris') ? 'selected' : ''; ?>>Europe/Paris</option>
                                    <option value="Europe/London" <?php echo (isset($_POST['timezone']) && $_POST['timezone'] == 'Europe/London') ? 'selected' : ''; ?>>Europe/London</option>
                                    <option value="Europe/Zurich" <?php echo (isset($_POST['timezone']) && $_POST['timezone'] == 'Europe/Zurich') ? 'selected' : ''; ?>>Europe/Zurich</option>
                                </optgroup>
                                <optgroup label="Liste complet des fuseau horaire">
                                    <?php foreach($timezones as $timezone): ?>
                                        <option value="<?= $timezone ?>" <?php echo (isset($_POST['timezone']) && $_POST['timezone'] == $timezone) ? 'selected' : ''; ?>><?= $timezone ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>

                            <label for="timeZone">Fuseau horaire</label>
                            <div id="timeZoneHelpBlock" class="form-text">
                                Le fuseau horaire permettra d'afficher correctement la date en fonction de la localisation du visiteur.
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-6">
                    <fieldset>
                        <legend>Informations de l'administrateur</legend>
                        <!-- Nom -->
                        <div class="form-floating mb-3">
                            <input name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>" type="text" class="form-control" id="adminName" placeholder="Nom">
                            <label for="adminName">Nom</label>
                        </div>
                        <!-- Prénom -->
                        <div class="form-floating mb-3">
                            <input name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>" type="text" class="form-control" id="adminFirstName" placeholder="Prénom">
                            <label for="adminFirstName">Prénom</label>
                        </div>
                        <!-- Nom d'utilisateur -->
                        <div class="form-floating mb-3">
                            <input name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" type="text" class="form-control" id="adminUsername" placeholder="Nom d'utilisateur">
                            <label for="adminUsername">Nom d'utilisateur</label>
                        </div>
                        <!-- Adresse e-mail -->
                        <div class="form-floating mb-3">
                            <input name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" type="email" class="form-control" id="adminEmail" placeholder="Adresse E-mail">
                            <label for="adminEmail">Adresse E-mail</label>
                        </div>
                        <!-- Description -->
                        <div class="form-floating mb-3">
                            <textarea name="biography" class="form-control" placeholder="Description" id="adminBiography"><?php echo isset($_POST['biography']) ? htmlspecialchars($_POST['biography']) : ''; ?></textarea>
                            <label for="adminBiography">Biographie</label>
                        </div>
                        <!-- Mot de passe -->
                        <div class="form-floating mb-3">
                            <input name="password" value="" type="password" class="form-control" id="adminPassword" placeholder="Mot de passe">
                            <label for="adminPassword">Mot de passe</label>
                        </div>
                        <!-- Confirmer le mot de passe -->
                        <div class="form-floating mb-3">
                            <input name="repeat_password" value="" type="password" class="form-control" id="confirmAdminPassword" placeholder="Confirmer le mot de passe">
                            <label for="confirmAdminPassword">Confirmer le mot de passe</label>
                        </div>
                        
                    </fieldset>
                    <button name="submit" class="mt-5 mb-4 btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>
