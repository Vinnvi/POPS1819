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

class GestionNotesDeFraisController extends AbstractController
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
     * @param $note1
     * @param $note2
     * @return bool
     */
    function comparator($note1, $note2){
        return $note1->getLastModif() < $note2->getLastModif();
    }

    /**
     * @Route("/gestionNotesDeFrais", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $notesRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
        $notesDeFraisEnAttente = array_merge($notesRepository->findByStatus(NoteDeFrais::STATUS[2]), $notesRepository->findByStatus(NoteDeFrais::STATUS[9]));
        $notesDeFraisPasses = array_merge($notesRepository->findByStatus(NoteDeFrais::STATUS[5]), $notesRepository->findByStatus(NoteDeFrais::STATUS[7]));

        return $this->render('pages/gestionNotesFrais.html.twig',
            [
                'notesDeFraisEnAttente' => $notesDeFraisEnAttente,
                'notesValideesRefusees' => $notesDeFraisPasses,
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
        if($noteDeFrais->getStatut() == NoteDeFrais::STATUS[9]){
            $lignesDeFrais =  $LigneRepository->findByNoteIDAndAvance($noteDeFrais->getId());
        }else{
            $lignesDeFrais =  $LigneRepository->findByNoteID($noteDeFrais->getId());
        }

        return $this->render('pages/gestionNotesFraisDetails.html.twig',[
            'noteDeFrais' => $noteDeFrais,
            'ligneDeFrais' => $lignesDeFrais,
            'statusNotes' => NoteDeFrais::STATUS,
        ]);
    }

    /**
     * @Route("/gestionNotesDeFraisChef/Validation", name="validationNotes")
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

        return $this->redirectToRoute('app_gestionNotesFrais');
    }


    /**
     * @Route("/gestionNotesDeFrais/validationDetails/", name="validation.lignes.details")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function validationDemandesLigne()
    {
        $LigneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
        $ok = true;
        //Validation des lignes
        foreach ($_POST as $key => $value){
            $LigneDeFrais = $LigneRepository->findOneByID($key);
            if($LigneDeFrais != null){
                if($value == "refus"){
                    $LigneDeFrais->setStatutValidation(LigneDeFrais::STATUS[7]);
                    $ok = false;
                }
                else{
                    if($LigneDeFrais->getNote()->getStatut() == LigneDeFrais::STATUS[2]){
                        $LigneDeFrais->setStatutValidation(LigneDeFrais::STATUS[5]);
                    }
                    else{
                        $LigneDeFrais->setStatutValidation(LigneDeFrais::STATUS[6]);
                    }
                }
                $LigneDeFrais->setLastModif(new \DateTime());
            }
        }
        //Validation ou non de la note
        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
        if(isset($_POST['id'])){
            $notesDeFrais = $noteRepository->findOneByID($_POST['id']);
            if($ok){
                if($notesDeFrais->getStatut() == NoteDeFrais::STATUS[2]){
                    $notesDeFrais->setStatut(NoteDeFrais::STATUS[5]);
                }
                else{
                    $notesDeFrais->setStatut(NoteDeFrais::STATUS[0]);
                }
            }
            else{
                $notesDeFrais->setStatut(NoteDeFrais::STATUS[7]);
            }
            $notesDeFrais->setLastModif(new \DateTime());
        }

        $this->em->flush();


        return $this->redirectToRoute('app_gestionNotesDeFrais');
    }




}