<?php $title = "Connecter vous" ?>
<?php ob_start(); ?>

<!-- Login -->
<div class="container register">
    <?php echo $errorHandler->displayErrors(); ?>

    <div class="row">
        <div class="col-md-6">
            <img src="/assets/img/typing-on-machine.png" alt="">
        </div>
        <div class="col-md-6 mt-5">

            <form class="form-floating mb-5" action="" method="POST">

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

                
                <div class="mb-3">
                    <input id="rememberMe" class="rememberMe" type="checkbox" name="remember_me" value="1" <?= $this->request->post('remember_me') === '1' ? 'checked' : ''; ?>>
                    <label for="rememberMe">Se souvenir de moi</label>
                </div>

                <div class="mb-3">
                    <button name="submit" class="btn btn-primary">Se connecter</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



