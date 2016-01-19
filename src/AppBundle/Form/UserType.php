<?php

// src/AppBundle/Form/UserType.php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class);
        if(isset($options['is_mode']) && ($options['is_mode'] != NULL) && ($options['is_mode'] == "save")){
            $builder
                ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                )
                )
                ->add('roles', ChoiceType::class, array(
    'choices'  => array(
        'ROLE_USER' => 'ROLE_USER',
        'ROLE_ADMIN' => 'ROLE_ADMIN',


    ),
    // *this line is important*
    'choices_as_values' => true,
    'multiple' => true,
))
            ->add('save', SubmitType::class);
            }
            elseif(isset($options['is_mode']) && ($options['is_mode'] != NULL) && ($options['is_mode'] == "saveAndDelete")){
            $builder
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                )
                )
                                ->add('roles', ChoiceType::class, array(
    'choices'  => array(
        'ROLE_USER' => 'ROLE_USER',
        'ROLE_ADMIN' => 'ROLE_ADMIN',


    ),
    // *this line is important*
    'choices_as_values' => true,
    'multiple' => true,))
            ->add('save', SubmitType::class)
            ->add('delete', SubmitType::class);
            }
        }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'is_mode' => "show",
        ));
    }
}