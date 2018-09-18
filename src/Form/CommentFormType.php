<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'answer',
                TextType::class, 
                [
                    'attr' => ['class' => 'form-control'],
                    'label'=> ' ',
        
                    
                ]
                )
        ;

        if ($options['standalone']) {
            $builder->add(
                'submit', 
                SubmitType::class, 
                [
                    'attr' => ['class' => 'btn btn-warning btn-block'],
                    'label'=>'Comment'
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'standalone' => false
        ]);
    }
}
