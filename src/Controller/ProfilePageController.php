<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Entity\ProfilePage;
use App\Repository\ProfilePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePageController extends AbstractController
{
    /**
    * @var Environment
    **/
    private $twig;

    /**
     * @var ProfilePageRepository
     */
    private $repository;


    public function __construct(Environment $twig,ProfilePageRepository $repository)
    {
      $this->twig = $twig;
      $this->repository = $repository;
    }

    public function index(): Response
    {
      return new Response($this->twig->render('pages/profilePage.html.twig',
        []));
    }

    /**
    * @Route("/profileChangePict", name="change.profilePicture")
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function changeProfilePicture() : Response {
      /* verification file */
      if(isset($_FILES['profilePicToUpload'])){
        $errors= array();
        $target_dir = "images/profile_pics/";
        //$file_name = basename($_FILES['profilePicToUpload']['name']);
        $target_file = $target_dir . basename($_FILES["profilePicToUpload"]["name"]);
        $file_size = $_FILES['profilePicToUpload']['size'];
        $file_tmp = $_FILES['profilePicToUpload']['tmp_name'];
        $file_type = $_FILES['profilePicToUpload']['type'];
        $tmp = explode('.',$_FILES['profilePicToUpload']['name']);
        $file_ext = strtolower(end($tmp));

         $extensions= array("jpeg","jpg","png");
         if(in_array($file_ext,$extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
         }
         if($file_size > 2097152) {
            $errors[]='File size must be excately 2 MB';
         }
         /* picture is valid, we can update it */
         if(empty($errors)==true) {
            //move file in our folder
            move_uploaded_file($file_tmp,$target_file);
            //update databse
            $collaborateur = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
            $collaborateur = $collaborateur->findById($this->getUser()->getId());
            $collaborateur[0]->setProfilePicPath($target_file);
            $this->getDoctrine()->getEntityManager()->persist($collaborateur[0]);
            $this->getDoctrine()->getEntityManager()->flush();
            echo "Success";
         }else{
            print_r($errors);
         }
      }
      if(isset($_FILES['bgProfilePicToUpload'])){
        $errorsBg= array();
        $target_dir = "images/profile_pics/";
        //$file_name = basename($_FILES['profilePicToUpload']['name']);
        $target_file = $target_dir . basename($_FILES["bgProfilePicToUpload"]["name"]);
        $file_name = $_FILES['bgProfilePicToUpload']['name'];
        $file_size = $_FILES['bgProfilePicToUpload']['size'];
        $file_tmp = $_FILES['bgProfilePicToUpload']['tmp_name'];
        $file_type = $_FILES['bgProfilePicToUpload']['type'];
        $tmp = explode('.',$_FILES['bgProfilePicToUpload']['name']);
        $file_ext = strtolower(end($tmp));

         $extensions= array('jpeg','jpg','png');
         if(in_array($file_ext,$extensions)=== false){
            $errorsBg[]="extension not allowed, please choose a JPEG or PNG file.";
         }
         if($file_size > 2097152) {
            $errorsBg[]='File size must be excately 2 MB';
         }
         /* picture is valid, we can update it */
         if(empty($errorsBg)==true) {
            //move file in our folder
            move_uploaded_file($file_tmp,$target_file);
            //update databse
            $collaborateur = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
            $collaborateur = $collaborateur->findById($this->getUser()->getId());
            $collaborateur[0]->setBackgroundPicPath($target_file);
            $this->getDoctrine()->getEntityManager()->persist($collaborateur[0]);
            $this->getDoctrine()->getEntityManager()->flush();
            echo "Success";
         }else{
            print_r($errorsBg);
         }
      }
      return $this->redirectToRoute('app_profile');
    }
    /**
    * @Route("/profileChangeInfos", name="change.infosProfile")
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function changeInfosProfile() : Response {
      dump('hum');
      print_r("checkHEre");
      //get current collaborateur
      $collaborateur = $this->getDoctrine()->getManager()->getRepository('App\Entity\Collaborateur');
      $collaborateur = $collaborateur->findById($this->getUser()->getId());
      if(isset($_POST['mail']))
      {
        $collaborateur[0]->setEmail($_POST['mail']);
        $this->getDoctrine()->getEntityManager()->persist($collaborateur[0]);
        $this->getDoctrine()->getEntityManager()->flush();
        dump('ohlala');
      }
      else{
        dump('noooo');
      }


      return $this->redirectToRoute('app_profile');
    }
}
