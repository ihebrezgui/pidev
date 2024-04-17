<?php

namespace App\Form;

use App\Entity\Givedonation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class GivedonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statutDonateur')
            ->add('montant')
            ->add('dateDeNaissance')
            ->add('dateDeNaissance', DateType::class, [
                'label' => 'dateDeNaissance',
            ])
            ->add('email')
            ->add('numeroDeTelephone')
            ->add('profession')
            ->add('methodePaiement')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Givedonation::class,
        ]);
    }
}
