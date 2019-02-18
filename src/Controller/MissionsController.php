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

      // Variable pour calculer les montants
      $montants = array();

      // CODE MISSION
      $missions = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Projet')->findByCollaborateurId($this->getUser()->getId());
      foreach ($missions as $mission) {
        $montants[$mission->getId()] = 0;
        $mission->getCollabos()->initialize();
        foreach ($mission->getCollabos() as $collabo) {
          //$collabo->getService()->initialize();
        }
      }
      // FIN

      // CODE MONTANTS LIGNES DE FRAIS
      $lignesDeFrais = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais')->findByCollaborateurID($this->getUser()->getId());
      foreach ($lignesDeFrais as $ligne) {
        $montants[$ligne->getProjet()->getId()] += $ligne->getMontant();
      }
      // FIN

    return new Response($this->twig->render('pages/missions.html.twig',[
                  'listeCollaborateurs' => $listeCollaborateursPrenomNom,
                  'missions' => $missions,
                  'montants' => $montants,
            ]));
    }
}
