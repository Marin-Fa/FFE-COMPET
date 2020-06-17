<?php

namespace App\Form;

use App\Entity\Horse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class HorseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => false))
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Mare' => 'MARE',
                    'Stallion' => 'STALLION',
                    'Gelding' => 'GELDING',
                ],
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'gender_' . strtolower($key)];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Horse::class,
        ]);
    }
}
