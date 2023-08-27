<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

use Portfolio\Ntimbablog\Models\CategoryManager;
use Portfolio\Ntimbablog\Models\Category;

use Portfolio\Ntimbablog\Service\EnvironmentService;


class CategoryController extends BaseController
{

    public function modifyCategory() : void
    {
        $this->authenticator->ensureAdmin();
        
        $categoryManager = new CategoryManager($this->db, $this->stringUtil);
        $data = $this->request->getAllPost();
        
        if( $this->request->post('category_modify') === 'delete' )
        {
            
            if(!isset($data['category_id'])){
                
                $errorMessage = $this->translationService->get('CHOOSE_A_CATEGORY','categories');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");
                
                $this->response->redirect('index.php?action=categories');
                return;
            }
            
            $categoryIds = $data['category_id'];
            $defaultCategoryId = $categoryManager->getCategoryId('Default');
            
            foreach( $categoryIds as $categoryId ) {
                $categoryId = (int) $categoryId;
                
                if( $categoryId === $defaultCategoryId )
                {
                    $errorMessage = $this->translationService->get('CANT_DELETE_DEFAULT_CATEGORY','categories');
                    $this->errorHandler->addFlashMessage($errorMessage, "warning");

                    $this->response->redirect('index.php?action=categories');
                    return;
                } 

                if( $categoryManager->isParent($categoryId) )
                {
                    // Si la catégorie est une parente, on affiche un message et sort de la fonction
                    $errorMessage = $this->translationService->get('CANT_DELETE_PARENT_CATEGORY','categories');
                    $this->errorHandler->addFlashMessage($errorMessage, "warning");

                    $this->response->redirect('index.php?action=categories');
                    return;
                }

                $categoryManager->deleteCategory($categoryId);
                $successMessage = $this->translationService->get('CATEGORY_DELETED_SUCCESS','categories');
                $this->errorHandler->addFlashMessage($successMessage, "success");
            }

            $this->response->redirect('index.php?action=categories');
            return;
            
        } elseif ( $this->request->post('category_modify') === 'update' ) { 

            $categoryIds = $data['category_id'];
            $defaultCategoryId = $categoryManager->getCategoryId('Default');

            if( count( $categoryIds ) > 1 ){
                $errorMessage = $this->translationService->get('ONLY_ONE_CATEGORY_AT_TIME','categories');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");
                
                $this->response->redirect('index.php?action=categories');
                return;
            }

            if( in_array($defaultCategoryId, $categoryIds))
            {
                $errorMessage = $this->translationService->get('CANT_UPDATE_DEFAULT_CATEGORY','categories');
                $this->errorHandler->addFlashMessage($errorMessage, "warning");
                
                $this->response->redirect('index.php?action=categories');
                return;
            }

            // convertir l'identifiant en entier
            $categoryId = (int) $categoryIds[0];

            $category = $categoryManager->getCategory($categoryId);
            $categoryData = [
                'category_id' => $category->getId(),
                'category_name' => $category->getName(),
                'category_slug' => $category->getSlug(),
                'category_description' => $category->getDescription(),
                'category_creation_date' => $category->getCreationDate(),
                'category_parent_id' => $category->getIdParent()
            ];

            // recupérer toute les catégories
            $categoriesData = $this->getFormattedCategories($categoryManager);

            $errorHandler = $this->errorHandler;
            require("./views/backend/formcategory.php");  
        } else{
            $errorMessage = $this->translationService->get('CHOOSE_AN_ACTION','categories');
            $this->errorHandler->addFlashMessage($errorMessage, "warning");

            $this->response->redirect('index.php?action=categories');
            return;
        }
               
    }

    public function updateCategory() : void {

        $data = $this->request->getAllPost();
        if( $this->validationService->validateCategoryData($data) ) {

            $categoryManager = new CategoryManager($this->db, $this->stringUtil);
            $categoryId = $categoryManager->getCategoryId($this->request->post('category_name'));

            $data['category_id'] = (int) $categoryId;
                        
            $categoryData = [
                'id' => $data['category_id'],
                'name' => $data['category_name'],
                'slug' => $data['category_slug'],
                'idParent' => $data['id_category_parent'],
                'description' => $data['category_description']
            ];
            
            $category = new Category($this->stringUtil, $categoryData);
            $categoryManager->updateCategory($category);

            $successMessage = $this->translationService->get('CATEGORY_UPDATED_SUCCESS','categories');
            $this->errorHandler->addFlashMessage($successMessage, "success");

            $this->response->redirect('index.php?action=categories');
            return;
        }

    }

    public function handleAddCategory() : void
    {

        $data = $this->request->getAllPost();

        
        if( $this->validationService->validateCategoryData($data) )
        {
            $categoryData = [
                'name' => $this->request->post('category_name'),
                'slug' => $this->request->post('category_slug'),
                'idParent' => $this->request->post('id_category_parent'),
                'description' => $this->request->post('category_description')
            ];

            $category = new Category($this->stringUtil,$categoryData);
            $categoryManager = new CategoryManager($this->db, $this->stringUtil);


            if(!$categoryManager->getCategoryId($categoryData['name']))
            {
                $categoryManager->insertCategory($category);
    
                $successMessage = $this->translationService->get('CATEGORY_ADDED_SUCCESS','categories');
                $this->errorHandler->addFlashMessage($successMessage, "success");
    
                $this->response->redirect('index.php?action=categories');
                return;
            }

            $errorMessage = $this->translationService->get('CATEGORY_EXIST','categories');
            $this->errorHandler->addFlashMessage($errorMessage, "danger");
            
        }

        $errorHandler = $this->errorHandler;
        $this->response->redirect('index.php?action=categories');
    }

    public function handleCategoriesPage() : void
    {
        $this->authenticator->ensureAdmin();
        
        $categoryManager = new CategoryManager($this->db, $this->stringUtil);
        $defaultCategory = new Category($this->stringUtil);
        $defaultCategory->setName('Default');
        $defaultCategory->setSlug('default');

        // Créer la catégorie par défaut
        if(!$categoryManager->getCategoryId('Default')){
            $categoryManager->insertCategory($defaultCategory);
        }
        
        // afficher les catégories
        $categoriesData = $this->getFormattedCategories($categoryManager);

        $errorHandler = $this->errorHandler;
        require("./views/backend/categories.php");
    }

    private function getFormattedCategories(CategoryManager $categoryManager) : array {

        $categories = $categoryManager->getCategories();
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
                $parent = $categoryManager->getCategory($category->getIdParent());
                $categoryData['category_parent_name'] = $parent->getName();
            }else{
                $categoryData['category_parent_name'] = '-';
            }

            $categoriesData[] = $categoryData;
        }

        return $categoriesData;
    }

}


