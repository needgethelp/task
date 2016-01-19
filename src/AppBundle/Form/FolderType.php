<?php

// src/AppBundle/Form/FolderType.php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\User;

class FolderType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $manager = $options['manager'];
        $member = $options['member'];
        
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('visibility', TextType::class)
            ->add('manager', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'name',
                'data' => $manager,
            ))
            ->add('member', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'name',
                'data' => $member,
                'multiple' => true,
            ))
            ->add('save', SubmitType::class)
            ->add('delete', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project',
            'manager' => null,
            'member' => null,
        ));
    }
}