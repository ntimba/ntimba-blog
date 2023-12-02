<?php $title = "Réseaux sociaux" ?>
<?php ob_start(); ?>

<!-- settings -->
<div id="alert-container">
    <?php echo $errorHandler->displayErrors(); ?>
</div>

<form class="mt-5" action="" method="POST" enctype="multipart/form-data">
    <div class="row">    
        <fieldset class="col-md">
            <legend>Réseaux sociaux</legend>
            <div class="form-floating mb-3">
                <input type="text" value="<?= $network->getNetworkName() ?>" name="network_name" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock">
                <label for="floatingFirstname">Nom du réseau social</label>
                <div id="FirstnameHelpBlock" class="form-text">
                    Le nom du réseau social
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="url" value="<?= $network->getNetworkUrl() ?>" name="network_link" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                <label for="floatingLastname">Lien du réseau social</label>
                <div id="LastnameHelpBlock" class="form-text">
                    Le lien du réseau social commence par toujours par https://
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" value="<?= $network->getNetworkIconClass() ?>" name="network_css_class" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                <label for="floatingLastname">classe css de l'icon</label>
                <div id="LastnameHelpBlock" class="form-text">
                    exemple du nom de la class css : bi bi-people.  Uniquement Bootstrap Icons. 
                </div>
            </div>
        </fieldset>

    </div>
    
    <button name="submit" class="mt-3 mb-4 btn"><i class="bi bi-check2-square"></i> Modifier</button>                        
</form>



<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



