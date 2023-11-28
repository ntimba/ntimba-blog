<?php
// Src/Helpers/Paginator.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Helpers;
use Portfolio\Ntimbablog\Http\Request;


class Paginator
{
    private $totalItems; 
    private $itemsPerPage; 
    private $currentPage; 
    private $linkParam;
    private $fetchCallback;
    
    private $request;

    public function __construct(Request $request, float $totalItems, int $itemsPerPage, int $currentPage, string $linkParam, callable $fetchCallback)
    {
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = $currentPage;
        $this->linkParam = $linkParam;
        $this->request = $request;
        $this->fetchCallback = $fetchCallback; 
    }

    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    public function getTotalPages(): float|false
    {
        /**
         * This method receives two parameters,
         * to be modified in the controller.
         */
        return ceil($this->totalItems/$this->itemsPerPage);
    }

    public function getCurrentPage(): int
    {
        $pageValue = $this->request->get('page');
        return max(1, intval($pageValue)); 
    }
    
    public function getItemsForCurrentPage(): array|false
    {
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $limit = $this->itemsPerPage;


        return call_user_func($this->fetchCallback, $offset, $limit);
    }   

    public function getPaginationLinks(): string
    {
        ob_start(); 
        ?>
        <nav aria-label="Page navigation">    
            <ul class="pagination d-flex justify-content-center mb-5 mt-5">
                <?php if ($this->getCurrentPage() > 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?action=<?= $this->linkParam ?>&page=<?= $this->getCurrentPage() - 1 ?>">Précédent</a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                    </li>
                <?php endif; ?>
            

                <?php 
                $numAdjacent = 2; 
                $ellipsisShown = false; 
                $totalPages = $this->getTotalPages();
                $currentPage = $this->getCurrentPage();


                for ($i = 1; $i <= $totalPages; $i++) : 
                    if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $numAdjacent && $i <= $currentPage + $numAdjacent)) :
                ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?action=<?= $this->linkParam ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                <?php 
                    elseif (($i == 2 || $i == $totalPages - 1) && !$ellipsisShown) :
                        $ellipsisShown = true;
                ?>
                        <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <?php 
                    endif;
                endfor; 
                ?>
        
                <?php if ($currentPage < $totalPages) : ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?action=<?= $this->linkParam ?>&page=<?= $currentPage + 1 ?>">Suivant</a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Suivant</a>
                    </li>
                <?php endif; ?>
            </ul>  
        </nav>
        <?php
        $links = ob_get_contents(); 
        ob_end_clean(); 
        return $links; 
    }    

}


