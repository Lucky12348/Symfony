<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as SFType;

class ConnexionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', null, [
                'attr' => [
                    'class' => 'form-field animation a3'
                ]
            ])
            ->add('mdp', null, [
                'attr' => [
                    'class' => 'form-field animation a4'
                ]
            ])
            ->add('connexion', SFType\SubmitType::class, [
                'attr' => [
                    'class' => 'animation a5'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}