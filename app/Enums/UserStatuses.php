<?php

namespace App\Enums;

enum UserStatuses: string
{
    case ACTIVE     = 'active';
    case PENDING    = 'pending';
    case INACTIVE   = 'inactive';

    public static function all(): array
    {
        return [
            self::ACTIVE->value,
            self::PENDING->value,
            self::INACTIVE->value,
        ];
    }
}
