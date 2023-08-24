<?php 

require __DIR__ . '/vendor/autoload.php';

use Portfolio\Ntimbablog\Lib\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->routeRequest();


function debug($var)
{
    echo "<pre>";
    var_dump($var);
    echo "<pre>";
}





