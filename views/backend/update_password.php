<?php $title = "Mots de passe" ?>
<?php ob_start(); ?>

<!-- settings -->
<div id="alert-container">
    <?php echo $errorHandler->displayErrors(); ?>
</div>

<form class="mt-5" action="" method="POST" enctype="multipart/form-data">
    <div class="row">    
        <fieldset class="col-md">
            <legend>Sécurité</legend>
            <div class="form-floating mb-3">
                <input type="password" name="old_password" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock">
                <label for="floatingFirstname">Mot de passe actuel</label>
                <div id="FirstnameHelpBlock" class="form-text">
                    Ce nom est utilisé un peut partout sur votre site
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="new_password" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                <label for="floatingLastname">Nouveau mot de passe</label>
                <div id="LastnameHelpBlock" class="form-text">
                    Ce nom est utilisé un peut partout sur votre site
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="repeat_password" class="form-control" id="floatingUsername" placeholder="Pseudo" aria-labelledby="UsernameHelpBlock">
                <label for="floatingLastname">Retaper le nouveau mot de passe</label>
                <div id="UsernameHelpBlock" class="form-text">
                    Ce nom d'utilisateur est utilisé un peut partout sur votre site
                </div>
            </div>
        </fieldset>

    </div>
    
    <button name="submit" class="mt-3 mb-4 btn"><i class="bi bi-check2-square"></i> Appliquer</button>                        
</form>



<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



