<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('yearsExperience', TextType::class, [
                'label' => 'Années d\'expérience',
                'attr' => [
                    'placeholder' => 'ex: 5',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6 text-slate-900 placeholder:text-slate-400 transition-all'
                ]
            ])
            ->add('highestProfit', TextType::class, [
                'label' => 'Plus haut profit réalisé (TND)',
                'attr' => [
                    'placeholder' => 'ex: 50000',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6 text-slate-900 placeholder:text-slate-400 transition-all'
                ]
            ])
            ->add('budget', TextType::class, [
                'label' => 'Budget d\'investissement (TND)',
                'attr' => [
                    'placeholder' => 'ex: 100000',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-xl py-4 px-6 text-slate-900 placeholder:text-slate-400 transition-all'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
