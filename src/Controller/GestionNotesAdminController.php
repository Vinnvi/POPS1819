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

class GestionNotesAdminController extends AbstractController
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
     * @Route("/gestionNotesDeFraisChef", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $serviceRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Service');
        //On recupere le(s) service(s) du chef
        $servicesIds = $serviceRepository->findByChefId($this->getUser()->getId());


        $collaborateurRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Collaborateur');

        //On recupere la liste des collaborateurs qu'on gere
        $collaborateurs =  array();
        foreach ($servicesIds as $servicesId){
            $collaborateurs = array_merge($collaborateurs, $collaborateurRepository->findByService($servicesId));
        }

        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');


        $notesEnAttente = array();
        $notesValideesRefusees = array();

        //On prend toutes les notes de frais en attente de validation de ces employes
        foreach ($collaborateurs as $collaborateur){
            $notesEnAttente = array_merge($notesEnAttente, $noteRepository->findByStatusAndCollabo(NoteDeFrais::STATUS[1],$collaborateur->getId()));
            $notesValideesRefusees = array_merge($notesValideesRefusees, $noteRepository->findByStatusAndCollabo(NoteDeFrais::STATUS[2],$collaborateur->getId()));
            $notesValideesRefusees = array_merge($notesValideesRefusees, $noteRepository->findByStatusAndCollabo(NoteDeFrais::STATUS[3],$collaborateur->getId()));
        }
        usort($notesValideesRefusees,array($this,"comparator"));

        return $this->render('pages/gestionNotesFraisChef.html.twig',
            [
                'notesDeFraisEnAttente' => $notesEnAttente,
                'notesValideesRefusees' => $notesValideesRefusees,
            ]);
    }

    /**
     * @Route("/gestionNotesDeFraisChef/details/{id}", name="gestionNotesChef.details")
     * @param NoteDeFrais $notesDeFrais
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(NoteDeFrais $noteDeFrais)
    {
        //Récupération des lignes de frais relatives à la note
        $LigneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
        $lignesDeFrais =  $LigneRepository->findByNoteId($noteDeFrais->getId());

        return $this->render('pages/gestionNotesFraisChefDetails.html.twig',[
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

        return $this->redirectToRoute('app_gestionNotesFraisAdmin');
    }


    /**
     * @Route("/gestionNotesDeFrais/validationDetails/", name="validation.lignes.chef.details")
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
                    $LigneDeFrais->setStatutValidation(LigneDeFrais::STATUS[3]);
                    $ok = false;
                }
                else{
                    $LigneDeFrais->setStatutValidation(LigneDeFrais::STATUS[2]);
                }
                $LigneDeFrais->setLastModif(new \DateTime());
            }
        }
        //Validation ou non de la note
        $noteRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\NoteDeFrais');
        if(isset($_POST['id'])){
            $notesDeFrais = $noteRepository->findOneByID($_POST['id']);
            if($ok){
                $notesDeFrais->setStatut(NoteDeFrais::STATUS[2]);
            }
            else{
                $notesDeFrais->setStatut(NoteDeFrais::STATUS[3]);
            }
            $notesDeFrais->setLastModif(new \DateTime());
        }

        $this->em->flush();


        return $this->redirectToRoute('app_gestionNotesFraisAdmin');
    }




}