<?php $title = "Le blog de Ntimba"; ?>
<?php ob_start(); ?>

<div class="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <h1>Articles</h1>
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                    <div class="col">
                        <div class="card h-100">
                            <img src="/assets/uploads/h2_project_img01.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <a href="post.html" class="card-link">Card link</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="/assets/uploads/h2_project_img02.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a short card.</p>
                                <a href="post.html" class="card-link">Card link</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="/assets/uploads/h2_project_img03.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                                <a href="post.html" class="card-link">Card link</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="/assets/uploads/h2_project_img04.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <a href="post.html" class="card-link">Card link</a>
                            </div>
                        </div>
                    </div>
                </div>

                <nav aria-label="Page navigation">
                    <ul class="pagination d-flex justify-content-center mb-5 mt-5">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-4">
                <div class="info">
                    <img src="" alt="">
                    <p>Bonjour, je suis Chancy</p>
                    <a href="#">À propos de mois</a>
                </div>

                <div class="categories">
                    <h3>Catégories</h3>
                    <ul>
                        <li>Technologie (3)</li>
                        <li>Voyage (12)</li>
                        <li>Crypto (4)</li>
                    </ul>
                </div>

                <div class="lastnew">
                    <h3>Dernier article</h3>
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="post.html" class="card-link">Card link</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>


