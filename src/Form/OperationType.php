<?php

namespace App\Form;

use App\Entity\Operation;
use App\Entity\Tresorerie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire Operation
 * 
 * Permet d'enregistrer une entrée ou sortie d'argent.
 * 
 * Maintenance :
 * - Filtre les comptes de trésorerie appartenant à l'utilisateur connecté.
 */
class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('reference', TextType::class, [
                'required' => false,
                'label' => 'Référence',
                'attr' => [
                    'placeholder' => 'ex: OP-2024-001',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Revenu (+)' => 'revenu',
                    'Dépense (-)' => 'depense',
                ],
                'label' => 'Type de transaction',
                'placeholder' => '--- Choisir un type ---',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900']
            ])
            ->add('montant', MoneyType::class, [
                'currency' => 'TND',
                'label' => 'Montant',
                'attr' => [
                    'placeholder' => '0.00',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'id' => 'operation_montant'
                ]
            ])
            ->add('deviseSaisie', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Devise (Conversion Automatique)',
                'choices' => [
                    'TND (Dinar Tunisien)' => 'TND',
                    'EUR (Euro)' => 'EUR',
                    'USD (Dollar US)' => 'USD',
                    'GBP (Livre Sterling)' => 'GBP',
                    'CHF (Franc Suisse)' => 'CHF',
                ],
                'attr' => [
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'id' => 'operation_devise'
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'placeholder' => '--- Choisir une catégorie ---',
                'choices' => [
                    'Vente' => 'Vente',
                    'Loyer' => 'Loyer',
                    'Salaire' => 'Salaire',
                    'Achat' => 'Achat',
                    'Impôt' => 'Impot',
                    'Maintenance' => 'Maintenance',
                    'Virement Interne' => 'Virement Interne',
                    'Solde Initial' => 'Solde Initial',
                ],
                'attr' => [
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'rows' => 3, 
                    'placeholder' => 'Détails de l\'opération...',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
            ->add('notesInterne', \Eckinox\TinymceBundle\Form\Type\TinymceType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Notes internes',
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Notes internes, détails supplémentaires...',
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900',
                    'id' => 'notesInterne'
                ]
            ])
            ->add('tresorerie', EntityType::class, [
                'class' => Tresorerie::class,
                'choice_label' => 'nom_compte',
                'label' => 'Compte de trésorerie',
                'placeholder' => '--- Sélectionner un compte ---',
                'attr' => ['class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'],
                'query_builder' => function (\App\Repository\TresorerieRepository $tr) use ($user) {
                    $qb = $tr->createQueryBuilder('t')->leftJoin('t.entreprise', 'e');
                    if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_INVESTISSEUR', $user->getRoles())) {
                        $qb->where('e.proprietaire = :user')
                           ->setParameter('user', $user);
                    }
                    return $qb;
                },
            ])
            ->add('dateOperation', DateTimeType::class, [
                'label' => 'Date de l\'opération',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/40 rounded-lg py-2.5 px-4 text-slate-900'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
            'attr' => ['novalidate' => 'novalidate'],
            'user' => null,
        ]);
    }
}
