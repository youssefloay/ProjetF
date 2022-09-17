<?php

namespace App\Controller;

use App\Entity\Files;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;

class FileController extends AbstractController
{
  #[Route(path: "/files", name: "files_list")]
  public function list(EntityManager $em)
  {
    $files = $em->getRepository(Files::class)->findAll();


    echo $this->twig->render('files/list.html.twig', ['files' => $files]);
  }
}
