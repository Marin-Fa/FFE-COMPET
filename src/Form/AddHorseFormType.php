<?php

namespace App\Form;

use App\Entity\Horse;
use App\Repository\HorseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class AddHorseFormType extends AbstractType
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
                'choice_label' => 'name',
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
            // Configure your form options here
        ]);
    }
}
