<?php

namespace App\Enums;

enum StatusPresensi: string
{
    case MASUK = 'MASUK';
    case TERLAMBAT = 'TERLAMBAT';
    public function color(): string
    {
        return match ($this) {
            self::MASUK => 'success',
            self::TERLAMBAT => 'warning'
        };
    }
}
