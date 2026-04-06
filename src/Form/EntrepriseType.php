<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Formulaire Entreprise
 * 
 * Permet de créer ou modifier une entreprise.
 * 
 * Maintenance :
 * - Les champs sont liés aux propriétés de l'entité Entreprise.
 * - Les styles Tailwind sont appliqués via l'attribut 'class'.
 */
class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'attr' => [
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'placeholder' => 'ex: Ma PME S.A.'
                ]
            ])
            ->add('secteur', TextType::class, [
                'label' => 'Secteur d\'activité',
                'attr' => [
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'placeholder' => 'ex: Technologie, Commerce...'
                ]
            ])
            ->add('forme_juridique', ChoiceType::class, [
                'label' => 'Forme juridique',
                'choices' => [
                    'SARL' => 'SARL',
                    'SA' => 'SA',
                    'SUARL' => 'SUARL',
                    'SNC' => 'SNC',
                ],
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('capital', NumberType::class, [
                'label' => 'Capital social (TND)',
                'attr' => [
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'placeholder' => '0.00'
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'placeholder' => '123 Rue de la Paix'
                ]
            ])
            ->add('dateCreation', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
