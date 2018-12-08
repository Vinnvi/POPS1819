<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class NoteFraisController
{
    /**
    * @var Environment
    **/
    private $twig;

    public function __construct(Environment $twig)
    {
      $this->twig = $twig;
    }

    public function index(): Response
    {
      return new Response($this->twig->render('pages/noteFrais.html.twig'));
    }
}
