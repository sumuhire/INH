<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Entity\Department;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class QuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class
            )
            // ->add(
            //     'editDate',
            //     DateTimeType::class
            // )
            ->add(
                'emergency',
                NumberType::class
            )
            ->add(
                'description',
                TextareaType::class
                )
            ->add(
                'targetDepartment',
                EntityType::class, 
                [
                    'class' => Department::class, 
                    'choice_label' => 'label',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Departments'
                ]
            )
        ;

        if ($options['standalone']) {
            $builder->add(
                'submit', 
                SubmitType::class, 
                ['attr' => ['class' => 'btn-success btn-block']]
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
