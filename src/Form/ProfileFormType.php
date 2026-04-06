<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $showExtra = $options['show_extra_fields'];

        $builder
            ->add('cin', IntegerType::class, [
                'label' => 'CIN',
                'attr' => ['maxlength' => 8],
            ])
            ->add('tel', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['maxlength' => 8],
            ])
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('faceImageFile', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Image invalide (JPG, PNG, WEBP uniquement).',
                    ]),
                ],
            ])
        ;

        if ($showExtra) {
            $builder
                ->add('yearsExperience', TextType::class, [
                    'label' => "Années d'expérience",
                    'required' => false,
                ])
                ->add('highestProfit', TextType::class, [
                    'label' => 'Profit le plus élevé (TND)',
                    'required' => false,
                ])
                ->add('budget', TextType::class, [
                    'label' => 'Budget (TND)',
                    'required' => false,
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'show_extra_fields' => false,
        ]);
    }
}
