<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\Http\HttpResponse;

use JetBrains\PhpStorm\ExpectedValues;
use \Exception;

class FilesManager
{
    private $response;

    public function __construct( HttpResponse $response )
    {
        $this->response = $response;
    }
    public function importFile(array $file, string $destination) : string|NULL
    {
        if(!isset($file['name'])) {
            throw new Exception('File name is not set.');
        }
        
        if($file['error'] !== 0) {
            throw new Exception('Error uploading file. Error code: ' . $file['error']);
        }
        
        if($file['size'] > 2000000) {
            throw new Exception('File size exceeds the limit of 2MB.');
        }
    
        $fileInfo = pathinfo($file['name']);
        $extension = $fileInfo['extension'];
        $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'ico','pdf', 'webp'];
    
        if(!in_array($extension, $allowedExtensions)) {
            throw new Exception('File type not allowed.');
        }
    
        $newFileName = str_replace(' ', '_', basename($file['name']));
        $filePath = $destination . $newFileName;
    
        if(!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Failed to move uploaded file.');
        }
    
        return $filePath;
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

            $this->response->setHeader('Content-Description', 'File Transfer');
            $this->response->setHeader('Content-Type', 'application/octet-stream');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . basename($filePath) . '"');
            $this->response->setHeader('Expires', '0');
            $this->response->setHeader('Cache-Control', 'must-revalidate');
            $this->response->setHeader('Pragma', 'public');
            $this->response->setHeader('Content-Length', (string)filesize($filePath));
            
            readfile($filePath);
            return;

        } else {
            throw new Exception("file not exist");
        }
    }
    
}



