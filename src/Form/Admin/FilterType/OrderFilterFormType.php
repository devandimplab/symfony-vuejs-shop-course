<?php

namespace App\Form\Admin\FilterType;

use App\Entity\Order;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use App\Form\DTO\EditOrderModel;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class, [
                'label' => 'Id',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('owner', Filters\EntityFilterType::class, [
                'label' => 'Owner',
                'class' => User::class,
                'choice_label' => function($user) {
                    return sprintf('#%s %s', $user->getId(), $user->getEmail());
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('status', Filters\ChoiceFilterType::class, [
                'label' => 'Status',
                'choices' => array_flip(OrderStaticStorage::getOrderStatusChoices()),
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('totalPrice', Filters\NumberRangeFilterType::class, [
                'label' => 'Total price',
                'left_number_options' => [
                    'label' => 'From',
                    'condition_operator' => FilterOperands::OPERATOR_GREATER_THAN_EQUAL,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
                'right_number_options' => [
                    'label' => 'To',
                    'condition_operator' => FilterOperands::OPERATOR_LOWER_THAN_EQUAL,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
            ])
            ->add('createdAt', Filters\DateTimeRangeFilterType::class, [
                'label' => 'Created at',
                'left_datetime_options' => [
                    'label' => 'From',
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
                'right_datetime_options' => [
                    'label' => 'To',
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'order_filter_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EditOrderModel::class,
            'method' => 'GET',
            'validation_groups' => ['filtering']
        ]);
    }
}
