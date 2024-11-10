<?php

namespace App\Form;

use App\Entity\ParkingSpot;
use App\Entity\TimeSlot;
use App\Enum\BookingStatusEnum;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;

class FullBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
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
            ->add('date', TypeDateType::class);
    }
}