<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserWithoutPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse e-mail ne peut pas être vide']),
                    new Email(['message' => 'L\'adresse e-mail "{{ value }}" n\'est pas valide']),
                ],
            ])
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Nom', // Label in French
            ])
            ->add('prenom', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Nom', // Label in French
            ])
            ->add('numTel', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Numéro de téléphone ne peut pas être vide']),
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'minMessage' => 'Le numéro de téléphone doit contenir au moins {{ limit }} numéro',
                    ]),
                ],
                'label' => 'Numéro de téléphone', 
            ]);
         
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
