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
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserSettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['attr' => ['class' => 'form-control']], ["label" => "Username"])
            ->add('firstname', TextType::class, ['attr' => ['class' => 'form-control']], ["label" => "Firstname"])
            ->add('lastname', TextType::class, ['attr' => ['class' => 'form-control']], ["label" => "Lastname"])
            ->add('gender', ChoiceType::class, array("choices" => ["m" => "m", "f" => "f", "o" => "o"], 'attr' => ['class' => 'form-control filter-list-input']))
            ->add('phoneFix', TextType::class, ['attr' => ['class' => 'form-control']], ["label" => "phone"])
            ->add('phoneMobile', TextType::class, ['attr' => ['class' => 'form-control']], ["label" => "mobile"])
            ->add(
                'picture',
                FileType::class,
                ["data_class" => null, "disabled" => true, "attr" => ["class" => "d-none"], 'label' => ' ']
                )
            ->add(
                'department',
                EntityType::class,
                [
                    'label' => 'Give the user Department',
                    'class' => Department::class,
                    "choice_label" => "label",
                    'attr' => ['class' => 'form-control filter-list-input']
                ]
            );
        if (!$options['standalone']) {
            $builder->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => 'btn btn-primary btn-lg']]
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
