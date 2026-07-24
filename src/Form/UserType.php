<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'Email',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => static fn (Doctor $doctor): string => $doctor->getFullName(),
                'placeholder' => 'Aucun',
                'required' => false,
                'label' => 'Docteur associé',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $options['role_choices'],
                'choice_value' => static fn (?string $choice): ?string => $choice,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
                'help' => 'Vous pouvez sélectionner plusieurs rôles.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'role_choices' => [],
        ]);

        $resolver->setAllowedTypes('role_choices', 'array');
    }
}
