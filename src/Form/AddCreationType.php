<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class AddCreationType extends AbstractType
{
    /**
     * Permet de définir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */

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
                        'class' => 'form-control',
                        'placeholder' => 'Nom de la création'
                    ),
                    'constraints' => array(
                        new NotBlank(),
                        new Length(['min' => 5])
                    )
                )
            )
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'required rows' => '5',
                    'placeholder' => 'Description de la création'
                ),
                'constraints' => array(
                    new NotBlank(),
                    new Length(['min' => 10])
                )
            ))
            ->add(
                'price',
                MoneyType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Prix de la création'
                    ),
                    'constraints' => array(
                        new NotNull(),
                        new GreaterThanOrEqual(['value' => 100])
                    )
                )
            )
            ->add(
                'quantity',
                IntegerType::class,
                $this->getConfiguration("Qauntité en stock", "Quantité", "")
            )
            ->add(
                'img',
                FileType::class,
                $this->getConfiguration("Image associée à la création", "Choisissez une image qui met valeur la création", "")
            )
            ->add(
                'highlighted',
                IntegerType::class,
                $this->getConfiguration("Produit mis en avant sur la page d'accueil", "Produit mis en avant sur la page d'acceuil", "")
            )
            ->add(
                'slug',
                HiddenType::class,
                $this->getConfiguration("Slug associé au produit", "Slug", "")
            )
            ->add(
                'isActive',
                HiddenType::class,
                array(
                    'label' => 'Produit visible par les visiteurs',
                    'attr' => array(
                        'class' => 'form-arround',
                        'required' => false,
                    )
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
