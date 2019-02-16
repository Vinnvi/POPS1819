<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class MissionsController extends AbstractController
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

      // CODE TOPBAR
      $listeCollaborateurs = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Collaborateur')->findAll();
      $listeCollaborateursPrenomNom = array();
      foreach($listeCollaborateurs as $collaborateur){
        $listeCollaborateursPrenomNom[$collaborateur->getNom() . ' ' . $collaborateur->getPrenom()] = null;
      }
      // FIN CODE TOPBAR

    return new Response($this->twig->render('pages/home.html.twig',[
                  'listeCollaborateurs' => $listeCollaborateursPrenomNom,
            ]));
    }
}
