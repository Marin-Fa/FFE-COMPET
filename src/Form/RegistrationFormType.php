<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ArrayTransformer();
        $data = ['roles' => []];
        $builder
            ->add('email')
            ->add('first_name')
            ->add('last_name')
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                    'entry_options' => [
                        'label' => false,
                        'choices' => [
                            'Rider' => 'ROLE_RIDER',
                            'Organizer' => 'ROLE_ORGANIZER',
                        ],
                        'choice_attr' => function ($choice, $key, $value) {
                            return ['class' => 'roles_' . strtolower($key)];
                        },
                    ],
            ])
            ->add('licence_number')

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        'max' => 128,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                    ]),
                ],
                'first_options' => [
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

//    public function onPreSubmit(FormEvent $form)
//    {
//        $data = $form->getData();
//        $data['roles'] = array_values($data['roles']);
//        $form->setData($data);
//    }
}
