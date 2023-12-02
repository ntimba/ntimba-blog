<?php $title = "Le Dashboard" ?>

<?php ob_start(); ?>

<div class="mt-5 mb-5">
    <div class="container dashboard">
        <?php echo $errorHandler->displayErrors(); ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="welcome rounded-3 p-5 d-flex align-items-center mt-5">
                    <div class="pe-4">
                        <h3 class="title">Bonjour Ntimba !</h3>
                        <p class="content">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid provident deserunt cupiditate voluptas dolorum modi, expedita tempora pariatur nesciunt vero, exercitationem ipsam, accusantium molestias repellat corrupti doloribus veniam ipsa enim.</p>
    
                        <a href="index.php?action=add_post" class="btn">
                            <i class="bi bi-plus-circle-fill"></i> 
                            Créer un article
                        </a>
                    </div>

                    <div class="d-none d-sm-block">
                        <img class="img-fluid" src="/assets/img/write-post.svg" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-cards row row-cols-1 row-cols-md-3 g-4 mb-4">
            <!-- Articles Card -->
            <div class="col">
                <div class="card card--dark">
                    <div class="card-body">
                        <h6 class="m-b-20 title font-size-large">Articles</h6>
                        <h2 class="text-right d-flex justify-content-between"><i class="bi bi-file-richtext"></i><span><?= $totalPosts ?></span></h2>
                        <p class="d-flex justify-content-between">Publié <span class="f-right"><?= $totalPublishedPosts ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Comments Card -->
            <div class="col">
                <div class="card card--dark">
                    <div class="card-body">
                        <h6 class="m-b-20 title font-size-xl">Commentaires</h6>
                        <h2 class="icon text-right d-flex justify-content-between"><i class="bi bi-file-richtext"></i><span><?= $totalComments ?></span></h2>
                        <p class="d-flex justify-content-between">approuvés <span class="f-right"><?= $totalApprovedComments ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Users Card -->
            <div class="col">
                <div class="card card--dark">
                    <div class="card-body">
                        <h6 class="m-b-20 title">Utilisateur</h6>
                        <h2 class="text-right d-flex justify-content-between"><i class="bi bi-file-richtext"></i><span><?= $totalUsers ?></span></h2>
                        <p class="d-flex justify-content-between">Actifs <span class="f-right"><?= $totalActiveUsers ?></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & others -->
        <div class="row mb-3">
            <!-- Charts -->
            <!-- Last articles -->
            <div class="col-md-7 background-light">
                <div class="card card--light p-4 mb-4">
                    <h3 class="title">Top articles</h3>   
                    <div class="card-body">
                        <div class="row">
                            <table class="table table--light">
                                <tbody class="background--light">
                                    <?php foreach( $lastPostsData as $lastPostData ): ?>
                                    <tr class="background--light">
                                        <th scope="row"></th>
                                        <td class="d-flex flex-row">
                                            <div class="thumbnail-container">
                                                <?php if($lastPostData['image'] != NULL): ?>
                                                <img class="thumbnail-image rounded-3 object-cover thumbnail-s" src="<?= $lastPostData['image'] ?>" alt="">
                                                <?php endif; ?>
                                            </div>
                                            <div class="ps-2">
                                                <div><?= $lastPostData['title'] ?></div>
                                                <div class="color--dark"><?= $lastPostData['publication_date'] ?></div>
                                            </div>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <?= $lastPostData['category'] ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
 
            <div class="col-md-5">
                <div class="card card--light">
                <div class="card-body">
                    <h3 class="title mb-4">Commentaires</h3>
                    <ul class="list-group list-group-flush">
                        <?php foreach( $unapprovedComments as $comment ): ?>
                        <li class="list-group-item background--light">

                            <div class="d-flex">
                                <div class="pe-3"><?= $comment['date'] ?></div>
    
                                <div>
                                    <div><?= $comment['content'] ?></div>
                                    <div class="mt-2 color--dark"><?= $comment['publish_by'] ?></div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach;  ?>
                    </ul>
                </div>
                </div>
                <div class="d-flex justify-content-center">
                    <!-- pagination -->
                    <?= $paginationLinks ?>
                </div>
            </div>

            <?php

            $dateDebutSemaine = date('Y-m-d', strtotime('last Monday'));
            $dateFinSemaine = date('Y-m-d', strtotime('next Sunday'));

            $jourDebutSemaine = new DateTime($dateDebutSemaine);
            $jourFinSemaine = new DateTime($dateFinSemaine);
            $jourFinSemaine->modify('+1 day');  

            $interval = DateInterval::createFromDateString('1 day');
            $periode = new DatePeriod($jourDebutSemaine, $interval, $jourFinSemaine);

            $dateAujourdhui = date('Y-m-d');
            ?>

            
            
        </div>

        <div class="row">
            <!-- Les dernier utilisateur qui on créé leur comptes -->
        </div>            
    </div>
    
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




