<?php

namespace App\Controller;


use App\Entity\Conge;
use App\Entity\Notification;
use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\NoteDeFraisRepository;

class GestionCongesChefController extends AbstractController
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
        $congesEnAttente = $congesRepository->findByServiceAndStatut($this->getUser()->getService()->getId(),Conge::STATUS[1]);
        $congesPassees = array_merge($congesRepository->findByServiceAndStatut($this->getUser()->getService()->getId(),Conge::STATUS[2]),$congesRepository->findByServiceAndStatut($this->getUser()->getService()->getId(),Conge::STATUS[3]));


        return $this->render('pages/gestionCongesChef.html.twig',
            [
                'congesEnAttente' => $congesEnAttente,
                'congesPassees' =>$congesPassees,
            ]);
    }


    /**
     * @Route("/gestionDemandesDeCongesChef/validationConges", name="valider.conges.chef")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validationConges() : Response{
        if(isset($_POST['decision']) and isset($_POST['demande'])){
            $conge = $this->repository->findOneById($_POST['demande']);

            //Ajout de la notification
            $notification = new Notification();
            $notification->setCollaborateur($conge->getCollabo());
            $notification->setCategorie(Notification::CATEGORIE[1]);
            $notification->setDescription("(Chef)".$conge->getType()." du ".$conge->getDate_debut()->format("d/m/Y")." au ".$conge->getDate_fin()->format("d/m/Y"));
            $notification->setDate(new \DateTime());
            $notification->setPersonnel(true);
            $notification->setCumulable(false);
            $notification->setVu(false);

            if($_POST['decision'] == "valider"){
                $conge->setStatut(Conge::STATUS[2]);
                $notification->setStatut(Notification::STATUT[0]);
            }
            else{
                $conge->setStatut(Conge::STATUS[3]);
                if($conge->getType() == "RTT"){
                    $conge->getCollabo()->setConge($conge->getCollabo()->getRtt()+$conge->getDuree());
                }
                else{
                    $conge->getCollabo()->setConge($conge->getCollabo()->getConge()+$conge->getDuree());
                }
                if(isset($_POST['motif'])){
                    $conge->setCommentaire($_POST['motif']);
                }
                else{
                    $conge->setCommentaire('Pas de motif');
                }
                $notification->setStatut(Notification::STATUT[3]);
                $notification->setDescription($notification->getDescription()." Motif : ".$conge->getCommentaire());
            }
            $conge->getCollabo()->addNotification($notification);
            $this->em->persist($notification);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_gestionDemandesDeCongesChef');
    }

}