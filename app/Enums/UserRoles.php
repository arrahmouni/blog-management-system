<?php

namespace App\Enums;

enum UserRoles: string
{
    case ADMIN  = 'admin';
    case WRITER = 'writer';
    case USER   = 'user';

    public static function all(): array
    {
        return [
            self::ADMIN->value,
            self::WRITER->value,
            self::USER->value,
        ];
    }
}
