<?php

namespace App\Form;

use App\Entity\UserPartenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matricule')
            ->add('username')
            ->add('password')
            ->add('nomComplet')
            ->add('adresse')
            ->add('telephone')
            ->add('email')
            ->add('partenaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserPartenaire::class,
        ]);
    }
}
