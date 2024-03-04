<?php

namespace App\Services;

use Hashids\Hashids;

class Hasher
{
    public static function encode(...$args)
    {
        return app(Hashids::class)->encode(...$args);
    }

    public static function decode($enc)
    {
        if (is_int($enc)) {
            return $enc;
        }

        return app(Hashids::class)->decode($enc)[0];
    }
}