<?php

namespace App\Form;

use App\Entity\Tresorerie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class TransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('source', EntityType::class, [
                'class' => Tresorerie::class,
                'choice_label' => 'nom_compte',
                'label' => 'Compte Source (Débit)',
                'placeholder' => 'Choisir le compte source',
                'query_builder' => function (\App\Repository\TresorerieRepository $tr) use ($user) {
                    return $tr->createQueryBuilder('t')
                        ->join('t.entreprise', 'e')
                        ->where('e.proprietaire = :user')
                        ->setParameter('user', $user);
                },
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('destination', EntityType::class, [
                'class' => Tresorerie::class,
                'choice_label' => 'nom_compte',
                'label' => 'Compte Destination (Crédit)',
                'placeholder' => 'Choisir le compte destination',
                'query_builder' => function (\App\Repository\TresorerieRepository $tr) use ($user) {
                    return $tr->createQueryBuilder('t')
                        ->join('t.entreprise', 'e')
                        ->where('e.proprietaire = :user')
                        ->setParameter('user', $user);
                },
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant à transférer (TND)',
                'constraints' => [
                    new NotBlank(['message' => 'Le montant est obligatoire']),
                    new Positive(['message' => 'Le montant doit être positif'])
                ],
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('description', TextType::class, [
                'label' => 'Motif du virement',
                'required' => false,
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900', 'placeholder' => 'Ex: Virement interne...']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
