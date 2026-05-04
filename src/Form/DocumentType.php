<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('nomDocument', null, [
                'label' => 'Nom du document',
                'attr' => ['placeholder' => 'Entrez le nom du document']
            ])
            ->add('typeDocument', null, [
                'label' => 'Type de document',
                'attr' => ['placeholder' => 'Ex: Contrat, Facture, Rapport...']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Décrivez ce document...'
                ]
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'Entreprise associée',
                'query_builder' => function (\App\Repository\EntrepriseRepository $er) use ($user, $options) {
                    $qb = $er->createQueryBuilder('e');
                    if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_INVESTISSEUR', $user->getRoles())) {
                        $qb->where('e.proprietaire = :user')
                           ->setParameter('user', $user);
                    }
                    return $qb;
                },
            ])
            ->add('fichiers', FileType::class, [
                'label' => 'Fichiers',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg',
                    'class' => 'file-input hidden',
                    'id' => 'docFileInput'
                ],
                'constraints' => [
                    new Assert\All([
                        new Assert\File([
                            'maxSize' => '10M',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'image/png',
                                'image/jpeg',
                            ],
                        ])
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'user' => null,
        ]);
    }
}
