<?php

namespace App\Enums;

enum UpgradeRequestStatuses: string
{
    case ACCEPTED  = 'accepted';
    case REJECTED  = 'rejected';
    case PENDING   = 'pending';

    public static function all(): array
    {
        return [
            self::ACCEPTED->value,
            self::REJECTED->value,
            self::PENDING->value,
        ];
    }
}
