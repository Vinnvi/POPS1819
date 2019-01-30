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
    public function edit(Collaborateur $collaborateur, Request $request)
    {
        $form = $this->createForm(CollaborateurType::class,$collaborateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            return $this->redirectToRoute('admin.collaborateur.index');
        }

        return $this->render('admin/collaborateur/edit.html.twig',[
            'collaborateur' => $collaborateur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/collaborateur/remove/{id}", name="admin.collaborateur.remove")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(Collaborateur $collaborateur)
    {
        return $this->index();
    }

    /**
     * @Route("/admin/collaborateur/create" , name="admin.collaborateur.new")
     */
    public function new(Request $request)
    {
        $collaborateur = new Collaborateur();
        $form = $this->createForm(CollaborateurType::class,$collaborateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($collaborateur);
            $this->em->flush();
            return $this->redirectToRoute('admin.collaborateur.index');
        }

        return $this->render('admin/collaborateur/new.html.twig',[
            'collaborateur' => $collaborateur,
            'form' => $form->createView()
        ]);
    }
}