<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use DateTime;

class IndexController extends AbstractController
{
  #[Route(path: "/")]
  public function index(): void
  {
    $user = new User();

    $user->setName("Bob")
      ->setFirstName("John")
      ->setUsername("Bobby")
      ->setPassword("randompass")
      ->setEmail("bob@bob.com")
      ->setBirthDate(new DateTime('1981-02-16'));

    // On demande au gestionnaire d'entités de persister l'objet
    // Attention, à ce moment-là l'objet n'est pas encore enregistré en BDD
      $this->entityManager->persist($user);
      $this->entityManager->flush();

  }

  #[Route(path: "/contact", httpMethod: "POST", name: "contact")]
  public function contact(): void
  {
    echo $this->twig->render('index/contact.html.twig');
  }
}
