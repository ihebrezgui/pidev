<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'constraints' => [
                new Assert\Length(['min' => 3]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z]+$/',
                    'message' => 'The name must contain only alphabetic characters.',
                ]),
            ],
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new Assert\Length(['min' => 3]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z]+$/',
                    'message' => 'The surname must contain only alphabetic characters.',
                ]),
            ],
        ])
        ->add('date_nais', DateType::class, [
            'html5' => true,
            'widget' => 'single_text', // Use single text widget for date picker
            'attr' => [
                'max' => '2011-12-31', // Set maximum date
            ],
        ])
        ->add('num_tel', TextType::class, [
            'constraints' => [
                new Assert\Regex([
                    'pattern' => '/^\d{8}$/',
                    'message' => 'The phone number must contain 8 digits.',
                ]),
            ],
        ])
        ->add('email', EmailType::class, [
            'constraints' => [
                new Assert\Email([
                    'message' => 'The email address "{{ value }}" is not valid.',
                ]),
            ],
        ])
        ->add('sexe', ChoiceType::class, [
            'choices' => [
                'F' => 'F',
                'M' => 'M',
            ],
        ])
        ->add('role', ChoiceType::class, [
            'choices' => [
                'Etudiant' => 'Etudiant',
                'Formateur' => 'Formateur',
                'RH' => 'RH',
                'Admin' => 'Admin',
            ],
        ])
        ->add('password', PasswordType::class, [
            'constraints' => [
                new Assert\Length(['min' => 8]),
                new Assert\Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/',
                    'message' => 'The password must contain at least one uppercase letter, one digit, one special character, and be at least 8 characters long.',
                ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
