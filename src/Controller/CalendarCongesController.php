<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Entity\Conge;
use App\Repository\CongeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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

    public function index(Request $request): Response
    {
      //recuperation des conges du collaborateur
      $mesConges = $this->repository->findByCollaborateurId($this->getUser());
      $congesService = $this->repository->findByServiceId($this->getUser()->getService()->getId());

      return new Response($this->twig->render('pages/calendarConges.html.twig',
        ['mesConges' => $mesConges,
        'congesService' => $congesService]));
    }


    /**
     * @Route("/mesCongesAfterDemande", name="modif.demandeConge")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demandeConge() : Response {
      // $collaborateur = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
      // $collaborateur = $collaborateur->findById($this->getUser()->getId());
      // $collaborateurRepository = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
      $collaborateur = $this->getUser();
      // $congeRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Conge');
      $newConge = new Conge();
      if(isset($_POST['timeCongeDebut']))
      {
        $newConge->setCollabo($collaborateur);
        $newConge->setService($this->getUser()->getService());
        $newConge->setType($_POST['typeConge']);
        $newConge->setDate_debut($_POST['startDate']);
        if($_POST['timeCongeDebut'] == "true"){$newConge->setDebut_matin(true);}
        else {$newConge->setDebut_matin(false);}
        $newConge->setDate_fin($_POST['endDate']);
        if($_POST['timeCongeFin'] == "true"){$newConge->setFin_matin(true);}
        else {$newConge->setFin_matin(false);}
        $newConge->setStatut('En attente chef');
        $newConge->setDuree((int)$_POST['inputDuree']);

        if($_POST['typeConge']=="Conge"){
          $collaborateur->setConge($collaborateur->getConge()-(int)$_POST['inputDuree']);
        }
        else{
          $collaborateur->setRtt($collaborateur->getRtt()-(int)$_POST['inputDuree']);
        }

        $this->getDoctrine()->getEntityManager()->persist($newConge);
        $this->getDoctrine()->getEntityManager()->persist($collaborateur);
        $this->getDoctrine()->getEntityManager()->flush();
      }
      // }
      // else{
        dump('ahhhh');
      // }
      return $this->redirectToRoute('app_calendarConges');
    }
}
