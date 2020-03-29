<?php

ini_set('display_errors', true);

$path = explode('?', $_SERVER['REQUEST_URI'])[0];
$path = trim($path, '/');

$routes = require 'routes/index.php';

if (array_key_exists($path, $routes)) {
    $route = $routes[$path];
    $controller = $route['controller'];

    require_once "tools/functions.php";
    require_once "controllers/$controller.php";

    call_user_func($route['action'] . 'Action');

    exit;
}

require_once 'controllers/error.php';

notFound();
