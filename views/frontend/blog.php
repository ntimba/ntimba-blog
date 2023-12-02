<?php $title = "Le blog de Ntimba"; ?>
<?php ob_start(); ?>

    <div class="container blog-header mt-5 ">
        <?php echo $errorHandler->displayErrors(); ?>
        <div class="d-flex align-items-center mt-5">
            <div class="">
                <img class="img-fluid" src="/assets/img/reading-book.svg" alt="">
            </div>
            <div class="">
                <h2 class="title">La Route du <span>PHP</span></h2>
                <h3 class="subtitle">Transformez vos idées en code efficace et sécurisé</h3>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row blog mt-4">
            <div class="col-md-8">
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                    <?php foreach($postsData as $postData): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if( $postData['post_image'] != NULL ): ?>
                            <img src="<?= $postData['post_image'] ?>" class="card-img-top" alt="<?= $postData['post_title'] ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <p><?= $postData['post_date'] ?></p>
                                <span class="badge background--primary"><?= $postData['post_category'] ?></span>
                                <h5 class="card-title title"><?= $postData['post_title'] ?></h5>
                                <p class="card-text content"><?= $postData['post_content'] ?></p>
                                <a href="index.php?action=post&id=<?= $postData['post_id'] ?>" class="card-link btn">Lire la suite</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>                   
                </div>

                <div class="d-flex justify-content-center">
                    <!-- pagination -->
                    <?= $paginationLinks ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <img src="<?= $adminData['image'] ?>" class="img-fluid rounded-img--m" alt="">
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5 class="card-title title">Bonjour, je suis <span><?= $adminData['firstname'] ?></span></h5>
                        </div>
                        <p class="card-text d-flex justify-content-center"><?= $adminData['biography'] ?></p>
                        <a href="index.php" class="btn d-flex justify-content-center">À propos de mois</a>
                    </div>
                </div>

                <?php if( isset($postData) ): ?>
                <div class="lastnews mb-5">
                    <h3>Dernier article</h3>
                    <div class="card h-100">
                        <?php if($postData['post_image'] != NULL): ?>
                        <img src="<?= $postData['post_image'] ?>" class="card-img-top" alt="<?= $postData['post_title'] ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <p><?= $postData['post_date'] ?></p>
                            <span class="badge background--primary"><?= $postData['post_category'] ?></span>
                            <h5 class="card-title title"><?= $postData['post_title'] ?></h5>
                            <p class="card-text content"><?= $postData['post_content'] ?></p>
                            <a href="index.php?action=post&id=<?= $postData['post_id'] ?>" class="card-link btn">Lire la suite</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




