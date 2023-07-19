<?php $title = "S'enregistrer" ?>
<?php ob_start(); ?>

<!-- Register -->
<div class="container register">
    <div class="row">
        <div class="col-md-6">
            <img src="/assets/img/typing-on-machine.png" alt="">
        </div>
        <div class="col-md-6">
            <form action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Prénom</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Nom</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Adresse E-mail</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Nom d'utilisateur</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Mot de passe</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                            <label for="floatingFirstname">Repeter le mot de passe</label>
                            <div id="FirstnameHelpBlock" class="form-text">
                                Ce nom est utilisé un peut partout sur votre site
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <input id="terms" class="terms" type="checkbox" name="password" placeholder="Repeter le mot de passe">
                        <label for="terms">J'accèpte  les conditions d'utilisation</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary">S'inscrire</button>
                </div>

            </form>
        </div>
    </div>
</div>
    
<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>