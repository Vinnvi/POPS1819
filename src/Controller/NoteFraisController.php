<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Entity\NoteDeFrais;
use App\Entity\TypePaiementEnum;
use App\Repository\NoteDeFraisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\LigneDeFrais;
use App\Entity\LigneDeFraisRepository;
use App\Entity\Projet;
use Doctrine\ORM\EntityRepository;

class NoteFraisController extends Controller
{
    /**
    * @var Environment
    **/
    private $twig;

    /**
     * @var NoteDeFraisRepository
     */
    private $repository;


    public function __construct(Environment $twig,NoteDeFraisRepository $repository)
    {
      $this->twig = $twig;
      $this->repository = $repository;
    }

    public function index(): Response
    {
      $currentMonth = date('n');
      $currentYear = date('Y');

      //recuperation des notes de frais du collaborateur trié par date la plus ancienne
      $mesNotesDeFrais = $this->repository->findByCollaborateurId($this->getUser()->getId());

      //On vérifie que c'est bien les deux dernières
      if($mesNotesDeFrais[1]->getMois() != $currentMonth){
        //on crée une nouvelle note de frais sinon
        $noteDeFrais = new NoteDeFrais();
        $noteDeFrais->setMois($currentMonth);
        $noteDeFrais->setAnnee($currentYear);
        $noteDeFrais->setMontant(0);
        $noteDeFrais->setStatut("En cours");
        $noteDeFrais->setCollabo($this->getUser());

        //on la sauvegarde
        $this->getDoctrine()->getEntityManager()->persist($noteDeFrais);
        $this->getDoctrine()->getEntityManager()->flush();

        //on remplace la plus ancienne
        $mesNotesDeFrais[0] = $mesNotesDeFrais[1];
        $mesNotesDeFrais[1] = $noteDeFrais;
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



      return new Response($this->twig->render('pages/noteFrais.html.twig',
        ['noteDeFrais' => $mesNotesDeFrais,
         'lignesDeFrais' => $lignesDeFrais,
         'typesPaiements' => $typesPaiements,
         'projectsAvailables' => $projectsAvailables]));
    }

    /**
     * @Route("/NoteDeFrais", name="touchLigne")
     */
    public function touchLigne() : Response {
      if(!isset($_POST['ligneId'])){ #Nouvelle Ligne
        //prepare repository for sql requests
        $projetRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Projet');
        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');

        //create new tuple and complete it
        $ligneDeFrais = new LigneDeFrais();
        $ligneDeFrais->setIntitule($_POST['typePaiement']);
        $ligneDeFrais->setMission($_POST['mission']);
        $ligneDeFrais->setMontant(floatval($_POST['montant']));
        $ligneDeFrais->setProjet( $projetRepository->findById($_POST['projet'])[0]);
        $ligneDeFrais->setNote($noteRepository->findByMonthAndYear($_POST['moisNote'],$_POST['anneeNote'])[0]);

        $this->getDoctrine()->getEntityManager()->persist($ligneDeFrais);
        $this->getDoctrine()->getEntityManager()->flush();
      }
      else{ #Modif ligne
        $projetRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Projet');
        $LigneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');

        $LignedeFraisModifiee = $LigneRepository->findById($_POST['ligneId'])[0];
        dump($LignedeFraisModifiee);
        $LignedeFraisModifiee->setIntitule($_POST['ligneIntitule']);
        $LignedeFraisModifiee->setMission($_POST['ligneMission']);
        $LignedeFraisModifiee->setMontant(floatval($_POST['ligneMontant']));
        $LignedeFraisModifiee->setProjet( $projetRepository->findById($_POST['projet'])[0]);
        $this->getDoctrine()->getEntityManager()->persist($LignedeFraisModifiee);
        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirectToRoute('app_noteFrais');
      }


      return $this->redirectToRoute('app_noteFrais');

    }
}
