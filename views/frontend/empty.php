<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Accueil</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img class="logo" src="./assets/img/logo_ntimba_transparency.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.html">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="portfolio.html">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.html">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.html">Contact</a>
                    </li>
                </ul>


                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Se connecter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.html">S'enregistrer</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center connected-user-badge">
                    <div class="rounded-img">
                        <img src="./assets/uploads/moi.jpg" alt="">
                    </div>
    
                    <div class="dropdown">
                        <a class="dropdown-toggle ms-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Chancy Ntimba
                        </a>
        
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="dashboard.html"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="posts.html"><i class="bi bi-file-richtext"></i> Articles</a></li>
                            <li><a class="dropdown-item" href="categories.html"><i class="bi bi-tags"></i> Catégories</a></li>
                            <li><a class="dropdown-item" href="comments.html"><i class="bi bi-chat-square-dots"></i> Commentaires</a></li>
                            <li><a class="dropdown-item" href="users.html"><i class="bi bi-people"></i> Utilisateurs</a></li>
                            <li><a class="dropdown-item" href="settings.html"><i class="bi bi-gear"></i> Paramètres</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right"></i> Se déconnecter</a></li>                            
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <footer class="footer p-4">
        <div class="container">
            <div class="row d-flex justify-content-between mb-4">
                <div class="col-md-6">
                    <a href="">
                        <img class="footer__logo" src="./assets/img/logo_ntimba_white.png" alt="">
                    </a>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <ul class="footer__nav d-flex">
                        <li><a class="footer__nav__link" href="#">Mentions légales</a></li>
                        <li><a class="footer__nav__link" href="#">Politique de confidentialité</a></li>
                        <li><a class="footer__nav__link" href="#">Cookies</a></li>
                        <li><a class="footer__nav__link" href="#">CGU</a></li>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>


