<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Formulaire Document
 * 
 * Permet d'uploader des fichiers pour une entreprise spécifique.
 * 
 * Maintenance :
 * - Le fichier est géré séparément (mapped => false) pour être stocké via un service d'upload.
 * - Les contraintes de type de fichier et taille sont définies ici.
 */
class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du document',
                'attr' => [
                    'placeholder' => 'Ex: Statuts de l\'entreprise',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de document',
                'choices' => [
                    'Pitch Deck' => 'pitch_deck',
                    'Business Plan' => 'business_plan',
                    'Statuts' => 'statuts',
                    'Contrat' => 'contrat',
                    'Autre' => 'autre',
                ],
                'placeholder' => '--- Choisir un type ---',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'rows' => 3,
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'PME concernée',
                'placeholder' => '--- Choisir une PME ---',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'],
                'query_builder' => function (\App\Repository\EntrepriseRepository $er) use ($user) {
                    return $er->createQueryBuilder('e')
                        ->where('e.proprietaire = :user')
                        ->setParameter('user', $user);
                },
            ])
            ->add('fichier', FileType::class, [
                'label' => 'Fichier (PDF, Image)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier PDF ou une Image valide (JPEG/PNG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'user' => null,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
