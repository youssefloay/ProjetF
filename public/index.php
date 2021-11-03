<?php

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";

if (
  php_sapi_name() !== 'cli' &&
  preg_match('/\.(?:png|jpg|jpeg|gif|ico)$/', $_SERVER['REQUEST_URI'])
) {
  return false;
}

use App\Controller\IndexController;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

// On indique à Doctrine où aller chercher les entités
// pour les analyser et les mapper dans la base de données
$paths = [__DIR__ . "/../src/Entity"];
$isDevMode = ($_ENV['APP_ENV'] === 'dev');

$dbParams = [
  'driver'   => $_ENV['DB_DRIVER'],
  'host'     => $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT'],
  'user'     => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD'],
  'dbname'   => $_ENV['DB_NAME'],
];

$config = Setup::createAnnotationMetadataConfiguration(
  $paths,
  $isDevMode,
  null,
  null,
  false
);

// Un gestionnaire d'entités = paramètres de connexion + objet configuration
$entityManager = EntityManager::create($dbParams, $config);

$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/') {
  $controller = new IndexController();
  $controller->index($entityManager);
}
