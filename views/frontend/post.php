<?php $title = "Titre de l'article" ?>
<?php ob_start(); ?>

<!-- Article -->
<div class="container article">
<?php echo $errorHandler->displayErrors(); ?>
    <div class="row">
        <div class="col-md-8">

            <div class="row mt-2 mb-5">
                <article class="col-sm-12">
                    <h1 class="mt-5"><?= $postData['post_title'] ?></h1>
                    
                    <img src="<?= $postData['post_featured_image_path'] ?>" alt="<?= $postData['post_title'] ?>">


                    
                    <div>
                         <?= $postData['post_content'] ?>
                    </div>
                </article>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <h3>Commentaires</h3>
                    <p>un compte qui n'est pas vérifié ne peut pas commenter</p>
                    <?php if($this->sessionManager->get('user_id')): ?>
                    <form action="index.php?action=add_comment" method="POST">
                        <input name="post_id" type="hidden" value="<?= $postData['post_id'] ?>" >
                        <textarea class="form-control" name="comment_content" id="" cols="" rows="" placeholder="Commentaire"></textarea>
                        <button name="submit" class="btn btn-primary">Commenter</button>
                    </form>
                    <?php else: ?>
                        <p>Il faut être connecter pour commenter</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <?php foreach( $commentsData as $commentData ) : ?>
                <div class="comment">
                    <img class="comment__image" src="<?= $commentData['comment_user_image'] ?>" alt="<?= $commentData['comment_user'] ?>">
                    <div class="comment__body">
                        <div class="comment__meta">
                            <p><?= $commentData['comment_user'] ?></p>
                            <p><?= $commentData['comment_date'] ?></p>
                        </div>
                        <div class="comment__content">
                            <p><?= $commentData['comment_content'] ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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



