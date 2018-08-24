<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\DTO\UserSearch;
use App\Entity\Role;

class UserSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'search',
                TextType::class,
                [
                    'required' => false,
                    'attr' => ['class' => 'form-control filter-list-input', 'placeholder' => 'Filter Users'], 'label' => ' '
                ]
            )
            /* ->add(
                'role',
                EntityType::class,
                [
                    'class' => Role::class,
                    'choice_label' => 'label'
                ])
            ; */
            ;

        // if ($options['standalone']) {
        //     $builder->add(
        //         'submit',
        //         SubmitType::class,
        //         ['attr' => ['class' => 'btn-warning btn-block'], 'label' => 'submit']
        //     );
        // }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'standalone' => false
        ]);
    }
}