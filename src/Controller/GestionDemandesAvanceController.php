<?php

namespace App\Controller;


use App\Entity\DemandeAvance;
use App\Entity\NoteDeFrais;
use App\Entity\LigneDeFrais;
use App\Entity\Service;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\NoteDeFraisRepository;

class GestionDemandesAvanceController extends AbstractController
{
    /**
     * @var Environment
     **/
    private $twig;

    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(Environment $twig,ObjectManager $em)
    {
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * @param $note1
     * @param $note2
     * @return bool
     */
    function comparator($note1, $note2){
        return $note1->getLastModif() < $note2->getLastModif();
    }

    /**
     * @Route("/gestionNotesDeFrais", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $demandeAvanceRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\DemandeAvance');

        $demandesEnAttente = $demandeAvanceRepository->findByStatut(DemandeAvance::STATUS[0]);

        $demandesPassees = array_merge($demandeAvanceRepository->findByStatut(DemandeAvance::STATUS[1]),$demandeAvanceRepository->findByStatut(DemandeAvance::STATUS[2]));
        usort($demandesPassees,array($this,"comparator"));
        return $this->render('pages/gestionDemandesAvance.html.twig',
            [
                'demandesEnAttente' => $demandesEnAttente,
                'demandesPassees' => $demandesPassees,
            ]);
    }

    /**
     * @Route("/gestionNotesDeFrais/details/{id}", name="gestionDemandes.details")
     * @param DemandeAvance $demande
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(DemandeAvance $demande){
        return $this->render('pages/gestionDemandesAvanceDetails.html.twig',[
            'demande' => $demande,
        ]);
    }


    /**
     * @Route("/gestionNotesDeFrais/choix/{id}", name="gestionDemandes.choix")
     * @param DemandeAvance $demande
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function choix(DemandeAvance $demande){
        if($_GET['choix'] == 0) {
            $demande->setStatut(DemandeAvance::STATUS[2]);
            $demande->setLastModif(new \DateTime());
        }
        elseif($_GET['choix'] == 1){
            $demande->setStatut(DemandeAvance::STATUS[1]);
            $demande->setLastModif(new \DateTime());
        }
        $this->em->flush();

        return $this->redirectToRoute('app_gestionDemandesAvance');
    }

}