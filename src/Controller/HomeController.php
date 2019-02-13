<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeController extends Controller
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

      // CODE NOTIFICATION
      $notification = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Notification')->findBy(
        array('collaborateur' => $this->getUser()->getId()),    // search criteria
        array('date' => 'DESC') // order criteria
      );
      // FIN

      // CODE MISSION
      $missions = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Projet')->findLast3EmergencyProjectByCollaboId($this->getUser()->getId());
      // FIN

      // CODE NOTE DE FRAIS
      $notes_de_frais = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais')->findByCollaborateurId($this->getUser()->getId());
      // FIN

      // CODE DATE ACTUELLE
      $currentDate = array();
      array_push($currentDate,date("j"));
      array_push($currentDate,date("n"));
      array_push($currentDate,date("Y"));
      // FIN DATE ACTUELLE

      // CODE SERVICE
      $service = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Service')->findBy(
        array('id' => $this->getUser()->getService()->getId())
      );
      $chefTab = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Service')->findBy(
        array('id' => $this->getUser()->getService()->getId())
      );
      // FIN

    return new Response($this->twig->render('pages/home.html.twig',[
                  'listeCollaborateurs' => $listeCollaborateursPrenomNom,
                  'currentDate' => $currentDate,
                  'notifications' => $notification,
                  'notes_de_frais' => $notes_de_frais,
                  'missions' => $missions, 
            ]));
    }

    /*private function sortFunction( $a, $b ) {
      return strtotime($a->getDate()) - strtotime($b->getDate());
    }*/
}
