<?php $title = "Le Portfolio de Ntimba" ?>

<?php ob_start(); ?>

<div class="dashboard mt-5 mb-5">
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> 
                    Créer un article
                </a>

            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
            <!-- Articles -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <div class="">
                                    <i class="bi bi-file-richtext"></i>                                    
                                </div>
                            </div>
                            <div class="col-6 ">
                                <h2 class="d-flex justify-content-end d-flex align-items-center">23</h2>
                                <p class="d-flex justify-content-end d-flex align-items-center">Articles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <div class="">
                                    <!-- <i class="bi bi-file-richtext"></i>    -->
                                    <i class="bi bi-chat-square-text"></i>                                 
                                </div>
                            </div>
                            <div class="col-6 ">
                                <h2 class="d-flex justify-content-end d-flex align-items-center">56</h2>
                                <p class="d-flex justify-content-end d-flex align-items-center">Commentaires</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <div class="">
                                    <!-- <i class="bi bi-file-richtext"></i> -->
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                            <div class="col-6 ">
                                <h2 class="d-flex justify-content-end d-flex align-items-center">232</h2>
                                <p class="d-flex justify-content-end d-flex align-items-center">Utilisateurs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & others -->
        <div class="row mb-3">
            <!-- Charts -->
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div id="myfirstchart" style="height: 250px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item">An item</li>
                    <li class="list-group-item">A second item</li>
                    <li class="list-group-item">A third item</li>
                    <li class="list-group-item">A fourth item</li>
                    <li class="list-group-item">And a fifth one</li>
                </ul>
            </div>
        </div>

        <!-- Last articles -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                        <th scope="col">Last</th>
                                        <th scope="col">Handle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td colspan="2">Larry the Bird</td>
                                        <td>@twitter</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>            
    </div>
    
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>