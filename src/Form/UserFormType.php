<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Department;
use App\Entity\Role;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ["label" => "Username", 'attr' => ['class' => 'form-control text-left']])
            ->add('firstname', TextType::class, ["label" => "Firstname", 'attr' => ['class' => 'form-control']])
            ->add('lastname', TextType::class, ["label" => "Lastname", 'attr' => ['class' => 'form-control']])
            ->add('gender', ChoiceType::class, array("choices" => ["m" => "m", "f" => "f", "o" => "o"], 'attr' => ['class' => 'form-control']))
            ->add('password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'first_options' => array('label' => 'Password', 'attr' => ['class' => 'form-control']),
                    'second_options' => array('label' => 'Repeat Password', 'attr' => ['class' => 'form-control'])
                ]
            )
            ->add('phoneFix', TextType::class, ["label" => "phone", 'attr' => ['class' => 'form-control']])
            ->add('phoneMobile', TextType::class, ["label" => "mobile", 'attr' => ['class' => 'form-control']])
            ->add('department',
                EntityType::class,
                [
                    'label' => 'Give the user Department',
                    'class' => Department::class,
                    "choice_label" => "label",
                    'attr' => ['class' => 'form-control']
                ])
        ;
        if (!$options['standalone']) {
            $builder->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => 'btn btn-success btn-block']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'standalone' => false
        ]);
    }
}
