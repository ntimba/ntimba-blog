<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Portfolio -->
<div class="container portfolio">
    <div class="row">
        <div class="col-md-6">
            <h3>Certains de mes travaux créatifs</h3>
            <p>
                En tant que développeur PHP, j'ai collaboré sur divers projets. Consultez mon portfolio et contactez-moi pour une collaboration efficace
            </p>
        </div>

        <div class="col-md-6">
            <img src="/assets/img/portfolio.svg" alt="">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">Tout</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-web-tab" data-bs-toggle="pill" data-bs-target="#pills-web" type="button" role="tab" aria-controls="pills-web" aria-selected="false">Web</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-mobile-tab" data-bs-toggle="pill" data-bs-target="#pills-mobile" type="button" role="tab" aria-controls="pills-mobile" aria-selected="false">Mobile</button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <!-- All -->
                <div class="tab-pane fade" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab" tabindex="0" >

                    <!-- All portfolio -->
                    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                        <div class="col project__item">
                            <div class="card h-100">
                                <a href="project.html">
                                    <img src="/assets/uploads/h2_project_img01.jpg" class="card-img-top" alt="...">
                                </a>
                            </div>
                        </div>

                    </div>
                    
                </div>


                <!-- Web -->
                <div class="tab-pane fade show active" id="pills-web" role="tabpanel" aria-labelledby="pills-web-tab" tabindex="0">
                    Web
                </div>

                <!-- Mobile -->
                <div class="tab-pane fade" id="pills-mobile" role="tabpanel" aria-labelledby="pills-mobile-tab" tabindex="0">
                    Mobile
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



