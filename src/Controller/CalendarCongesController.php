<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalendarCongesController extends AbstractController
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
      return new Response($this->twig->render('pages/calendarConges.html.twig',
        []));
    }

    /**
     * @Route("/mesCongesAfterDemande", name="modif.demandeConge")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demandeConge() : Response {
      //get current collaborateur
      $collaborateur = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
      $collaborateur = $collaborateur->findById($this->getUser()->getId());
      if(isset($_POST['dateDebutConge']))
      {
        $collaborateur[0]->setEmail($_POST['mail']);
        $this->getDoctrine()->getEntityManager()->persist($collaborateur[0]);
        $this->getDoctrine()->getEntityManager()->flush();
      }
      else{
        dump('noooo');
      }
      return $this->redirectToRoute('app_calendarConges');
    }
}
