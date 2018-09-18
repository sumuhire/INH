<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                ['attr' => ['class' => 'form-control']]
            )
            // ->add(
            //     'editDate',
            //     DateTimeType::class
            // )
            ->add(
                'emergency',
                NumberType::class,
                [
                    'class' => Department::class, 
                    'choice_label' => 'label',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Departments'
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                ['attr' => ['class' => 'form-control']]
                )
            ->add(
                'targetDepartment',
                EntityType::class, 
                [
                    'class' => Department::class, 
                    'choice_label' => 'label',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Departments',
                    'attr' => ['class' => 'form-control']
                ]
            )
        ;

        if ($options['standalone']) {
            $builder->add(
                'submit', 
                SubmitType::class, 
                ['attr' => ['class' => 'btn-warning btn-block']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'standalone' => false
        ]);
    }
}
