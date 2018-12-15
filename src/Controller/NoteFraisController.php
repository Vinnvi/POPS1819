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

      $mesNotesDeFrais = $this->repository->findByCollaborateurId($this->getUser()->getId());

      $lignesDeFraisRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
      $lignesDeFrais = array();
      foreach ($mesNotesDeFrais as $note) {
        array_push($lignesDeFrais,$lignesDeFraisRepository->findByNoteID($note->getId()));
      }

      //Recuperation des categories de paiements
      $typesPaiements = TypePaiementEnum::getAvailableTypes();

      # !!!!!DEV MODE ONLY !!!!!! JUST TO SEE QUERIES RESULTS
      #dump($this->repository->findByCollaborateurId($this->getUser()->getId()));
      dump($lignesDeFrais);

      return new Response($this->twig->render('pages/noteFrais.html.twig',
        ['noteDeFrais' => $mesNotesDeFrais,
         'lignesDeFrais' => $lignesDeFrais,
         'typesPaiements' => $typesPaiements]));
    }
}
