<?php

namespace App\Controller;


use App\Entity\NoteDeFrais;
use App\Entity\LigneDeFrais;
use App\Entity\Service;
use App\Entity\Conge;
use App\Repository\CongeRepository;
use PhpParser\Node\Expr\Array_;
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
     * @Route("/gestionNotesDeFrais", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {

        $congesRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Conge');
        $congesEnAttente = $congesRepository->findByServiceAndStatut($this->getUser()->getService()->getId(),Conge::STATUS[1]);
        dump($congesEnAttente);

        return $this->render('pages/gestionCongesChef.html.twig',
            [
                'congesEnAttente' => $congesEnAttente
            ]);
    }


    /**
     * @Route("/gestionNotesDeFrais", name="valider.conges")
     */
    public function validationConges(){
        if(isset($_POST['decision']) and isset($_POST['demande'])){
            $conge = $this->repository->findOneById($_POST['demande']);
            if($_POST['decision'] == "valider"){
                $conge->setStatut(Conge::STATUS[2]);
            }
            else{
                $conge->setStatut(Conge::STATUS[2]);
            }
            $this->em->flush();
        }

        $this->redirectToRoute('app_gestionDemandesDeCongesChef');
    }

}