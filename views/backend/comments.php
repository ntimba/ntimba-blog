<?php $title = "Les commentaires" ?>
<?php ob_start(); ?>

<!-- Comments -->
<div class="posts mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col">
            </div>

            <div class="col">
                <select class="form-select" aria-label="Default select example col-md-3">
                    <option selected>Action grouper</option>
                    <option value="1">Approuver</option>
                    <option value="2">Désapprouver </option>
                    <option value="3">Supprimer</option>
                </select>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <table class="table mt-5">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            </th>
                            <th scope="col">Utilisateur</th>
                            <th scope="col">Commentaire</th>
                            <th scope="col">Date</th>
                            <th scope="col">Approuver</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            </th>
                            <td>[image] Patrick Luvre</td>
                            <td>Super article j'ai adoré ...</td>
                            <td>23 Juillet 2023</td>
                            <td class="d-flex justify-content-start">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                                </div>
                            </td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                    </div>
                    
                    <div class="col d-flex justify-content-end">
                        <button class="btn btn-primary col-md-3"><i class="bi bi-save-fill"></i> Enregistrer</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



