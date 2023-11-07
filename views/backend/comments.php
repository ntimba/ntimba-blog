<?php $title = "Les commentaires" ?>
<?php ob_start(); ?>

<!-- Comments -->
<div class="posts mt-5 mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>
        <form method="POST" action="index.php?action=modify_comment">
        <div class="row">
            <div class="col">
            </div>

            <div class="col">
                <select name="action" class="form-select" aria-label="Default select example col-md-3">
                    <option selected>Action grouper</option>
                    <option value="approve">Approuver</option>
                    <option value="disapprove">Désapprouver </option>
                    <option value="delete">Supprimer</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table mt-5">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </th>
                            <th scope="col">Utilisateur</th>
                            <th scope="col">Commentaire</th>
                            <th scope="col">Date</th>
                            <th scope="col">Approuver</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $commentsData as $commentData ):  ?>
                        <tr>
                            <th scope="row">
                                <input name="comment_ids[]" class="form-check-input table-item" type="checkbox" id="inlineCheckbox1" value="<?= $commentData['comment_id'] ?>">
                            </th>
                            <td class="d-flex align-items-center">
                                <div class="rounded-img--s"> <img src="<?= $commentData['comment_user_image'] ?>" alt="<?= $commentData['comment_user'] ?>"> </div> 
                                <div class="ps-2"><?= $commentData['comment_user'] ?> </div>
                            </td>
                            <td><?= $commentData['comment_content'] ?></td>   
                            <td><?= $commentData['comment_date'] ?></td>
                            <td class="">
                                <div class="form-check form-switch">
                                    <!-- <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked> -->
                                    <input value="<?= $commentData['comment_id']; ?>" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?= $commentData['comment_status'] ? 'checked' : ''; ?> disabled>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                       
                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                    </div>
                    
                    <div class="col d-flex justify-content-end">
                        <button name="submit" class="btn col-md-3"><i class="bi bi-save-fill"></i> Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



