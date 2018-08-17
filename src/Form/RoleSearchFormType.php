<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\DTO\RoleSearch;

class RoleSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'search',
                TextType::class,
                ['required' => false]
            );

        if ($options['standalone']) {
            $builder->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => 'btn-success btn-block'], 'label' => 'search']
            );
        }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoleSearch::class,
            'standalone' => false
        ]);
    }
}