<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['class' => 'w-full bg-white/5 border border-white/10 focus:ring-2 focus:ring-admin-primary/40 rounded-xl py-3 px-4 text-white placeholder:text-slate-600 transition-all']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'w-full bg-white/5 border border-white/10 focus:ring-2 focus:ring-admin-primary/40 rounded-xl py-3 px-4 text-white placeholder:text-slate-600 transition-all']
            ])
            ->add('dbRole', ChoiceType::class, [
                'label' => 'Rôle Système',
                'choices' => [
                    'Propriétaire PME' => 'proprietaire',
                    'Investisseur' => 'investisseur',
                    'Administrateur' => 'administrateur',
                ],
                'attr' => ['class' => 'w-full bg-white/5 border border-white/10 focus:ring-2 focus:ring-admin-primary/40 rounded-xl py-3 px-4 text-white transition-all appearance-none']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de famille',
                'attr' => ['class' => 'w-full bg-white/5 border border-white/10 focus:ring-2 focus:ring-admin-primary/40 rounded-xl py-3 px-4 text-white transition-all']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'w-full bg-white/5 border border-white/10 focus:ring-2 focus:ring-admin-primary/40 rounded-xl py-3 px-4 text-white transition-all']
            ])
            ->add('tel', TextType::class, [
                'label' => 'Numéro de Téléphone',
                'required' => false,
                'attr' => ['class' => 'w-full bg-white/5 border border-white/10 focus:ring-2 focus:ring-admin-primary/40 rounded-xl py-3 px-4 text-white transition-all']
            ])
            ->add('cin', TextType::class, [
                'label' => 'Numéro CIN',
                'required' => false,
                'attr' => ['class' => 'w-full bg-white/5 border border-white/10 focus:ring-2 focus:ring-admin-primary/40 rounded-xl py-3 px-4 text-white transition-all']
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Activer le compte immédiatement',
                'required' => false,
                'attr' => ['class' => 'w-5 h-5 rounded border-white/10 bg-white/5 text-admin-primary focus:ring-admin-primary/40 focus:ring-offset-admin-bg']
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
