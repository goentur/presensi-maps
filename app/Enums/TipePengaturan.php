<?php

namespace App\Enums;

enum TipePengaturan: string
{
    case PRESENSI_MASUK = 'PRESENSI MASUK';
    case PRESENSI_PULANG = 'PRESENSI PULANG';
    public function color(): string
    {
        return match ($this) {
            self::PRESENSI_MASUK => 'primary',
            self::PRESENSI_PULANG => 'success'
        };
    }
}
