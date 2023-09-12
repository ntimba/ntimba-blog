<?php $title = "Mes informations personnelle" ?>
<?php ob_start(); ?>

<!-- settings -->
<div class="settings mt-5 mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>
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
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="">
                                        <h3 class="mb-5">Informations personnelles</h3>
                                        
                                        <div class="mb-3">
                                            <div class="avatar">
                                                <!-- Afficher l'image du photo-profile -->
                                                <!-- <img class="img-thumbnail" src="/assets/uploads/moi.jpg" alt=""> -->
                                            </div>                                        
                                            
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Télécharger votre photo profile</label>
                                                <input name="profile_picture" class="form-control" type="file" id="formFile">                                
                                            </div>
                                        </div>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis eos eius voluptatum asperiores amet, quod alias necessitatibus sunt, nihil deserunt laudantium distinctio earum ullam nulla ad cupiditate, eum labore voluptate.</p>
    
                                        <div class="form-floating mb-3">
                                            <input type="text" name="firstname" class="form-control" id="floatingFirstname" placeholder="Prénom" aria-labelledby="FirstnameHelpBlock" value="<?= $user->getFirstname() ?>">
                                            <label for="floatingFirstname">Prénom</label>
                                            <div id="FirstnameHelpBlock" class="form-text">
                                                Ce nom est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating mb-3">
                                            <input type="text" name="lastname" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock" value="<?= $user->getLastname() ?>">
                                            <label for="floatingLastname">Nom de famille</label>
                                            <div id="LastnameHelpBlock" class="form-text">
                                                Ce nom est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating mb-3">
                                            <input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Pseudo" aria-labelledby="UsernameHelpBlock" value="<?= $user->getUsername() ?>">
                                            <label for="floatingLastname">Nom d'utilisateur</label>
                                            <div id="UsernameHelpBlock" class="form-text">
                                                Ce nom d'utilisateur est utilisé un peut partout sur votre site
                                            </div>
                                        </div>
                
                                        <div class="form-floating">
                                            <textarea name="biography" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"><?= $user->getBiography() ?></textarea>
                                            <label for="floatingTextarea">Biographie</label>
                
                                            <div class="form-text" id="categoryParentHelpBlock">
                                                Écrivez votre Biographie
                                            </div>
                                        </div>
                                        <button name="submit" class="mt-3 mb-4 btn btn-primary"><i class="bi bi-check2-square"></i> Appliquer</button>                        
    
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                            <!-- Security -->
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="">
                                        <h3 class="mb-5">Sécurité</h3>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus, nemo animi. Animi sit voluptas amet, iste, vitae hic ipsam soluta, dolorem molestias at exercitationem illo numquam eveniet quos totam eaque!</p>
                    
                                            <div class="form-floating mb-3">
                                                <input name="email" type="text" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock" value="<?= $user->getEmail() ?>">
                                                <label for="floatingFirstname">Adresse E-mail</label>
                                                <div id="FirstnameHelpBlock" class="form-text">
                                                    Ce nom est utilisé un peut partout sur votre site
                                                </div>
                                            </div>
                    
                                            <div class="form-floating mb-3">
                                                <input type="password" name="old_password" class="form-control" id="floatingFirstname" placeholder="Mot de passe actuel" aria-labelledby="FirstnameHelpBlock">
                                                <label for="floatingFirstname">Mot de passe actuel</label>
                                                <div id="FirstnameHelpBlock" class="form-text">
                                                    Ce nom est utilisé un peut partout sur votre site
                                                </div>
                                            </div>
                    
                                            <div class="form-floating mb-3">
                                                <input type="password" name="new_password" class="form-control" id="floatingLastname" placeholder="Nom de famille" aria-labelledby="LastnameHelpBlock">
                                                <label for="floatingLastname">Nouveau mot de passe</label>
                                                <div id="LastnameHelpBlock" class="form-text">
                                                    Ce nom est utilisé un peut partout sur votre site
                                                </div>
                                            </div>
                    
                                            <div class="form-floating mb-3">
                                                <input type="password" name="repeat_password" class="form-control" id="floatingUsername" placeholder="Pseudo" aria-labelledby="UsernameHelpBlock">
                                                <label for="floatingLastname">Retaper le nouveau mot de passe</label>
                                                <div id="UsernameHelpBlock" class="form-text">
                                                    Ce nom d'utilisateur est utilisé un peut partout sur votre site
                                                </div>
                                            </div>
                    
                                            
                                        </div>
                                    </div>
                                    <button name="submit" class="mt-3 mb-4 btn btn-primary"><i class="bi bi-check2-square"></i>Appliquer</button>                        
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>                
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



