<?php

namespace App\Form;

use App\Entity\ParkingSpot;
use App\Enum\ParkingSpotStatusEnum;
use App\Enum\ParkingSpotTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParkingSpotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('spot_number')
            ->add('location')
            ->add('type', EnumType::class, [
                'class' => ParkingSpotTypeEnum::class,
            ])
            ->add('status', EnumType::class, [
                'class' => ParkingSpotStatusEnum::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParkingSpot::class,
        ]);
    }
}
