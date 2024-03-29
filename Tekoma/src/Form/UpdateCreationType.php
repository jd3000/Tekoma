<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class UpdateCreationType extends AbstractType
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
        $product = $options['data'];
        // dump($product);
        // $productName = $product->getProduct()->getName();

        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'attr' => array(
                        'class' => '',
                        'placeholder' => 'Nom de la création',
                    ),
                    'constraints' => array(
                        new NotBlank(),
                        new Length(['min' => 5])
                    )
                )
            )
            ->add(
                'description',
                TextareaType::class,
                array(
                    'attr' => array(
                        'class' => 'form-arround',
                        'required rows' => '5',
                        'placeholder' => 'Description de la création'
                    ),
                    'constraints' => array(
                        new NotBlank(),
                        new Length(['min' => 10])
                    )
                )
            )
            ->add(
                'price',
                MoneyType::class,
                array(
                    'attr' => array(
                        'class' => '',
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
                array(
                    'attr' => array(
                        'class' => '',
                        'placeholder' => 'Quantité disponnible'
                    ),
                    'constraints' => array(
                        new GreaterThanOrEqual(['value' => 0])
                    )
                )
            )
            ->add(
                'img',
                FileType::class,
                array(
                    'mapped' => false,
                    'required' => false,
                    'label' => 'Choisissez une image qui met valeur la création'
                )
            )
            ->add(
                'highlighted',
                HiddenType::class,
                array(
                    'label' => 'highlighted',
                    'attr' => array(
                        'class' => '',
                        'required' => false,
                    )
                )
            )
            ->add(
                'slug',
                HiddenType::class,
                $this->getConfiguration("Slug associé au produit", "Slug", "")
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
