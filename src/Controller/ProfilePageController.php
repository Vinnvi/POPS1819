<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Entity\ProfilePage;
use App\Repository\ProfilePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePageController extends Controller
{
    /**
    * @var Environment
    **/
    private $twig;

    /**
     * @var ProfilePageRepository
     */
    private $repository;


    public function __construct(Environment $twig,ProfilePageRepository $repository)
    {
      $this->twig = $twig;
      $this->repository = $repository;
    }

    public function index(): Response
    {
      return new Response($this->twig->render('pages/profilePage.html.twig',
        []));
    }

}
