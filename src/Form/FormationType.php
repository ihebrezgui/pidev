<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;


class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('typeF', ChoiceType::class, [
            'choices' => [
                'Devops' => 'Devops',
                'Mobile' => 'Mobile',
                'GL' => 'GL',
                'Santé' => 'Santé',
                'Math et logique' => 'Math et logique',
                'Développement Personnel' => 'Développement Personnel',
                'Data Science' => 'Data Science',
                'Culture générale' => 'Culture générale',
                'Finance' => 'Finance',
            ],  ]) 
            ->add('img', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],])         
            ->add('prix')
            ->add('duree')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}


