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
            'attr' => [
                'novalidate' => 'novalidate',
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
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ])
        ->add('date_nais', DateType::class, [
            'html5' => true,
            'widget' => 'single_text', 
            'attr' => [
                'max' => '2011-12-31',
                'novalidate' => 'novalidate',

            
            ],
        
        ])
        ->add('num_tel', TextType::class, [
            'constraints' => [
                new Assert\Regex([
                    'pattern' => '/^\d{8}$/',
                    'message' => 'The phone number must contain 8 digits.',
                ]),
            ],
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ])
        ->add('email', EmailType::class, [
            'constraints' => [
                new Assert\Email([
                    'message' => 'The email address "{{ value }}" is not valid.',
                ]),
            ],
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ])
        ->add('sexe', ChoiceType::class, [
            'choices' => [
                'F' => 'F',
                'M' => 'M',
            ],
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ])
        ->add('role', ChoiceType::class, [
            'choices' => [
                'Etudiant' => 'Etudiant',
                'Formateur' => 'Formateur',
                'RH' => 'RH',
                'Admin' => 'Admin',
            ],
            'attr' => [
                'novalidate' => 'novalidate',
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
            'attr' => [
                'novalidate' => 'novalidate',
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
