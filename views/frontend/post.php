<?php $title = "Titre de l'article" ?>
<?php ob_start(); ?>

<!-- Article -->
<div class="container article">
    <div class="row">
        <div class="col-md-8">

            <div class="row mt-2 mb-5">
                <article class="col-sm-12">
                    <h1 class="mt-5">Titre article</h1>
                    <img src="" alt="">
                     
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ea mollitia distinctio consequatur quam cum? Ut, maxime! Commodi, pariatur quasi dignissimos beatae, quia sed voluptatem blanditiis reprehenderit soluta enim a recusandae!</p>
                </article>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <h3>Commentaires</h3>
                    <form action="">
                        <textarea class="form-control" name="" id="" cols="" rows="" placeholder="Commentaire"></textarea>
                        <button class="btn btn-primary">Commenter</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="comment">
                    <img class="comment__image" src="" alt="">
                    <div class="comment__body">
                        <div class="comment__meta">
                            <p>Chancy Ntimba</p>
                            <p>12 juin 2023</p>
                        </div>
                        <div class="comment__content">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum iste aliquam voluptates facere autem. Dolorum repellendus mollitia commodi! In odit iste voluptate ad rem similique incidunt rerum sint eum laborum!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Hide on mible device -->
        <div class="col-md-4 d-block d-none d-sm-block d-sm-none d-md-block">
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
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>