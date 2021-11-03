<?php

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";

if (
  php_sapi_name() !== 'cli' &&
  preg_match('/\.(?:png|jpg|jpeg|gif|ico)$/', $_SERVER['REQUEST_URI'])
) {
  return false;
}

use App\Config\Connection;
use App\Controller\IndexController;
use App\Router;
use Symfony\Component\Dotenv\Dotenv;

// Env vars - Possibilité d'utiliser le pattern Adapter
// Pour pouvoir varier les dépendances qu'on utilise
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

// BDD
$connection = new Connection();
$entityManager = $connection->init();

// Routage
$router = new Router();

$router->addRoute(
  'home',
  '/',
  'GET',
  IndexController::class,
  'index'
)->addRoute(
  'contact',
  '/contact',
  'GET',
  IndexController::class,
  'contact'
);

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->execute($requestUri, $requestMethod);
