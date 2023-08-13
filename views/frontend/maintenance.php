<?php $title = "Contact" ?>
<?php ob_start(); ?>

<!-- Contact -->
<div class="container contact">
    <div class="row">
        <div class="col-md-6">
            <img src="/assets/img/typing-on-machine.png" alt="">
        </div>
        <div class="col-md-6">
            <form action="">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingName" placeholder="Nom complet" aria-labelledby="NameHelpBlock">
                    <label for="floatingName">Nom Complet</label>
                    <div id="NameHelpBlock" class="form-text">
                        Ce nom est utilisé un peut partout sur votre site
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingName" placeholder="mail@domain.tld" aria-labelledby="NameHelpBlock">
                    <label for="floatingName">Adresse E-mail</label>
                    <div id="NameHelpBlock" class="form-text">
                        Ce e-mail est utilisé un peut partout sur votre site
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingName" placeholder="mail@domain.tld" aria-labelledby="SubjectHelpBlock">
                    <label for="floatingSubject">Sujet</label>
                    <div id="SubjectHelpBlock" class="form-text">
                        Ce e-mail est utilisé un peut partout sur votre site
                    </div>
                </div>

                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">Message</label>

                    <div class="form-text" id="categoryParentHelpBlock">
                        Écrivez votre Biographie
                    </div>
                </div>

                <div class="mb-3">
                    <input id="terms" class="terms" type="checkbox" name="password" placeholder="Repeter le mot de passe">
                    <label for="terms">J'accepte les conditions d'utilisation de mes données</label>
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary">Envoyer</button>
                </div>


            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>


