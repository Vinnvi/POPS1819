<?php


namespace App\Controller\Admin;


use App\Entity\Service;
use App\Entity\Collaborateur;
use App\Form\CollaborateurType;
use App\Repository\ServiceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;





class AdminServiceController extends AbstractController
{
    /**
     * @var ServiceRepository
     */
    private $repository;

    /**
     * @var $em
     */
    private $em;

    public function __construct(ServiceRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.service.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $services = $this->repository->findAll();
        return $this->render('admin/service/services.html.twig',
            ['services' => $services]);
    }



     /**
     * @Route("/admin/service/edit/{id}", name="admin.service.removeCollaborateur", methods="DELETE")
     * @param Collaborateur $collaborateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeCollaborateur(Collaborateur $collaborateur, Request $request)
    {
        $idService = $collaborateur->getService()->getId();
        if($this->isCsrfTokenValid('delete' . $collaborateur->getId(), $request->get('_token'))){
            $entityManager = $this->getDoctrine()->getManager();

            $collaborateurBD = $entityManager->getRepository('App\Entity\Collaborateur')->find($collaborateur->getId());
            $collaborateurBD->setService(null);
            $collaborateurBD->setRoleEntreprise(Collaborateur::STATUS[0]);

            $entityManager->flush();

            $this->addFlash('success','Le collaborateur a ete retire');
            return $this->redirectToRoute('admin.service.edit', ['id' => $idService]);
        }
        else
        {
            $this->addFlash('error','La suppression a échoué');
            return $this->redirectToRoute('admin.service.edit', ['id' => $idService]);
        }

    }


    /**
     * @Route("/admin/service/{id}", name="admin.service.remove", methods="DELETE")
     * @param Service $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeService(Service $service, Request $request)
    {
        //$idService = $collaborateur->getService()->getId();

        if($this->isCsrfTokenValid('delete' . $service->getId(), $request->get('_token'))){
            $collaborateursDuService = $this->em->getRepository('App\Entity\Collaborateur')->findBy(
            ['service' => $service]);

            foreach($collaborateursDuService as $collaborateur)
            {
                $collaborateur->setService(null);
                $collaborateur->setRoleEntreprise(Collaborateur::STATUS[0]);
            }
            //var_dump($service->getChef()->getNom());
            $this->em->remove($service);

            $this->em->flush();

            $this->addFlash('success','Le service a ete supprime');
            return $this->redirectToRoute('admin.service.index');
        }
        else
        {
            $this->addFlash('error','La suppression a échoué');
            return $this->redirectToRoute('admin.service.index');
        }

    }

    /**
     * @Route("/admin/service/create" , name="admin.service.new")
     * @param Service $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newService(Service $service = null, Request $request)
    {
        if ($service == null) $service = new Service();

        $listeSansService = $this->em->getRepository('App\Entity\Collaborateur')->findBy(
            ['service' => null],
            ['id' => 'DESC']);

        if (!empty($listeSansService)) $service->setChef($listeSansService[0]);

        $form = $this->createForm('App\Form\ServiceType', $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($service);

            $chefBD = $service->getChef();
            $chefBD->setService($service);
            $chefBD->setRoleEntreprise(Collaborateur::STATUS[1]);

            $this->em->flush();
            $this->addFlash('success','Service ajoute');
            return $this->redirectToRoute('admin.service.index');
        }

        return $this->render('admin/service/new_service.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/admin/edit/{id}/createCollaborateur" , name="admin.service.newCollaborateur")
     * @param Service $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newCollaborateur(Service $service, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $collaborateur = new Collaborateur();
        $collaborateur->setService($service);
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
            $this->addFlash('success','Collaborateur ajoute');
            return $this->redirectToRoute('admin.service.edit', ['id' => $service->getId()]);
        }

        return $this->render('admin/collaborateur/new.html.twig',[
            'collaborateur' => $collaborateur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/service/create/createCollaborateur" , name="admin.service.newCollaborateurService")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newCollaborateurService(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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


            $service = new Service();
            $service->setChef($collaborateur);
            $collaborateur->setService(null);
            $this->em->persist($collaborateur);
            $this->em->flush();
            return $this->redirectToRoute('admin.service.new',  ['service' => $service]);
        }

        return $this->render('admin/service/newChef.html.twig',[
            'collaborateur' => $collaborateur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/service/edit/{id}", name="admin.service.edit")
     * @param Service $service
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Service $service, Request $request)
    {
        $collaborateurs = $this->getDoctrine()->getRepository('App\Entity\Collaborateur')->findBy(
            ['service' => $service],
            ['Nom' => 'ASC']);

        $id = $service->getId();
        $formBuilder = $this->createFormBuilder($service)
            ->add('chef', EntityType::class, [
                'class' => Collaborateur::class,
                'label' => 'Ajouter un collaborateur existant',
                'choice_label' => function($choiceValue, $key, $value){
                    return $choiceValue->getNom().' '.$choiceValue->getPrenom();
                }
            ,
            'query_builder' => function (EntityRepository $er) use ($service){
                return $er->createQueryBuilder('c')
                    ->where('c.service != :service')
                    ->setParameter('service', $service)
                    ->orWhere('c.service is NULL')
                    ->andWhere('c.role_entreprise != :role')
                    ->setParameter('role', Collaborateur::STATUS[1])
                    ->orderBy('c.service', 'ASC');
            },
            'group_by' =>
                function($choiceValue, $key, $value){
                    if($choiceValue->getService() == null) return 'Sans service';
                    else return $choiceValue->getService()->getNom();
                }
            ]);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $chefBD = $this->em->getRepository('App\Entity\Collaborateur')->findOneBy(
            ['service' => $service,
            'role_entreprise' => Collaborateur::STATUS[1]]);
            
            $collaborateur = $service->getChef();
            $collaborateur->setService($service);

            $service->setChef($chefBD);
            $this->em->flush();
            return $this->redirectToRoute('admin.service.edit',  ['id' => $service->getId()]);
        }

        return $this->render('admin/service/edit.html.twig',[
            'service' => $service,
            'collaborateurs' => $collaborateurs,
            'chef' => $service->getChef(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edit/setChef/{id}", name="admin.service.setChef")
     * @param Collaborateur $collborateur
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setChef(Collaborateur $collaborateur, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $serviceBD = $entityManager->getRepository(Service::class)->find($collaborateur->getService()->getId());
        $ancienChefBD = $entityManager->getRepository(Collaborateur::class)->find($serviceBD->getChef()->getId());
        $collaborateurBD = $entityManager->getRepository(Collaborateur::class)->find($collaborateur->getId());

        $collaborateurBD->setRoleEntreprise(Collaborateur::STATUS[1]);
        $ancienChefBD->setRoleEntreprise(Collaborateur::STATUS[0]);
        $serviceBD->setChef($collaborateur);

        $entityManager->flush();

        //TODO : confirmations? puis envois des notifs vers le nouveau chef.

        $this->addFlash('success', "Chef de service modifie avec succes");
        return $this->redirectToRoute('admin.service.edit', ['id' => $serviceBD->getId()]);
    }
}