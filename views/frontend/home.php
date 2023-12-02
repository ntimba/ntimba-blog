<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<div id="scroll-container" class="homepage">
    <!-- display errors -->
    <section id="home" class="scroll-section row hero d-flex align-items-center">
        <?php echo $errorHandler->displayErrors(); ?>
        <div class="col-md-6">
            <p class="title">Salut, <br> je suis <span><?= $adminData['firstname'] ?></span></p>
            <p class="subtitle">Developpeur d'application php</p>
            <p class="content">Cherchez-vous un développeur web PHP pour booster votre entreprise ? Unissons-nous ! Contactez-moi bour concretiser vos projets et propulser votre présence en ligne</p>
            <a class="btn" href="index.php?action=contact">Contactez-moi</a>
        </div>
        <div class="col-md-6">
            <img class="img-fluid d-none d-md-block" src="/assets/img/aboutme.png" alt="">
        </div>
    </section>

    <section id="skills" class="scroll-section row skills d-flex align-items-center">
        <div class="col-md-6">
            <img class="img-fluid" src="/assets/img/skills.svg" alt="">
        </div>
        <div class="col-md-6">
            <h3 class="subtitle">Développeur PHP polyvalent avec une gamme de compétences variée</h3>
            <p class="content">
                <?= $adminData['biography'] ?>
            </p>
    
            <div class="">
                <strong>PHP</strong>
                <div class="progress mb-3" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progressbar" style="width: 60%"></div>
                </div>
    
                <strong>SQL</strong>
                <div class="progress mb-3" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: 40%"></div>
                </div>
    
                <strong>Symfony</strong>
                <div class="progress mb-3" role="progressbar" aria-label="Basic example" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: 20%"></div>
                </div>
                
                <strong>JavaScript</strong>
                <div class="progress mb-3" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: 35%"></div>
                </div>  
            </div>
            
        </div>
    </section>
</div>
    
<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>

