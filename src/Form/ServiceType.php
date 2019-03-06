<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Collaborateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('chef', EntityType::class, [
                'class' => Collaborateur::class,
                'choice_label' => function($choiceValue, $key, $value){
                    return $choiceValue->getNom().' '.$choiceValue->getPrenom();
                }
            ,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->where('c.role_entreprise != :role')
                    ->setParameter('role', Collaborateur::STATUS[1])
                    ->orderBy('c.service', 'ASC');
            },
            'group_by' =>
                function($choiceValue, $key, $value){
                    if($choiceValue->getService() == null) return 'Sans service';
                    else return $choiceValue->getService()->getNom();
                }
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
