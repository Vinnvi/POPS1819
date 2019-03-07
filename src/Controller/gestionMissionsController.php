<?php
namespace App\Controller;

use App\Entity\LigneDeFrais;
use App\Entity\NoteDeFrais;
use App\Entity\Notification;
use App\Entity\Projet;
use App\Repository\ProjetRepository;
use App\Repository\LigneDeFraisRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
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
        $dateActuelle = new \DateTime();


        //Recuperation de tous les collaborateurs
        $collaboRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Collaborateur');
        $collaborateurs = $collaboRepository->findAll();

        return new Response($this->twig->render('pages/gestionMissions/gestionMissionsHome.html.twig',[
            'missions' => $toutesLesMissions,
            'collaborateurs' => $collaborateurs,
            'dateActuelle' => $dateActuelle,
            ]));
    }

    /**
     * @Route("/gestionMissions/validation", name="ajout.Mission")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajoutMission(){

        if(!isset($_POST['id']) or $_POST['id'] == null){
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

    /**
     * @Route("/gestionMissions/details/collabos/{id}", name="gestion.mission.collabos")
     * @param Projet $projet
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionMissionCollabos(Projet $projet){
        $collaboRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Collaborateur');
        
        $projet->getCollabos()->initialize();
        $collabos = $collaboRepository->findAll();
        $collabosDuProjet = $projet->getCollabos();
        $collabosPasDuProjet = array();
        foreach ($collabos as $collabo){
            if( !($collabosDuProjet->contains($collabo)) ){
                array_push($collabosPasDuProjet,$collabo);
            }
        }    

        return new Response($this->twig->render('pages/gestionMissions/gestionMissionsDetailsCollabos.html.twig',[
            'mission' => $projet,
            'collabosProjet' => $collabosDuProjet,
            'collabosPasProjet' => $collabosPasDuProjet,
        ]));
    }

    /**
     * @Route("/gestionMissions/details/lignes/{id}", name="gestion.mission.lignes")
     * @param Projet $projet
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionMissionLignes(Projet $projet){
        $projet->getCollabos()->initialize();

        $ligneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
        $lignesATraiter = $ligneRepository->findLignesChef($projet->getId());        

        return new Response($this->twig->render('pages/gestionMissions/gestionMissionsDetailsLignes.html.twig',[
            'mission' => $projet,
            'lignesATraiter' => $lignesATraiter,
        ]));
    }

    /**
     * @Route("/gestionMissions/ajoutCollabos", name="ajout.collabos")
     */
    public function ajoutCollabos(){
        $projet = $this->repository->findOneById($_POST['projet']);
        if(isset($_POST['choix'])){

            $collaboRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Collaborateur');

            foreach ($_POST['choix'] as $idCollabo){
                $collabo = $collaboRepository->findOneById($idCollabo);
                if($collabo != null){
                    $collabo->addProjet($projet);
                    $projet->addCollabo($collabo);

                    //Notification au collabo
                    $notification = new Notification();
                    $notification->setCollaborateur($collabo);
                    $notification->setStatut(Notification::STATUT[1]);
                    $notification->setCategorie(Notification::CATEGORIE[3]);
                    $notification->setDescription("Vous avez été ajouté à la mission ".$projet->getNom()." du service ".$projet->getService()->getNom());
                    $notification->setDate(new \DateTime());
                    $notification->setPersonnel(true);
                    $notification->setCumulable(false);
                    $notification->setVu(false);
                    $collabo->addNotification($notification);
                    $this->em->persist($notification);
                }
            }

            $this->em->flush();
        }

        return $this->redirectToRoute('gestion.mission.collabos',array('id' => $projet->getId()));

    }

    /**
     * @Route("/gestionMissions/suppressionCollabos", name="suppression.collabos")
     */
    public function suppressionCollabos(){
        $projet = $this->repository->findOneById($_POST['projet']);
        if(isset($_POST['choix'])){
            $projet = $this->repository->findOneById($_POST['projet']);
            $collaboRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Collaborateur');

            foreach ($_POST['choix'] as $idCollabo){
                $collabo = $collaboRepository->findOneById($idCollabo);
                if($collabo != null){
                    $collabo->removeProjet($projet);
                    $projet->removeCollabo($collabo);


                    //Notification au collabo
                    $notification = new Notification();
                    $notification->setCollaborateur($collabo);
                    $notification->setStatut(Notification::STATUT[1]);
                    $notification->setCategorie(Notification::CATEGORIE[3]);
                    $notification->setDescription("Vous avez retiré de la mission ".$projet->getNom()." du service ".$projet->getService()->getNom());
                    $notification->setDate(new \DateTime());
                    $notification->setPersonnel(true);
                    $notification->setCumulable(false);
                    $notification->setVu(false);
                    $collabo->addNotification($notification);
                    $this->em->persist($notification);
                }
            }

            $this->em->flush();
        }

        return $this->redirectToRoute('gestion.mission.collabos',array('id' => $projet->getId()));

    }

    /**
     * @Route("/gestionMissions/validationLigne", name="valider.ligne")
     */
    public function validerLigne(){
        if(isset($_POST['ligne'])){
            $ligneRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\LigneDeFrais');
            $ligne = $ligneRepository->findOneById($_POST['ligne']);

            //Notification au collabo du refus/validation de la ligne
            $notification = new Notification();
            $notification->setCollaborateur($ligne->getNote()->getCollabo());
            $notification->setCategorie(Notification::CATEGORIE[0]);
            $notification->setDescription("La ligne ".$ligne->getIntitule()." ");
            $notification->setDate(new \DateTime());
            $notification->setPersonnel(true);
            $notification->setCumulable(false);
            $notification->setVu(false);
            $ligne->getNote()->getCollabo()->addNotification($notification);


            if($_POST['decision'] == "refuser"){
                $ligne->setStatutValidation(LigneDeFrais::STATUS[3]);
                $notification->setDescription($notification->getDescription()."a été refusée");
                $notification->setStatut(Notification::STATUT[2]);
            }
            else{
                $ligne->setStatutValidation(LigneDeFrais::STATUS[2]);
                $notification->setDescription($notification->getDescription()."a été validée");
                $notification->setStatut(Notification::STATUT[0]);
            }
            $this->em->persist($notification);

            //vérification si la note est validée en fonction des status des lignes
            $lignes = $ligneRepository->findByNoteID($ligne->getNote()->getId());
            $noteAStatuer = true;
            $noteValidee = true;
            foreach ($lignes as $ligne){
                if($ligne->getStatutValidation() == LigneDeFrais::STATUS[1]){
                    $noteAStatuer = false;
                    break;
                }
                else if($ligne->getStatutValidation() == LigneDeFrais::STATUS[3]){
                    $noteValidee = false;
                }
            }
            if($noteAStatuer){
                if($noteValidee){
                    //Notification au collabo de la validation de la ligne
                    $notification = new Notification();
                    $notification->setCollaborateur($ligne->getNote()->getCollabo());
                    $notification->setStatut(Notification::STATUT[0]);
                    $notification->setCategorie(Notification::CATEGORIE[0]);
                    $notification->setDescription("Votre note de frais de ".$ligne->getNote()->getMois()." ".$ligne->getNote()->getAnnee()." a été validée par le(s) chef(s) de service");
                    $notification->setDate(new \DateTime());
                    $notification->setPersonnel(true);
                    $notification->setCumulable(false);
                    $notification->setVu(false);
                    $ligne->getNote()->getCollabo()->addNotification($notification);
                    $this->em->persist($notification);

                    if($ligne->getNote()->getStatut() == NoteDeFrais::STATUS[1]){
                        $ligne->getNote()->setStatut(NoteDeFrais::STATUS[2]);
                    }
                    else{
                        $ligne->getNote()->setStatut(NoteDeFrais::STATUS[9]);
                    }
                }
                else{
                    $ligne->getNote()->setStatut(NoteDeFrais::STATUS[3]);
                    //Notification au collabo de la validation de la ligne
                    $notification = new Notification();
                    $notification->setCollaborateur($ligne->getNote()->getCollabo());
                    $notification->setStatut(Notification::STATUT[2]);
                    $notification->setCategorie(Notification::CATEGORIE[0]);
                    $notification->setDescription("Votre note de frais de ".$ligne->getNote()->getMois()." ".$ligne->getNote()->getAnnee()." a été refusée.");
                    $notification->setDate(new \DateTime());
                    $notification->setPersonnel(true);
                    $notification->setCumulable(false);
                    $notification->setVu(false);
                    $ligne->getNote()->getCollabo()->addNotification($notification);
                    $this->em->persist($notification);
                }
                $ligne->getNote()->setLastModif(new \DateTime());
            }

            $ligne->setLastModif(new \DateTime());
            $this->em->flush();
        }

        $projet = $this->repository->findOneById($_POST['mission']);
        return $this->redirectToRoute('gestion.mission.lignes',array('id' => $projet->getId()));

    }

}
