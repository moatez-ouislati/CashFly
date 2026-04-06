<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomDocument')
            ->add('typeDocument')
            ->add('statut')
            ->add('cheminFichier')
            ->add('description')
            ->add('texteOcr')
            ->add('dateUpload', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'upload'
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom', // C'est souvent plus joli d'afficher le nom de l'entreprise plutôt que son ID
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}