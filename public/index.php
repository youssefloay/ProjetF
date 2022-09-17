<?php
// pour ne pas afficher les warnings => à retirer si on prod.
error_reporting(E_ERROR | E_PARSE);

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";

if (
  php_sapi_name() !== 'cli' &&
  preg_match('/\.(?:png|jpg|jpeg|gif|ico)$/', $_SERVER['REQUEST_URI'])
) {
  return false;
}

use App\Config\Connection;
use App\Config\TwigEnvironment;
use App\Controller\FileController;
use App\Controller\IndexController;
use App\DependencyInjection\Container;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;

// Env vars - Possibilité d'utiliser le pattern Adapter
// Pour pouvoir varier les dépendances qu'on utilise
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

// BDD
$connection = new Connection();
$entityManager = $connection->init();

// Twig - Vue
$twigEnvironment = new TwigEnvironment();
$twig = $twigEnvironment->init();

// Service Container
$container = new Container();
$container->set(EntityManager::class, $entityManager);
$container->set(Environment::class, $twig);

// Routage
$router = new Router($container);
$router->registerRoutes();
// la page d'accueil
$router->addRoute(
    'home',
    '/',
    'GET',
    IndexController::class,
    'index'
);

// liste des fichier
$router->addRoute(
    'files_list',
    '/files',
    'GET',
    FileController::class,
    'list'
);

// Nouveau un fichier
$router->addRoute(
    'files_new',
    '/files/new',
    'GET',
    FileController::class,
    'new'
);

// Ajouter un fichier POST FORM
$router->addRoute(
    'files_add',
    '/files/add',
    'POST',
    FileController::class,
    'add'
);

// Action de modification/supression d'un fichier
$router->addRoute(
    'files_action',
    '/files/action',
    'POST',
    FileController::class,
    'action'
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
