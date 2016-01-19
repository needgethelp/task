<?php

// src/AppBundle/Form/TaskType.php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Comment;


class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $project = $options['project'];
        $assignee = $options['assignee'];
        $follower = $options['follower'];
        $tags = $options['tag'];
        $comments = $options['comment'];
        
        $builder
            ->setAttribute('here','thename')
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('startDate', DateType::class, array(
                    'widget' => 'single_text'))
            ->add('dueDate', DateType::class, array(
                    'widget' => 'single_text'))
            ->add('project', EntityType::class, array(
                'class' => 'AppBundle:Project',
                'choice_label' => 'title',
                'data' => $project,
            ))
            ->add('assignee', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'name',
                'data' => $assignee,
            ))
            ->add('follower', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'name',
                'data' => $follower,
                'multiple' => true,
            ))
            ->add('tags', EntityType::class, array(
                'class' => 'AppBundle:Tag',
                'choice_label' => 'title',
                'data' => $tags,
                'multiple' => true,
            ))
                ->add('save', SubmitType::class)
                ->add('delete', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task',
            'project' => null,
            'assignee' => null,
            'follower' => null,
            'tag' => null,
            'comment' => null,
        ));
    }
    
}