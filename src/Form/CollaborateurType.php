<?php

namespace App\Form;

use App\Entity\Collaborateur;
use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CollaborateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('username')
            ->add('password',PasswordType::class,[
                'required' => true,
                'always_empty' => false,
            ])
            //->add('roles', ChoiceType::class,[
            //    'choices' =>$this->getChoices(),
            //])
            //->add('profile_pic_path')
            ->add('email')
            ->add('service', EntityType::class,[
                    'class' => Service::class,
                    'choice_label' => 'nom',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collaborateur::class,
        ]);
    }

    private function getChoices(){
        $choices = Collaborateur::STATUS;
        $output = [];
        foreach ($choices as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }

}
