<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Tresorerie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire Tresorerie
 * 
 * Permet de configurer un compte financier pour une entreprise.
 * 
 * Maintenance :
 * - Le paramètre 'user' dans les options permet de filtrer les entreprises du propriétaire connecté.
 */
class TresorerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('nom_compte', TextType::class, [
                'label' => 'Nom du compte',
                'attr' => [
                    'placeholder' => 'ex: Compte Courant BIAT',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('type_compte', ChoiceType::class, [
                'choices' => [
                    'Caisse' => 'CAISSE',
                    'Banque' => 'BANQUE',
                    'Carte' => 'CARTE',
                    'Wallet' => 'WALLET',
                ],
                'label' => 'Type de compte',
                'placeholder' => '--- Choisir un type ---',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('solde', MoneyType::class, [
                'currency' => 'TND',
                'label' => 'Solde initial',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('devise', TextType::class, [
                'data' => 'TND',
                'label' => 'Devise',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('rib', TextType::class, [
                'required' => false,
                'label' => 'RIB / IBAN',
                'attr' => [
                    'placeholder' => 'Optionnel',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('numero_compte', TextType::class, [
                'required' => false,
                'label' => 'Numéro de compte',
                'attr' => [
                    'placeholder' => 'Optionnel',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'Entreprise concernée',
                'placeholder' => '--- Sélectionner une PME ---',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'],
                'query_builder' => function (\App\Repository\EntrepriseRepository $er) use ($user) {
                    return $er->createQueryBuilder('e')
                        ->where('e.proprietaire = :user')
                        ->setParameter('user', $user);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tresorerie::class,
            'attr' => ['novalidate' => 'novalidate'],
            'user' => null,
        ]);
    }
}
