<?php $title = "Mots de passe" ?>
<?php ob_start(); ?>

<!-- settings -->
<div id="alert-container">
    <?php echo $errorHandler->displayErrors(); ?>
</div>

<form class="mt-5" action="" method="POST" enctype="multipart/form-data">
    <div class="row">    
        <fieldset class="col-md">
            <legend>Mot de passe oublié</legend>
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock">
                <label for="floatingFirstname">Adresse e-mail</label>
                <div id="FirstnameHelpBlock" class="form-text">
                    Ce nom est utilisé un peut partout sur votre site
                </div>
            </div>
        </fieldset>
    </div>
    <button name="submit" class="mt-3 mb-4 btn"><i class="bi bi-check2-square"></i> Continuer</button>                        
</form>



<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



