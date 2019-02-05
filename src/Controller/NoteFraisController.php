<?php
namespace App\Controller;

use App\Form\LigneDeFraisFormType;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Entity\NoteDeFrais;
use App\Entity\TypePaiementEnum;
use App\Repository\NoteDeFraisRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\LigneDeFrais;
use App\Entity\LigneDeFraisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoteFraisController extends AbstractController
{
    /**
    * @var Environment
    **/
    private $twig;

    /**
     * @var NoteDeFraisRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(Environment $twig,NoteDeFraisRepository $repository,ObjectManager $em)
    {
      $this->twig = $twig;
      $this->repository = $repository;
      $this->em = $em;
    }

    /**
     * @Route("/mesNotesDeFrais", name="index")
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request): Response
    {
      $currentMonth = date('n');
      $currentYear = date('Y');

      //recuperation des notes de frais du collaborateur trié par date la plus ancienne
      $mesNotesDeFrais = $this->repository->findByCollaborateurId($this->getUser()->getId());


      
      //On vérifie que c'est bien les deux dernières
      if($mesNotesDeFrais == null or $mesNotesDeFrais[0]->getMois() != $currentMonth){
        //on crée une nouvelle note de frais sinon
        $noteDeFrais = new NoteDeFrais($currentMonth,$currentYear,$this->getUser());

        //on la sauvegarde
        $this->getDoctrine()->getEntityManager()->persist($noteDeFrais);
        $this->getDoctrine()->getEntityManager()->flush();

        //on remplace la plus ancienne
        if(count($mesNotesDeFrais) > 1){
          $temp = $mesNotesDeFrais[0];
          $mesNotesDeFrais[0] = $mesNotesDeFrais[1];
          $mesNotesDeFrais[1] = $temp;
        }

      }

      //Recuperation des lignes de frais en fonction des notes
      $lignesDeFraisRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
      $lignesDeFrais = array();
      foreach ($mesNotesDeFrais as $note) {
        array_push($lignesDeFrais,$lignesDeFraisRepository->findByNoteID($note->getId()));
      }

      //recuperation des projets de l'utilisateur
      $projetRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Projet');
      $projectsAvailables = array();
      array_push($projectsAvailables,$projetRepository->findByCollaborateurId($this->getUser()->getId()));

      //Recuperation des categories de paiements
      $typesPaiements = TypePaiementEnum::getAvailableTypes();


      //Partie form
        $maLigneDeFrais = new LigneDeFrais();
        $form = $this->createForm(LigneDeFraisFormType::class,$maLigneDeFrais);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //Put noteDeFrais
            $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
            dump($request->get('moisNote'));
            dump($request->get('anneeNote'));
            $maLigneDeFrais->setNote($noteRepository->findByMonthAndYear($request->get('moisNote'),$request->get('anneeNote'),$this->getUser()->getId())[0]);

            $this->em->persist($maLigneDeFrais);
            $this->em->flush();
            $this->addFlash('success','Ligne de frais ajoutée');
            return $this->redirectToRoute('app_noteFrais');
        }


      return new Response($this->twig->render('pages/noteFrais.html.twig',
        ['noteDeFrais' => $mesNotesDeFrais,
         'mesLignesDeFrais' => $lignesDeFrais,
         'typesPaiements' => $typesPaiements,
         'projectsAvailables' => $projectsAvailables,
         'ligneDeFrais' => $maLigneDeFrais,
         'form' => $form->createView()]));
    }
}
