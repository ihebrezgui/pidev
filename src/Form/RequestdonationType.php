<?php

namespace App\Form;

use App\Entity\Requestdonation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RequestdonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idRequest')
            ->add('formation_souhaitee')
            ->add('email')
            ->add('dateLimite', DateType::class, [
                'label' => 'Date limite',
            ])
            ->add('lieu_de_residence')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Requestdonation::class,
        ]);
    }
}
