<?php

declare(strict_types=1);

namespace App\Enum;

enum BookingStatusEnum: string
{
    case CONFIRMED = "Confirmée";
    case CANCELED = "Annulée";
    case PENDING = "En attente";
}