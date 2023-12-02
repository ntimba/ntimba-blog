<?php $title = "Installation du blog" ?>
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
                        <div class="form-floating mb-3">
                            <input name="blog_name" value="<?= $this->request->post('blog_name', '') ?>" type="text" class="form-control" id="blogName" placeholder="Nom du blog" aria-describedby="blogNameHelpBlock">
                            <label for="blogName">Nom du blog</label>
                            <div id="blogNameHelpBlock" class="form-text">
                                Le nom du blog est affiché à plusieurs endroits clé du site, par exemple : pied de page.
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea name="blog_description" value="<?= $this->request->post('blog_description', '') ?>" class="form-control" placeholder="Description du blog" id="blogDescription" aria-describedby="blogDescriptionHelpBlock"></textarea>
                            <label for="blogDescription">Description du blog</label>
                            <div id="blogDescriptionHelpBlock" class="form-text">
                                La description du blog peut aider à attirer et informer les visiteurs.
                            </div>
                        </div>

                        <div class="mb-3">
                            <input name="logo_path" class="form-control" type="file" id="blogLogo" aria-describedby="logoHelpBlock">
                            <div id="logoHelpBlock" class="form-text">
                                Le logo sera utilisé à divers endroits sur le site.
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input name="contact_email" value="<?= $this->request->post('contact_email', '') ?>" type="email" class="form-control" id="contactEmail" placeholder="mail@site.tld" aria-describedby="contactEmailHelpBlock">
                            <label for="contactEmail">Adresse e-mail de contact</label>
                            <div id="contactEmailHelpBlock" class="form-text">
                                Cette adresse e-mail sera utilisée pour les communications et les demandes des visiteurs.
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="default_language" class="form-select" id="siteLanguage" aria-describedby="languageHelpBlock">
                                <option value="fr" <?php $this->request->post('default_language') === 'fr' ? 'selected' : ''; ?>>Francais</option>
                                <option value="de" <?php $this->request->post('default_language') === 'de' ? 'selected' : ''; ?>>Deutsch</option>
                                <option value="en" <?php $this->request->post('default_language') === 'en' ? 'selected' : ''; ?>>English</option>
                            </select>

                            <label for="siteLanguage">Langue du site</label>
                            <div id="languageHelpBlock" class="form-text">
                                Le site sera affiché dans la langue choisie.
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="timezone" class="form-select" id="timeZone" aria-describedby="timeZoneHelpBlock">
                                <optgroup label="Les plus utilisé">
                                    <option value="Europe/Paris" <?php $this->request->post('timezone') === 'Europe/Paris' ? 'selected' : ''; ?>>Europe/Paris</option>
                                    <option value="Europe/London" <?php $this->request->post('timezone') === 'Europe/London' ? 'selected' : ''; ?>>Europe/London</option>
                                    <option value="Europe/Zurich" <?php $this->request->post('timezone') === 'Europe/Zurich' ? 'selected' : ''; ?>>Europe/Zurich</option>
                                </optgroup>

                                <optgroup label="Liste complet des fuseau horaire">
                                <?php foreach($timezones as $timezone): ?>
                                    <option value="<?= $timezone ?>" <?= $this->request->post('timezone') === $timezone ? 'selected' : ''; ?>><?= $timezone ?></option>
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
                        <div class="form-floating mb-3">
                            <input name="lastname" value="<?= $this->request->post('lastname', '') ?>" type="text" class="form-control" id="adminName" placeholder="Nom">
                            <label for="adminName">Nom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="firstname" value="<?= $this->request->post('firstname', '') ?>" type="text" class="form-control" id="adminFirstName" placeholder="Prénom">
                            <label for="adminFirstName">Prénom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="username" value="<?= $this->request->post('username', '') ?>" type="text" class="form-control" id="adminUsername" placeholder="Nom d'utilisateur">
                            <label for="adminUsername">Nom d'utilisateur</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="email" value="<?= $this->request->post('email', '') ?>" type="email" class="form-control" id="adminEmail" placeholder="Adresse E-mail">
                            <label for="adminEmail">Adresse E-mail</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea name="biography" class="form-control" placeholder="Description" id="adminBiography"><?= $this->request->post('biography', '') ?></textarea>
                            <label for="adminBiography">Biographie</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="password" value="" type="password" class="form-control" id="adminPassword" placeholder="Mot de passe">
                            <label for="adminPassword">Mot de passe</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="repeat_password" value="" type="password" class="form-control" id="confirmAdminPassword" placeholder="Confirmer le mot de passe">
                            <label for="confirmAdminPassword">Confirmer le mot de passe</label>
                        </div>
                    </fieldset>
                    <button name="submit" class="mt-5 mb-4 btn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



