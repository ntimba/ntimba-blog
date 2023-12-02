<?php $title = "Mes informations personnelle" ?>
<?php ob_start(); ?>

<!-- settings -->
<div id="alert-container">
    <?php echo $errorHandler->displayErrors(); ?>
</div>

<form class="mt-5" action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <fieldset class="col-md">
            <legend>Informations personnelles</legend>
            <div class="mb-3">
                <label for="formFile" class="form-label">Télécharger votre photo profile</label>
                <input name="image" class="form-control" type="file" id="formFile">                                
            </div>

            <div class="form-floating mb-3">
                <input name="email" type="text" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock" value="<?= $user->getEmail() ?>" disabled>

                <label for="floatingFirstname">Adresse E-mail</label>
                <div id="FirstnameHelpBlock" class="form-text">
                    Ce nom est utilisé un peut partout sur votre site
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="firstname" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock" value="<?= $user->getFirstname() ?>">
                <label for="floatingFirstname">Prénom</label>
                <div id="FirstnameHelpBlock" class="form-text">
                    Ce nom est utilisé un peut partout sur votre site
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="lastname" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock" value="<?= $user->getLastname() ?>">
                <label for="floatingLastname">Nom de famille</label>
                <div id="LastnameHelpBlock" class="form-text">
                    Ce nom est utilisé un peut partout sur votre site
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Pseudo" aria-labelledby="UsernameHelpBlock" value="<?= $user->getUsername() ?>">
                <label for="floatingLastname">Nom d'utilisateur</label>
                <div id="UsernameHelpBlock" class="form-text">
                    Ce nom d'utilisateur est utilisé un peut partout sur votre site
                </div>
            </div>

            <div class="form-floating">
                <textarea name="biography" rows="5" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"><?= $user->getBiography() ?></textarea>
                <label for="floatingTextarea">Biographie</label>

                <div class="form-text" id="categoryParentHelpBlock">
                    Écrivez votre Biographie
                </div>
            </div>
        </fieldset>
    </div>
    
    <button name="submit" class="mt-3 mb-4 btn"><i class="bi bi-check2-square"></i> Appliquer</button>                        
</form>



<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



