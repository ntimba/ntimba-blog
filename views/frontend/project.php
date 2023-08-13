<?php $title = "Le Titre du projet" ?>
<?php ob_start(); ?>

<!-- Project -->
<div class="container project">
    <div class="row">
        <div class="col-md-8">
            <img src="/assets/uploads/port-gallery-10.jpg" alt="">
            <img src="/assets/uploads/port-gallery-11.jpg" alt="">
            <img src="/assets/uploads/port-gallery-12.jpg" alt="">
        </div>

        <div class="col-md-4">
            <h2>Blue Ballon</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta perferendis fugit quasi reprehenderit? Voluptatum nobis magnam possimus, quas iste facere repellat necessitatibus odit ea fuga, ipsum vero? Suscipit, eveniet eum.</p>
            <ul>
                <li>Catégorie : Application Web</li>
                <li>Date : 16 Avril 2020</li>
                <li>Étiquette : Art, illustration</li>
            </ul>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require('./views/layout.php'); ?>



