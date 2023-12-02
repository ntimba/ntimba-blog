<?php $title = $postData['post_title'] ?>
<?php ob_start(); ?>

<!-- Article -->
<div class="container article">
    <?php echo $errorHandler->displayErrors(); ?>
    <?php if( !$postData['post_status'] ): ?>
        <?php $this->response->redirect('index.php?action=blog'); ?>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
    
                <div class="row mt-2 mb-5">
                    <article class="col-sm-12">
                        <h1 class="mt-5"><?= $postData['post_title'] ?></h1>
                        <?php if($postData['post_featured_image_path'] != NULL): ?>
                        <img class="img-fluid" src="<?= $postData['post_featured_image_path'] ?>" alt="<?= $postData['post_title'] ?>">                    
                        <?php endif; ?>
                        <div class="mt-5">
                             <?= nl2br($postData['post_content']) ?>
                        </div>
                    </article>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Commentaires</h3>
                        <p>un compte qui n'est pas vérifié ne peut pas commenter</p>
                        <?php if($this->sessionManager->get('user_id') || $this->sessionManager->get('limited_access') ): ?>
                        <form action="index.php?action=add_comment" class="mb-5" method="POST">
                            <input name="post_id" type="hidden" value="<?= $postData['post_id'] ?>" >
                            <textarea class="form-control" name="comment_content" id="" cols="" rows="" placeholder="Commentaire"></textarea>
                            <button name="submit" class="btn mt-2">Commenter</button>
                        </form>
                        <?php else: ?>
                            <p>Il faut être connecter pour commenter</p>
                        <?php endif; ?>
                    </div>
                </div>
    
                <div class="row mb-5">
                    <?php foreach( $commentsData as $commentData ) : ?>
                    <div class="comment d-flex mt-1 background--light p-3">
                        <img class="comment__image rounded-img--s me-3" src="<?= $commentData['comment_user_image'] ?>" alt="<?= $commentData['comment_user'] ?>">
                        <div class="comment__body">
                            <div class="comment__meta">
                                <div class="d-flex">
                                    <div class="me-3"><?= $commentData['comment_user'] ?></div>
                                    <div><?= $commentData['comment_date'] ?></div>
                                </div>
    
                                <p></p>
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
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <img src="<?= $adminData['image'] ?>" class="img-fluid rounded-img--m" alt="">
                        </div>
                        <div class="d-flex">
                            <h5 class="card-title title">Bonjour, je suis <span><?= $adminData['firstname'] ?></span></h5>
                        </div>
                            <p class="card-text d-flex justify-content-center"><?= $adminData['biography'] ?></p>
                            <a href="index.php" class="btn d-flex justify-content-center">À propos de mois</a>
                        </div>
                    </div>
    
                    <div class="lastnews">
                        <?php if(count($lastPostData) > 0): ?>
                        <h3>Dernier article</h3>
                        <div class="card h-100">
                            <?php if( $lastPostData['post_image'] != NULL): ?>
                            <img src="<?= $lastPostData['post_image'] ?>" class="card-img-top" alt="<?= $lastPostData['post_title'] ?>">
                            <?php endif; ?>

                            <div class="card-body">
                                <p><?= $lastPostData['post_date'] ?></p>
                                <span class="badge background--primary"><?= $lastPostData['post_category'] ?></span>
                                <h5 class="card-title title"><?= $lastPostData['post_title'] ?></h5>
                                <p class="card-text content"><?= $lastPostData['post_content'] ?></p>
                                <a href="index.php?action=post&id=<?= $lastPostData['post_id'] ?>" class="card-link btn">Lire la suite</a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
    
        </div>
    <?php endif; ?>

</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



