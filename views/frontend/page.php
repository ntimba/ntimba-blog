<?php $title = $pageData['page_title'] ?>
<?php ob_start(); ?>

<!-- Article -->
<?php if( !$pageData['page_status'] ): ?>
    <?php $this->response->redirect('index.php?action=home'); ?>
<?php else: ?>
<div class="container article">
    <div class="row">
        <div class="col-md-8">

            <div class="row mt-2 mb-5">
                <article class="col-sm-12">
                    <h1 class="mt-5"><?= $pageData['page_title'] ?></h1>

                    <?php if( $pageData['page_featured_image_path'] != NULL ): ?>
                        <img class="img-fluid" src="<?= $pageData['page_featured_image_path'] ?>" alt="<?= $pageData['page_title'] ?>">                    
                    <?php endif; ?>
                    
                    <div>
                         <?= nl2br( $pageData['page_content'] ) ?>
                    </div>
                </article>
            </div>
        </div>

    </div>
</div>
<?php endif; ?>
<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>


