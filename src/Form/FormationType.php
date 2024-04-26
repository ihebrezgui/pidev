<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'allow_delete' => true,  // Allows the deletion of the file
                'download_uri' => false,  // Do not generate a download URI
                'image_uri' => true,      // Optionally set to false if you handle image display differently
                'label' => 'Image (PNG, JPEG)',
            ])    
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


