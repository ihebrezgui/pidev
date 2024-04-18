<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCours')
            ->add('description')
            ->add('categorie', ChoiceType::class, [
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
                ],
            ])
            ->add('cours')
            ->add('idFormation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => function (Formation $formation) {
                    return $formation->getIdFormation() . ' - ' . $formation->getTypeF(); 
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
