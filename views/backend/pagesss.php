<?php $title = "Le Portfolio de Ntimba" ?>
<?php ob_start(); ?>

<!-- Pages -->
<div class="pages mt-5 mb-5">
    <div class="container">
        <?php echo $errorHandler->displayErrors(); ?>
        <div class="row">
            <div class="col">
                <a href="index.php?action=add_page" class="btn btn-primary col-md-3"><i class="bi bi-plus-circle-fill"></i> Nouvelle page</a>
            </div>

            <div class="col">
                <select class="form-select" aria-label="Default select example col-md-3">
                    <option selected disabled>Action grouper</option>
                    <option value="1">Publier</option>
                    <option value="2">Masquer </option>
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
                            <th scope="col">Titre</th>
                            <th scope="col">Date</th>
                            <th scope="col">Publier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            </th>
                            <td>la sortie de l'iphone 15</td>
                            <td>14 Juin 2023</td>
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


