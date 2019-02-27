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
use Symfony\Component\Validator\Constraints\DateTime;

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

      return new Response($this->twig->render('pages/noteFrais.html.twig',
        ['noteDeFrais' => $mesNotesDeFrais,
         'mesLignesDeFrais' => $lignesDeFrais,
         'typesPaiements' => $typesPaiements,
         'projectsAvailables' => $projectsAvailables,
         ]));
    }

    /**
     * @Route("/mesNotesDeFrais/creationOrModification", name="modif.ligne")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function touchLigne() : Response {
        $projetRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Projet');
        $LigneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
        
        if($_POST['isCreation'] == "true"){
          $LignedeFraisModifiee = new LigneDeFrais();
          $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
          $LignedeFraisModifiee->setNote($noteRepository->findByMonthAndYear($_POST['creationMois'],$_POST['creationAnnee'],$this->getUser()->getId())[0]);
          $LignedeFraisModifiee->setAvance(false);
        }else{
          $LignedeFraisModifiee = $LigneRepository->findById($_POST['ligneId'])[0];
        }


        $LignedeFraisModifiee->setIntitule($_POST['ligneIntitule']);
        $LignedeFraisModifiee->setType($_POST['ligneType']);
        $LignedeFraisModifiee->setDate(\DateTime::createFromFormat('Y-m-d', $_POST['ligneDate']));
        $LignedeFraisModifiee->setMission("inutile");
        $LignedeFraisModifiee->setMontant(floatval($_POST['ligneMontant']));
        $LignedeFraisModifiee->setProjet( $projetRepository->findById($_POST['projet'])[0]);

        $ligneId = $_POST['ligneId'];
        if($_POST['idJustificatif'] == "Perdu"){
          //set to "Perdu"
          $listeJustificatifs = $LignedeFraisModifiee->getJustificatif();
          if(empty($listeJustificatifs)){
            array_push($listeJustificatifs, $_POST['idJustificatif']);
            $LignedeFraisModifiee->setJustificatif($listeJustificatifs);
          }
        }
        elseif(isset($_FILES['justificatifUpload'])){
          $errors= array();
          $target_dir = "images/justificatifs/";
          $target_file = $target_dir . basename($_FILES["justificatifUpload"]["name"]);
          $file_size = $_FILES['justificatifUpload']['size'];
          $file_tmp = $_FILES['justificatifUpload']['tmp_name'];
          $file_type = $_FILES['justificatifUpload']['type'];
          $tmp = explode('.',$_FILES['justificatifUpload']['name']);
          $file_ext = strtolower(end($tmp));
            
          $extensions= array("jpeg","jpg","png","pdf");
          if(in_array($file_ext,$extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
          }
          if($file_size > 2097152) {
            $errors[]='File size must be excately 2 MB';
          }
          // picture is valid, we can update it
          if(empty($errors)==true) {
            $res = array_search($target_file, $LignedeFraisModifiee->getJustificatif());
            if($res === false){
              //move file in our folder
              move_uploaded_file($file_tmp,$target_file);
              //update databse
              $listeJustificatifs = $LignedeFraisModifiee->getJustificatif();
              array_push($listeJustificatifs, $target_file);
              //Remove "Perdu"
              $res = array_search("Perdu", $LignedeFraisModifiee->getJustificatif());
              if($res !== false){
                unset($listeJustificatifs[array_search("Perdu", $listeJustificatifs)]);
              }
              $LignedeFraisModifiee->setJustificatif($listeJustificatifs);
            }
          }else{
            print_r($errors);
          }
        }

        $this->getDoctrine()->getEntityManager()->persist($LignedeFraisModifiee);
        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirectToRoute('app_noteFrais');
    }

    /**
     * @Route("/mesNotesDeFrais/suppressionJustificatif", name="suppresion.justificatif")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeJustificatif() : Response {
      $LigneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
      $ID_PATH = explode(',', $_POST['idLigneASuppJustificatif']);
      $LignedeFraisModifiee = $LigneRepository->findById($ID_PATH[0])[0];
      $listeJustificatifs = $LignedeFraisModifiee->getJustificatif();
      unset($listeJustificatifs[array_search($ID_PATH[1], $listeJustificatifs)]);
      unlink($ID_PATH[1]);
      $LignedeFraisModifiee->setJustificatif($listeJustificatifs);
      $this->getDoctrine()->getEntityManager()->persist($LignedeFraisModifiee);
      $this->getDoctrine()->getEntityManager()->flush();
      return $this->redirectToRoute('app_noteFrais');
    } 
}
