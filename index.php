<?php 
session_start();

require __DIR__ . '/vendor/autoload.php';

use Portfolio\Ntimbablog\Lib\Router;

function debug( $var )
{
    echo "<pre>";
    var_dump( $var );
    echo "<pre>";
}


$router = new Router();
$router->routeRequest();

