<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Entity\ProfilePage;
use App\Repository\ProfilePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePageController extends Controller
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
    * @Route("/profile", name="change.profilePicture")
    * //return \Symfony\Component\HttpFoundation\Response
    */
    /*
    public function changeProfilPicture() : Response {
      if (isset($_POST["profilePicToUpload"])){
        return $this->redirectToRoute('app_profile');
      }
      else
      {
        $projetRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\Projet');
        $ProfileRepository = $this->getDoctrine()->getEntityManager()->getRepository('App\Entity\ProfilePage');

        $LignedeFraisModifiee = $ProfileRepository->findById($_POST['ligneId'])[0];

        $target_dir = "images/profile_pics/";
        $target_file = $target_dir . basename($_FILES["profilePicToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check file size
        if ($_FILES["profilePicToUpload"]["size"] > 500000) {
          echo "Sorry, your file is too large.";
          $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        }
        else {
          if (move_uploaded_file($_FILES["profilePicToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["profilePicToUpload"]["name"]). " has been uploaded.";
          }
          else {
            echo "Sorry, there was an error uploading your file.";
          }
        }
        return $this->redirectToRoute('app_profile');
      }
    }*/

}
