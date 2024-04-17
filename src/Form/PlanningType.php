<?php

namespace App\Form;

use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length([
                    'max' => 20,
                    'maxMessage' => 'The title cannot be longer than {{ limit }} characters.',
                ]),
                new Regex([
                    'pattern' => '/^[a-zA-Z]+$/',
                    'message' => 'The title can only contain letters.',
                ]),
            ],
        ])
            ->add('date', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Type(['type' => \DateTimeInterface::class]),
                ],
            ])
            ->add('approved', null, [
                'constraints' => [
                    new NotBlank(),
                    new Type(['type' => 'bool']),
                ],
            ])
            ->add('idEvent', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}
