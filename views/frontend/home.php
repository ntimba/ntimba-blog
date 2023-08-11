<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Home page -->
<div class="container homepage">
    
    <!-- display errors -->
    <?php echo $errorHandler->displayErrors(); ?>

    <?php echo $_SERVER['MYSQL_ROOT_USER']; ?>
    
    <div class="row hero">
        <div class="col-md-6">
            <p>Salut, je suis Chancy</p>
            <p>Developpeur d'application php</p>
            <p>Cherchez-vous un développeur web PHP pour booster votre entreprise ? Unissons-nous ! Contactez-moi bour concretiser vos projets et propulser votre présence en ligne</p>
            <a href="contact.html">Contactez-moi</a>
        </div>
        <div class="col-md-6">
            <img src="/assets/img/aboutme.png" alt="">
        </div>
    </div>

    <div class="row skills">
        <div class="col-md-6">
            <img src="/assets/img/skills.svg" alt="">
        </div>
        <div class="col-md-6">
            <h3>Développeur PHP polyvalent avec une gamme de compétences variée</h3>
            <p>
                Spécialiste en développement web PHP, je conçois des solutions fonctionnelles et esthétiques. Fort de mon expertise et ma volonté d'apprendre, je fournis des résultats de qualité en respectant les normes de securite et les besoins specitiques de chaque projet.
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
    </div>
</div>
    
<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>