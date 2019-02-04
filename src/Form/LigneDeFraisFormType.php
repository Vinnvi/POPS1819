<?php

namespace App\Form;

use App\Entity\LigneDeFrais;
use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class LigneDeFraisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule')
            ->add('mission')
            ->add('montant', IntegerType::class)
            ->add('date',DateType::class)
            //->add('statutValidation')
            //->add('avance')
            //->add('justificatif')
            //->add('Note')
            ->add('Projet', EntityType::class,[
                'class' => Projet::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneDeFrais::class,
        ]);
    }
}
