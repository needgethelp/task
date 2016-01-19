<?php

// src/AppBundle/Form/CommentType.php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Comment;

class CommentType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $task = $options['task'];
        
        $builder
            ->add('text', TextType::class)
            ->add('save', SubmitType::class)
            ->add('delete', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Comment',
            'task' => null,
        ));
    }
}