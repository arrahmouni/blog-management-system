<?php

namespace App\Enums;

enum HttpStatusCodes: int
{
    case OK                     = 200;
    case BAD_REQUEST            = 400;
    case UNAUTHORIZED           = 401;
    case FORBIDDEN              = 403;
    case NOT_FOUND              = 404;
    case INTERNAL_SERVER_ERROR  = 500;

    public static function all(): array
    {
        return [
            self::OK->value,
            self::BAD_REQUEST->value,
            self::UNAUTHORIZED->value,
            self::FORBIDDEN->value,
            self::NOT_FOUND->value,
            self::INTERNAL_SERVER_ERROR->value,
        ];
    }
}
