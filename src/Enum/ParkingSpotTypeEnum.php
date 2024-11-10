<?php

declare(strict_types=1);

namespace App\Enum;

enum ParkingSpotTypeEnum: string
{
    case CAR = "Voiture";
    case MOTORCYCLE = "Moto";
    case BICYCLE = "Vélo";
}
