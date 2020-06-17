<?php

namespace App\Form;

use App\Entity\Contest;
use Symfony\Component\Form\AbstractType;
use App\Form\DateTimePickerType;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stable_name')
            ->add('adress')
            ->add('zipcode')
            ->add('city')
            ->add('country')
            ->add('discipline', ChoiceType::class, [
                'choices' => [
                    'CSO' => 'CSO',
                    'CCE' => 'CCE',
                    'DRESSAGE' => 'DRESSAGE',
                    'HUNTER' => 'HUNTER',
                    'HOSE-BALL' => 'HORSE-BALL',
                    'ENDURANCE' => 'ENDURANCE',
                ],
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'discipline_' . strtolower($key)];
                },
            ])
            ->add('max_contestants_total')
            ->add('beginning_date')
            ->add('end_date')
            ->add('end_of_registration')
            ->add('picture', FileType::class, [
                'label' => 'Picture (JPG/PNG file)',
                'attr' => [
                    'placeholder' => 'Select a contest image'
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4000k',
                        'mimeTypesMessage' => 'Please upload a valid JPG/PNG file',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contest::class,
        ]);
    }
}
