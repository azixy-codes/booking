<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\ParkingSpot;
use App\Entity\TimeSlot;
use App\Entity\User;
use App\Enum\BookingStatusEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EnumType::class, [
                'class' => BookingStatusEnum::class
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('spot', EntityType::class, [
                'class' => ParkingSpot::class,
                'choice_label' => fn($spot) => $spot->getSpotNumber() . ' ' . $spot->getLocation(),
            ])
            ->add('timeslot', EntityType::class, [
                'class' => TimeSlot::class,
                'choice_label' => function ($timeSlot) {
                    $labelText = \DateTime::createFromImmutable($timeSlot->getStartTime())->format('G:s') . ' - ';
                    $labelText .= \DateTime::createFromImmutable($timeSlot->getEndTime())->format('G:s');
                    return $labelText;
                },
            ])
            ->add('date', DateType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
