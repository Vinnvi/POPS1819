<?php

namespace App\Controller\gestionConges;


use App\Entity\Conge;
use App\Entity\Notification;
use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\NoteDeFraisRepository;

class GestionCongesRHController extends AbstractController
{
    /**
     * @var Environment
     **/
    private $twig;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var CongeRepository
     */
    private $repository;


    public function __construct(Environment $twig,CongeRepository $repository, ObjectManager $em)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */

    public function index(): Response
    {

        $congesRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Conge');
        $demandesEnAttente = $congesRepository->findByStatut(Conge::STATUS[2]);
        $congesPassees = array_merge($congesRepository->findByStatut(Conge::STATUS[4]),$congesRepository->findByStatut(Conge::STATUS[5]));
        return $this->render('pages/gestionConges/gestionCongesRH.html.twig',
            [
                'demandesEnAttente' => $demandesEnAttente,
                'congesPassees' => $congesPassees,
                'status' => Conge::STATUS,
            ]);
    }

    /**
     * @Route("/gestionDemandesDeCongesRH/details/{id}", name="demande.details")
     * @param Conge $conge
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(Conge $conge){
        $congesAttenteService = $this->repository->findByServiceAndStatut($conge->getCollabo()->getService()->getId(),Conge::STATUS[2]);
        $congesAttenteGlobal = $this->repository->findByStatut(Conge::STATUS[2]);
        $congesValideesGlobale = $this->repository->findByDate($conge->date_debut,$conge->date_fin);
        $congesValideesService = $this->repository->findByDateAndService($conge->date_debut,$conge->date_fin, $conge->getService()->getId());
        return $this->render('pages/gestionConges/gestionCongesRHDetails.html.twig',
            [
                'demande' => $conge,
                'congesAttenteService' => $congesAttenteService,
                'congesAttenteGlobal' => $congesAttenteGlobal,
                'congesValideesGlobale' => $congesValideesGlobale,
                'congesValideesService' => $congesValideesService,
            ]);
    }


    /**
     * @Route("/gestionDemandesDeCongesRH/validation", name="valider.conges")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validationConges() : Response{
        if(isset($_POST['decision']) and isset($_POST['demande'])){
            $conge = $this->repository->findOneById($_POST['demande']);

            //Ajout de la notification
            $notification = new Notification();
            $notification->setCollaborateur($conge->getCollabo());
            $notification->setCategorie(Notification::CATEGORIE[1]);
            $notification->setDescription("(RH)".$conge->getType()." du ".$conge->getDate_debut()->format("d/m/Y")." au ".$conge->getDate_fin()->format("d/m/Y"));
            $notification->setDate(new \DateTime());
            $notification->setPersonnel(true);
            $notification->setCumulable(false);
            $notification->setVu(false);


            if($_POST['decision'] == "valider"){
                $conge->setStatut(Conge::STATUS[4]);
                $notification->setStatut(Notification::STATUT[0]);
                $notification->setDescription("ACCEPTATION ".$notification->getDescription());
            }
            else{
                $conge->setStatut(Conge::STATUS[5]);
                if(isset($_POST['motif'])){
                    $conge->setCommentaire($_POST['motif']);
                }
                else{
                    $conge->setCommentaire('Aucun motif');
                }
                $notification->setStatut(Notification::STATUT[3]);
                $notification->setDescription("REFUS ".$notification->getDescription()." Motif : ".$conge->getCommentaire());

            }
            $conge->getCollabo()->addNotification($notification);
            $this->em->persist($notification);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_gestionDemandesDeCongesRH');
    }

}