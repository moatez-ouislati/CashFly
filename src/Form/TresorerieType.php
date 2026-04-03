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

class TresorerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_compte', TextType::class, [
                'label' => 'Account Name',
                'attr' => ['placeholder' => 'e.g. Main Bank Account']
            ])
            ->add('type_compte', ChoiceType::class, [
                'choices' => [
                    'Caisse' => 'CAISSE',
                    'Banque' => 'BANQUE',
                    'Carte' => 'CARTE',
                    'Wallet' => 'WALLET',
                ],
                'label' => 'Account Type',
                'placeholder' => '--- Choose Account Type ---'
            ])
            ->add('solde', MoneyType::class, [
                'currency' => 'TND',
                'label' => 'Initial Balance'
            ])
            ->add('devise', TextType::class, [
                'data' => 'TND',
                'label' => 'Currency'
            ])
            ->add('rib', TextType::class, [
                'required' => false,
                'label' => 'RIB'
            ])
            ->add('numero_compte', TextType::class, [
                'required' => false,
                'label' => 'Account Number'
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'Select PME',
                'placeholder' => '--- Select a Company ---'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tresorerie::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
