<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Twig\Environment;

abstract class AbstractController
{
    protected EntityManager $entityManager;
    protected Environment $twig;

    public function __construct(EntityManager $entityManager, Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    /*
   * Retourne l'utilisateur par défeaut id 1.
   */
    public function getConnectedUser()
    {
        return $this->entityManager->getRepository(User::class)->find(1);
    }

    // mettre en place http foundation de symfony ou une lib de http pour gérer la request et les red
    public function redirect(string $location)
    {
        header("Location: /$location");
        die();
    }

    public function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
