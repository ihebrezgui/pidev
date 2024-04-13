<?php

namespace App\Form;

use App\Entity\CodePromo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodePromoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateExpiration', DateType::class, [
                'html5' => true,
                'widget' => 'single_text', // Use single text widget for date picker
                'attr' => [
                    'class' => 'form-control', // Add Bootstrap form control class
                    'min' => '2024-04-17', // Set maximum date
                ],
            ])
            ->add('active',TextType::class, [
                'label' => '    ACTIVE',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('iduser', TextType::class, [
                'label' => 'User ID',
                'attr' => ['class' => 'form-control'],
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CodePromo::class,
        ]);
    }
}
