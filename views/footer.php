<footer class="footer p-4 mt-5 py-3 mt-auto">
    <?php  $pages = $this->sessionManager->get('pages'); ?>

    <?php 
        // debug( $pages );
    ?>
    
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