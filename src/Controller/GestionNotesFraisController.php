<?php

namespace App\Controller;


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

class GestionNotesFraisController extends AbstractController
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


    public function __construct(Environment $twig,ObjectManager $em)
    {
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * @Route("/gestionNotesDeFrais", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {

        $notesRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
        $notesDeFraisEnAttente = $notesRepository->findByStatus(NoteDeFrais::STATUS[2]);
        $notesDeFraisPasses = array();
        $notesDeFraisPasses = array_merge($notesDeFraisPasses,$notesRepository->findByStatus(NoteDeFrais::STATUS[5]));
        $notesDeFraisPasses = array_merge($notesDeFraisPasses,$notesRepository->findByStatus(NoteDeFrais::STATUS[7]));

        dump($notesDeFraisPasses);
        return $this->render('pages/gestionNotesFrais.html.twig',
            [
                'notesDeFraisEnAttente' => $notesDeFraisEnAttente,
                'notesDeFraisPassees' => $notesDeFraisPasses,
            ]);
    }

    /**
     * @Route("/gestionNotesDeFrais/details/{id}", name="gestionNotes.details")
     * @param NoteDeFrais $notesDeFrais
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(NoteDeFrais $noteDeFrais)
    {
        //Récupération des lignes de frais relatives à la note
        $LigneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
        $lignesDeFrais =  $LigneRepository->findByNoteId($noteDeFrais->getId());
        return $this->render('pages/gestionNotesFraisDetails.html.twig',[
            'noteDeFrais' => $noteDeFrais,
            'ligneDeFrais' => $lignesDeFrais,
        ]);
    }

    /**
     * @Route("/gestionNotesDeFrais/validationDetails", name="validation.lignes.details.compta")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function validationDemandesLigne()
    {
        $LigneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
        $ok = true;
        //Validation des lignes
        foreach ($_POST as $key => $value){
            dump($key);
            dump($value);
            $LigneDeFrais = $LigneRepository->findOneByID($key);
            if($LigneDeFrais != null){
                if($value == "refus"){
                    $LigneDeFrais->setStatutValidation(LigneDeFrais::STATUS[7]);
                    $ok = false;
                }
                else{
                    $LigneDeFrais->setStatutValidation(LigneDeFrais::STATUS[5]);
                }
                $LigneDeFrais->setLastModif(new \DateTime());
            }
        }
        //Validation ou non de la note
        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
        if(isset($_POST['id'])){
            $notesDeFrais = $noteRepository->findOneByID($_POST['id']);
            if($ok){
                $notesDeFrais->setStatut(NoteDeFrais::STATUS[5]);
            }
            else{
                $notesDeFrais->setStatut(NoteDeFrais::STATUS[7]);
            }
            $notesDeFrais->setLastModif(new \DateTime());
        }

        $this->em->flush();


        return $this->redirectToRoute('app_gestionNotesFrais');

    }

    /**
     * @Route("/gestionNotesDeFrais/ValidationGlobale", name="validationNotes.compta")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validationNotes(){
        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
        $LigneDeFraisRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');

        //Pour chaque id on update le statut de la note
        foreach ($_POST['choix'] as $value){

            //La note est validée ...
            $noteRepository->findOneByID($value)->setStatut(NoteDeFrais::STATUS[5]);
            $noteRepository->findOneByID($value)->setLastModif(new \DateTime());

            //...ainsi que l'ensemble de ses lignes
            $lignes = $LigneDeFraisRepository->findByNoteID($value);
            foreach ($lignes as $ligne){
                $ligne->setStatutValidation(LigneDeFrais::STATUS[5]);
                $ligne->setLastModif(new \DateTime());
            }

        }
        $this->em->flush();

        return $this->redirectToRoute('app_gestionNotesFrais');
    }


}