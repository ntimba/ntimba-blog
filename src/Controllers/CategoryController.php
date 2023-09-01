<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Helpers\ErrorHandler;
use Portfolio\Ntimbablog\Helpers\StringUtil;
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
            $authenticator
        );

        $this->categoryManager = new CategoryManager($db, $stringUtil);
        $this->category = new Category($stringUtil);
    }
    
    public function create(): bool  {

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

                    $errorMessage = $this->translationService->get('SLUG_EXIST','categories');
                    $this->errorHandler->addFlashMessage($errorMessage, "warning");
                                
                    $this->response->redirect('index.php?action=categories');
                    return false; 
                }

                if( $this->categoryManager->create($this->category) ){
                    $successMessage = $this->translationService->get('CATEGORY_ADDED','categories');
                    $this->errorHandler->addFlashMessage($successMessage, "success");
                                
                    $this->response->redirect('index.php?action=categories');
                }   
            }
        }else{
            $this->response->redirect('index.php?action=categories');
            return false;   
        }

        return true;
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
        
        // récupérer les nouveau données
        $data = $this->request->getAllPost();

        // créer un objet avec les nouveau données
        $categoryId = (int) $data['category_id'];
        
        $this->category->setId($categoryId); 
        $this->category->setDescription($data['category_description']); 
        $this->category->setIdParent($data['id_category_parent']);
        
        if(!$this->categoryManager->getCategoryId($data['category_name'])){
            $this->category->setName($data['category_name']); 
        }else{
            $warningMessage = $this->translationService->get('CATEGOSRY_NAME_EXIST','categories');
            $this->errorHandler->addFlashMessage($warningMessage, "warning");
                                
            $this->response->redirect('index.php?action=categories');
            return;
        }
        
        if( !$this->categoryManager->slugExists($data['category_slug']) ){
            $this->category->setSlug($data['category_slug']); 
        }else{
            $warningMessage = $this->translationService->get('SLUG_EXISTS','categories');
            $this->errorHandler->addFlashMessage($warningMessage, "warning");
                                
            $this->response->redirect('index.php?action=categories');
            return;
        }
        
        // Mettre à jour la table 
        // Interdir de supprimer la catégorie par défaut 

        if( $this->categoryManager->getCategoryId('Default') ){
            $warningMessage = $this->translationService->get('CANT_DELETE_DEFAULT_CATEGORY','categories');
            $this->errorHandler->addFlashMessage($warningMessage, "warning");
                                
            $this->response->redirect('index.php?action=categories');
            return;
        }

        if( $this->categoryManager->update($this->category) ){
            $successMessage = $this->translationService->get('CATEGORY_UPDATED','categories');
            $this->errorHandler->addFlashMessage($successMessage, "success");
                                
            $this->response->redirect('index.php?action=categories');
        }
    }

    public function modifyCategory(){
        $this->authenticator->ensureAdmin();

        $data = $this->request->getAllPost();

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
                    $this->categoryManager->delete($categoryId);
                }
            }

            $successMessage = $this->translationService->get('CATEGORY_DELETED','categories');
            $this->errorHandler->addFlashMessage($successMessage, "success");
            
            $this->response->redirect('index.php?action=categories');
                
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

    }

    public function handleAdminCategories():void {
        $this->authenticator->ensureAdmin();
        
        $this->category->setName('Default');
        $this->category->setSlug('default');

        // Créer la catégorie par défaut
        if(!$this->categoryManager->getCategoryId('Default')){
            $this->categoryManager->create($this->category);
        }
        
        // afficher les catégories
        $categoriesData = $this->getFormattedCategories($this->categoryManager);

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


