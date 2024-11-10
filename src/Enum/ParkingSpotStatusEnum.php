<?php

declare(strict_types=1);

namespace App\Enum;

enum ParkingSpotStatusEnum: string
{
    case AVAILABLE = "Disponible";
    case RESERVED = "Reservée";
    case UNDER_MAINTENANCE = "En maintenance";
}