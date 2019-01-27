<?php


namespace App\Controller\Admin;


use App\Entity\Collaborateur;
use App\Form\CollaborateurType;
use App\Repository\CollaborateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminCollaborateurController extends AbstractController
{
    /**
     * @var CollaborateurRepository
     */
    private $repository;

    public function __construct(CollaborateurRepository $repository)
    {
        $this->repository = $repository;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Collaborateur $collaborateur)
    {
        $form = $this->createForm(CollaborateurType::class,$collaborateur);
        return $this->render('admin/collaborateur/edit.html.twig',[
            'collaborateur' => $collaborateur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/remove/{id}", name="admin.collaborateur.remove")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(Collaborateur $collaborateur)
    {
        return $this->index();
    }
}