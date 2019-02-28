<?php
namespace App\Controller;

use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class gestionMissionsController extends AbstractController
{
    /**
    * @var Environment
    **/
    private $twig;

    /**
     * @var ProjetRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(Environment $twig,ProjetRepository $repository,ObjectManager $em)
    {
        $this->twig = $twig;
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): Response
    {
        $toutesLesMissions = $this->repository->findByService($this->getUser()->getService());


        return new Response($this->twig->render('pages/gestionMissions/gestionMissionsHome.html.twig',[
            'missions' => $toutesLesMissions,
            ]));
    }

    /**
     * @Route("/gestionMissions/validation", name="ajout.Mission")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajoutMission(){

        if(!isset($_POST['id'])){
            $projet = new Projet();
            if(isset($_POST['nom']) and isset($_POST['dateDebut']) and isset($_POST['dateFin'])){
                $projet->setNom($_POST['nom']);
                $projet->setDateDebut(\DateTime::createFromFormat('Y-m-d', $_POST['dateDebut']));
                $projet->setDateFin(\DateTime::createFromFormat('Y-m-d', $_POST['dateFin']));
                $projet->setService($this->getUser()->getService());
            }
            else{
                $this->redirectToRoute('app_gestionMissions');
            }
            if(isset($_POST['description'])){
                $projet->setDescription($_POST['description']);
            }

            $this->getDoctrine()->getEntityManager()->persist($projet);
            $this->getDoctrine()->getEntityManager()->flush();
        }

        else{
            $projet = $this->repository->findOneById($_POST['id']);
            if($projet != null){
                $projet->setNom($_POST['nom']);
                $projet->setDateDebut(\DateTime::createFromFormat('Y-m-d', $_POST['dateDebut']));
                $projet->setDateFin(\DateTime::createFromFormat('Y-m-d', $_POST['dateFin']));
                $projet->setDescription($_POST['description']);
                $this->getDoctrine()->getEntityManager()->persist($projet);
                $this->getDoctrine()->getEntityManager()->flush();
            }


        }


        return $this->redirectToRoute('app_gestionMissions');
    }

}
