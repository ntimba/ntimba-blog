<?php $title = "Contact" ?>
<?php ob_start(); ?>

    <!-- display errors -->
    <div class="mt-5">
        <?php echo $errorHandler->displayErrors(); ?>
    </div>

    <div class="row mt-5 contact d-flex align-items-center">
        <div class="col-md-6 mt-5">
            <img class="img-fluid" src="/assets/img/typing-on-machine.png" alt="">
        </div>
        <div class="col-md-6 mt-5">
            <form class="form" action="" method="POST">
                <div class="form-floating mb-3">
                    <input name="full_name" value="<?= $this->request->post('full_name', '') ?>" type="text" class="form-control" id="floatingName" placeholder="Nom complet" aria-labelledby="NameHelpBlock">
                    <label for="floatingName">Nom Complet</label>
                    <div id="NameHelpBlock" class="form-text">
                        Ce nom est utilisé un peut partout sur votre site
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input name="email" value="<?= $this->request->post('email', '') ?>" type="email" class="form-control" id="floatingEmail" placeholder="mail@domain.tld" aria-labelledby="NameHelpBlock">
                    <label for="floatingEmail">Adresse E-mail</label>
                    <div id="EmailHelpBlock" class="form-text">
                        Ce e-mail est utilisé un peut partout sur votre site
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input name="subject" value="<?= $this->request->post('subject', '') ?>" type="text" class="form-control" id="floatingSubject" placeholder="mail@domain.tld" aria-labelledby="SubjectHelpBlock">
                    <label for="floatingSubject">Sujet</label>
                    <div id="SubjectHelpBlock" class="form-text">
                        Ce e-mail est utilisé un peut partout sur votre site
                    </div>
                </div>

                <div class="form-floating">
                    <textarea name="message" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"><?= $this->request->post('message', '') ?></textarea>
                    <label for="floatingTextarea">Message</label>

                    <div class="form-text" id="textareaHelpBlock">
                        Écrivez votre Message
                    </div>
                </div>

                <div class="mb-5">
                    <input name="terms" value="1" id="terms" class="terms" type="checkbox" name="password" placeholder="Repeter le mot de passe">
                    <label for="terms">J'accepte les conditions d'utilisation de mes données</label>
                </div>

                <div class="mb-3">
                    <button type="submit" name="submit" class="btn">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
    

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



