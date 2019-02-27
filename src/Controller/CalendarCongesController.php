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
      $congesService = $this->repository->findByServiceId($this->getUser()->getService());
      return new Response($this->twig->render('pages/calendarConges.html.twig',
        ['mesConges' => $mesConges,
        'congesService' => $congesService]));
    }

    /**
     * @Route("/mesCongesAfterDemande", name="modif.demandeConge")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demandeConge() : Response {
      //get current collaborateur
      dump('ahhhh');
      $collaborateurRepository = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
      $collaborateurId = $this->getUser()->getId();
      $congeRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Conge');
      $newConge = new Conge();
      // if(isset($_POST['timeCongeDebut']))
      // {
        dump('no');
        $newConge->setId_collabo($collaborateurId);
        $newConge->setId_service(3);
        $newConge->setType('Conge');
        // $newConge->setDate_debut($_POST['dateDebutConge']);
        $newConge->setDate_debut(new \DateTime('2000-01-01'));
        $newConge->setDebut_matin(true);
        // $newConge->setDate_fin($_POST['dateFinConge']);
        $newConge->setDate_fin(new \DateTime('2000-01-01'));
        $newConge->setFin_matin(true);
        $newConge->setStatut('En Attente');
        $newConge->setDuree(16);
          // $collaborateur[0]->setEmail($_POST['mail']);

        $this->getDoctrine()->getEntityManager()->persist($newConge);
        $this->getDoctrine()->getEntityManager()->flush();
      // }
      // else{
        dump('ahhhh');
      // }
      return $this->redirectToRoute('app_calendarConges');
    }
}
