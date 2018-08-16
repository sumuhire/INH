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
            ->add('username', TextType::class, ["label" => "Username"])
            ->add('firstname', TextType::class, ["label" => "Firstname"])
            ->add('lastname', TextType::class, ["label" => "Lastname"])
            ->add('gender', ChoiceType::class, array("choices" => ["m" => "m", "f" => "f", "o" => "o"]))
            #->add('email', EmailType::class, ["label" => "email"])
            ->add('password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password')
                ]
            )
            ->add('phoneFix', TextType::class, ["label" => "phone"])
            ->add('phoneMobile', TextType::class, ["label" => "mobile"])
            ->add('department',
                EntityType::class,
                [
                    'label' => 'Give the user Department',
                    'class' => Department::class,
                    "choice_label" => "label"
                ])
        ;
        if (!$options['standalone']) {
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
            'data_class' => User::class,
            'standalone' => false
        ]);
    }
}
