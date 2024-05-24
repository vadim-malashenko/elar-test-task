<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'user@shop.local',
                ],
                'constraints' => [
                    new NotBlank(null, 'Email should not be blank.'),
                ]
            ])
            ->add('product', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Select Product',
                'choices' => $options['data']['products'],
                'choice_label' => 'name',
                'choice_value' => 'id',
                'choice_attr' => function($product, $key, $index) {
                    return [
                        'data-price' => $product->getPrice(),
                    ];
                },
                'constraints' => [
                    new NotBlank(null, 'Product should not be blank.'),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);
    }
}
