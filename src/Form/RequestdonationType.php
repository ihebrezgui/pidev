<?php

namespace App\Form;

use App\Entity\Requestdonation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RequestdonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('formation_souhaitee', TextType::class, [ // Adjust field type as needed
                'label' => 'Formation souhaitée',
                'attr' => [
                    'class' => 'form-control' // Add a common class for styling
                ]
            ])
            ->add('email', EmailType::class, [ // Use the appropriate field type
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control' // Add a common class for styling
                ]
            ])
            ->add('dateLimite', DateType::class, [
                'label' => 'Date limite',
                'attr' => [
                    'class' => 'form-control' // Add a common class for styling (date picker)
                ]
            ])
            ->add('lieu_de_residence', TextType::class, [ // Adjust field type as needed
                'label' => 'Lieu de résidence',
                'attr' => [
                    'class' => 'form-control' // Add a common class for styling
                ]
            ])
            ->add('description', TextareaType::class, [ // Adjust field type as needed
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control' // Add a common class for styling
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Requestdonation::class,
        ]);
    }
}
