<?php $title = "S'enregistrer" ?>
<?php ob_start(); ?>

<!-- Register -->
<div class="container register">
    <!-- display errors -->
    <?php echo $errorHandler->displayErrors(); ?>
        
    <div class="row">
        <div class="col-md-6">
            <img src="/assets/img/typing-on-machine.png" alt="">
        </div>
        <div class="col-md-6">
            <form action="" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Prénom</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Nom</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Adresse E-mail</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Mot de passe</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" name="repeat_password" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Repeter le mot de passe</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <input id="terms" class="terms" type="checkbox" name="terms" value="1" <?php if (isset($_POST['terms']) && $_POST['terms'] == '1') echo 'checked'; ?>>
                        <label for="terms">J'accèpte  les conditions d'utilisation</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button name="submit" class="btn btn-primary">S'inscrire</button>
                </div>

            </form>
        </div>
    </div>
</div>
    
<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>