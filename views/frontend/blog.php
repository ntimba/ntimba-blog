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
                            <img src="<?= $postData['post_image'] ?>" class="card-img-top" alt="<?= $postData['post_title'] ?>">
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


                <nav aria-label="Page navigation">
                    
                    <ul class="pagination d-flex justify-content-center mb-5 mt-5">
                        <!-- Bouton "Previous" -->
                        <?php if ($page > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="index.php?action=blog&page=<?= $page - 1 ?>">Précédent</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                            </li>
                        <?php endif; ?>
                    
                        <?php 
                        $numAdjacent = 2; // Nombre de pages à afficher avant et après la page actuelle
                        $ellipsisShown = false; 

                        for ($i = 1; $i <= $totalPages; $i++) : 
                            
                            // Si la page est proche du début, de la fin, ou proche de la page actuelle
                            if ($i == 1 || $i == $totalPages || ($i >= $page - $numAdjacent && $i <= $page + $numAdjacent)) :
                        ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="index.php?action=blog&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                        <?php 
                            // Pour gérer l'affichage des ellipses
                            elseif (($i == 2 || $i == $totalPages - 1) && !$ellipsisShown) :
                                $ellipsisShown = true;
                        ?>
                                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                        <?php 
                            endif;
                        endfor; 
                        ?>
                    
                        <!-- Bouton "Next" -->
                        <?php if ($page < $totalPages) : ?>
                            <li class="page-item">
                                <a class="page-link" href="index.php?action=blog&page=<?= $page + 1 ?>">Suivant</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Suivant</a>
                            </li>
                        <?php endif; ?>
                    </ul>  

                </nav>
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
                        <img src="<?= $postData['post_image'] ?>" class="card-img-top" alt="<?= $postData['post_title'] ?>">
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




