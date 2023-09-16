<?php $title = "Le Portfolio de Ntimba" ?>

<?php ob_start(); ?>

<div class="dashboard mt-5 mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>
        <div class="row mb-3">
            <div class="col-md-8">
                <div>
                    <h3>Hello Chancy!</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid provident deserunt cupiditate voluptas dolorum modi, expedita tempora pariatur nesciunt vero, exercitationem ipsam, accusantium molestias repellat corrupti doloribus veniam ipsa enim.</p>

                    <a href="index.php?action=add_post" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> 
                        Créer un article
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Mettre des card ici -->
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
            <!-- Articles Card -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-b-20">Articles</h6>
                        <h2 class="text-right d-flex justify-content-between"><i class="bi bi-file-richtext"></i><span><?= $totalPosts ?></span></h2>
                        <p class="d-flex justify-content-between">Publié <span class="f-right"><?= $totalPublishedPosts ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Comments Card -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-b-20">Commentaires</h6>
                        <h2 class="text-right d-flex justify-content-between"><i class="bi bi-file-richtext"></i><span><?= $totalComments ?></span></h2>
                        <p class="d-flex justify-content-between">approuvés <span class="f-right"><?= $totalApprovedComments ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Users Card -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-b-20">Utilisateur</h6>
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
            <div class="col-md-8">
                <h3>Top articles</h3>   
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col">Date de publication</th>
                                    <th scope="col">Catégorie</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php foreach( $lastPostsData as $lastPostData ): ?>
                                    <tr>
                                        <th scope="row"><?= $lastPostData['ranking'] ?></th>
                                        <td><?= $lastPostData['title'] ?></td>
                                        <td><?= $lastPostData['publication_date'] ?></td>
                                        <td><?= $lastPostData['category'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
 


            <?php
            // Déterminez la date du début et de la fin de la semaine
            $dateDebutSemaine = date('Y-m-d', strtotime('last Monday'));
            $dateFinSemaine = date('Y-m-d', strtotime('next Sunday'));

            $jourDebutSemaine = new DateTime($dateDebutSemaine);
            $jourFinSemaine = new DateTime($dateFinSemaine);
            $jourFinSemaine->modify('+1 day');  // Ajouter un jour à la date de fin

            $interval = DateInterval::createFromDateString('1 day');
            $periode = new DatePeriod($jourDebutSemaine, $interval, $jourFinSemaine);

            $dateAujourdhui = date('Y-m-d');
            ?>





            <div class="col-md-4">  
                <h3>Today's comments</h3>   
                <table class="calendrier table-borderless mt-3">
                    <tr>
                        <th>Lun</th>
                        <th>Mar</th>
                        <th>Mer</th>
                        <th>Jeu</th>
                        <th>Ven</th>
                        <th>Sam</th>
                        <th>Dim</th>
                    </tr>
                    <tr>
                        <?php
                        foreach ($periode as $jour) {
                            $classeAujourdhui = $jour->format('Y-m-d') == $dateAujourdhui ? 'aujourdhui' : '';
                            echo '<td class="' . $classeAujourdhui . '">' . $jour->format('d') . '</td>';
                        }
                        ?>
                    </tr>
                </table>

                <!-- la liste des dernier articles -->
                <ul class="list-group mt-3">
                    <?php foreach( $todaysComments as $comment ): ?>
                    <li class="list-group-item">
                        <span><?= $comment['hour'] ?></span>
                        <span><?= $comment['content'] ?></span>
                        Publié par :<span><?= $comment['publish_by'] ?></span>
                    </li>
                    <?php endforeach;  ?>
                </ul>
            </div>
            
            
            <div class="col-md-4">
            </div>
        </div>

        <div class="row">
            <!-- Les dernier utilisateur qui on créé leur comptes -->
        </div>            
    </div>
    
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>




