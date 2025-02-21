<?php

namespace App\Enums;

enum StatusPresensiData: string
{
    case TERIMA = 'TERIMA';
    case TOLAK = 'TOLAK';
    public function color(): string
    {
        return match ($this) {
            self::TERIMA => 'success',
            self::TOLAK => 'warning'
        };
    }
}
