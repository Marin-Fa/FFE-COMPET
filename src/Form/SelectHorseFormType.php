<?php

namespace App\Form;

use App\Entity\Horse;
use App\Repository\HorseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class SelectHorseFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', EntityType::class, [
                'class' => Horse::class,
                'choice_label' => function (Horse $horse) {
                    dump($horse->getName());
                    return $horse->getName();
                },
                'attr' => [
                    'class' => 'form-control'
                ],
                'query_builder' => function (HorseRepository $repo) {
                return $repo->createQueryBuilder('h')
                    ->andWhere('h.user = :val')
                    ->setParameter('val', $this->security->getUser()->getId());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Horse::class,
        ]);
    }
}
