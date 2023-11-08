<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

    <title><?= $title ?></title>


    <style>
        html{
            scroll-snap-type: y mandatory;
            overflow-y: scroll;
            height: 100%;
        }

        /* .scroll-section {
            scroll-snap-align: start;
            height: 100vh;
        } */

        .home{
            height: 100vh;
        }
        .skills{
            height: 100vh;
        }

        .main-content{
            padding-top: 70px;
        }

        

    </style>


    
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav id="nav" class="navbar fixed-top navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="?action=home#home">
                <img class="logo" src="./assets/img/logo_ntimba_transparency.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php foreach( $this->mainMenu as $key => $menuItem ): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $menuItem['status'] ?? '' ?>" aria-current="page" href="?action=<?= $menuItem['link'] ?>"><?= $menuItem['name'] ?></a>
                        </li>
                    <?php endforeach;  ?>
                </ul>


                <!-- visible when logged in -->
                <?php if( $this->sessionManager->get('user_id') ) : ?>
                <div class="d-flex align-items-center connected-user-badge">
                    <div class="rounded-img">
                        <img src="<?=  $this->sessionManager->get('profile_picture'); ?>" alt="">
                    </div>
    
                    <div class="dropdown">
                        <a class="dropdown-toggle ms-3" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?=  $this->sessionManager->get('full_name'); ?>
                        </a>

                        <ul class="dropdown-menu">
                            <?php if($this->sessionManager->get('user_role') === 'admin'): ?>
                            <li><a class="dropdown-item" href="?action=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="?action=posts"><i class="bi bi-file-richtext"></i> Articles</a></li>
                            <li><a class="dropdown-item" href="?action=categories"><i class="bi bi-tags"></i> Catégories</a></li>
                            <li><a class="dropdown-item" href="?action=pages"><i class="bi bi-file-richtext"></i> Pages</a></li>
                            <li><a class="dropdown-item" href="?action=comments"><i class="bi bi-chat-square-dots"></i> Commentaires</a></li>
                            <li><a class="dropdown-item" href="?action=users"><i class="bi bi-people"></i> Utilisateurs</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="?action=update_user"><i class="bi bi-gear"></i> Paramètres</a></li>
                            <li><a class="dropdown-item" href="?action=update_password"><i class="bi bi-lock"></i> Mot de passe</a></li>
                            <li><a class="dropdown-item" href="?action=logout"><i class="bi bi-box-arrow-right"></i> Se déconnecter</a></li>                            
                        </ul>
                    </div>
                </div>

                <?php else: ?>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="?action=login">Se connecter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=register">S'enregistrer</a>
                    </li>
                </ul>
                <?php endif; ?>

            </div>
        </div>
    </nav>

    <!-- page content -->
    <div class="container my-auto main-content">
        <?= $content ?>
    </div>


    <!-- Footer -->
    <footer class="footer p-4 mt-5 py-3 mt-auto">
        <?php  $pages = $this->sessionManager->get('pages'); ?>

        <div class="container mt-3">
            <div class="row d-flex justify-content-between mb-4">
                <div class="col-md-6">
                    <!-- Logo -->
                    <a href="">
                        <img class="footer__logo" src="./assets/img/logo_ntimba_white.png" alt="">
                    </a>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <ul class="footer__nav d-flex">
                        <li><a class="footer__nav__link" href="../assets/uploads/cv.pdf">Télécharger mon CV</a></li>
                        <?php if(isset( $this->footerMenu )): ?>
                        <?php foreach($this->footerMenu as $link): ?>
                        <li><a class="footer__nav__link" href="index.php?action=page&id=<?=  $link['id'] ?>"><?= $link['title'] ?></a></li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="row border-top pt-4">
                <div class="col-md-6">
                    <p class="footer__copyright">All rights reserved © Ntimba Software 2023</p>
                </div>

                <div class="col-md-6 d-flex justify-content-end">
                    <ul class="footer__social-media">
                        <li><a class="footer__social-media__link" href="https://github.com/ntimba"><i class="bi bi-github"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>



    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script> -->
    <!-- <script src="/node_modules/@popperjs/core/lib/popper.js"></script>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="/assets/js/chartsdata.js"></script>
    <script src="/assets/js/selectall.js"></script>
    <script src="/assets/js/publishpost.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const fileInput = document.querySelector("input[name='image']");
            const alertContainer = document.getElementById("alert-container");

            form.addEventListener("submit", function(event) {
            const fileSize = fileInput.files[0]?.size || 0;

            // Taille maximale en octets (2 Mo)
            const maxSize = 2 * 1024 * 1024;

            if (fileSize > maxSize) {
                const alertMessage = `
                <div class="alert alert-warning" role="alert">
                    La taille de l'image dépasse la limite autorisée de 2 Mo.
                </div>
                `;

                alertContainer.innerHTML = alertMessage;
                event.preventDefault();
            } else {

                alertContainer.innerHTML = '';
            }
            });
        });
    </script>
</body>
</html>


