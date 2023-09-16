<?php $title = "Le blog de Ntimba"; ?>
<?php ob_start(); ?>

<div class="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <h1>Articles</h1>
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                    <?php foreach($postsData as $postData): ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="<?= $postData['post_image'] ?>" class="card-img-top" alt="<?= $postData['post_title'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $postData['post_title'] ?></h5>
                                <p class="card-text"><?= $postData['post_content'] ?></p>
                                <a href="index.php?action=post&id=<?= $postData['post_id'] ?>" class="card-link">Lire la suite</a>
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
                <div class="info">
                    <img src="" alt="">
                    <p>Bonjour, je suis Chancy</p>
                    <a href="#">À propos de mois</a>
                </div>

                <div class="categories">
                    <h3>Catégories</h3>
                    <ul>
                        <?php foreach($categoriesData as $categoryData): ?>
                        <li><?= $categoryData['name'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="lastnews">
                    <h3>Dernier article</h3>
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $lastPostData['title'] ?></h5>
                            <p class="card-text"><?= $lastPostData['content'] ?></p>
                            <a href="?action=post&id=<?= $lastPostData['id'] ?>" class="card-link">Lire la suite</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




