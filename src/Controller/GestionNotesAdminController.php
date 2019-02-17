<?php

namespace App\Controller;


use App\Entity\NoteDeFrais;
use App\Entity\Service;
use App\Entity\LigneDeFrais;
use App\Entity\Collaborateur;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\NoteDeFraisRepository;

class GestionNotesAdminController extends AbstractController
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
     * @Route("/gestionNotesDeFraisChef", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $serviceRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Service');
        //On recupere le(s) service(s) du chef
        $servicesIds = $serviceRepository->findByChefId($this->getUser()->getId());


        $collaborateurRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Collaborateur');


        //On recupere la liste des collaborateurs qu'on gere
        $collaborateurs =  array();
        foreach ($servicesIds as $servicesId){

            $collaborateurs = array_merge($collaborateurs, $collaborateurRepository->findByService($servicesId));
        }

        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');


        $notesEnAttente = array();
        //On prend toutes les notes de frais en attente de validation de ces employes
        foreach ($collaborateurs as $collaborateur){
            $notesEnAttente = array_merge($notesEnAttente, $noteRepository->findByStatusAndCollabo(NoteDeFrais::STATUS[1],$collaborateur->getId()));
        }

        return $this->render('pages/gestionNotesFraisChef.html.twig',
            [
                'notesDeFraisEnAttente' => $notesEnAttente,
            ]);
    }

    /**
     * @Route("/gestionNotesDeFraisChefValidation", name="validationNotes")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validationNotes(){
        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
        $LigneDeFraisRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');

        //Pour chaque id on update le statut de la note
        foreach ($_POST['choix'] as $value){
            //La note est validée ...
            $noteRepository->findOneByID($value)->setStatut(NoteDeFrais::STATUS[2]);
            $noteRepository->findOneByID($value)->setLastModif(new \DateTime());

            //...ainsi que l'ensemble de ses lignes
            $lignes = $LigneDeFraisRepository->findByNoteID($value);
            foreach ($lignes as $ligne){
                $ligne->setStatutValidation(LigneDeFrais::STATUS[2]);
                $ligne->setLastModif(new \DateTime());
            }

        }
        $this->em->flush();

        return $this->redirectToRoute('app_gestionNotesFraisAdmin');
    }




}