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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                    'class' => 'form-arround',
                    'required rows' => '5',
                    'placeholder' => 'Message'
                )
            ))
            ->add('agreeTerms', CheckboxType::class, array(
                'label' => 'agree terms',
                'attr' => array(
                    'class' => 'form-arround',
                    'required' => true,
                )
            ))
            ->add('check', SubmitType::class, array(
                'label' => 'Vérification du formulaire',
                'attr' => array(
                    'class' => 'form-control-check',
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
