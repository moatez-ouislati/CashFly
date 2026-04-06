<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Investissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire Investissement
 * 
 * Permet aux investisseurs de proposer un placement.
 * 
 * Maintenance :
 * - Les champs numériques sont validés par les contraintes de l'entité Investissement.
 */
class InvestissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'Entreprise cible',
                'placeholder' => '--- Sélectionner une PME ---',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('montant', MoneyType::class, [
                'currency' => 'TND',
                'label' => 'Montant de l\'investissement',
                'attr' => [
                    'placeholder' => '0.00',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('tauxRendementPrevu', NumberType::class, [
                'label' => 'Taux de rendement prévu (%)',
                'required' => false,
                'attr' => [
                    'step' => '0.01', 'min' => 0, 'max' => 100,
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'placeholder' => 'ex: 12.5'
                ]
            ])
            ->add('dureeMois', NumberType::class, [
                'label' => 'Durée prévue (en mois)',
                'required' => false,
                'attr' => [
                    'min' => 1,
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'placeholder' => 'ex: 24'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Notes ou description',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'placeholder' => 'Détails de l\'investissement...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Investissement::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
