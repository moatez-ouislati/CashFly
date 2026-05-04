<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Investissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvestissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'Entreprise Cible',
                'placeholder' => 'Sélectionnez une entreprise',
                'attr' => ['class' => 'w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6']
            ])
            ->add('montant', MoneyType::class, [
                'currency' => 'TND',
                'label' => 'Montant à investir',
                'attr' => ['class' => 'w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6']
            ])
            ->add('tauxRendementPrevu', NumberType::class, [
                'label' => 'Taux de rendement attendu (%)',
                'required' => false,
                'attr' => ['class' => 'w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6']
            ])
            ->add('dureeMois', NumberType::class, [
                'label' => 'Durée prévue (mois)',
                'required' => false,
                'attr' => ['class' => 'w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Note / Message au propriétaire',
                'required' => false,
                'attr' => ['rows' => 4, 'class' => 'w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Investissement::class,
        ]);
    }
}
