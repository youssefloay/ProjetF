<?php
// pour ne pas afficher les warnings => à retirer si on prod.
error_reporting(E_ERROR | E_PARSE);

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";
// Routage
$router = new Router($container);
$router->registerRoutes();
// la page d'accueil
$router->addRoute(
    'home',
    '/',
    IndexController::class,
    'index'
);

// liste des fichier
$router->addRoute(
    'files_list',
    '/files',
    'list'
);

// Ajouter un fichier
$router->addRoute(
    'files_new',
    '/files/new',
    'GET',
    FileController::class,
    'new'
);

// Ajouter un fichier
$router->addRoute(
    'files_add',
    '/files/add',
    'POST',
    FileController::class,
    'add'
);

if (php_sapi_name() === 'cli') {
  return;
}

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
  $router->execute($requestUri, $requestMethod);
} catch (RouteNotFoundException $e) {
  http_response_code(404);
  echo $twig->render('404.html.twig', ['title' => $e->getMessage()]);
}
