<?php $title = "Mes informations personnelle" ?>
<?php ob_start(); ?>

<!-- settings -->
<div class="settings mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Informations personnelles</button>
                        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Sécurités</button>
                    </div>
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                            <!-- Personal infos -->
                            <div class="row">
                                <div class="">
                                    <h3 class="mb-5">Informations personnelles</h3>
                                    <div class="mb-3">
                                        <div class="avatar">
                                            <!-- <img class="img-thumbnail" src="/assets/uploads/moi.jpg" alt=""> -->
                                        </div>
                                        <!-- <a href="">Modifier l'image</a> -->
                                        <!-- Button trigger modal -->
                                        <a href="#" type="button" class="" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Modifier l'image
                                        </a>
                                    
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier ma photo profile</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="formFile" class="form-label">Télécharger votre photo profile</label>
                                                            <input class="form-control" type="file" id="formFile">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Télécharger</button>
                                                    </div>
                                                </form>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                    </div>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis eos eius voluptatum asperiores amet, quod alias necessitatibus sunt, nihil deserunt laudantium distinctio earum ullam nulla ad cupiditate, eum labore voluptate.</p>
                                    <form class="form-floating mb-5" >
                
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock">
                                            <label for="floatingFirstname">Prénom</label>
                                            <div id="FirstnameHelpBlock" class="form-text">
                                                Ce nom est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                                            <label for="floatingLastname">Nom de famille</label>
                                            <div id="LastnameHelpBlock" class="form-text">
                                                Ce nom est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingUsername" placeholder="Pseudo" aria-labelledby="UsernameHelpBlock">
                                            <label for="floatingLastname">Nom d'utilisateur</label>
                                            <div id="UsernameHelpBlock" class="form-text">
                                                Ce nom d'utilisateur est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                            <label for="floatingTextarea">Biographie</label>
                
                                            <div class="form-text" id="categoryParentHelpBlock">
                                                Écrivez votre Biographie
                                            </div>
                                        </div>
                
                                        <button class="mt-3 mb-4 btn btn-primary"><i class="bi bi-check2-square"></i> Appliquer</button>                        
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                            <!-- Security -->
                            <div class="row">
                                <div class="">
                                    <h3 class="mb-5">Sécurité</h3>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus, nemo animi. Animi sit voluptas amet, iste, vitae hic ipsam soluta, dolorem molestias at exercitationem illo numquam eveniet quos totam eaque!</p>
                                    <form class="form-floating mb-5" >
                
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock">
                                            <label for="floatingFirstname">Adresse E-mail</label>
                                            <div id="FirstnameHelpBlock" class="form-text">
                                                Ce nom est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock">
                                            <label for="floatingFirstname">Mot de passe actuel</label>
                                            <div id="FirstnameHelpBlock" class="form-text">
                                                Ce nom est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                                            <label for="floatingLastname">Nouveau mot de passe</label>
                                            <div id="LastnameHelpBlock" class="form-text">
                                                Ce nom est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingUsername" placeholder="Pseudo" aria-labelledby="UsernameHelpBlock">
                                            <label for="floatingLastname">Retaper le nouveau mot de passe</label>
                                            <div id="UsernameHelpBlock" class="form-text">
                                                Ce nom d'utilisateur est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <button class="mt-3 mb-4 btn btn-primary"><i class="bi bi-check2-square"></i> Appliquer</button>                        
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



