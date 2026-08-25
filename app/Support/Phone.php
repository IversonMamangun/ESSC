<?php

namespace App\Support;

class Phone
{
    public static function toLocal(string $phone): string
    {
        return preg_replace('/^63/', '0', $phone);
    }

    public static function toInternational(string $phone): string
    {
        return preg_replace('/^0/', '63', $phone);
    }
}