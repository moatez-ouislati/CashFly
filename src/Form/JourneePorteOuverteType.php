<?php

namespace App\Form;

use App\Entity\JourneePorteOuverte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire JourneePorteOuverte
 * 
 * Permet de créer un événement de type JPO.
 * 
 * Maintenance :
 * - Les styles sont cohérents avec le reste de l'application.
 */
class JourneePorteOuverteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'événement',
                'attr' => [
                    'placeholder' => 'Ex: Forum de l\'investissement',
                    'class' => 'w-full bg-slate-50 border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('dateEvenement', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de l\'événement',
                'attr' => ['class' => 'w-full bg-slate-50 border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
                'attr' => [
                    'placeholder' => 'Ex: Technopole El Ghazela',
                    'class' => 'w-full bg-slate-50 border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('maxParticipants', IntegerType::class, [
                'label' => 'Nombre maximum de participants',
                'attr' => [
                    'min' => 1,
                    'class' => 'w-full bg-slate-50 border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description détaillée',
                'attr' => [
                    'rows' => 5, 
                    'placeholder' => 'Décrivez l\'objectif de cette journée...',
                    'class' => 'w-full bg-slate-50 border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JourneePorteOuverte::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
