<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;


class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('date_nais', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'max' => '2011-12-31',
                ],
            ])
            ->add('num_tel', TextType::class)
            ->add('email', EmailType::class)
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'F' => 'Female',
                    'M' => 'Male',
                    
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
                'label' => 'Mot de passe',
            ])
            ->add('recaptcha', ReCaptchaType::class, [
                'mapped' => false,
                'label' => 'Captcha',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
