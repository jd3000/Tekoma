<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Prospect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProspectProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $product = $options['data'];
        // dump($product);
        $productName = $product->getProduct()->getName();



        $builder

            ->add('firstname', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Prénom'
                )
            ))
            ->add('lastname', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                )
            ))
            ->add('prospectEmail', EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                )
            ))


            ->add('subject', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Objet',
                    'value' => 'COMMANDE - ' . $productName
                )
            ))
            ->add('message', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-arround',
                    'required rows' => '5',
                    'placeholder' => 'Votre message pour commander le ' . $productName,
                )
            ))
            ->add('agreeTerms', CheckboxType::class, array(
                'label' => 'J\'accepte l\'utilisation de ces informations pour être recontacté par Tekoma',
                'attr' => array(
                    'class' => 'form-arround',
                    'required' => true,
                )
            ));
        # code...

        // ))
        // ->add('recaptcha', HiddenType::class, array(
        //     'mapped' => false,
        // ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class
        ]);
    }
}
