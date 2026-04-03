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

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('reference', TextType::class, [
                'required' => false,
                'label' => 'Reference (Optional)',
                'attr' => ['placeholder' => 'e.g. OP-00001']
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Income (Revenu)' => 'revenu',
                    'Expense (Dépense)' => 'depense',
                ],
                'label' => 'Transaction Type',
                'placeholder' => '--- Select Transaction Type ---'
            ])
            ->add('montant', MoneyType::class, [
                'currency' => 'TND',
                'label' => 'Amount'
            ])
            ->add('categorie', TextType::class, [
                'required' => false,
                'label' => 'Category',
                'attr' => ['placeholder' => 'e.g. Sales, Rent, Utilities']
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => ['rows' => 3, 'placeholder' => 'Details about this transaction...']
            ])
            ->add('tresorerie', EntityType::class, [
                'class' => Tresorerie::class,
                'choice_label' => 'nom_compte',
                'label' => 'Select Account',
                'placeholder' => '--- Select an Account ---',
                'query_builder' => function (\App\Repository\TresorerieRepository $tr) use ($user) {
                    return $tr->createQueryBuilder('t')
                        ->join('t.entreprise', 'e')
                        ->where('e.proprietaire = :user')
                        ->setParameter('user', $user);
                },
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
