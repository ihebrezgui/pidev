<?php

namespace App\Form;

use App\Entity\Partnership;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PartnershipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomP', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'The name cannot be blank.'
                ]),
                new Regex([
                    'pattern' => '/^[A-Z][a-zA-Z]*$/',
                    'message' => 'The name must start with a capital letter and only contain letters.'
                ])
            ],
        ])
            ->add('domaine', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('choix', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partnership::class,
        ]);
    }
}