<?php
// Src/Helpers/LayoutHelper.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Helpers;

use Portfolio\Ntimbablog\Models\PageManager;
use Portfolio\Ntimbablog\Http\Request;


class LayoutHelper
{
    private $pageManager; 
    private $request;

    public function __construct(PageManager $pageManager, Request $request)
    {
        $this->pageManager = $pageManager;
        $this->request = $request;
    }

    public function mainMenuHelper() // : array | null
    {

        // recupréer le lien actuel
        $currentPath = $this->request->getAllGet();

        $mainMenu = [
            ["name" => "Accueil", "link" => "home"],
            ["name" => "Blog", "link" => "blog"],
            ["name" => "Contact", "link" => "contact"]
        ]; 

        if(isset($currentPath['action'])){
            foreach( $mainMenu as $key => $menuItem ) {
                if( $menuItem['link'] === $currentPath['action'] )
                {
                    $mainMenu[$key]['status'] = 'active';
                }
            }
        }

        
        return $mainMenu;        
    }
    
    public function footerHelper() : array | null
    {
        /**
         * This method retrieves all the pages
         * and stores their identifier and title
         * in the variable $pageList
         */

        $pages = $this->pageManager->getAll();
        $listPages = [];
        foreach( $pages as $page ){
            if( $page->getStatus() ){
                $pageData['id'] = $page->getId();
                $pageData['title'] = $page->getTitle();

                $listPages[] = $pageData;
            }
        }
        return $listPages; 
        
    }
}
