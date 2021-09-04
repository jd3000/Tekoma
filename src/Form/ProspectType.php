<?php

namespace App\Form;

use App\Entity\Prospect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                    'placeholder' => 'Objet'
                )
            ))
            ->add('message', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'required rows' => '5',
                    'placeholder' => 'Message'
                )
            ))
            ->add('agreeTerms', CheckboxType::class, array(
                'label' => 'J\'accepte les conditions d\'utilisation',
                'attr' => array(
                    'class' => 'form-arround',
                    'required' => true,
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class
        ]);
    }
}
