<?php

// src/AppBundle/Form/TagType.php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Tag;

class TagType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $owner = $options['owner'];
        
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('owner', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'name',
                'data' => $owner,
            ))
            ->add('save', SubmitType::class)
            ->add('delete', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Tag',
            'owner' => null,
        ));
    }
}