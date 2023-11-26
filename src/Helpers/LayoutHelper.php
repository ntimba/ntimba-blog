<?php
// Src/Helpers/LayoutHelper.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Helpers;
use Portfolio\Ntimbablog\Models\SocialnetworkManager;
use Portfolio\Ntimbablog\Models\PageManager;
use Portfolio\Ntimbablog\Http\Request;


class LayoutHelper
{
    private $pageManager; 
    private $networkManager;
    private $request;

    public function __construct(PageManager $pageManager, SocialnetworkManager $networkManager ,Request $request)
    {
        $this->pageManager = $pageManager;
        $this->networkManager = $networkManager; 
        $this->request = $request;
        
        
    }

    public function mainMenuHelper() // : array | null
    {
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

    public function networksHelper(): array | null
    {
        $networks = $this->networkManager->getAll();
        $listNetworks = [];
        foreach( $networks as $network ){
            $networkData['name'] = $network->getNetworkName();
            $networkData['url'] = $network->getNetworkUrl();
            $networkData['class_css'] = $network->getNetworkIconClass();

            $listNetworks[] = $networkData; 
        }

        return $listNetworks;
    }

}
