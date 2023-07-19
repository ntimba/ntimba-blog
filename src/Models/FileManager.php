<?php

declare(strict_types=1);

namespace Ntimbablog\Portfolio\Models;

use JetBrains\PhpStorm\ExpectedValues;
use \Exception;

class FilesManager
{
    public function importFile(array $file, string $destination) : string|NULL
    {
        if( isset($file['name']) && $file['error'] == 0 ) {
            if( $file['size'] <= 2000000 )
            {
                $fileInfo = pathinfo($file['name']);
                $extension = $fileInfo['extension'];
                $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'ico','pdf'];

                if( in_array( $extension, $allowedExtensions ))
                {
                    $newFileName = str_replace(' ', '_', basename($file['name']) );
                    $filePath = $destination . $newFileName;
                    if( move_uploaded_file($file['tmp_name'], $filePath) )
                    {
                        return $filePath;
                    }else {
                        return NULL;
                    }
                }
            }
        }
    }

    public function deleteFile(string $filePath) : bool
    {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public function renameFile(string $oldName, string $newName) : bool
    {
        if (file_exists($oldName)) {
            return rename($oldName, $newName);
        }
        return false;
    }

    public function copyFile(string $source, string $destination) : bool
    {
        if (file_exists($source)) {
            return copy($source, $destination);
        }
        return false;
    }

    public function moveFile(string $source, string $destination) : bool
    {
        if (file_exists($source)) {
            return rename($source, $destination);
        }
        return false;
    }
    
    public function fileDetails(string $filePath) : array
    {
        if (file_exists($filePath)) {
            return [
                'size' => filesize($filePath),
                'modified' => filemtime($filePath),
            ];
        }
        return [];
    }

    public function createDirectory(string $dirPath) : bool
    {
        if (!file_exists($dirPath)) {
            return mkdir($dirPath, 0777, true);
        }
        return false;
    }

    public function readDirectory(string $directoryPath) : array|false
    {
        if (is_dir($directoryPath)) {
            $files = scandir($directoryPath);
            return array_filter($files, function($file) use ($directoryPath) {
                return is_file($directoryPath . '/' . $file);
            });
        }
        return false;
    }

    public function downloadFile(string $filePath) : void
    {
        if (file_exists($filePath)) {
            // Définition des en-têtes HTTP
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            // Lecture du fichier et envoi vers le navigateur
            readfile($filePath);
            exit;

        } else {
            throw new Exception("Le fichier n'existe pas.");
        }
    }
    
}



