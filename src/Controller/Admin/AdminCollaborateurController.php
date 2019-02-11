<?php


namespace App\Controller\Admin;


use App\Entity\Collaborateur;
use App\Form\CollaborateurType;
use App\Repository\CollaborateurRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminCollaborateurController extends AbstractController
{
    /**
     * @var CollaborateurRepository
     */
    private $repository;

    /**
     * @var $em
     */
    private $em;

    public function __construct(CollaborateurRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.collaborateur.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $collaborateurs = $this->repository->findAll();
        return $this->render('admin/collaborateur/collaborateur.html.twig',compact('collaborateurs'));
    }

    /**
     * @Route("/admin/edit/{id}", name="admin.collaborateur.edit")
     * @param Collaborateur $collaborateur
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Collaborateur $collaborateur, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(CollaborateurType::class,$collaborateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $collaborateur->setPassword(
                $passwordEncoder->encodePassword(
                    $collaborateur,
                    $form->get('password')->getData()
                )
            );

            $this->em->flush();
            $this->addFlash('success','Collaborateur modifié');
            return $this->redirectToRoute('admin.collaborateur.index');
        }

        return $this->render('admin/collaborateur/edit.html.twig',[
            'collaborateur' => $collaborateur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/collaborateur/remove/{id}", name="admin.collaborateur.remove", methods="DELETE")
     * @param Collaborateur $collaborateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(Collaborateur $collaborateur, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $collaborateur->getId(), $request->get('_token'))){
            $this->em->remove($collaborateur);
            $this->em->flush();
            $this->addFlash('success','suppression effectuée');
            return $this->redirectToRoute('admin.collaborateur.index');
        }
    }

    /**
     * @Route("/admin/collaborateur/create" , name="admin.collaborateur.new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $collaborateur = new Collaborateur();
        $form = $this->createForm(CollaborateurType::class,$collaborateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $collaborateur->setPassword(
                $passwordEncoder->encodePassword(
                    $collaborateur,
                    $form->get('password')->getData()
                )
            );

            $this->em->persist($collaborateur);
            $this->em->flush();
            $this->addFlash('success','Collaborateur ajouté');
            return $this->redirectToRoute('admin.collaborateur.index');
        }

        return $this->render('admin/collaborateur/new.html.twig',[
            'collaborateur' => $collaborateur,
            'form' => $form->createView()
        ]);
    }
}