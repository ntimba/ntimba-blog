<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Controllers;

abstract class CRUDController extends BaseController {

    public function create(): bool {
        // Afficher le formulaire de création

        throw new \Exception("Méthode non implémentée");
    }
    
    public function read(): void {
        // Afficher les détails d'un élément

        throw new \Exception("Méthode non implémentée");
    }
    
    public function update(): void {
        // Afficher un formulaire de mise à jour

        throw new \Exception("Méthode non implémentée");
    }
    
    public function delete(int $id): void {
        // pour supprimer un élément 

        throw new \Exception("Méthode non implémentée");
    }
}