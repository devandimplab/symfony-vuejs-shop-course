<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([], 'Should be filled')
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'required' => true,
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => '0.01'
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'overflow: hidden;'
                ]
            ])
            ->add('newImage', FileType::class, [
                'label' => 'Choose new image',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-file'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Is published',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('isDeleted', CheckboxType::class, [
                'label' => 'Is deleted',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EditProductModel::class,
        ]);
    }
}
