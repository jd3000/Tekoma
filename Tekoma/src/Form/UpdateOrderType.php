<?php

namespace App\Form;

use App\Entity\OrderStripe;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UpdateOrderType extends AbstractType
{
    private function getConfiguration($label, $placeholder, $class)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
                'class' => $class
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'attr' => array(
                        'class' => '',
                        'placeholder' => 'Nom',
                    ),
                    'constraints' => array(
                        new NotBlank()
                    )
                )
            )
            ->add(
                'city',
                TextType::class,
                array(
                    'attr' => array(
                        'class' => '',
                        'placeholder' => 'Ville',
                    ),
                    'constraints' => array(
                        new NotBlank()
                    )
                )
            )
            ->add(
                'adress_line1',
                TextType::class,
                array(
                    'attr' => array(
                        'class' => '',
                        'placeholder' => 'Adresse',
                    ),
                    'constraints' => array(
                        new NotBlank()
                    )
                )
            )
            ->add(
                'adress_line2'
            )
            ->add(
                'postal_code',
                TextType::class,
                array(
                    'attr' => array(
                        'class' => '',
                        'placeholder' => 'Code postal',
                    ),
                    'constraints' => array(
                        new NotBlank()
                    )
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderStripe::class,
        ]);
    }
}
