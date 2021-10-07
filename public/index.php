<?php

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";

if (
  php_sapi_name() !== 'cli' &&
  preg_match('/\.(?:png|jpg|jpeg|gif|ico)$/', $_SERVER['REQUEST_URI'])
) {
  return false;
}

use App\Entity\User;
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

$user = new User();

$user->setName("Bob")
  ->setFirstName("John")
  ->setUsername("Bobby")
  ->setPassword("randompass")
  ->setEmail("bob@bob.com")
  ->setBirthDate(new DateTime('1981-02-16'));

// On demande au gestionnaire d'entités de persister l'objet
// Attention, à ce moment-là l'objet n'est pas encore enregistré en BDD
$entityManager->persist($user);
$entityManager->flush();
