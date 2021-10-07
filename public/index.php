<?php

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// On indique à Doctrine où aller chercher les entités
// pour les analyser et les mapper dans la base de données
$paths = [__DIR__ . "/../src/Entity"];
$isDevMode = true;

$dbParams = [
  'driver'   => 'pdo_mysql',
  'host'     => 'localhost:3640',
  'user'     => 'root',
  'password' => 'motdepasse',
  'dbname'   => 'php_mvc',
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
