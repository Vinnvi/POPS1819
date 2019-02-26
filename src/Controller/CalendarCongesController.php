<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Entity\Conge;
use App\Repository\CongeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalendarCongesController extends AbstractController
{
    /**
    * @var Environment
    **/
    private $twig;
    /**
     * @var CongeRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(Environment $twig, CongeRepository $repository, ObjectManager $em)
    {
      $this->twig = $twig;
      $this->repository = $repository;
      $this->em = $em;
    }

    public function index(): Response
    {
      //recuperation des conges du collaborateur
      $mesConges = $this->repository->findByCollaborateurId($this->getUser()->getId());
      return new Response($this->twig->render('pages/calendarConges.html.twig',
        ['mesConges' => $mesConges]));
    }

    /**
     * @Route("/mesCongesAfterDemande", name="modif.demandeConge")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demandeConge() : Response {
      //get current collaborateur
      dump('ahhhh');
      print_r('aaaah');
      $collaborateur = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
      $collaborateur = $collaborateur->findById($this->getUser()->getId());
      if( (isset($_POST['dateDebutConge']))&&(isset($_POST['dateFinConge'])) )
      {
        dump($_POST['dateDebutConge']);
        // $collaborateur[0]->setEmail($_POST['mail']);
      }
      else{
        dump('noooo');
      }
      // $this->getDoctrine()->getEntityManager()->persist($collaborateur[0]);
      // $this->getDoctrine()->getEntityManager()->flush();
      return $this->redirectToRoute('app_calendarConges');
    }
}
