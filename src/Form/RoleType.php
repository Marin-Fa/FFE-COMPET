<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
             ->add('roles', ChoiceType::class, [
                 'choices' => [
                     'Rider' => 'ROLE_RIDER',
                     'Organizer' => 'ROLE_ORGANIZER',
                 ]
             ]);
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($array) {
                    return $array;
                },
                function ($array) {
                    return json_encode($array);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            [
                'choices' => [
                    'Rider' => 'ROLE_RIDER',
                    'Organizer' => 'ROLE_ORGANIZER',
                ]
            ]
        ]);
    }

    public function getName()
    {
        return 'roles';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => User::class,
        );
    }
}
