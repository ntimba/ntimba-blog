<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\LayoutHelper;
use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Helpers\Paginator;
use Portfolio\Ntimbablog\Http\HttpResponse;
use Portfolio\Ntimbablog\Http\Request;
use Portfolio\Ntimbablog\Http\SessionManager;
use Portfolio\Ntimbablog\Lib\Database;
use Portfolio\Ntimbablog\Models\CategoryManager;
use Portfolio\Ntimbablog\Models\Category;
use Portfolio\Ntimbablog\Service\Authenticator;
use Portfolio\Ntimbablog\Service\EnvironmentService;
use Portfolio\Ntimbablog\Service\MailService;
use Portfolio\Ntimbablog\Service\TranslationService;
use Portfolio\Ntimbablog\Service\ValidationService;

class CategoryController extends CRUDController
{
    private $categoryManager;
    private $category;

    public function __construct(
        ErrorHandler $errorHandler,
        MailService $mailService,
        TranslationService $translationService,
        ValidationService $validationService,
        Request $request,
        Database $db,
        HttpResponse $response,
        SessionManager $sessionManager,
        StringUtil $stringUtil,
        Authenticator $authenticator,
        LayoutHelper $layoutHelper
        )
    {
        parent::__construct(
            $errorHandler,
            $mailService,
            $translationService,
            $validationService,
            $request,
            $db,
            $response,
            $sessionManager,
            $stringUtil,
            $authenticator,
            $layoutHelper
        );

        $this->categoryManager = new CategoryManager($db, $stringUtil);
        $this->category = new Category($stringUtil);
    }
    
    public function create(): void  {

        $this->authenticator->ensureAdmin();
        
        $data = $this->request->getAllPost();

        if( $this->validationService->validateCategoryData($data) ){
            if( !$this->categoryManager->getCategoryId($data['category_name']) ){

                $this->category->setName($data['category_name']); 
                $this->category->setDescription($data['category_description']); 
                $this->category->setIdParent($data['id_category_parent']); 

                if( !$this->categoryManager->slugExists($data['category_slug']) ){
                    $this->category->setSlug($data['category_slug']); 
                }else{

                    $errorMessage = $this->translationService->get('SLUG_EXISTS','categories');
                    $this->errorHandler->addFlashMessage($errorMessage, "warning");
                                
                    $this->response->redirect('index.php?action=categories');
                    return; 
                }

                if( $this->categoryManager->create($this->category) ){
                    $successMessage = $this->translationService->get('CATEGORY_ADDED','categories');
                    $this->errorHandler->addFlashMessage($successMessage, "success");
                                
                    $this->response->redirect('index.php?action=categories');
                }   
            }
        }else{
            $this->response->redirect('index.php?action=categories');
            return;   
        }
    }

    public function read(): void {
        $this->authenticator->ensureAdmin();

        $categoryId = (int) $this->request->get('id');

        if( $categoryId === 0 ){
            $errorMessage = $this->translationService->get('CHOOSE_CATEGORY','categories');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");
            
            $this->response->redirect('index.php?action=categories');
            return;
        }
        
        $category = $this->categoryManager->read($categoryId);
        $categoryData['category_id'] = $category->getId();
        $categoryData['category_name'] = $category->getName();
        $categoryData['category_slug'] = $category->getSlug();
        $categoryData['category_description'] = $category->getDescription();
        $categoryData['category_date'] = $category->getCreationDate();
        $categoryData['category_parent'] = $category->getIdParent();
        
        // recupéréer les catégories existant dans la base de données
        $categoriesData = $this->getFormattedCategories($this->categoryManager);

        $errorHandler = $this->errorHandler;
        require("./views/backend/formcategory.php");
    }
    
    public function update(): void
    {
        // protéger par mot de passe 
        $this->authenticator->ensureAdmin();
        
        /**
         * Ce bout de code permet de mettre à jour les catégories
         */
        $categoryModifiedData = $this->request->getAllPost();
        if( $this->validationService->validateCategoryData($categoryModifiedData) )
        {
            $categoryId = (int) $categoryModifiedData['category_id'];
            
            // recupérer la catégorie de la base de données
            $category = $this->categoryManager->read( $categoryId );
            $category->setName($categoryModifiedData['category_name']);
            $category->setSlug($categoryModifiedData['category_slug']);
            $category->setDescription($categoryModifiedData['category_description']);
            $category->setIdParent($categoryModifiedData['id_category_parent']);
            
            // mettre à jour la base de données
            $this->categoryManager->update($category);
            $successMessage = $this->translationService->get('CATEGORY_UPDATED','categories');
            $this->errorHandler->addFlashMessage($successMessage, "success");

            $this->response->redirect('index.php?action=categories');
        }
    }

    public function modifyCategory(): void
    {
        $this->authenticator->ensureAdmin();

        $categoriesDataList = $this->request->getAllPost();

        if( !isset( $categoriesDataList['category_ids'] ) )
        {
            $warningMessage = $this->translationService->get('CHOOSE_A_CATEGORY','categories');
            $this->errorHandler->addFlashMessage($warningMessage, "warning");
            $this->response->redirect('index.php?action=categories');
        }

        if ($categoriesDataList['category_modify'] != 'update' && $categoriesDataList['category_modify'] != 'delete') {
            $warningMessage = $this->translationService->get('CHOOSE_AN_ACTION','categories');
            $this->errorHandler->addFlashMessage($warningMessage, "warning");
            $this->response->redirect('index.php?action=categories');
        }

        /**
         * Ce bout de code interdit de pouvoir choisir plusieurs catégorie
         * pour les modifier en même temps
         */
        if( $categoriesDataList['category_modify'] === 'update' && count($categoriesDataList['category_ids']) > 1 ){
            $warningMessage = $this->translationService->get('CHOOSE_ONLY_ONE_CATEGORY','categories');
            $this->errorHandler->addFlashMessage($warningMessage, "warning");
            $this->response->redirect('index.php?action=categories');
        }
        
        foreach( $categoriesDataList['category_ids'] as $categoryId ){
            $categoryId = intval( $categoryId );

            /**
             * Ce bout de code interdit la modification de 
             * la catégorie par défaut
             */
            $categoryData = $this->categoryManager->read( $categoryId );
            if( $categoryData->getName() === 'Default'){
                $warningMessage = $this->translationService->get('CANT_UPDATE_DEFAULT_CATEGORY','categories');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
                $this->response->redirect('index.php?action=categories');
            }

            if( $categoriesDataList['category_modify'] === 'update'){

                if( $this->categoryManager->isParent( $categoryId ) ){
                    $warningMessage = $this->translationService->get('CANT_UPDATE_PARENT_CATEGORY','categories');
                    $this->errorHandler->addFlashMessage($warningMessage, "warning");
                    session_write_close();
                    $this->response->redirect('index.php?action=categories');
                }

                $category = $this->categoryManager->read($categoryId);
                $categoryData = [];
                $categoryData['category_id'] = $category->getId();
                $categoryData['category_name'] = $category->getName();
                $categoryData['category_slug'] = $category->getSlug();
                $categoryData['category_description'] = $category->getDescription();
                $categoryData['category_date'] = $category->getCreationDate();
                $categoryData['category_parent'] = $category->getIdParent();
                

                /**
                 * Ce bout de code prepare les catégories à afficher dans 
                 * la partie catégorie parent 
                 */
                // $categoriesData
                $categories = $this->categoryManager->getAll();
                $categoriesData = [];
                foreach( $categories as $category ){
                    $categoryData['category_id'] = $category->getId();
                    $categoryData['category_name'] = $category->getName();
                    $categoriesData[] = $categoryData;
                }

                $errorHandler = $this->errorHandler;
                require("./views/backend/formcategory.php");

            }elseif( $categoriesDataList['category_modify'] === 'delete'){

                if( $this->categoryManager->isParent( $categoryId ) ){
                    $warningMessage = $this->translationService->get('CANT_DELETE_PARENT_CATEGORY','categories');
                    $this->errorHandler->addFlashMessage($warningMessage, "warning");
                    session_write_close();
                    $this->response->redirect('index.php?action=categories');
                }
                
                $this->categoryManager->delete( $categoryId );

                $successMessage = $this->translationService->get('CATEGORY_DELETED','categories');
                $this->errorHandler->addFlashMessage($successMessage, "success");
                $this->response->redirect('index.php?action=categories');
            }
            
        }

        
        // debug( $categoriesDataList );

        // debug($categoriesDataList['category_modify']);
        // debug( $categoriesDataList['category_ids'] );
        
        
        /*
        if( $this->request->post('category_modify') === 'delete' )
        {
            // On peut effacer plusieurs catégorie à la fois sauf la catégorie par défaut
            if(!isset($data['category_ids'])){
                $errorMessage = $this->translationService->get('CHOOSE_A_CATEGORY','categories');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");
                
                $this->response->redirect('index.php?action=categories');
                return;
            }

            foreach( $data['category_ids'] as $categoryId ){
                $categoryId = (int) $categoryId;
                $category = $this->categoryManager->read($categoryId);

                if( $category->getName() === 'Default' ){
                    $errorMessage = $this->translationService->get('CANT_DELETE_DEFAULT_CATEGORY','categories');
                    $this->errorHandler->addFlashMessage($errorMessage, "warning");
                }else{
                    if( !$this->categoryManager->isParent( $categoryId ) ){
                        $this->categoryManager->delete($categoryId);

                        $successMessage = $this->translationService->get('CATEGORY_DELETED','categories');
                        $this->errorHandler->addFlashMessage($successMessage, "success");
                        $this->response->redirect('index.php?action=categories');
                    }else{
                        
                        $warningMessage = $this->translationService->get('CANT_DELETE_CATEGORY','categories');
                        $this->errorHandler->addFlashMessage($warningMessage, "warning");
                        $this->response->redirect('index.php?action=categories');
                    }
                    
                }
            }

                
        }elseif( $this->request->post('category_modify') === 'update' ){
            // on ne peut pas modifier la catégorie par défaut
            // On peut editer q'une catégorie à la fois

            $data = $this->request->getAllPost();

            if( !isset($data['category_ids']) ){
                $warningMessage = $this->translationService->get('CHOOSE_A_CATEGORY','categories');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
                
                $this->response->redirect('index.php?action=categories');
                return;
            }
            
            if( count($data['category_ids']) > 1 ){
                $warningMessage = $this->translationService->get('CHOOSE_ONLY_ONE_CATEGORY','categories');
                $this->errorHandler->addFlashMessage($warningMessage, "warning");
                
                $this->response->redirect('index.php?action=categories');
                return;
            }


            $categoryId = (int) $data['category_ids'][0];
            
            $category = $this->categoryManager->read($categoryId);
            $categoryData = [];
            $categoryData['category_id'] = $category->getId();
            $categoryData['category_name'] = $category->getName();
            $categoryData['category_slug'] = $category->getSlug();
            $categoryData['category_description'] = $category->getDescription();
            $categoryData['category_date'] = $category->getCreationDate();
            $categoryData['category_parent'] = $category->getIdParent();
            

            // Afficher le formulaire qui permet de modifier la catégorie
            $errorHandler = $this->errorHandler;
            require("./views/backend/formcategory.php");
              
        }else{
            $errorMessage = $this->translationService->get('CHOOSE_AN_ACTION','categories');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");

            $this->response->redirect('index.php?action=categories');
            return;
        }
        */


    }

    public function handleAdminCategories():void {
        $this->authenticator->ensureAdmin();

        $this->category->setName('Default');
        $this->category->setSlug('default');

        // Créer la catégorie par défaut
        if(!$this->categoryManager->getCategoryId('Default')){
            $this->categoryManager->create($this->category);
        }
        
        $totalItems = $this->categoryManager->getTotalCategoriesCount();
        $itemsPerPage = 10;
        $currentPage = intval($this->request->get('page')) ?? 1;
        $linkParam = 'categories';
        
        $fetchUsersCallback = function($offset, $limit){
            return $this->categoryManager->getCategoriesByPage($offset, $limit);
        };
        
        $paginator = new Paginator($this->request, $totalItems, $itemsPerPage, $currentPage,$linkParam , $fetchUsersCallback);
        
        $categories = $paginator->getItemsForCurrentPage();
        foreach( $categories as $category )
        {
            $categoryData = [];
    
            $categoryData['category_id'] = $category->getId();
            $categoryData['category_name'] = $category->getName();
            $categoryData['category_slug'] = $category->getSlug();
            $categoryData['category_total_posts'] = '300 Articles';
            if($category->getIdParent())
            {
                $parent = $this->categoryManager->read($category->getIdParent());
                $categoryData['category_parent_name'] = $parent->getName();
            }else{
                $categoryData['category_parent_name'] = '-';
            }

            $categoriesData[] = $categoryData;
        }
        
        
        $paginationLinks = $paginator->getPaginationLinks($currentPage, $paginator->getTotalPages());
        $errorHandler = $this->errorHandler;
        require("./views/backend/categories.php");
    }


    private function getFormattedCategories(CategoryManager $categoryManager) : array {

        $categories = $categoryManager->getAll();
        $categoriesData = [];
        foreach( $categories as $category )
        {
            $categoryData = [];
    
            $categoryData['category_id'] = $category->getId();
            $categoryData['category_name'] = $category->getName();
            $categoryData['category_slug'] = $category->getSlug();
            $categoryData['category_total_posts'] = '300 Articles';
            if($category->getIdParent())
            {
                $parent = $categoryManager->read($category->getIdParent());
                $categoryData['category_parent_name'] = $parent->getName();
            }else{
                $categoryData['category_parent_name'] = '-';
            }

            $categoriesData[] = $categoryData;
        }

        return $categoriesData;
    }    

}


