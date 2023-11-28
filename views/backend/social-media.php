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
                <input type="text" name="network_name" value="<?= $this->request->post('network_name', '') ?>" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock">
                <label for="floatingFirstname">Nom du réseau social</label>
                <div id="FirstnameHelpBlock" class="form-text">
                    Le nom du réseau social
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="url" name="network_link" value="<?= $this->request->post('network_link', '') ?>" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                <label for="floatingLastname">Lien du réseau social</label>
                <div id="LastnameHelpBlock" class="form-text">
                    Le lien du réseau social commence par toujours par https://
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="network_css_class" value="<?= $this->request->post('network_css_class', '') ?>" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                <label for="floatingLastname">classe css de l'icon</label>
                <div id="LastnameHelpBlock" class="form-text">
                    exemple du nom de la class css : bi bi-people.  Uniquement Bootstrap Icons. 
                </div>
            </div>
        </fieldset>

    </div>
    
    <button name="submit" class="mt-3 mb-4 btn"><i class="bi bi-check2-square"></i> Ajouter</button>                        
</form>


<div class="row mb-5">
    <table class="table mb-5">
    <thead>
        <tr>
            <th scope="col">Nom résocial</th>
            <th scope="col">Lien réseau social</th>
            <th scope="col">Modifier</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($networks as $network): ?>
        <tr>
            <td><?= $network->getNetworkName() ?></td>
            <td><?= $network->getNetworkUrl() ?></td>
            <td> <a href="index.php?action=delete_social_media&id=<?= $network->getNetworkId() ?>"><i class="bi bi-trash"></i></a> <a href="index.php?action=update_social_media&id=<?= $network->getNetworkId(); ?>"><i class="bi bi-pencil-square"></i></a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>



<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



