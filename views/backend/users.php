<?php $title = "Les utilisateurs" ?>
<?php ob_start(); ?>

<!-- Users -->
<div class="users mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col">
                <a href="#" class="btn btn-primary col"><i class="bi bi-plus-circle-fill"></i> Créer un utilisateur</a>
            </div>

            <div class="col">
                <select class="form-select" aria-label="Default select example col-md-3">
                    <option selected>Action grouper</option>
                    <option value="1">Activer</option>
                    <option value="2">Desactiver </option>
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
                            <th scope="col">Pseudo</th>
                            <th scope="col">Membre depuis</th>
                            <th scope="col">Mail</th>
                            <th scope="col">Commentaires</th>
                            <th scope="col">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            </th>
                            <td>[image] Inkylego</td>
                            <td>14 Juin 2023</td>
                            <td>ch.ntimba@bluewin.ch</td>
                            <td>23 Commentaires</td>
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