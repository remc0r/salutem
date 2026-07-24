<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Specialty;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // If a current user is provided, we assume some patient fields are prefilled and hide them from the form
        $currentUser = $options['current_user'] ?? null;

        $builder
            ->add('patientName', options: ['label' => 'Nom'])
            ->add('patientFirstName', options: ['label' => 'Prénom'])
            ->add('patientMail', options: ['label' => 'Email'])
            ->add('patientPhoneNumber', options: ['label' => 'Numéro de téléphone'])
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('message')
            ->add('specialty', EntityType::class, [
                'class' => Specialty::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'current_user' => null,
        ]);

        $resolver->setAllowedTypes('current_user', ['null', User::class]);
    }
}
