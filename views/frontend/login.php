<?php $title = "Connecter vous" ?>
<?php ob_start(); ?>

<!-- Login -->
<div class="container">
    <?php echo $errorHandler->displayErrors(); ?>

    <div class="row login d-flex align-items-center">
        <div class="col-md-6 mt-5">

            <form class="form form-floating mb-5" action="" method="POST">
                <div class="col-md-8">
                    <div class="form-floating mb-3">
                        <input type="email" name="email" value="<?= $this->request->post('email', '') ?>" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                        <label for="floatingFirstname">Adresse E-mail</label>
                        <div id="FirstnameHelpBlock" class="form-text">
                            Ce nom est utilisé un peut partout sur votre site
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                        <label for="floatingFirstname">Mot de passe</label>
                        <div id="FirstnameHelpBlock" class="form-text">
                            Ce nom est utilisé un peut partout sur votre site
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <a href="index.php?action=forgottenpassword" class="text-decoration-none">Mot de passe oublié</a>
                </div>

                <div class="mb-3">
                    <button name="submit" class="btn">Se connecter</button>
                </div>

            </form>
        </div>
        <div class="col-md-6">
            <img class="img-fluid d-none d-md-block" src="/assets/img/unlock.svg" alt="">
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



